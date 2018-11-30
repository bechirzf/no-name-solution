<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Position;
use App\Models\Manager;
use App\Models\Department;
use App\Models\Office;
use App\Models\Skill;
use App\Models\Group;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Model::unguard();
        // Register the user seeder
        $this->call(UsersTableSeeder::class);
        Model::reguard();*/

        /*******************************************************
        *   Data Feed Starts Here
        ********************************************************/
        // Get Storage Path
        $storagePath = storage_path().'/datasource';
        $this->feedUserFromRoster($storagePath);
        $this->command->info('Roster is ready!');
        $this->feedSkillMatrix($storagePath);
        $this->command->info('Skill Matrix is ready!');
    }

    public function getData($storagePath,$filename,$header,$row = 1)
    {
        try {
            // Read File
            $handle = fopen($storagePath.'/'.$filename, "r");

            if ($handle) {
                $ctr = 1;
                $status = [
                    'failed' => [
                        'count' =>    0,
                        'lines' => [],
                    ],
                    'success' =>[
                        'count' =>    0,
                        'lines' => [],
                    ]
                ];
                
                $data = [];
                while (($line = fgets($handle)) !== false) {
                    
                    $arrayData = str_getcsv($line, ',');
                    
                    if($ctr >= $row){
                        $csv = [];
                        foreach($arrayData as $index => $column){
                            // Limit Column Here
                            if(isset($header[$index])){
                                // Map the data.
                                $csv[$header[$index]] = $column;
                            }
                        }
                        // \Log::info('CTR : ' .$ctr.'--- ROW: '.$row.' csv: '.print_r($csv, 1));
                        if(empty(trim(implode("",$csv)))) break;
                        $data[] = $csv;
                    }
                    $ctr++;
                }

                fclose($handle);
                return $data;
                    
            } else {
                return [];
            }
        } catch (\Exception $e) {
            \Log::error(get_class($e).' '.$e->getCode().': '.$e->getMessage().
                    "\n\t".$e->getFile().': '.$e->getLine());
        }
    }

    public function feedSkillMatrix($storagePath)
    {
        $filename = "skillmatrix.csv";
        $header = ['Precision Queue','Agent_Name','PeripheralNumber'];
        $skills = $this->getData($storagePath,$filename,$header,3);
        \DB::beginTransaction();
        try{
            $user_skill = [];
            // Insert Data
            foreach ($skills as $csv){
                // Skill
                $skill = Skill::where('code',$csv['Precision Queue'])->first();
                if(!$skill){
                    $skill = new Skill();
                    $skill->code = $csv['Precision Queue'];
                    $skill->name = $csv['Precision Queue'];
                    $skill->save();
                }
                // User
                $user = User::where(['name' => strtolower($csv['Agent_Name']),'deleted_at' => null]);
                if($user->first()){
                    $user->update(['peripheral_number'=>$csv['PeripheralNumber']]);
                    // User Skill
                    $user_skill[] = ['user_id' =>$user->first()['id'],'skill_id' => $skill['id']];
                }else{
                    $user = User::where(['peripheral_number' => strtolower($csv['PeripheralNumber']),'deleted_at' => null])->first();
                    if($user){
                        $user_skill[] = ['user_id' =>$user['id'],'skill_id' => $skill['id']];
                    }
                }
            }
            \DB::table('user_skill')->insert($user_skill);
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            \Log::error(get_class($e).' '.$e->getCode().': '.$e->getMessage().
                    "\n\t".$e->getFile().': '.$e->getLine());
            $this->command->error($e->getMessage());
        }

    }
    public function feedUserFromRoster($storagePath)
    {
        Log::info("Start - " . __METHOD__);
        $filename = "roster_upload.csv";
        $header = ['employeeID','ISNumber','displayName','mail','manager','manager_mail','physicalDeliveryOfficeName','title','NTID','department','telephoneNumber'];
        $rosters = $this->getData($storagePath,$filename,$header,2);
        \DB::beginTransaction();
        try{
            $manager_email = [];
            // Insert Data
            foreach ($rosters as $csv){
                $usersEmail = trim(strtolower($csv['mail']));
                $managersEmail = trim(strtolower($csv['manager_mail']));
                $user = User::where('email',$usersEmail)->first();
                if(!$user){
                   $data = [
                        'name' =>  $csv['displayName'], // Read Only
                        'employee_id' =>  $csv['employeeID'], // Read Only
                        'email' =>  $usersEmail, // Read Only 
                        'username' => $csv['NTID'], // Read Only
                        'telephone_number' => $csv['telephoneNumber'],
                   ]; 
                   $new_user = User::processRosterFeed($data);
                }

                $position = Position::where('title',$csv['title'])->first();
                if(!$position){
                    $new_position = new Position();
                    $new_position->title = $csv['title'];
                    $new_position->save();
                }

                $manager = Manager::where('email',$managersEmail)->first();
                if(!$manager){
                    $new_manager = new Manager();
                    $new_manager->name = $csv['manager'];
                    $new_manager->email = $managersEmail;
                    $new_manager->save();
                }

                $office = Office::where('name',$csv['physicalDeliveryOfficeName'])->first();
                if(!$office){
                    $new_office = new Office();
                    $new_office->name = $csv['physicalDeliveryOfficeName'];
                    $new_office->save();
                }

                $department = Department::where('name',$csv['department'])->first();
                if(!$department){
                    $new_department = new Department();
                    $new_department->name = $csv['department'];
                    $new_department->save();
                }
                if(!in_array($csv['manager_mail'], $manager_email)){
                    $manager_email[]= $managersEmail;
                }
            }
            
            // Update Data
            foreach ($rosters as $csv){
                $office = Office::where('name',$csv['physicalDeliveryOfficeName'])->first();
                $department = Department::where('name',$csv['department'])->first();
                $position = Position::where('title',$csv['title'])->first();
                $manager = Manager::where('email',$csv['manager_mail'])->first();
                User::where('email',trim(strtolower($csv['mail'])))
                    ->update([
                        'manager_id' =>  $manager['id'],
                        'office_id' => $office['id'],
                        'position_id' => $position['id'],
                        'department_id' => $department['id'],
                    ]);

            }
            foreach ($manager_email as $value) {
                $user = User::where('email',$value)->first();
                Manager::where('email',$value)->update(['position_id' => $user['position_id']]);
            }
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            \Log::error(get_class($e).' '.$e->getCode().': '.$e->getMessage().
                    "\n\t".$e->getFile().': '.$e->getLine());
            $this->command->error($e->getMessage());
        }
    }
    public function setDefaultAdminGroup($windowsNT){
        Log::info("Start - " . __METHOD__);
        \DB::beginTransaction();
        try {
            $user = User::where('username',$windowsNT)->first();
            $data = [
                'name' => "Administrators",
                'description' => 'System Administrators',
                'user_id' => $user['id'],
                'created_by' => $user['name'],
                'updated_by' => $user['name'],
            ];
            Group::updateOrCreate(['id' => $id], $data);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error(get_class($e).' '.$e->getCode().': '.$e->getMessage().
                    "\n\t".$e->getFile().': '.$e->getLine());
            $this->command->error($e->getMessage());
        }
    }
}
