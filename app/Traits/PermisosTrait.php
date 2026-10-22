<?php

namespace App\Traits;

use App\Http\Modelos\CarCotizadores;
use App\Http\Modelos\CarCustomers;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

trait PermisosTrait
{
    public static function idUsuario() {
        $Login = session('usuario');
        
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $id_usuario = $datos_usuario[0]->Id_Usuario;

        return $id_usuario;
    }

    public static function datosUsuario() {
        $Login = session('usuario');
        
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);

        return $datos_usuario;
    }

    public static function jefeDiseñadores() {
        $roles = DB::table('v_catIntegrantesRoles')->where('idRol', 1020)->get();
        $jefe = $roles[0]->Nombre;
       
        return $jefe;
    }

    public static function customerAsignado() {
        $roles = DB::table('IntegrantesRoles')->Where('idRol', 1023)->get();

            $customer_asignado = $roles[0]->idUsuario;
        
        return $customer_asignado;
    }

    public static function idRevisado($nombre) {
        $revisa = DB::table('RevisaOportunidad')->where('nombreRevisa', $nombre)->get();
        $idRevisa = $revisa[0]->idRevisa; 
        
        return $idRevisa;
    }

    /**
     * Función para tomar el customer al que se le asignara la oportunidad
     * 
     * @return mixed
     */
    public static function customerLibre()
    {
        /*                                              Paso 1                                              */
        // Se tendra que llamar a la tabla de catCustomers esto para de primera instancia cambiemos la fecha de ruleta
        $currentDate = now()->format('Y-m-d');

        // Obtenemos el primer registro de la tabla para saber que fecha es la que tiene actualmente
        $carCustomerFirst = CarCustomers::first();

        // Validamos si fecha actual ya esta en la tabla
        if (!($carCustomerFirst->date_current == $currentDate)) {

            /*                                              Paso 4                                              */
            // Validar que cada dia que pase se tendra que eliminar el dia previo e reiniciar el nuevo contador

            // Actualizamos las fechas de todos los registros de la tabla
            DB::statement('exec sp_updateDateAndCounterCarCustomers ?', [
                $currentDate
            ]);
        }

        /*                                              Paso 2                                              */
        // Se tendra que ir tomando primero por id de forma asc y que tenga el menor numero en el contador
        
        // Obtenemos el primer registro de la tabla por id y por contador
        $firstRegister = CarCustomers::where('active', 1)
                                    ->orderBy('counter', 'asc')
                                    ->orderBy('idUsuarioCus', 'asc')
                                    ->first();

        /*                                              Paso 3                                              */
        // Se tendra que ir actualizando el contador cada que se realice la llamada a esta función

        // Obtenemos el contador de un usuario 
        $counter = $firstRegister->counter + 1;

        // Actualizamos el registro con el nuevo contador
        DB::statement('exec sp_updateCounterCarCustomers ?, ?', [
            $counter, 
            $firstRegister->idUsuarioCus
        ]);

        // Regresamos el primer registro
        return $firstRegister->idUsuarioCus;
    }

    /**
     * Función para tomar el cotizador al que se le asignara la oportunidad
     * 
     * @return mixed
     */
    public static function cotizadorLibre()
    {
        /*                                              Paso 1                                              */
        // Se tendra que llamar a la tabla de catCustomers esto para de primera instancia cambiemos la fecha de ruleta
        $currentDate = now()->format('Y-m-d');

        // Obtenemos el primer registro de la tabla para saber que fecha es la que tiene actualmente
        $carCotizadorFirst = CarCotizadores::first();

        // Validamos si fecha actual ya esta en la tabla
        if (!($carCotizadorFirst->date_current == $currentDate)) {
            
            /*                                              Paso 4                                              */
            // Validar que cada dia que pase se tendra que eliminar el dia previo e reiniciar el nuevo contador

            // Actualizamos las fechas de todos los registros de la tabla
            DB::statement('exec sp_updateDateAndCounterCarCotizadores ?', [
                $currentDate
            ]);
        }

        /*                                              Paso 2                                              */
        // Se tendra que ir tomando primero por id de forma asc y que tenga el menor numero en el contador
        
        // Obtenemos el primer registro de la tabla por id y por contador
        $firstRegister = CarCotizadores::where('active', 1)
                                    ->orderBy('counter', 'asc')
                                    ->orderBy('idUsuarioCot', 'asc')
                                    ->first();
        
        /*                                              Paso 3                                              */
        // Se tendra que ir actualizando el contador cada que se realice la llamada a esta función

        // Obtenemos el contador de un usuario 
        $counter = $firstRegister->counter + 1;

        // Actualizamos el registro con el nuevo contador
        DB::statement('exec sp_updateCounterCarCotizadores ?, ?', [
            $counter, 
            $firstRegister->idUsuarioCot
        ]);
        
        // Regresamos el primer registro
        return $firstRegister->idUsuarioCot;
    }

    public static function agentes(){
        $Login = session('usuario');
        $agentes = DB::select('exec GetAuxiliaresAgentes ?', [
            $Login
        ]);

        return $agentes;
    }

    public static function idRol() {
        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $roles = DB::select('exec GetRolesXUsuario @idUsuario=' . $datos_usuario[0]->Id_Usuario);
        $id_rol = $roles[0]->idRol;

        return $id_rol;
    }

    public static function loginUsuario() {
        $Login = session('usuario');

        return $Login;
    }
    
    public static function canAccessModuloComisiones() {
        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $estatusComLito = $datos_usuario[0]->estatusComisionesLito;

        return $estatusComLito;
    }
    
    public static function tipoUsuarioModuloComisiones() {
        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $tipoUsuario = $datos_usuario[0]->tipoUsuarioComisionesLito;

        return $tipoUsuario;
    }

    public static function permisosModulo($modulo){
        $Login = session('usuario');
        $permisos = DB::select('exec GetPermisos ?, ?', [$Login, $modulo]);

        return $permisos;
    }

    public static function permisos()
    {
        $modulos = DB::select('exec GetModulos');
        $Login = session('usuario');

        // Acceso para administracion de comisiones
        $canAccessModuloComisiones = PermisosTrait::canAccessModuloComisiones();
        session(['admin_comisiones' => $canAccessModuloComisiones]);

        // Fin acceso para administracion de comisiones

        foreach ($modulos as $modulo) {

            $permisos = DB::select('exec GetPermisos ?, ?', [$Login, $modulo->descModulo]);

            if ($permisos != null) {
                session(['rol_usuario' => $permisos[0]->idRol]);
                session(['rol_usuario_admin' => $permisos[0]->idRol]);
            } else {
                session(['rol_usuario' => 8]);
            }
 
            if ($modulo->descModulo === 'Cuentas') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['clientes' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['clientes' => 'SinAcceso']);
                    }

                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crear' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crear' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editar' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editar' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'EditarClientes' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarClientes' => 'EditarClientes']);
                    } else if ($permisos[$i]->descAccion === 'EditarClientes' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarClientes' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminar' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminar' => '']);
                    }
                }
            } else if ($modulo->descModulo == 'Oportunidades') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['oportunidades' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['oportunidades' => 'SinAcceso']);
                    }

                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crearOpor' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crearOpor' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarOpor' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarOpor' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'EditarOportunidades' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarOportunidades' => 'EditarOportunidades']);
                    } else if ($permisos[$i]->descAccion === 'EditarOportunidades' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarOportunidades' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminarOpor' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminarOpor' => '']);
                    }
                }

            } else if ($modulo->descModulo == 'Envios') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['envios' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['envios' => 'SinAcceso']);
                    }

                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crearEnv' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crearEnv' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarEnv' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarEnv' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'EditarEnvios' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarEnvios' => 'EditarEnvios']);
                    } else if ($permisos[$i]->descAccion === 'EditarEnvios' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarEnvios' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminarEnv' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminarEnv' => '']);
                    }
                }

            } else if ($modulo->descModulo == 'Elementos Presupuestados') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['ElementoPresupuestados' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['ElementoPresupuestados' => 'SinAcceso']);
                    }

                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crearElemento' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crearElemento' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarElemento' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarElemento' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'EditarElementosP' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarElementosP' => 'EditarElementosP']);
                    } else if ($permisos[$i]->descAccion === 'EditarElementosP' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarElementosP' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminarElemento' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminarElemento' => '']);
                    }
                }

            } else if ($modulo->descModulo == 'Diseño Estructural') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['DiseñoEstructural' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['DiseñoEstructural' => 'SinAcceso']);
                    }

                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crearEstructural' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crearEstructural' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarEstructural' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarEstructural' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'EditarEstructuralP' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarEstructuralP' => 'EditarEstructuralP']);
                    } else if ($permisos[$i]->descAccion === 'EditarEstructuralP' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarEstructuralP' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminarEstructural' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminarEstructural' => '']);
                    }
                }

            } else if ($modulo->descModulo == 'Documentos') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['Documentos' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['Documentos' => 'SinAcceso']);
                    }
                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crearDocumento' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crearDocumento' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarDocumento' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarDocumento' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminarDocumento' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminarDocumento' => '']);
                    }
                }
            } else if ($modulo->descModulo == 'Documentos Administrativos') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['Documentos Administrativos' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['Documentos Administrativos' => 'SinAcceso']);
                    }
                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crearDocumentos' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crearDocumentos' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarDocumentos' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarDocumentos' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminarDocumentos' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminarDocumentos' => '']);
                    }
                }

            } else if ($modulo->descModulo == 'Reuniones') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['Reuniones' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['Reuniones' => 'SinAcceso']);
                    }

                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crearReunion' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crearReunion' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarReunion' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarReunion' => '']);
                    
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminarReunion' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminarReunion' => '']);
                    }

                }

            } else if ($modulo->descModulo == 'Llamadas') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['Llamadas' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['Llamadas' => 'SinAcceso']);
                    }

                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crearLlamada' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crearLlamada' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarLlamada' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarLlamada' => '']);
                    
                    }
                    
                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminarLlamada' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminarLlamada' => '']);
                    }

                }
            } else if ($modulo->descModulo == 'Entregas') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['Entregas' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['Entregas' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crearEntrega' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crearEntrega' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarEntrega' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarEntrega' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminarEntrega' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminarEntrega' => '']);
                    }

                }

            } else if ($modulo->descModulo == 'Usuarios') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['Usuarios' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['Usuarios' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crearUsuario' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crearUsuario' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarUsuario' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarUsuario' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminarUsuario' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminarUsuario' => '']);
                    }

                }

            } else if ($modulo->descModulo === 'Prospectos') {
                
                $permisos_count = count($permisos);

                for ($i = 0; $i < $permisos_count; $i++) {

                    if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                        session(['prospectos' => 'AccesoModulo']);
                    } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                        session(['prospectos' => 'SinAcceso']);
                    }

                    if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['crear_prospectos' => 'Crear']);
                    } else if ($permisos[$i]->descAccion === 'Crear' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['crear_prospectos' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editar_prospectos' => 'Editar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editar_prospectos' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'EditarProspectos' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['editarProspectos' => 'EditarProspectos']);
                    } else if ($permisos[$i]->descAccion === 'EditarProspectos' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['editarProspectos' => '']);
                    }

                    if ($permisos[$i]->descAccion === 'Eliminar' && $permisos[$i]->descNivelPerm === 'Todo') {
                        session(['eliminar_prospectos' => 'Eliminar']);
                    } else if ($permisos[$i]->descAccion === 'Editar' && $permisos[$i]->descNivelPerm === 'Ninguno') {
                        session(['eliminar_prospectos' => '']);
                    }
                }
        
            }
        }
    }
    
    public static function permisosXusuario($user)
    {
    
        // var_dump(session('usuario'));exit();
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $user);
        $roles = DB::select('exec GetPermisosXUsuario @Id_usuario=' . $datos_usuario[0]->Id_Usuario);
        $idModulo = $roles;

        return $idModulo;
    }

    public static function diaSemanaEN(){
        $diaSemana = 'Mon';
        return $diaSemana;
    }

    public static function diaSemanaMX(){
        $diaSemana = 'Lun';
        return $diaSemana;
    }

    public static function hora(){
        $hora = '9';
        return $hora;
    }

    public static function entregasSinRespuesta() {
        $Login = session('usuario');
        $entregas_sin_respuesta = DB::table('v_repEntregas')
        ->where('Login', $Login)->where('respuesta', null)
        ->orWhere('LoginAuxiliar', $Login)->where('respuesta', null)
        ->orWhere('LoginAuxiliar2', $Login)->where('respuesta', null)
        ->orWhere('LoginAuxiliar3', $Login)->where('respuesta', null)
        ->orWhere('LoginAuxiliar4', $Login)->where('respuesta', null)->get();

        return $entregas_sin_respuesta;
    }

    public function configMail(){

        Artisan::call('config:clear');

        // config(['mail.driver' => 'smtp']);
        // config(['mail.host' => 'smtp.mailtrap.io']);
        // config(['mail.port' => '2525']);
        // config(['mail.username' => '30c21fac841b00']);
        // config(['mail.password' => '934eb3e2544936']);
        // config(['mail.encryption' => 'tls']);

        config(['mail.driver' => 'smtp']);
        config(['mail.host' => 'smtp.gmail.com']);
        config(['mail.port' => '587']);
        config(['mail.username' => 'noreply@litoprocess.com']);
        config(['mail.password' => 'xoiknkhpofzaytcu']);
        config(['mail.encryption' => 'tls']);

    }
}
