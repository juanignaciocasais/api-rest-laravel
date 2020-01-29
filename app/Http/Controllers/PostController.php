<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Post;
use App\Helpers\JwtAuth;  

class PostController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth',['except' => [
            'index', 
            'show', 
            'getImage',
            'getPostsByCategory',
            'getPostsByUser'
        ]]);
    }

    public function index(){
        $posts = Post::all()->load('category');

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'post' => $posts
        ], 200);
    }

    public function show($id){
        $post = Post::find($id)->load('category');

        if(is_object($post)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $post
            ];
        } else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La entrada no existe.'
            ]; 
        }

        return response()->json($data, $data ['code']);

    }

    public function store(Request $request){
        //RECOGER DATOS POR POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            //CONSEGUIR EL USUARIO IDENTIFICADO
            $user = $this->getIdentity($request);
            
            //VALIDAR LOS DATOS
            $validate =\Validator::make($params_array, [
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required',
                'image' => 'required'
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha guardadpo el post, faltan datos.'
                ]; 

            } else {
                //GUARDAR EL POST (ARTICULO)
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->title = $params->title;
                $post->content = $params->content;
                $post->image = $params->image;
                $post->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'post' => $post
                ];
            }

        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Envia los datos correctamente.'
            ]; 
        }        
        //DEVOLVER LA RESPUESTA

        return response()->json($data, $data ['code']);
    }

    public function update($id, Request $request){
        //RECOGER LOS DATOS POR POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        //DATOS A DEVOLVER
        $data = array (
            'code' => 400,
            'status' => 'error',
            'message' => 'Los datos no son correctos.'
        );

        if(!empty($params_array)){
            //VALIDAR LOS DATOS
            $validate =\Validator::make($params_array, [
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required'
            ]);

            //ELIMINAR LOS DATOS QUE NO QUEREMOS ACTUALIZAR
            unset($params_array['id']);
            unset($params_array['user_id']);
            unset($params_array['created_at']);
            unset($params_array['user']);

            //CONSEGUIR EL USUARIO IDENTIFICADO
            $user = $this->getIdentity($request);

            //BUSCAR EL REGISTRO A ACTUALIZAR
            $post = Post::where('id', $id)
                        ->where('user_id', $user->sub)
                        ->first();

            if(!empty($post) && is_object($post)){
                //ACTUALIZAR EL REGISTRO EN CONCRETO
                $post->update($params_array);
                
                // DEVOLVER LOS DATOS ACTUALIZADOS
                $data = array (
                    'code' => 200,
                    'status' => 'success',
                    'post' => $post,
                    'changes' => $params_array
                );  
            }

            /*
            $where = [
                'id'=> $id,
                'user_id'=> $user->sub
            ];
            $post = Post::updateOrCreate($where, $params_array);
            */ 
            
        }
        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request){
        //CONSEGUIR EL USUARIO IDENTIFICADO
        $user = $this->getIdentity($request);
        
        //CONSEGUIR EL POST
        $post = Post::where('id', $id)
                    ->where('user_id', $user->sub)
                    ->first();

        if(!empty($post)){
            //BORRAR EL POST
            $post->delete();

            //DEVOLVER LA RESPUESTA
            $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $post
            ];
        } else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'El post no existe.'
            ];
        }
        return response()->json($data, $data['code']);
    }

    public function getIdentity ($request){
        //CONSEGUIR EL USUARIO IDENTIFICADO
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }

    public function upload(Request $request){
        //RECOGER LA IMAGEN DE LA PETICION
        $image = $request->file('file0');

        //VALIDAR LA IMAGEN
        $validate =\Validator::make($request->all(), [
            'file0' =>'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        //GUARDAR LA IMAGEN
        if(!$image|| $validate->fails()){
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Error al subir la imagen.'
            ];
        } else {
            $image_name = time().$image->getClientOriginalName();

            \Storage::disk('images')->put($image_name, \File::get($image));

            $data = [
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            ];
        }

        //DEVOLVER LOS DATOS
        return response()->json($data, $data['code']);
    }

    public function getImage($filename){
        //COMPROBAR SI EXISTE EL FICHERO
        $isset = \Storage::disk('images')->exists($filename);
        
        if($isset){
            //CONSEGUIR LA IMAGEN
            $file = \Storage::disk('images')->get($filename);

            //DEVOLVER LA IMAGEN
            return new  Response($file, 200);

        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'image' => 'La imagen no existe.'
            ];
        }
        //MOSTRAR EL ERROR
        return response()->json($data, $data['code']);
    }

    public function getPostsByCategory($id){
        $post = Post::where('category_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'post' => $post
        ], 200);
   }

   public function getPostsByUser($id){
    $post = Post::where('user_id', $id)->get();

    return response()->json([
        'status' => 'success',
        'post' => $post
    ], 200);
}



}
