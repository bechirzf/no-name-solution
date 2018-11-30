<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\Response;
use App\Models\Schedule;
use Log;
use DB;

class ScheduleController extends Controller
{
    /**
     * Get the rules specified for the controller.
     */
    public function getRules($key,$id = null)
    {
        $rules = [
            #region for 1on1
                'create' => [
                    'name' => 'required|string',//should be unique
                    'time_in' => 'required|date_format:H:i',
                    'time_out' => 'required|date_format:H:i|after:time_in'//should greater than time_in
                ],
                'update' => [
                    'id' =>  'required|exists:schedules',
                    'name' => 'required|string',//should be unique
                    'time_in' => 'required|date_format:H:i',
                    'time_out' => 'required|date_format:H:i|after:time_out'//should greater than time_in
                ],
            #endofregion
        ];
        return $rules[$key];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \App\Component\Response
     */
    public function index()
    {
        // $result = Schedule::all()->sortByDesc('time_in');
        $result = Schedule::orderByRaw("STR_TO_DATE(`time_in`,'%h:%i')")->get();
        return Response::success(200, "Success", ["data" => $result]);
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
    public function store(Request $request,$id=null)
    {
        /*{
            "name":""
            "time_in":"",
            "time_out":"",
        }*/ 

        Log::info("Start - " . __METHOD__);
        DB::beginTransaction();
        try{
            $data = $this->getData($request);
            if ($id){
                $request->merge(['id' => $id]);
            }
            $rules = $this->getRules($id ? 'update' : 'create');
            // check the data if it is in correct format and also valid.
            if($this->validate($request,$rules)){
                $data['time_in'] = date('H:i', strtotime($data['time_in']));
                $data['time_out'] = date('H:i', strtotime($data['time_out']));
                $schedule = Schedule::updateOrCreate(['id' => $id], $data);
                DB::commit();
            }
            return Response::success(200, "Success", ["data" => $schedule]);
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Component\Response
     */
    public function show(Request $request,$id)
    {
        $parameters = [
            'include' => $request->get('include') ? explode(',', $request->get('include')) : null
        ];
        $schedule = $parameters['include'] ? Schedule::where('id',$id)->with($parameters['include'])->first() : Schedule::where('id',$id)->first();
        return Response::success(200, "Success", ["data" => $schedule]);
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
        //
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
