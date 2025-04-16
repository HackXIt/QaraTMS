<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class StudentsSeeder extends Seeder
{
    private $password = 'password123'; // Set your desired common password here

    public function run()
    {
        $this->reset();

        // Permissions without delete for project & repository
        $permissions = [
            'add_edit_projects',
            'add_edit_repositories',

            'add_edit_test_suites',
            'delete_test_suites',

            'add_edit_test_cases',
            'delete_test_cases',

            'add_edit_test_plans',
            'delete_test_plans',

            'add_edit_test_runs',
            'delete_test_runs',

            'add_edit_documents',
            'delete_documents'
        ];

        // Ensure all permissions exist
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        for ($i = 1; $i <= 30; $i++) {
            $number = str_pad($i, 2, '0', STR_PAD_LEFT);

            $user = User::create([
                'name' => 'student' . $number,
                'email' => 'student' . $number . '@hackxit.com',
                'password' => Hash::make($this->password)
            ]);

            $user->givePermissionTo($permissions);
        }
    }

    /**
     * Reset function to delete previously created student users.
     */
    public function reset(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $number = str_pad($i, 2, '0', STR_PAD_LEFT);
            User::where('email', 'student' . $number . '@hackxit.com')->delete();
        }
    }
}
