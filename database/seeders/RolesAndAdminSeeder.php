<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);


        // Create the admin account
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('123'),
            ]
        );

        //create some teachers for testing
        $teachers = collect();

        for ($i = 0; $i < 3; $i++) {
            $teachers->push(User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'password' => bcrypt('123'),
            ]));
        }

        // Assign admin role
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Assign teacher role
        foreach ($teachers as $teacher) {

            $teacher->assignRole($teacherRole);

        }

        //create some students 
        $students = collect();

        for ($i = 0; $i < 3; $i++) {
            $students->push(User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'password' => bcrypt('123'),
            ]));
        }
        // Assign user role
        foreach ($students as $student) {

            $student->assignRole($studentRole);

        }


    }
}



// // Create permissions
// $createPathsPermission = Permission::firstOrCreate(['name' => 'create paths']);
// $editPathsPermission = Permission::firstOrCreate(['name' => 'edit paths']);
// $deletePathsPermission = Permission::firstOrCreate(['name' => 'delete paths']);
// $viewPathsPermission = Permission::firstOrCreate(['name' => 'view paths']);