<?php

namespace App\Http\Controllers;

use App\Models\RatingType;
use Illuminate\Http\Request;
use App\Components\Response;
use Illuminate\Support\Facades\Validator;
use DB;
use Log;
class RatingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RatingType $ratingType,Request $request)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $distinct = $request->get('distinct');
            // $result = $ratingType->TypeOf($type)->get();
            $result = $distinct ? $ratingType->DistinctOnly()->get() : $ratingType->with(['ratings'])->get();
            return Response::success(200, "Success", ["data" => $result]);
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
        
    }

    public function test(RatingType $rating)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $result = $rating->all()->toArray();
            return response()->json($result);
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
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Position $position)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        //
    }
}
