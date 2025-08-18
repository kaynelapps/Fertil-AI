<?php

namespace App\Traits;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use ZipArchive;

trait DatabaseBackupTrait
{
    public function createAndSendDatabaseBackup()
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "backup-{$timestamp}.sql";
        $directory = storage_path("app/backups");

        if (!file_exists($directory) && !mkdir($directory, 0755, true)) {
            Log::error("Failed to create backups directory: {$directory}");
            return false;
        }

        $sqlFilePath = $directory . '/' . $filename;
        
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $command = sprintf(
            "mysqldump -h %s -u %s -p%s %s > %s",
            escapeshellarg($host),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($sqlFilePath)
        );

        exec($command, $output, $returnVar);
        if ($returnVar !== 0) {
            Log::error("Database dump failed", [
                'command' => $command,
                'output' => $output,
                'returnVar' => $returnVar,
            ]);
            return false;
        }

        $zipPath = $directory . "/backup-{$timestamp}.zip";
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            Log::error("Failed to create ZIP archive at {$zipPath}");
            return false;
        }
        $zip->addFile($sqlFilePath, $filename);
        $zip->close();

        if (file_exists($sqlFilePath)) {
            unlink($sqlFilePath);
        }

        if (!$this->sendBackupEmail($zipPath)) {
            Log::error("Failed to send backup email.");
        }

        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        return true;
    }

    protected function sendBackupEmail($filePath)
    {
        $appSetting = AppSetting::select('backup_email')->first();
        if (!$appSetting || empty($appSetting->backup_email)) {
            Log::error("Backup email recipient is not set.");
            return false;
        }

        $recipientEmail = $appSetting->backup_email;
        Log::info("Sending backup email to: {$recipientEmail}");

        $data = [
            'fileName' => basename($filePath),
            'message'  => 'Please find attached the latest database backup.',
        ];

        try {
            Mail::send('emails.databasebakupmail', $data, function ($message) use ($filePath, $recipientEmail) {
                $message->to($recipientEmail)
                        ->subject('Database Backup')
                        ->attach($filePath, [
                            'as'   => basename($filePath),
                            'mime' => 'application/zip',
                        ]);
                Log::info("Email prepared for: {$recipientEmail}");
            });
        } catch (\Exception $e) {
            Log::error("Error sending backup email: " . $e->getMessage());
            return false;
        }
        return true;
    }

}
