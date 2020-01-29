<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;

class UserController extends Controller
{
    public function pruebas(Request $request){
        return "Acción de pruebas de USER-CONTROLLER";
    }

    public function register(Request $request){

        //RECOGER LOS DATOS DEL USUARIO POR POST
        $json = $request ->input('json', null);
        $params = json_decode($json);               //Objeto
        $params_array = json_decode($json, true);   //Array

        if (!empty($params_array) && !empty($params)){

            //LIMPIAR DATOS

            $params_array = array_map ('trim', $params_array);

            //VALIDAR LOS DATOS

            $validate = \Validator::make($params_array, [
                'name'      => 'required|alpha',
                'surname'   => 'required|alpha',
                'email'     => 'required|email|unique:users', //Comprobar si el usuario ya existe
                'password'  => 'required'
            ]);

            if ($validate->fails()){
                //LA VALIDACIÓN HA FALLADO
                $data = array (
                    'status' => 'error',
                    'code'   => 404,
                    'message'=> 'El usuario no se ha creado',
                    'errors' => $validate->errors()
                );
            }else{
                //VALIDACIÓN PASADA CORRECTAMENTE

                //CIFRAR LA CONTRASEÑA
                $pwd = hash('sha256', $params->password);

                //CREAR EL USUARIO
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = 'ROLE_USER';

                //GUARDAR EL USUARIO
                $user->save();
            

                $data = array (
                    'status' => 'success',
                    'code'   => 200,
                    'message'=> 'El usuario se ha creado correctamente',
                    'user'   => $user
                );
            } 

        }else{
            $data = array (
                'status' => 'error',
                'code'   => 404,
                'message'=> 'No se han ingresado los datos correctamente' 
            );
        } 
        
        return response()->json($data, $data['code']);  
    }

    public function login(Request $request){

        $jwtAuth = new \JwtAuth();

        //RECIBIR LOS DATOS POR POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        //VALIDAR LOS DATOS

        $validate = \Validator::make($params_array, [
            'email'     => 'required|email', 
            'password'  => 'required'
        ]);

        if ($validate->fails()){
            //LA VALIDACIÓN HA FALLADO
            $data = array (
                'status' => 'error',
                'code'   => 404,
                'message'=> 'El usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
        }else{
            //CIFRAR LA PASSWORD
            $pwd = hash('sha256', $params->password);

            //DEVOLVER TOKEN O DATOS
            $signup = $jwtAuth->signup($params->email, $pwd);
            if(!empty($params->gettoken)){
                $signup = $jwtAuth->signup($params->email, $pwd, true);
            }
        }
        return response()->json($signup, 200);
    }

    public function update(Request $request){

        //COMPROBAR SI EL USUARIO ESTA IDENTIFICADO
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        //RECOGER LOS DATOS POR POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if($checkToken && !empty($params_array)){
            

            //SACAR USUARIO IDENTIFICADO
            $user = $jwtAuth->checkToken($token, true);
                                
            //VALIDAR LOS DATOS
            $validate =\Validator::make($params_array, [
                'name'      => 'required|alpha',
                'surname'   => 'required|alpha',
                'email'     => 'required|email|unique:users'
            ]);

            //QUITAR LOS CAMPOS QUE NO QUIERO ACTUALIZAR
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);

            //ACTUALIZAR EL USUARIO EN BBDD
            $user_update = User::where('id', $user->sub)->update($params_array); 
            
            //DEVOLVER ARRAY CON RESULTADO
            
            $data = array(
                'code'      => 200,
                'status'    => 'success',
                'user'      => $user,
                'changes'   => $params_array
            );
            

        }else{
            $data = array(
                'code'      => 404,
                'status'    => 'error',
                'message'   => 'El usuario no está identificado'
            );
        }
        
        return response()->json($data, $data['code']);
    }

    public function upload(Request $request){
        //RECOGER LOS DATOS DE LA PETICIÓN
        $image = $request->file('file0');

        //VALIDACIÓN DE LA IMAGEN
        $validate = \Validator::make($request->all(), [
            'file0'=> 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        //GUARDAR LA IMAGEN
        if(!$image || $validate->fails()){
            $data = array(
                'code'      => 404,
                'status'    => 'error',
                'message'   => 'Error al subir la imagen'
            );
        }else {
            $image_name = time().$image->getclientOriginalName();
            \Storage::disk('users')->put($image_name, \File::get($image));

            $data = array(
                'code'  => 200,
                'status'=> 'success',
                'image' => $image_name
            );
        }

        return response()->json($data, $data['code']);
 
    }

    public function getImage($filename){
        $isset = \Storage::disk('users')->exists($filename);
        if($isset){
            $file = \Storage::disk('users')->get($filename);
            return new Response($file, 200);
        }else{
            $data = array(
                'code'  => 404,
                'status'=> 'error',
                'message' => 'La imagen no existe.'
            );

        return response()->json($data, $data['code']);
        
        }
    }

    public function detail($id){
        $user = User::find($id);

        if (is_object($user)){
            $data = array(
                'code'  => 200,
                'status'=> 'success',
                'user' => $user
            );
        }else {
            $data = array(
                'code'  => 404,
                'status'=> 'error',
                'message' => 'El usuario no existe.'
            );
        
        }
    
    return response()->json($data, $data['code']);    
        
    }


}