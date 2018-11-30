<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use App\Components\Response;
use Illuminate\Support\Facades\Validator;
use DB;
use Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Component\Response
     */
    public function index(Request $request)
    {
        $parameters = [
            'per_page' => $request->get('per_page'),
            'page' => $request->get('page'),
            'sort' => $request->get('sort') && in_array($request->get('sort'), ['asc', 'desc']) ?: 'asc'
        ];
        $include = explode(',', $request->get('include'));
        // if (Input::has('orderby')) { // Ordering 
        //     $segments = $segments->orderBy(Input::get('orderby'), $sort);
        // }
        $users = $request->get('include')? User::with($include)->orderBy('id','ASC')->paginate($parameters['per_page'], ['*'],'page', $parameters['page']) : User::orderBy('id','ASC')->paginate($parameters['per_page'], ['*'],'page', $parameters['page']);
       
        return Response::success(200, "Success", ["data" => $users]);
    }
    public function loggedin(Request $request){
        Log::info("Start - " . __METHOD__);
        try{
            $data = $request->user()->toArray();

            // $data = User::find($data['user_id']); 

            return Response::success(200, "Success", ["data" => $data]);
            
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \App\Component\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Component\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function show(Request $request,$id)
    {
        $include = explode(',', $request->get('include'));
        $user = $request->get('include')? User::where('id',$id)->with($include)->first() : User::find($id);
        return Response::success(200, "Success", ["data" => $user]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
           
            if($request->get('peripheral_number')){
                $user->peripheral_number = $request->get('peripheral_number');
                $user->save();
            }
            
            if($request->get('schedules')){
                $constants = [
                    'default',
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday'
                ];
                // if default is closer delete all schedules.
                $closerSchedule = Schedule::where('time_in',config('settings.constants.schedule.closer.time_in'))->first();
                // Log::info('Closer ID: '.$closerSchedule['id']);
                $raw_schedules = explode(',', $request->get('schedules'));
                $schedules = [];
                $user_schedule = DB::table('user_schedule')->where('user_id',$id);
                $arr_schedule_id = $user_schedule->pluck('schedule_id')->toArray();
                $new_user_schedule = [];
                // check the data if it is in correct format and also valid.
                // if($this->validate($request,[''])){
                    
                // }
                foreach ($raw_schedules as $val) {
                    $array_schedule = explode('=', $val);
                    $schedules[]=$array_schedule[1];
                    Log::info('day: '.$array_schedule[0].' ID: '.$array_schedule[1]);
                    if(($closerSchedule['id'] === $array_schedule[1] || $array_schedule[1]==="") && $constants[0] === $array_schedule[0]){ 
                        // This will delete all user schedule since all new schedule will only accept closer id so if it was the default no need to add and just delete the existing to just replace the default sched which is closer.
                        DB::table('user_schedule')->where('user_id',$id)->delete();
                    }
                    DB::table('user_schedule')->where(['user_id' => $id,'days' => $array_schedule[0]])->delete();
                    if(!$request->get('delete') && $array_schedule[1]){
                       DB::table('user_schedule')->insert([['user_id' => $id,'schedule_id' => $array_schedule[1],'days' => $array_schedule[0]]]);
                    }
                    break;
                }
            }
            $include = explode(',', $request->get('include'));
            $user = $request->get('include')? User::with($include)->where('id',$id)->first() : $user ;
            DB::commit();
            return Response::success(200, "Success", ["data" => $user]);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error(get_class($e).' '.$e->getCode().': '.$e->getMessage().
                    "\n\t".$e->getFile().': '.$e->getLine());
            return Response::error(200, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function destroy($id)
    {
        //
    }
}
