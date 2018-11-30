<?php

namespace App\Console\Commands\Migration;

use DB;
use Log;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Models\User;
use App\Models\Skill;

/**
 * Migrate the CUIC skill details.
 * 
 * Migrate to user and user_skills
 *
 * @author original - syntax3rror  , customized version - r3xgamax
 */
class SkillCommand extends Command {
    
    /**
     * The console command name.
     * ./artisan psts:skill-migration --per_page=33
     *
     * @var string
     */
    protected $name = 'psts:skill-migration';

    /**
     * Skill Matrix Arguments
     * ./artisan psts:skill-migration
     * 
     * @var string Artisan command 
     */
    //protected $signature = 'psts:skill-migration {per_page}';
                      

    /*
        @Sample use:
            php artisan psts:skill-migration  --database=skill --datasource=C:\xampp\htdocs\psts\storage\datasource\skillmatrix.csv --row_start=3
    */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Skills Related Tables
                                    - @Sample use: 
                                        php artisan psts:skill-migration  --table=skill --datasource=C:\xampp\htdocs\psts\storage\datasource\skillmatrix.csv --row_start=3';
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
    }
    /**
     * Execute the console command.
     * Same behavior with fire, only this  handle was proirity.
     * 
     * ./artisan psts:skill-migration --per_page=33 --table=skill
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

        $header = ['Precision Queue','Agent_Name','PeripheralNumber'];

        if($this->database){
            $this->data = $this->getData($this->filePath,$header,$this->lineStart,$this->perPage);
            echo "Data Count : ".count($this->data);
        }else{
            $this->info('No Specified Database Table : '.$this->database);
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
            ['table', null, InputOption::VALUE_OPTIONAL, 'Database table migration', ''],
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
                        \Log::info('CTR : ' .$ctr.'--- ROW: '.$row.' csv: '.print_r($csv, 1));
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
    public function feedSkill()
    {
        $skills = $this->data;
        DB::beginTransaction();
        try{
            $user_skill = [];
            // Insert Data
            foreach ($skills as $csv){
                // Skill
                $skill = Skill::where('code',$csv['Precision Queue'])->first();
                if(!$skill){
                    $skill = new Skill();
                    $skill->code = $csv['Precision Queue'];
                    $skill->name = str_replace('_', ' ',str_replace("US_BUF_TS_", "", $csv['Precision Queue']));
                    $skill->save();
                }
                // User
                $user = User::where(['name' => strtolower($csv['Agent_Name']),'deleted_at' => null]);
                if($user->first()){
                    $user->update(['peripheral_number'=>$csv['PeripheralNumber']]);
                    $exist = DB::table('user_skill')->where(['user_id' =>$user->first()['id'],'skill_id' => $skill['id']])->count() > 0;
                    if(!$exist)$user_skill[] = ['user_id' =>$user->first()['id'],'skill_id' => $skill['id']];
                }else{
                    $user = User::where(['peripheral_number' => strtolower($csv['PeripheralNumber']),'deleted_at' => null])->first();
                    if($user){
                        $exist = DB::table('user_skill')->where(['user_id' =>$user['id'],'skill_id' => $skill['id']])->count() > 0;
                        if(!$exist)$user_skill[] = ['user_id' =>$user['id'],'skill_id' => $skill['id']];
                    }
                }
            }
            DB::table('user_skill')->insert($user_skill);
            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
            \Log::error(get_class($e).' '.$e->getCode().': '.$e->getMessage().
                    "\n\t".$e->getFile().': '.$e->getLine());
            $this->command->error($e->getMessage());
        }

    }
}

