<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    //Formulario de Rol
    public function formRol(){

        return view('roles.createRol');
    }

    public function saveRol(Request $request){

        $validator = $this->validate($request, [
            'descripcion'=> 'required|string|max:45',
        ]);

        Rol::create([
            'descripcion'=>$validator['descripcion']
        ]);

        return redirect('/listRol')->with('guardar', 'ok');
    }

    //Listado de Rol
    public function listaRol(){
        $datarol['roles'] = Rol::paginate(7);

        return view('roles.listadoRol', $datarol);
    }
}
