<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Define permissions
        $permissions = [
            'create books',
            'edit books',
            'delete books',
            'borrow books',
            'return books',
            'view books',
        ];

        // Create and assign permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(['create books', 'edit books', 'delete books', 'view books']);
        $userRole->givePermissionTo(['borrow books', 'return books', 'view books']);

        // Assign admin role to admin
        $adminUser = User::where('email', 'admin@gmail.com')->first();

        if($adminUser){
            $adminUser->assignRole('admin');
        }
    }
}
