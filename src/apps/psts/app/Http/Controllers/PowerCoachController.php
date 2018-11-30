<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\Response;
use App\Models\User;
use App\Models\Manager;
use App\Models\Main;
use App\Models\Monitoring;
use Illuminate\Validation\ValidationException;
use Validator;
use Log;
use DB;

class PowerCoachController extends Controller
{
    protected $party;
    protected $role;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {
        $party = $request->user();
        $manager = Manager::where('email',$party['email'])->where('deleted_at',NULL)->first();
        // @TODO: Get the role of the user
        $this->role = $manager ?'manager':'user';
        $class = "App\Models\\".ucfirst($this->role);
        // Get the manager_id/user_id
        $this->party = $manager ?:$class::where('email',$party['email'])->where('deleted_at',NULL)->first();
    }
    /**
     * Get the rules specified for the controller.
     */
    public function getRules($key,$id = null)
    {
        $rules = [
            #region for 1on1
                'create' => [
                    'user_id' => 'required|integer|exists:users,id,deleted_at,NULL',
                    'manager_id' => 'required|integer|exists:managers,id,deleted_at,NULL',
                    'dashboard' => 'present|array',
                    'main_type' => 'nullable|array',
                    'notes' => 'nullable|string'
                ],
                'update' => [
                    'id' =>  'required|exists:mains',
                    'dashboard' => 'present|array',
                    'main_type' => 'nullable|array',
                    'notes' => 'nullable|string'
                ],
            #endofregion
            #region for call audit
                'monitoring.create' => [
                    'user_id' => 'required|integer|exists:users,id,deleted_at,NULL',// should not be the user
                    'manager_id' => 'required|integer|exists:managers,id,deleted_at,NULL|not_in:'.$id, // SHOULD NOT BE the Manager id of the user
                    'call_date' => 'required', // Add Date Format
                    'call_url' => 'required|string',
                    'quote_id' => 'required|string',// TODO :  Call OR QUERY FROM IM360 Database if the quote_id Exist or valid
                    'rating_type' => 'required|array', // TODO : This must be required.
                    'rating_type.*.rating_type_id'  => 'required|integer|exists:rating_type,id',
                    'rating_type.*.notes'           => 'nullable|string',
                    'notes' => 'nullable|string',
                    'created_by'  => 'required|exists:managers,email,deleted_at,NULL',
                ],
                'monitoring.update' => [
                    'id' =>  'required|exists:monitorings',
                    'call_date' => 'required', // Add Date Format
                    'call_url' => 'required|string',
                    'quote_id' => 'required|string', // TODO :  Call OR QUERY FROM IM360 Database if the quote_id Exist or valid
                    'rating_type' => 'required|array', // TODO : This must be required.
                    'rating_type.*.rating_type_id'  => 'required|integer|exists:rating_type,id',
                    'rating_type.*.notes'           => 'nullable|string',
                    'notes' => 'nullable|string',
                    'updated_by'  => 'required|exists:managers,email,deleted_at,NULL',
                ],
            #endofregion
        ];
        return $rules[$key];
    }
    /**
     * Display all lists of agents that have been coached by the user as supervisor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Component\Response
     */
    public function mainIndex()
    {
        Log::info("Start - " . __METHOD__);
        try{
            // Get all 1on1 coaching of that current user as agent OR manager
            $data = Main::with('users')->where([$this->role.'_id' => $this->party['id']])->paginate(100);
            return Response::success(200, "Success", ["data" => $data]);
        }  catch (\Exception $e){
            $this->logException($e);
            return Response::exception($e);
        }
        
    }
    /**
     * Display all lists of agents that have been randomly call audited by other supervisors and by the user as supervisor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Component\Response
     */
    public function monitoringIndex()
    {
        Log::info("Start - " . __METHOD__);
        try{
            // Get all call audit of that current user as agent OR manager
            $data = Monitoring::with(['users','managers','auditors'])->where([$this->role.'_id' => $this->party['id']])->paginate(100);
            
            $result["data"] = $data;
            $result["other"] = null;
            /*
            * If i am the manager i should also see the list of agents who i was call audited.
            */
            if($this->role == 'manager') {
                // users who were call audited by the current user as manager
                $call_audited = Monitoring::with(['users','managers','auditors'])->where(['created_by' => $this->party['email']])->paginate(100);
                $result["other"] = $call_audited;
            }
            return Response::success(200, "Success", $result);
        }  catch (\Exception $e){
            $this->logException($e);
            return Response::exception($e);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function showMain($id)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $main = Main::with(['users','dashboards','types'])->find($id);
            $result = ($main[$this->role.'_id'] === $this->party['id'])? $main:null;
            return Response::success(200, "Success", ["data" => $result]);
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function showMonitoring($id)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $monitoring = Monitoring::with(['users','rating_type'])->find($id);
            $allowView = ($this->role === 'user' && $monitoring[$this->role.'_id'] === $this->party['id']) || ($monitoring['created_by'] === $this->party['email']);
            $result = $allowView ? $monitoring:null;
            return Response::success(200, "Success", ["data" => $monitoring]);
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }

    /**
     * Create or Update 1 on 1 Coaching
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function createOrEdit(Request $request, $id = null)
    {
        /*{
            "user_id":"",
            "manager_id":""
            "coached_date":"",
            "dashboard":[],
            "main_type":[],
            "notes":""
        }*/ 
        /** 
            ****You can only add/update 1on1 coaching if the users manager_id is equak to the current logged in user_id.
            ****validate the manager_id if it's the correct manager_id of that user_id
            ****
        **/

        Log::info("Start - " . __METHOD__);
        DB::beginTransaction();
        try{
            if(isset($this->party['id'])){
                $request->merge(['manager_id' => $this->party['id']]);
            }
            $data = $this->getData($request);
            if ($id){
                $request->merge(['id' => $id]);
                unset($data['user_id']);
                unset($data['manager_id']);
            }else{
                $data['coached_date'] = date('Y-m-d H:i:s');
            }
            $rules = $this->getRules($id ? 'update' : 'create');
            // check the data if it is in correct format and also valid.
            if($this->validate($request,$rules)){
                $main = Main::updateOrCreate(['id' => $id], $data);
                if($id){
                    $mainDashExist = DB::table('main_dashboard')->where('main_id',$id);
                    $mainTypeExist = DB::table('main_type')->where('main_id',$id);
                    if($mainDashExist->count()){
                        $mainDashExist->delete();
                    }
                    if($mainTypeExist->count()){
                        $mainTypeExist->delete();
                    }
                }
                $main_dashboard = [];
                foreach ($data['dashboard'] as $dash) {
                    $main_dashboard[] = ['main_id' => $main['id'],'dashboard_id' => $dash];
                }
                $main_type = [];
                // Types can be successes and oppurtunities id's in the types table
                foreach ($data['main_type'] as $key => $value) {
                    $main_type[] = ['main_id' => $main['id'],'type_id' => $value['type_id'] , 'notes' =>  $value['notes']];
                }
                DB::table('main_dashboard')->insert($main_dashboard);
                DB::table('main_type')->insert($main_type);
                DB::commit();
            }
            return Response::success(200, "Success", ["data" => $main]);
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }

    }

    /**
     * Store or Update a call audit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function storeOrUpdate(Request $request, $id = null)
    {
        /*{
            "user_id":"", --on create only
            "manager_id":"", // You are not allowed to audit an agent that is under your supervision. 
                                // - Show or allow the manager to pick only agents of other teams. // on create only
            "call_date":"",
            "call_url":"",
            "quote_id":"",
            "rating_type":[
                {"rating_type_id":"","notes":""}
            ],
            "notes":""
        }*/
        Log::info("Start - " . __METHOD__);
        DB::beginTransaction();
        try{

            $request->merge([($id?'updated_by':'created_by') => $this->party['email']]);
            if($id){
                $request->merge(['id' => $id]);
            }else{
                $user = User::find($request->get('user_id'));
                if($user){
                    $request->merge(['manager_id' => $user['manager_id']]);
                }
            }
            $data = $this->getData($request);
            $rules = $this->getRules(($id ? 'monitoring.update' : 'monitoring.create'),$this->party['id']);
            // check the data if it is in correct format and also valid.
            if($this->validate($request,$rules)){
                $monitoring = Monitoring::updateOrCreate(['id' => $id], $data);
                if($id){
                    $callAuditExist = DB::table('monitoring_rating_type')->where('monitoring_id',$id);
                    if($callAuditExist->count()){
                        $callAuditExist->delete();
                    }
                }
                $monitoring_rating_type = [];
                // Types can be successes , opportunities,Category,Experience id's in the types table
                foreach ($data['rating_type'] as $details) {
                    $monitoring_rating_type[] = [
                        'monitoring_id' => $monitoring['id'],
                        'rating_type_id' => $details['rating_type_id'],
                        'notes' =>  $details['notes']
                    ];
                }
                DB::table('monitoring_rating_type')->insert($monitoring_rating_type);
                DB::commit();
            }
            return Response::success(200, "Success", ["data" => $monitoring]);
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function destroy(Request $request,$id)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $request->merge(['id' => $id]);
            $this->validate($request, ['id' => 'required|numeric']);
            
            //  Throw exception if id is not exist.
            $asset = Main::findOrFail($id);
            
            // Delete Entry to DB
            Main::destroy($id);
            
            return Response::success(200, "Success", ['data' => ['id' => $id]]);
            
        }  catch (\Exception $e){
            $this->logException($e);
            return Response::exception($e);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function delete(Request $request,$id)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $request->merge(['id' => $id]);
            $this->validate($request, ['id' => 'required|numeric']);
            
            //  Throw exception if id is not exist.
            $asset = Monitoring::findOrFail($id);
            
            // Delete Entry to DB
            Monitoring::destroy($id);
            
            return Response::success(200, "Success", ['data' => ['id' => $id]]);
            
        }  catch (\Exception $e){
            $this->logException($e);
            return Response::exception($e);
        }
    }
}
