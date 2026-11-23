<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'username',
        'cis_id',
        'alias',
        'nim',
        'nidn',
        'password',
        'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];

    public $timestamps = true;

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function roles()
    {
        return $this->belongsToMany(
            \App\Models\Role::class,
            'user_roles',
            'user_id',
            'role_id'
        );
    }

    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    // Override notify() — routing ke NotificationService, tidak butuh tabel notifications
    public function notify($notification)
    {
        if (method_exists($notification, 'toNotificationService')) {
            $data = $notification->toNotificationService($this);
            app(\App\Services\NotificationService::class)->send(
                userId: $this->id,
                title: $data['title'],
                message: $data['message'],
                options: $data['options'] ?? []
            );
        }
    }

    // Dummy — agar tidak error jika ada kode lama yang masih memanggil ini
    public function notifications()
    {
        return collect([]);
    }

    public function unreadNotifications()
    {
        return collect([]);
    }
}



