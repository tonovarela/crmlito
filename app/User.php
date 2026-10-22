<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    protected $table = 'ControlLogin.dbo.cat_Usuarios';
    protected $primaryKey = 'Id_Usuario';

    /**
     * Indicamos si el modelo debe manejar las marcas de tiempo.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Relación con la tabla IntegrantesRoles
     *
     * @return BelongsTo
     */
    public function rol() {
        return $this->belongsTo(IntegrantesRoles::class, 'Id_Usuario', 'idUsuario');
    }

    /**
     * Obtiene los permisos del usuario basados en su rol.
     *
     * @return \Illuminate\Support\Collection
     */
    public function permisos()
    {
        // Ajusta los nombres de las tablas y columnas según tu estructura real
        return DB::table('v_Permisos')
            ->where('idRol', $this->rol->idRol)
            ->select('permiso', 'modulo')->get();
    }

    /**
     * Verifica si el usuario tiene un permiso específico y si pertenece al módulo correspondiente.
     *
     * @param string|array $permiso
     * @param string|null $modulo
     * @return bool
     */
    public function canDo($permiso, $modulo = null)
    {
        // Si $permiso es un array, verifica si al menos uno de los permisos está presente
        if (is_array($permiso)) {
            return collect($permiso)->contains(function ($p) use ($modulo) {
                return $this->permisos()->contains(function ($value) use ($p, $modulo) {
                    if ($modulo) {
                        return $value->permiso === $p && $value->modulo === $modulo;
                    }

                    return $value->permiso === $p;
                });
            });
        }

        return $this->permisos()->contains(function ($value) use ($permiso, $modulo) {
            if ($modulo) {
                return $value->permiso === $permiso && $value->modulo === $modulo;
            }

            return $value->permiso === $permiso;
        });
    }

    /**
     * Verifica si el usuario tiene un rol específico.
     * 
     * @param string $rol
     * @return bool
     */
    public function hasRole($rol)
    {
        return $this->rol && $this->rol->rol === $rol;
    }

    public function setRememberToken($value)
    {
    }
}
