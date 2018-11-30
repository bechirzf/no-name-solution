<?php
use Illuminate\Database\Seeder;
class ManagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create 10 managers using the model factory
        factory(App\Models\Manager::class, 10)->create();
    }
}