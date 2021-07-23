<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'url',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** Evento que se ejecuta cuando se crea un usuario */
    public static function boot()
    {
        parent::boot();
        // Asigna un perfil una vez se haya creado un usuario nuevo
        static::created(function($user){
            $user->perfil()->create();
        });
    }

    /* Relacion 1:n de Usuarios a Recetas*/
    public function recetas()
    {
        return $this->hasMany(Receta::class);
    }
    /** Relacion 1:1 de Usuario con Perfil */
    public function perfil()
    {
        return $this->hasOne(Perfil::class);
    }
    //Recetas a las que el usuario le ha dado me gusta
    public function meGusta()
    {
        return $this->belongsToMany(Receta::class,'likes_receta');
    }
}
