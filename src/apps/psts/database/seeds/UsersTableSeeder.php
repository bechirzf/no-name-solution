<?php
use Illuminate\Database\Seeder;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //create 10 users
        factory(App\Models\User::class, 10)->create();
        // Fetch the position ids
        // $pos_ids = App\Models\Position::all('id')->pluck('id')->toArray();
        // Fetch the user ids
        // $user_ids = App\Models\User::all('id')->take(2)->pluck('id')->toArray();
    }
}