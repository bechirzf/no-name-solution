<?php

namespace App\Console\Commands\Migration;

use DB;
use Log;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Models\Cabinet;
use App\Models\Topic;
use App\Models\Content;
use App\Models\Medicine;


/**
 * Seed the medicines,cabinets and topics details.
 *   
 * 
 * Seeds to
 *      create or update medicines,cabinets and topics table 
 *
 * @author original - syntax3rror  , customized version - r3xgamax
 */
class MedicineCabinetCommand extends Command {
    
    /**
     * The console command name.
     * ./artisan 
     *
     * @var string
     */
    protected $name = 'psts:medicine_cabinet-seeder';

    /**
     * User Arguments
     * ./artisan psts:medicine_cabinet-seeder
     * 
     * @var string Artisan command 
     */
    //protected $signature = 'psts:medicine_cabinet-seeder {per_page}';
                      

    /*
        @Sample use:
            php artisan psts:medicine_cabinet-seeder
    */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Medicine Cabinet Related Tables
                                    - @Sample use:
                                        php artisan psts:medicine_cabinet-seeder';
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
        Medicine::truncate();
        Content::truncate();
        Topic::truncate();
        Cabinet::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->info('DONE cleaning the DB for all Medicine Cabinet Related Tables');

        $this->info('Start Seeding...');

        Cabinet::insert([
            array(
                'name'       => 'PSTS Focus Areas',
                'pos'        => 1,
                'department_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'image_url'  => 'http://ingrammicroconnect/us/department/pss/PSTS/QualityandTraining/PublishingImages/Customer%20Focus.png'
            ),
            array(
                'name'       => 'IM Core Values',
                'pos'        => 2,
                'department_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'image_url'  => 'http://ingrammicroconnect/us/department/pss/PSTS/QualityandTraining/PublishingImages/Core%20Values.png'
            ),
            array(
                'name'       => 'IM Leadership Competencies',
                'pos'        => 3,
                'department_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'image_url'  => 'http://ingrammicroconnect/us/department/pss/PSTS/QualityandTraining/PublishingImages/Leadership%20Competencies.png'
            ),
        ]);

        $this->info('Cabinets created!');

        Topic::insert([
            /*---- PSTS Focus Areas -----*/
                array(
                    'name'       => 'Attitude',
                    'pos'        => 1,
                    'cabinet_id' => Cabinet::where('name','PSTS Focus Areas')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/PSTS%20Focus%20Areas/attitude%20grey.png'
                ),
                array(
                    'name'       => 'Acknowledgment & Understanding',
                    'pos'        => 2,
                    'cabinet_id' => Cabinet::where('name','PSTS Focus Areas')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/PSTS%20Focus%20Areas/acknowledgement%20and understanding%20grey.png'
                ),
                array(
                    'name'       => 'Full Solution',
                    'pos'        => 3,
                    'cabinet_id' => Cabinet::where('name','PSTS Focus Areas')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/PSTS%20Focus%20Areas/full%20solution%20grey.png'
                ),
                array(
                    'name'       => 'Customer Experience',
                    'pos'        => 4,
                    'cabinet_id' => Cabinet::where('name','PSTS Focus Areas')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/PSTS%20Focus%20Areas/customer%20experience%20grey.png'
                ),
            /*---- IM Core Values -----*/
                array(
                    'name'       => 'Innovation',
                    'pos'        => 1,
                    'cabinet_id' => Cabinet::where('name','IM Core Values')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Core%20Values/innovation%20grey.png'
                ),
                array(
                    'name'       => 'Accountability',
                    'pos'        => 2,
                    'cabinet_id' => Cabinet::where('name','IM Core Values')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Core%20Values/accountability%20grey.png'
                ),
                array(
                    'name'       => 'Integrity',
                    'pos'        => 3,
                    'cabinet_id' => Cabinet::where('name','IM Core Values')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Core%20Values/integrity%20grey.png'
                ),
                array(
                    'name'       => 'Teamwork and Respect',
                    'pos'        => 4,
                    'cabinet_id' => Cabinet::where('name','IM Core Values')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Core%20Values/teamwork%20grey.png'
                ),
                array(
                    'name'       => 'Learning',
                    'pos'        => 5,
                    'cabinet_id' => Cabinet::where('name','IM Core Values')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Core%20Values/learning%20grey.png'
                ),
                array(
                    'name'       => 'Social Responsibility',
                    'pos'        => 6,
                    'cabinet_id' => Cabinet::where('name','IM Core Values')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Core%20Values/social%20responsibility%20grey.png'
                ),
            /*---- IM Leadership Competencies -----*/
                array(
                    'name'       => 'Collaboration and Influence',
                    'pos'        => 1,
                    'cabinet_id' => Cabinet::where('name','IM Leadership Competencies')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Leadership%20Competencies/collaboration%20and%20influence%20grey.png'
                ),
                array(
                    'name'       => 'Results Oriented',
                    'pos'        => 2,
                    'cabinet_id' => Cabinet::where('name','IM Leadership Competencies')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Leadership%20Competencies/results%20oriented%20grey.png'
                ),
                array(
                    'name'       => 'Judgment and Decision Making',
                    'pos'        => 3,
                    'cabinet_id' => Cabinet::where('name','IM Leadership Competencies')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Leadership%20Competencies/judgement%20and%20decision%20making%20grey.png'
                ),
                array(
                    'name'       => 'Customer Mindset Top of Mind',
                    'pos'        => 4,
                    'cabinet_id' => Cabinet::where('name','IM Leadership Competencies')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Leadership%20Competencies/customer%20mindset%20grey.png'
                ),
                array(
                    'name'       => 'Team Leadership',
                    'pos'        => 5,
                    'cabinet_id' => Cabinet::where('name','IM Leadership Competencies')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Leadership%20Competencies/team%20leadership%20grey.png'
                ),
                array(
                    'name'       => 'Strategic and Global Mindset​',
                    'pos'        => 6,
                    'cabinet_id' => Cabinet::where('name','IM Leadership Competencies')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Leadership%20Competencies/strategic%20and%20global%20grey.png'
                ),
                array(
                    'name'       => 'Change Agent',
                    'pos'        => 7,
                    'cabinet_id' => Cabinet::where('name','IM Leadership Competencies')->first()['id'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Topics%20Images/IM%20Leadership%20Competencies/change%20agent%20grey.png'
                ),
        ]);

        $this->info('Topics created!');

        Content::insert([
            array(
                'name'       => 'Call Recordings',
                'pos'        => 1,
                'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Content%20Images/phone%20grey.png',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'       => 'Videos',
                'pos'        => 2,
                'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Content%20Images/videos%20grey.png',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'       => 'Articles',
                'pos'        => 3,
                'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Content%20Images/articles%20grey.png',
                'created_at' => date("Y-m-d H:i:s"),
            ),
            array(
                'name'       => 'Trainings',
                'pos'        => 4,
                'image_url'  => '/us/department/pss/PSTS/QualityandTraining/PublishingImages/Content%20Images/trainings%20grey.png',
                'created_at' => date("Y-m-d H:i:s"),
            ),
        ]);

        $this->info('Contents created!');

        $this->info('End Seeding...');
    }
    /**
     * Execute the console command.
     * Same behavior with fire, only this  handle was proirity.
     * 
     * ./artisan php artisan psts:medicine_cabinet-seeder
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
            $this->info('Seeding/Updating Medicine Cabinet data.');
        }else{
            $this->error('[CANCELED]');
            return ;
        }
        $this->initialization();

        $page = 1;
        $offset = 0;
        $startTime = time();
        $pagesProcess['page '.$page]['start'] = $startTime;
        
        DB::beginTransaction();
        try{
            
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
        ];
    }
}

