<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $empresas = Empresa::all();
        return view('empleados.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        Empleado::create($request->all());
        return redirect()->route('empleados.index');
    }

    public function edit(Empleado $empleado)
    {
        $empresas = Empresa::all();
        return view('empleados.edit', compact('empleado', 'empresas'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $empleado->update($request->all());
        return redirect()->route('empleados.index');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->delete();
        return redirect()->route('empleados.index');
    }
}
