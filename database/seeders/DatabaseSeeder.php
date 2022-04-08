<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::create([
            'names' => 'Administrador',
            'username' => 'admin',
            'password' => bcrypt(123)
        ]);

        $this->call(PermissionsUser::class);

        $permissions = \App\Models\Permission::get(['id']);

        $user->permissions()->attach($permissions);

        \App\Models\Config::create([
            'company_name' => 'Digital App',
            'company_facebook_url' => 'https://web.facebook.com/digitalappsoftware',
        ]);
    }
}
