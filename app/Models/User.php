<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'UsuarioId';
    public $timestamps = false;

    protected $fillable = [
        'Username',
        'PasswordHash',
        'RolId',
    ];

    protected $hidden = [
        'PasswordHash',
    ];

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->PasswordHash;
    }

    /**
     * Relationship with Rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'RolId', 'RolId');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return (int)$this->RolId === 1; // 1 = Admin
    }
}
