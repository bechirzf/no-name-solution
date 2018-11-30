<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Skill;



class SkillMatrixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Component\Response
     */
    public function index(Request $request)
    {
        $parameters = [
            'include' => $request->get('include') ? explode(',', $request->get('include')) : null
        ];
        $skills = $parameters['include'] ? Skill::with($parameters['include'])->paginate($request->get('per_page'), ['*'],'page', $request->get('page')) : Skill::paginate($request->get('per_page'), ['*'],'page', $request->get('page'));

        if(!$request->get('per_page')){
            $skills = $parameters['include'] ? Skill::with($parameters['include'])->all() : Skill::all();
        }
       
        return Response::success(200, "Success", ["data" => $skills]);
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
        $parameters = [
            'include' => $request->get('include') ? explode(',', $request->get('include')) : null
        ];
        $skill = $parameters['include'] ? Skill::where('id',$id)->with($parameters['include'])->first() : Skill::where('id',$id)->first();
        return Response::success(200, "Success", ["data" => $skill]);
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
