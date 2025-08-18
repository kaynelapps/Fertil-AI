<?php

use App\Helpers\SeederHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected function shouldRun(): bool
    {
        return !User::where('email', 'admin@admin.com')->exists();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!$this->shouldRun()) {
            return;
        }

        $permissions = [
            [
                'name' => 'role',
                'sub_permission' => ['role-add', 'role-list'],
            ],
            [
                'name' => 'permission',
                'sub_permission' => ['permission-add', 'permission-list'],
            ],
            [
                'name' => 'user',
                'sub_permission' => ['user-list','user-add','user-edit','user-show','user-delete'],
            ],
            [
                'name' => 'category',
                'sub_permission' => ['category-list','category-add','category-show','category-edit','category-delete'],
            ],
            [
                'name' => 'sections',
                'sub_permission' => ['sections-list','sections-add','sections-edit','sections-show','sections-delete'],
            ],
            [
                'name' => 'common-question',
                'sub_permission' => ['common-question-list','common-question-add','common-question-edit','common-question-delete'],
            ],
            [
                'name' => 'sections-data-main',
                'sub_permission' => ['sections-data-main-list','sections-data-main-add','sections-data-main-edit','sections-data-main-show','sections-data-main-delete','sections-data-main-restore'],
            ],
            [
                'name' => 'sections-data',
                'sub_permission' => ['sections-data-list','sections-data-add','sections-data-edit','sections-data-delete'],
            ],
            [
                'name' => 'article',
                'sub_permission' => ['article-list','article-add','article-show','article-edit','article-delete'],
            ],
            [
                'name' => 'tags',
                'sub_permission' => ['tags-list','tags-add','tags-edit','tags-delete'],
            ],
            [
                'name' => 'insights',
                'sub_permission' => ['insights-list','insights-add','insights-edit','insights-show','insights-delete'],
            ],
            [
                'name' => 'symptoms',
                'sub_permission' => ['symptoms-list','symptoms-add','symptoms-show','symptoms-edit','symptoms-delete'],
            ],
            [
                'name' => 'sub-symptoms',
                'sub_permission' => ['sub-symptoms-list','sub-symptoms-add','sub-symptoms-edit','sub-symptoms-delete'],
            ],
            [
                'name' => 'health-experts',
                'sub_permission' => ['health-experts-list','health-experts-add','health-experts-show','health-experts-edit','health-experts-delete'],
            ],
            [
                'name' => 'imagesection',
                'sub_permission' => ['imagesection-list','imagesection-add','imagesection-edit','imagesection-delete'],
            ],
            [
                'name' => 'default-log-category',
                'sub_permission' => ['default-log-category-list','default-log-category-edit',],
            ],
            [
                'name' => 'calculatortool',
                'sub_permission' => ['calculatortool-list','calculatortool-edit'],
            ],
            [
                'name' => 'pregnancydate',
                'sub_permission' => ['pregnancydate-list','pregnancydate-add','pregnancydate-edit','pregnancydate-delete'],
            ],
            [
                'name' => 'cycledays',
                'sub_permission' => ['cycledays-list','cycledays-add','cycledays-show','cycledays-delete'],
            ],
            [
                'name' => 'uploadvideos',
                'sub_permission' => ['uploadvideos-list','uploadvideos-add','uploadvideos-edit','uploadvideos-delete'],
            ],
            [
                'name' => 'faq',
                'sub_permission' => ['faq-list','faq-add','faq-edit','faq-delete'],
            ],
            [
                'name' => 'push notification',
                'sub_permission' => ['push notification-list','push notification-add','push notification-delete'],
            ],
            [
                'name' => 'pages',
                'sub_permission' => ['terms-condition','privacy-policy'],
            ],
            [
                'name' => 'screen',
                'sub_permission' => ['screen-list'],
            ],
            [
                'name' => 'defaultkeyword',
                'sub_permission' => ['defaultkeyword-list','defaultkeyword-add','defaultkeyword-edit'],
            ],
            [
                'name' => 'languagelist',
                'sub_permission' => ['languagelist-list','languagelist-add','languagelist-edit','languagelist-delete'],
            ],
            [
                'name' => 'languagewithkeyword',
                'sub_permission' => ['languagewithkeyword-list','languagewithkeyword-edit'],
            ],
            [
                'name' => 'bulkimport',
                'sub_permission' => ['bulkimport-add'],
            ],
            [
                'name' => 'healthexpertsession',
                'sub_permission' => ['healthexpertsession-list','healthexpertsession-add','healthexpertsession-edit','healthexpertsession-delete'],
            ],
            [
                'name' => 'educationalsession',
                'sub_permission' => ['educationalsession-list','educationalsession-add','educationalsession-edit','educationalsession-delete'],
            ],
            [
                'name' => 'subadmin',
                'sub_permission' => ['subadmin-list','subadmin-add','subadmin-edit','subadmin-delete'],
            ],
            [
                'name' => 'personalinsights',
                'sub_permission' => ['personalinsights-list','personalinsights-add','personalinsights-edit','personalinsights-delete'],
            ],
            [
                'name' => 'subscription',
                'sub_permission' => ['subscription-list','subscription-delete'],
            ],
            [
                'name' => 'askexpert',
                'sub_permission' => ['askexpert-list','askexpert-delete','askexpert-image'],
            ],
            [
                'name' => 'pregnancyweek',
                'sub_permission' => ['pregnancyweek-list','pregnancyweek-add','pregnancyweek-edit','pregnancyweek-delete'],
            ],
            [
                'name' => 'customtopic',
                'sub_permission' => ['customtopic-list','customtopic-add','customtopic-show','customtopic-edit','customtopic-delete'],
            ],
            [
                'name' => 'dailyinsight',
                'sub_permission' => ['dailyinsight-list','dailyinsight-add','dailyinsight-edit','dailyinsight-delete'],
            ],
            [
                'name' => 'website-section',
                'sub_permission' => ['website-section-list'],
            ],
        ];
          SeederHelper::seedPermissions($permissions);

          $roles = [
            [
                'name' => 'admin',
                'status' => 1,
                'permissions' => [
                    'role-add', 'role-list','permission-add', 'permission-list','user-list','user-add','user-edit','user-show','user-delete','category-list','category-add','category-show','category-edit','category-delete','sections-list','sections-add','sections-edit','sections-show','sections-delete','common-question-list','common-question-add','common-question-edit','common-question-delete',
                    'sections-data-main-list','sections-data-main-add','sections-data-main-edit','sections-data-main-show','sections-data-main-delete','sections-data-main-restore','sections-data-list','sections-data-add','sections-data-edit','sections-data-delete','article-list','article-add','article-show','article-edit','article-delete','tags-list','tags-add','tags-edit','tags-delete','insights-list','insights-add','insights-edit','insights-show','insights-delete',
                    'symptoms-list','symptoms-add','symptoms-show','symptoms-edit','symptoms-delete','sub-symptoms-list','sub-symptoms-add','sub-symptoms-edit','sub-symptoms-delete','health-experts-list','health-experts-add','health-experts-edit','health-experts-delete','imagesection-list','imagesection-add','imagesection-edit','imagesection-delete','default-log-category-list','default-log-category-edit','calculatortool-list','calculatortool-edit','pregnancydate-list','pregnancydate-add','pregnancydate-edit','pregnancydate-delete',
                    'cycledays-list','cycledays-add','cycledays-show','cycledays-delete','uploadvideos-list','uploadvideos-add','uploadvideos-edit','uploadvideos-delete','faq-list','faq-add','faq-edit','faq-delete','push notification-list','push notification-add','push notification-delete','terms-condition','privacy-policy','screen-list','defaultkeyword-list','defaultkeyword-add','defaultkeyword-edit','languagelist-list','languagelist-add','languagelist-edit','languagelist-delete','languagewithkeyword-list','languagewithkeyword-edit',
                    'bulkimport-add','healthexpertsession-list','healthexpertsession-add','healthexpertsession-edit','health-experts-show','healthexpertsession-delete','educationalsession-list','educationalsession-add','educationalsession-edit','educationalsession-delete','subadmin-list','subadmin-add','subadmin-edit','subadmin-delete','personalinsights-list','personalinsights-add','personalinsights-edit','personalinsights-delete','subscription-list','subscription-delete','askexpert-list','askexpert-delete','askexpert-image',
                    'pregnancyweek-list','pregnancyweek-add','pregnancyweek-edit','pregnancyweek-delete','customtopic-list','customtopic-add','customtopic-show','customtopic-edit','customtopic-delete','dailyinsight-list','dailyinsight-add','dailyinsight-edit','dailyinsight-delete','website-section-list'
                ],
            ],
            [
                'name' => 'app_user',
                'status' => 1,
                'permissions' => []
            ],
            [
                'name' => 'anonymous_user',
                'status' => 1,
                'permissions' => []
            ],
            [
                'name' => 'doctor',
                'status' => 1,
                'permissions' => []
            ],
          ];
            SeederHelper::seedRoles($roles);

            $users = [
            [
                'id' => 1,
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'username' => 'admin',
                'contact_number' => '+919876543210',
                'address' => NULL,
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678'),
                'email_verified_at' => NULL,
                'user_type' => 'admin',
                'player_id' => NULL,
                'remember_token' => NULL,
                'last_notification_seen' => NULL,
                'cycle_length' => 0,
                'period_length' => 0,
                'luteal_phase' => 0,
                'status' => 'active',
                'timezone' => 'UTC',
                'display_name' => 'Admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
        ];

        foreach ($users as $value) {
            $user = User::create($value);
            $user->assignRole($value['user_type']);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
