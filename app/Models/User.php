<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'school',
        'address',
        'phone',
        'expires_at',
        'type_id',
        'created_by_admin',
        'stand_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const TYPES = [
        'admin' => 1,
        'teacher' => 2,
        'student' => 3,
    ];

    public function getExpiresAtAttribute($value)
    {
        if ($this->attributes['expires_at'] != null) {
            if ($this->attributes['expires_at'] < Carbon::now()){
                $users = $this->where('expires_at', '<', Carbon::now())->get();
                foreach ($users as $user) {
                    $user->delete();
                }
            }
            return $this->attributes['expires_at'] = Carbon::parse($value)->format('Y-m-d H:i');
        }
    }
    public function payments()
    {
        return $this->hasMany(PayPalUser::class)->orderBy('id', 'desc');
    }

    public function answers()
    {
        return $this->hasMany(UserTest::class);
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }
}
