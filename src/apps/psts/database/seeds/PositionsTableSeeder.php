<?php
use Illuminate\Database\Seeder;
class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create 10 positions using the model factory
        factory(App\Models\Position::class, 10)->create();
    }
}