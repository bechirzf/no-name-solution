<?php

namespace App\Console\Commands\Migration;

use DB;
use Log;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Models\User;
use App\Models\Manager;
use App\Models\Main;
use App\Models\Monitoring;
use App\Models\Rating;
use App\Models\RatingType;
use App\Models\Type;
use App\Models\Dashboard;

/**
 * Migrate the powercoach details.
 *   Main & Monitoring
 * 
 * Migrate to
 *      create rating 
 *      create type
 *      create rating type
 *      create main & monitoring
 *      link user's user_id and manager's manager_id
 *
 * @author original - syntax3rror  , customized version - r3xgamax
 */
class PowerCoachCommand extends Command {
    
    /**
     * The console command name.
     * ./artisan psts:powercoach-migration --per_page=33
     *
     * @var string
     */
    protected $name = 'psts:powercoach-migration';

    /**
     * PowerCoach Arguments
     * ./artisan psts:powercoach-migration
     * 
     * @var string Artisan command 
     */
    //protected $signature = 'psts:powercoach-migration {per_page}';
                      

    /*
        @Sample use:
            php artisan psts:powercoach-migration  --table=main --datasource=C:\xampp\htdocs\psts\storage\datasource\powercoach-main.csv --row_start=3 --per_page=23
    */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate PowerCoach Related Tables
                                    - @Sample use: 
                                        php artisan psts:powercoach-migration  --table=main --datasource=C:\xampp\htdocs\psts\storage\datasource\powercoach-main.csv --row_start=3 --per_page=23';
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
        $this->info('CLEAR DATABASE');
        $this->warn("[WARNING]: Make sure that all table with foreign key will be deleted first before the primary related table.");
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('main_dashboard')->truncate(); // ==> Pivot Table
        DB::table('main_type')->truncate(); // ==> Pivot Table
        DB::table('monitoring_rating_type')->truncate(); // ==> Pivot Table
        RatingType::truncate(); // ==> Pivot Table
        $modules = DB::table('types as t')->join('modules as m', 't.id', '=', 'm.type_id')->first();
        if(!$modules){
            $this->warn("[WARNING]: No Data in Modules Table ==> Proceed to Truncate");
            Type::truncate(); // ==> Primary Table Note : This will be used soon so careful on truncating this one. Check The Modules Table. If there is existing data that used that type.
        }
        Rating::truncate();
        Dashboard::truncate();
        Monitoring::truncate();
        Main::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->info('DONE cleaning the DB');


        /*Seed the data for Dashboard*/
        Dashboard::insert([
            array(
                'name'         => 'Agent Metrics',
                'url' => 'https://app.powerbi.com/groups/me/dashboards/a0acabfe-d1ac-45a3-9ce6-09c08b259e89',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Current Scorecard',
                'url' => 'https://app.powerbi.com/groups/me/dashboards/fcc10407-f49b-4f69-987c-0c6ea9adcc91',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Previous Scorecard',
                'url' => 'https://app.powerbi.com/groups/me/dashboards/4826371a-6c17-4a70-a3f3-bbb504ebc266',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Champ Scorecard',
                'url' => 'https://app.powerbi.com/groups/me/dashboards/194862bf-99e3-4607-bc3d-fae7f3700a2c',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            )
        ]);
        $this->info('Dashboards created!');

        /*Seed the data for Types*/
        $types = Type::whereIn('name',['Successes','Opportunity','Category','Experience'])->delete();
        Type::insert([
            array(
                'name'         => 'Successes',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Opportunity',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Category',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Experience',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
        ]);

        $this->info('Types seeded!');

        /*Seed the data for Ratings*/
        Rating::insert([
            array(
                'name'         => 'Outstanding',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Exceeds Expectations',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Meets Expectations',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Below Expectations',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Yes',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'No',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'N/A',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Attitude',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Understanding & Acknowledgment',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Full Solution',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'         => 'Customer Experience',
                'created_by' => 'Administrator',
                'created_at' => date("Y-m-d H:i:s"),
            ),
        ]);

        $this->info('Ratings created!');

        /*Seed the data for Rating Type which is the categories,experiences,successes, opportunities*/
        $rating_type = [];
        $ratings1 = Rating::whereIn('name',['Outstanding','Exceeds Expectations','Meets Expectations','Below Expectations'])->get();
        $ratings2 = Rating::whereIn('name',['Yes','No','N/A'])->get();
        $ratings3 = Rating::whereIn('name',['Attitude','Understanding & Acknowledgment','Full Solution','Customer Experience'])->get();
        $ratingTypeName1 = ['Attitude','Understanding & Acknowledgment','Full Solution','Customer Experience','Overall Rating'];
        $ratingTypeName2 = ['End User Collection','Close Date','Discussion of Budget','Proper Use of Hold','Reviewed the Call'];
        $ratingTypeName3 = ['Success #1','Success #2'];
        $ratingTypeName4 = ['Opportunity #1','Opportunity #2'];
        $type_id1=Type::where('name','Category')->first()->id;
        $type_id2=Type::where('name','Experience')->first()->id;
        $type_id3=Type::where('name','Successes')->first()->id;
        $type_id4=Type::where('name','Opportunity')->first()->id;

        foreach ($ratings1 as $rating) {
            foreach ($ratingTypeName1 as $name) {
                $rating_type[] = array(
                    'name'         => $name,
                    'type_id'      =>  $type_id1,
                    'rating_id'    =>  $rating['id'],
                    'created_by' => 'Administrator'
                );
            }
        }
        foreach ($ratings2 as $rating) {
            foreach ($ratingTypeName2 as $name) {
                $rating_type[] = array(
                    'name'         => $name,
                    'type_id'      =>  $type_id2,
                    'rating_id'    =>  $rating['id'],
                    'created_by' => 'Administrator'
                );
            }
        }
        foreach ($ratings3 as $rating) {
            foreach ($ratingTypeName3 as $name) {
                $rating_type[] = array(
                    'name'         => $name,
                    'type_id'      =>  $type_id3,
                    'rating_id'    =>  $rating['id'],
                    'created_by' => 'Administrator'
                );
            }
        }
        foreach ($ratings3 as $rating) {
            foreach ($ratingTypeName4 as $name) {
                $rating_type[] = array(
                    'name'         => $name,
                    'type_id'      =>  $type_id4,
                    'rating_id'    =>  $rating['id'],
                    'created_by' => 'Administrator'
                );
            }
        }

        RatingType::insert($rating_type);

        $this->info('Rating Types created!');

        $this->info('Proceed to Migration of 1on1 and Call Audit!');
    }
    /**
     * Execute the console command.
     * Same behavior with fire, only this  handle was proirity.
     * 
     * ./artisan psts:powercoach-migration --per_page=33 --table=main
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
            $this->info('Migrating 1 on 1 coaching and call audit monitoring.');
        }else{
            $this->error('[CANCELED]');
            return ;
        }
        $this->info('[PARAMETERS]');
        $this->info('Per Page : '.$this->option('per_page'));
        $this->info('Database Table : '.$this->option('table'));
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
        if(strtolower($this->database) == "main"){
            $header = [
                "Tech",
                "Supervisor",
                "1-1 DateTime",
                "Agent Metrics",
                "Current Scorecard",
                "Previous Scorecard",
                "Champ Scorecard",
                "Success #1",
                "Success #2",
                "Opportunity #1",
                "Opportunity #2",
                "Notes"
            ];
        }
        else if(strtolower($this->database) == "monitoring"){
            $header = [
                "Agent:",
                "Call Auditor:",
                "Supervisor:",
                "Date:",
                "Call URL:",
                "Quote ID:",
                "Attitude:",
                "Attitude Notes:",
                "Verify Request:",
                "Verify Request Notes:",
                "Full Solution:",
                "Full Solution Notes:",
                "Customer Experience:",
                "Customer Experience Notes:",
                "Overall Rating:",
                "Overall Rating Notes: ",
                "Top Success #1",
                "Top Success #1 Notes",
                "Top Success #2",
                "Top Success #2 Notes",
                "Top Opp #1",
                "Top Opp #1 Notes",
                "Top Opp #2",
                "Top Opp #2 Notes",
                "General Notes to Agent",
                "Logged Date",
                "IM Exp1",
                "IM Exp2",
                "IM Exp3",
                "IM Exp4",
                "IM Exp5"
            ];
        }

        if($this->database){
            $this->data = $this->getData($this->filePath,$header,$this->lineStart,$this->perPage);
            echo "Data Count : ".count($this->data);
        }else{
            $this->info('No Specified Datebase : '.$this->database);
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
            ['table', null, InputOption::VALUE_OPTIONAL, 'Database migration', ''],
            ['datasource', null, InputOption::VALUE_OPTIONAL, 'File Directory', 'C:\/'],
            ['row_start', null, InputOption::VALUE_OPTIONAL, 'Exclude header of the file, if there is any', 1],
        ];
    }

    public function getData($filePath,$header,$row = 1,$per_page = 1000)
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
                    if($ctr == $per_page) break;
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
            Log::error(get_class($e).' '.$e->getCode().': '.$e->getMessage().
                    "\n\t".$e->getFile().': '.$e->getLine());
        }
    }
    public function feedMain()
    {
        // Insert Data
        foreach ($this->data as $csv){
            $main = [];
            $main['user_id'] = User::where('name',$csv['Tech'])->first();
            $main['manager_id'] = Manager::where('name',$csv['Supervisor'])->first();
            $d=strtotime($csv['1-1 DateTime']);
            $main['coached_date'] = date('Y-m-d h:i:sa',$d);
            $main['notes'] = $csv['Notes'];
            $main['created_by'] = "Administrator";
            if(!$main['user_id']||!$main['manager_id'])continue;

            $main['user_id'] = $main['user_id']['id'];
            $main['manager_id'] = $main['manager_id']['id'];
            // Save the main details
            $main_created = Main::create($main);
            $dashboard = ["Agent Metrics","Current Scorecard","Previous Scorecard","Champ Scorecard"];
            $main_dashboard = [];
            foreach ($dashboard as $key) {
                if($csv[$key]){
                    $dashboard = Dashboard::where('name',$key)->first();
                    if($dashboard) $main_dashboard[] = ["main_id" => $main_created['id'],'dashboard_id' => $dashboard['id']];
                }
            }
            // Insert into main_dashboard after insert of main table
            DB::table('main_dashboard')->insert($main_dashboard);
            $main_type = [];
            if($csv['Success #1'] || $csv['Success #2']){
                $success = Type::where('name',"Successes")->first();
                if ($success) {
                    $successes = ['main_id' => $main_created['id'] , 'type_id' => $success['id'],'notes'=> trim($csv['Success #1']) ];
                    if(trim($csv['Success #1'])){
                        $main_type[] = $successes;
                    }elseif (trim($csv['Success #2'])) {
                        $successes['notes'] = trim($csv['Success #2']);
                        $main_type[] = $successes;
                    }
                }
            }else if($csv['Opportunity #1'] || $csv['Opportunity #2']){
                $oppurtunity = Type::where('name',"Opportunity")->first();
                if ($oppurtunity) {
                    $oppurtunities = ['main_id' => $main_created['id'] , 'type_id' => $oppurtunity['id'],'notes'=> trim($csv['Opportunity #1']) ];
                    if(trim($csv['Opportunity #1'])){
                        $main_type[] = $oppurtunities;
                    }elseif (trim($csv['Opportunity #2'])) {
                        $successes['notes'] = trim($csv['Opportunity #2']);
                        $main_type[] = $oppurtunities;
                    }
                }
            }

            Log::info('Basic Details ===> user_id : ' .$main['user_id'].'--- manager_id: '.$main['manager_id'].' 1on1: '.$main['coached_date'].' Notes: '.$main['notes']);
            Log::info('Pivot Details ===> Dashboards : ' .print_r($main_dashboard,1));
            Log::info('Pivot Details ===> Main Type : ' .print_r($main_type,1));
        }
    }
    public function feedMonitoring()
    {
        // Insert Data
        foreach ($this->data as $csv){
            
        }
    }
}

