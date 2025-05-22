<?php

namespace Database\Seeders;

use App\Models\Message\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::inRandomOrder()->take(3)->get();

        foreach ($users as $user) {
            Message::factory()->count(3)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
