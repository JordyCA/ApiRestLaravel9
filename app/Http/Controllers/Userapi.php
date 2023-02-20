<?php

namespace App\Http\Controllers;

use App\Models\Userapi as ModelsUserapi;
use Illuminate\Http\Request;



class Userapi extends Controller
{
    //
    public function setData(Request $request) {
        $getData = file_get_contents("https://jsonplaceholder.typicode.com/todos",false);
        $data = json_decode($getData);
        $statusSave = false;
        foreach ($data as $key => $value) {
            try {
                $statusSave = ModelsUserapi::addNewRow($value);
            } catch (\Throwable $th) {
                //throw $th;
                //echo "\n Ocurrio un error con el id $value->id";
                $statusSave = false;
            }
        }
        return response()->json([
            'status' => $statusSave,
            'message' => $statusSave ?'Se guardo la información' : 'Ocurrieron errores mientras se guardaba la información.'
        ] );
    }
    
    
    public function getTasks(Request $request) {

        $status = true;

        $values = new \stdClass;
        $values->page = $request->page;
        $values->per_page = $request->per_page;
        $values->search = $request->search;
        $values->user_id = $request->user_id;
        $values->completed = $request->completed;
        //echo var_dump($request);
        //echo var_dump($values);
        try {
            $data = ModelsUserapi::getData($values);
        } catch (\Throwable $th) {
            //throw $th;
            $status = false;
        }
       

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Se obtuvo la información' : 'Ocurrio un error en la obtención de la iformación',
            'data' => $data
        ] );

    }
}
