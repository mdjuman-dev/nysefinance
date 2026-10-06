<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * LOCAL ONLY: admin account for testing the admin panel on 127.0.0.1.
 * Username: localadmin / Password: local-admin-7c3e91d2
 * Refuses to run against any database other than the local copy. Do not deploy.
 */
class LocalTestAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::getDatabaseName() !== 'nysefinance') {
            throw new \RuntimeException('LocalTestAdminSeeder only runs on the local nysefinance database.');
        }

        Admin::updateOrCreate(['username' => 'localadmin'], [
            'name'     => 'Local Admin',
            'email'    => 'localadmin@example.test',
            'password' => Hash::make('local-admin-7c3e91d2'),
        ]);
    }
}
