<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(['email' => 'asmaa@app.com'], [
                'name' => 'Asmaa',
                'email' => 'asmaa@app.com',
                'password' => Hash::make('123')
            ]
        );
    }
}
