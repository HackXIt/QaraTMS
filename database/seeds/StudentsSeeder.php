<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class StudentsSeeder extends Seeder
{
    private $password = 'Bad_Security!'; // Set your desired common password here

    /**
     * Run the seeder (create users and assign permissions).
     */
    public function run(): void
    {
        $this->reset(); // First remove existing users to avoid duplicates

        // Define base permissions
        $permissions = [
            'project.add', 'project.edit',
            'repository.add', 'repository.edit',
            'testsuite.add', 'testsuite.edit',
            'testcase.add', 'testcase.edit',
            'testplan.add', 'testplan.edit',
            'testrun.add', 'testrun.edit',
            'document.add', 'document.edit',
        ];

        // Create permissions (if they don't exist)
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Create a common 'student' role with these permissions
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->syncPermissions($permissions);

        // Create 30 student users
        for ($i = 1; $i <= 30; $i++) {
            $number = str_pad($i, 2, '0', STR_PAD_LEFT);

            $user = User::create([
                'name' => 'student' . $number,
                'email' => 'student' . $number . '@hackxit.com',
                'password' => Hash::make($this->password),
            ]);

            // Assign the student role to each user
            $user->assignRole($studentRole);
        }
    }

    /**
     * Reset function to delete previously created student users and role.
     */
    public function reset(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $number = str_pad($i, 2, '0', STR_PAD_LEFT);
            User::where('email', 'student' . $number . '@hackxit.com')->delete();
        }

        // Optional: Remove student role if you also want to reset roles
        $role = Role::where('name', 'student')->first();
        if ($role) {
            $role->delete();
        }
    }
}
