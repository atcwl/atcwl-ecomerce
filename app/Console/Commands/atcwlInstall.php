<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class atcwlInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atcwl:install {--force : Do not ask for user confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install dummy data for the application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('force')) {
            $this->proceed();
        } else {
            if ($this->confirm('This will delete ALL your current data and install the default dummy data. Are you sure?')) {
                $this->proceed();
            }
        }
    }

    protected function proceed()
    {
            File::deleteDirectory(public_path('storage/products'));
            File::deleteDirectory(public_path('storage/settings'));
            File::deleteDirectory(public_path('storage/pages'));
            File::deleteDirectory(public_path('storage/posts'));
            File::deleteDirectory(public_path('storage/users'));

            $this->callSilent('storage:link');
            $copySuccess = File::copyDirectory(public_path('app-assets/products'), public_path('storage/products/dummy'));
            if ($copySuccess) {
                $this->info('Images successfully copied to storage folder.');
            }

            File::copyDirectory(public_path('app-assets/pages'), public_path('storage/pages'));
            File::copyDirectory(public_path('app-assets/images'), public_path('storage/users'));

            try {
                $this->call('migrate:fresh', [
                    '--seed' => true,
                    '--force' => true,
                ]);
            } catch (\Exception $e) {
                $this->error('Algolia credentials incorrect. Your products table is NOT seeded correctly. If you are not using Algolia, remove Laravel\Scout\Searchable from App\Product');
            }

            $this->call('db:seed', [
                '--class' => 'VoyagerDatabaseSeeder',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'VoyagerDummyDatabaseSeeder',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'DataTypesTableSeederCustom',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'DataRowsTableSeederCustom',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'MenusTableSeederCustom',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'MenuItemsTableSeederCustom',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'RolesTableSeederCustom',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'PermissionsTableSeederCustom',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'PermissionRoleTableSeeder',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'PermissionRoleTableSeederCustom',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'UsersTableSeederCustom',
                '--force' => true,
            ]);

            $this->call('db:seed', [
                '--class' => 'SettingsTableSeederCustom',
                '--force' => true,
            ]);

            try {
                $this->call('scout:clear', [
                    'model' => 'App\\Product',
                ]);

                $this->call('scout:import', [
                    'model' => 'App\\Product',
                ]);
            } catch (\Exception $e) {
                $this->error('Algolia credentials incorrect. Check your .env file. Make sure ALGOLIA_APP_ID and ALGOLIA_SECRET are correct.');
            }

            $this->info('Dummy data installed');
        }
}
