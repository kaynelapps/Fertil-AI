FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure zip && \
    docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Copy the Modules directory (if needed for composer install)
COPY Modules ./Modules

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts

# Copy the rest of the application
COPY . .

# Set proper ownership and permissions
RUN chown -R www-data:www-data /var/www && \
    mkdir -p storage/framework/{sessions,views,cache} && \
    chmod -R 775 storage bootstrap/cache

# Set default PORT if not provided
ENV PORT=8000

# Install PostgreSQL client
RUN apt-get update && apt-get install -y postgresql-client && rm -rf /var/lib/apt/lists/*

# Create entrypoint script for runtime commands
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Wait for database to be ready\n\
until pg_isready -h $DB_HOST -p $DB_PORT -U $DB_USERNAME\n\
do\n\
  echo "Waiting for database to be ready..."\n\
  sleep 2\n\
done\n\
\n\
# Run Laravel setup commands\n\
php artisan module:enable Frontend\n\
composer dump-autoload -o\n\
\n\
# Clear and cache configurations\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan route:clear\n\
php artisan view:clear\n\
\n\
# Run database migrations and seeders\n\
echo "Running migrations and seeders..."\n\
php artisan migrate:fresh --seed --force\n\
\n\
# Only run these if not in production or if explicitly needed\n\
if [ "$APP_ENV" != "production" ] || [ "$FORCE_PUBLISH" = "true" ]; then\n\
    php artisan vendor:publish --all --force\n\
fi\n\
\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Start the application\n\
exec php artisan serve --host=0.0.0.0 --port=$PORT\n\
' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

EXPOSE $PORT

# Use the entrypoint script
CMD ["/usr/local/bin/start.sh"]