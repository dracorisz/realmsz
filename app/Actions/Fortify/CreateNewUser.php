<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'role' => 'manager',
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $user->update(['organization_id' => $user->id]);

        $default = ['name' => 'default', 'website' => 'default', 'phone' => 'default', 'logo' => 'default'];
        $organization = Organization::create($default);

        $srows = [
            ['name' => 'Active', 'color' => '#00449f', 'icon_id' => 1],
            ['name' => 'Paused', 'color' => '#00c2c5', 'icon_id' => 1],
            ['name' => 'Canceled', 'color' => '#ff0033', 'icon_id' => 1],
            ['name' => 'Completed', 'color' => '#22c55e', 'icon_id' => 1],
            ['name' => 'Recurring', 'color' => '#fff', 'icon_id' => 1],
            ['name' => 'Unknown', 'color' => '#555', 'icon_id' => 1],
        ];

        foreach ($srows as $row) {
            $row['organization_id'] = $organization->id;
            Status::create($row);
        }

        $prows = [
            ['icon_id' => 1, 'name' => 'High', 'color' => '#888'],
            ['icon_id' => 1, 'name' => 'Medium', 'color' => '#777'],
            ['icon_id' => 1, 'name' => 'Low', 'color' => '#666'],
            ['icon_id' => 1, 'name' => 'Unknown', 'color' => '#555'],
        ];

        foreach ($prows as $row) {
            $row['organization_id'] = $organization->id;
            Priority::create($row);
        }

        $crows = [
            ['name' => 'Common', 'color' => '#aaa', 'icon_id' => 1],
            ['name' => 'Lifestyle', 'color' => '#999', 'icon_id' => 1],
            ['name' => 'Business', 'color' => '#888', 'icon_id' => 1],
            ['name' => 'Shopping', 'color' => '#777', 'icon_id' => 1],
            ['name' => 'Travel', 'color' => '#666', 'icon_id' => 1],
            ['name' => 'Unknown', 'color' => '#555', 'icon_id' => 1],
        ];

        foreach ($crows as $row) {
            $row['organization_id'] = $organization->id;
            Category::create($row);
        }

        return $user;
    }
}
