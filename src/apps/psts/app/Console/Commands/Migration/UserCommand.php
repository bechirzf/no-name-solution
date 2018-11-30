<?php

namespace App\Console\Commands\Migration;

use DB;
use Log;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Models\User;
use App\Models\Position;
use App\Models\Manager;
use App\Models\Department;
use App\Models\Office;
use App\Models\Group;

/**
 * Migrate the users details.
 *   
 * 
 * Migrate to
 *      create or update users table 
 *
 * @author original - syntax3rror  , customized version - r3xgamax
 */
class UserCommand extends Command {
    
    /**
     * The console command name.
     * ./artisan 
     *
     * @var string
     */
    protected $name = 'psts:user-migration';

    /**
     * User Arguments
     * ./artisan psts:user-migration
     * 
     * @var string Artisan command 
     */
    //protected $signature = 'psts:user-migration {per_page}';
                      

    /*
        @Sample use:
            php artisan psts:user-migration  --table=user --datasource=C:\xampp\htdocs\psts\storage\datasource\roster_upload.csv --row_start=2
    */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate User Related Tables 
                                    - @Sample use: 
                                        php artisan psts:user-migration  --table=user --datasource=C:\xampp\htdocs\psts\storage\datasource\roster_upload.csv --row_start=2';
    protected $lineStart = 0;
    protected $filePath = "C:\/";
    protected $database = null;
    protected $perPage = 0;
    protected $conn;
    protected $data = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Clear Database / Truncate or Delete
     *      
     * @return void
     */
    public function initialization(){
        // clear our database
        
    }
    /**
     * Execute the console command.
     * Same behavior with fire, only this  handle was proirity.
     * 
     * ./artisan psts:user-migration --per_page=0 --table=user
     * @return mixed
     */
    public function handle()
    {
        Log::info(__METHOD__. ' -> START' );
        
        $pagesProcess['command'] = [
            'start' => time(),
        ];

        if ($this->confirm('Do you wish to continue?')) {
            $this->info('[START]');
            $this->info('Migrating/Updating users data.');
        }else{
            $this->error('[CANCELED]');
            return ;
        }
        $this->info('[PARAMETERS]');
        $this->info('Per Page : '.$this->option('per_page'));
        $this->info('Table : '.$this->option('table'));
        $this->info('Data Source : '.$this->option('datasource'));
        $this->info('Row Start : '.$this->option('row_start'));

        $this->initialization();

        $header = [];
        $page = 1;
        $offset = 0;
        $this->perPage = $this->option('per_page');
        $this->lineStart = $this->option('row_start');
        $this->filePath = $this->option('datasource');
        $this->database = $this->option('table');
        $startTime = time();
        $pagesProcess['page '.$page]['start'] = $startTime;
        
        $header = ['employeeID','ISNumber','displayName','mail','manager','manager_mail','physicalDeliveryOfficeName','title','NTID','department','telephoneNumber'];

        if($this->database){
            $this->data = $this->getData($this->filePath,$header,$this->lineStart);
            echo "Data Count : ".count($this->data);
        }else{
            $this->info('No Specified Database table : '.$this->database);
        }

        DB::beginTransaction();
        try{
            $feed = 'feed'.ucfirst($this->database);
            if(method_exists($this,$feed)){
                $this->$feed();
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
            Log::error(get_class($e).' '.$e->getCode().': '.$e->getMessage().
                    "\n\t".$e->getFile().': '.$e->getLine());
            echo "\n";
            $this->error($e->getMessage());
        }

        $endTime = time();
        $pagesProcess['page '.$page]['end'] = $endTime;
        $pagesProcess['command']['end'] = $endTime;
        
        
        echo "\n=================================DONE======================\n";

        foreach ($pagesProcess as $key =>  $process){
            $this->info('Time usage ==> '.$key.': '. ($process['end'] - $process['start']));
            unset($pagesProcess[$key]);
        }
    }
    
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        \Log::info(__METHOD__.' -> Start ');

        return [
            ['per_page', null, InputOption::VALUE_OPTIONAL, 'Since data is too large, chuncked into pages', 2000],
            ['table', null, InputOption::VALUE_OPTIONAL, 'Database migration table', 'test'],
            ['datasource', null, InputOption::VALUE_OPTIONAL, 'File Directory', 'C:\/'],
            ['row_start', null, InputOption::VALUE_OPTIONAL, 'Exclude header of the file, if there is any', 1],
        ];
    }
    public function getData($filePath,$header,$row = 1)
    {
        try {
            // Read File
            $handle = fopen($filePath, "r");

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
    public function feedUser()
    {
        Log::info("Start - " . __METHOD__);
        $rosters = $this->data;
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
}

