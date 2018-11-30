<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Components\Response;
use Illuminate\Validation\ValidationException;
use Validator;
use Log;
use DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info("Start - " . __METHOD__);
        try{
            $result = Department::all();
            return Response::success(200, "Success", ["data" => $result]);
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department,$id)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $id = 1;
            $result = $department->with('cabinets')->find($id);
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
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department,$id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department,$id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department,$id)
    {
        //
    }
}
