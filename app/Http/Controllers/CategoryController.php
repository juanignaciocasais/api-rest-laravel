<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Category;

class CategoryController extends Controller{

    public function __construct(){
        $this->middleware('api.auth',['except' => ['index', 'show']]);
    }

    public function index(){
        $categories = Category::all();

        return response ()->json ([  
            'code' => 200,
            'status' => 'success',
            'categories' => $categories 
        ]);
    }

    public function show($id){
        $category = Category::find($id);

        if (is_object($category)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'categories' => $category
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La categoria no existe'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        //RECOGER LOS DATOS POR POST
        $json = $request->input ('json',null);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){
        
            //VALIDAR LOS DATOS     
            $validate = \Validator::make($params_array, [
                'name' => 'required'
            ]);

            //GUARDAR LA CATEGORIA
            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha gaurdado la categoria'
                ];
            } else {
                $category = new Category();
                $category->name = $params_array['name'];
                $category-> save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'category' => $category
                ];
            }

        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No has enviado ninguna categoria'
            ];
        }

        //DEVOLVER EL RESULTADO
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request){
        //RECOGER LOS DATOS POR POST
        $json = $request->input ('json',null);
        $params_array = json_decode($json,true);
        
        if(!empty($params_array)){
            //VALIDAR LOS DATOS
            $validate = \Validator::make($params_array, [
                'name' => 'required'
            ]);

            //ELIMINAR LOS DATOS QUE NO QUIERO ACTUALIZAR
            unset($params_array['id']);
            unset($params_array['created_at']);
            
            //ACTUALIZAR LA CATEGORIA
            $category = Category::where('id',$id)->update($params_array);

            $data = [
                'code' => 200,
                'status' => 'success',
                'category' => $params_array
            ];

        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No has enviado ninguna categoria'
            ];
        }
        
        //DEVOLVER LOS DATOS
        return response()->json($data, $data['code']);    
    }


}
