<?php

namespace App\Http\Controllers;

use App\Traits\PermisosTrait;
use Illuminate\Http\Request;

class NoAutenticadoController extends Controller
{
    use PermisosTrait;
    
    public function index()
    {   
        $login = session('Login');

        if (!$login) {
            return view('errors.no-autenticado');
        } else {
            return redirect()->route('Dashboard', ['usuario' => $login]);
        }
    }
}
