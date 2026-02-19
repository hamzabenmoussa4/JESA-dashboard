<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // On s'assure que le rôle 'admin' existe
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Créer l'utilisateur admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole->id,
        ]);
    }
}
