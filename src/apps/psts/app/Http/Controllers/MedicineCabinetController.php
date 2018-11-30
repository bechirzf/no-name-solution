<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\Response;
use App\Models\Department;
use App\Models\Cabinet;
use App\Models\Topic;
use App\Models\Content;
use App\Models\Medicine;
use Illuminate\Validation\ValidationException;
use Validator;
use Log;
use DB;


class MedicineCabinetController extends Controller
{
    /**
     * Get the rules specified for the controller.
     */
    public function getRules($key,$id = null)
    {
        $rules = [
            #region for medicine
                'create' => [
                    'title' => 'required|string',
                    'description' => 'nullable|string',
                    'author' => 'required|string',
                    'date_published' => 'nullable|string',
                    'site_url' => 'required|string',
                    'image_url' => 'required|string',
                    'created_by' => 'required|string',
                    'content_id' => 'required|integer|exists:contents,id',
                    'topic_id' => 'required|integer|exists:topics,id',
                ],
                'update' => [
                    'id' =>  'required|exists:medicines',
                    'title' => 'sometimes|required|string',
                    'description' => 'sometimes|nullable|string',
                    'author' => 'sometimes|required|string',
                    'date_published' => 'nullable|string',
                    'site_url' => 'sometimes|required|string',
                    'image_url' => 'sometimes|required|string',
                    'updated_by' => 'required|string',
                    'content_id' => 'sometimes|required|integer|exists:contents,id',
                    'topic_id' => 'sometimes|required|integer|exists:topics,id',
                ],
            #endofregion
        ];
        return $rules[$key];
    }

    /**
     * Display a listing of the resource.
     *
     * @return App\Components\Response
     */
    public function index()
    {
        Log::info("Start - " . __METHOD__);
        try{
            $result = Medicine::all();
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
     * @return App\Components\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Nullable | Integer  $id
     * @return App\Components\Response
     */
    public function store(Request $request, $id = null)
    {
        /**** Sample Create/Update Objects 
            dev <- 172.30.229.24
            psts <- psts/public/api
            #1 URL:{{dev}}/{{psts}}/medicine
            {
                "title":"Listen, Learn… Then Lead",
                "author":"Stanley Mcchrystal",
                "description":"Four-star general Stanley McChrystal shares what he learned about leadership. How can you build a sense of shared purpose among people of many ages and skill sets? By listening and learning -- and addressing the possibility of failure.",
                "site_url":"https://embed.ted.com/talks/lang/en/stanley_mcchrystal",
                "image_url":"https://pi.tedcdn.com/r/pe.tedcdn.com/images/ted/1e1176d6968f6b244a1962d6231a5410fa7d8ef9_800x600.jpg?quality=89&w=800",
                "created_by":"Administrator",
                "content_id":2,
                "topic_id":15
            }
            #2 URL:{{dev}}/{{psts}}/medicine/1
            {
                "title":"The Puzzle of Motivation",
                "author":"Stanley Mcchrystal",
                "description":"Career analyst Dan Pink examines the puzzle of motivation, starting with a fact that social scientists know but most managers don't: Traditional rewards aren't always as effective as we think.",
                "site_url":"https://embed.ted.com/talks/dan_pink_on_motivation",
                "image_url":"https://pi.tedcdn.com/r/pe.tedcdn.com/images/ted/110884_800x600.jpg?quality=89&w=800",
                "created_by":"Administrator",
                "content_id":2,
                "topic_id":15
            }
        *****/

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
                $medicine = Medicine::updateOrCreate(['id' => $id], $data);
                DB::commit();
            }
            return Response::success(200, "Success", ["data" => $medicine]);
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  
     * @return App\Components\Response
     */
    public function edit(Medicine $medicine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  
     * @return App\Components\Response
     */
    public function update(Request $request, Medicine $medicine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  
     * @return App\Components\Response
     */
    public function destroy(Medicine $medicine)
    {
        //
    }

    /** Anonymous API Calls STARTS HERE **/
    
     /**
     * Display the specified resource.
     *
     * @param  $id integer
     * @return App\Components\Response
     */
    public function showDepartment($id)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $id = 1;
            $result = Department::with('cabinets')->find($id);
            return Response::success(200, "Success", ["data" => $result]);
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }
    

    /**
     * Display the specified resource.
     *
     * @param  $id integer
     * @return App\Components\Response
     */
    public function showCabinet($id)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $result = Cabinet::with('topics')->find($id);
            return Response::success(200, "Success", ["data" => $result]);
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  $id integer : cabinet_id
     * @return App\Components\Response
     */
    public function showTopic($id)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $result = Topic::with(['contents','medicines'])->find($id);
            $contents = $result['contents']?:[];
            $medicines = $result['medicines']?:[];
            foreach ($contents as $key => $value) {
                $count = 0;
                $id = $value['id'];
                foreach ($medicines as $i => $j) {
                    if($j['content_id'] === $id)$count++;
                }
                $value['count'] = $count;
                $contents[$key] = $value;
            }
            $result['contents'] = $contents;
            return Response::success(200, "Success", ["data" => $result]);
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  $id integer : medicine id
     * @return App\Components\Response
     */
    public function show($id)
    {
        Log::info("Start - " . __METHOD__);
        try{
            $result = Medicine::with(['content','topic'])->find($id);
            return Response::success(200, "Success", ["data" => $result]);
        } catch (\Exception $e) {
            $this->logException($e);
            Log::error(get_class($e). $e->getMessage());
            return Response::exception($e);
        }
    }
}