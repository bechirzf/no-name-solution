<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
	/**
     * @const string FAIL_406 Error template
     */
    const FAIL_406 = 'Invalid Data';
    /**
     * @var array $data passed from the request body.
     */
    protected $data = array();

    protected function getData(Request $request = null)
    {
        $request = ($request)?:app()->request;
        if ($request->isJson()){
            return $request->json()->all();
        }else{
            return $request->all();
        }
    }
    protected function logException(\Exception $e){
        if ($e instanceof  ValidationException ){
            \Log::error('ValidationError: '.$e->validator->messages());

        }else{
            \Log::error(get_class($e).' '.$e->getCode().': '.$e->getMessage().
                    "\n\t".$e->getFile().': '.$e->getLine());
        }
        
    }

    protected function setData(array $data){
        //if no data
        if (empty($data)) {
            //there should be a global catch somewhere
            throw new \Exception(self::FAIL_406);
        }else{
            $this->data = $data;
        }

        return $this;
    }
}
