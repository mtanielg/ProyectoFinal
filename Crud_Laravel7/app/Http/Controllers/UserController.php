<?php

namespace App\Http\Controllers;


use App\Models\Rol;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{

    //Lisatdo de Usuarios
    public function lista(){

        $users = DB::table('usuarios')
            // Toma de referencial el rol_id de usuairos para traer descripcion de rol
            ->join('rol', 'usuarios.rol_id', '=', 'rol.id_rol')
            ->select('usuarios.*', 'rol.descripcion')
            ->paginate(3);


        return view('usuarios.listar', compact('users'));
    }

    //Formulario de Usuario
    public function userform(){

        $rol=Rol::all();

        return view('usuarios.userform', compact('rol'));
    }

    //Guardar Usuarios
    public function save(Request $request){
        /* Validamos los campos */
        $validator = $this->validate($request, [
            'nombre'=> 'required|string|max:75',
            'email'=> 'required|string|max:45|email|unique:usuarios',
            'foto' => 'required|',
            'rol'=> 'required'
        ]);
        /*Recolecion de la foto de Usuario*/
        if($request->hasFile('foto')){
            $validator['foto'] = $request-> file('foto')->store('uploads','public');
        }
        /* Guardamos en la Base de datos */
        Usuario::create([
            'nombre'=>$validator['nombre'],
            'email'=>$validator['email'],
            'foto' =>$validator['foto'],
            'rol_id'=>$validator['rol']
        ]);

        return redirect('/')->with('guardar', 'ok');
    }

    //Eliminar Usuarios
    public function delete($id){

        $usuario = Usuario::findOrFail($id);
        if(Storage::delete('public/'.$usuario->foto)){
            Usuario::destroy($id);
        }

        return back()->with('eliminar', 'ok');
    }

    //Formulario Editar Usuarios
    public function editform($id){
        $usuario = Usuario::findOrFail($id);
        $rol=Rol::all();

        return view('usuarios.editform', compact('usuario', 'rol'));
    }

    //Edicion de Usuarios
    public function edit(Request $request, $id){
        $dataUsuario = request()->except((['_token','_method']));

        /*Recolecion de la foto de Usuario*/
        if($request->hasFile('foto')){
            $usuario = Usuario::findOrFail($id);
            Storage::delete('public/'.$usuario->foto);
            $dataUsuario ['foto'] = $request-> file('foto')->store('uploads','public');
        }

        Usuario::where('id', '=', $id)->update($dataUsuario);

        return redirect('/')->with('editar', 'ok');
    }

}
