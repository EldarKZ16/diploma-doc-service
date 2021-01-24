<?php

namespace App;

use Amir\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;

class SignDocs extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id', 'signer_role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function application() {
        return $this->belongsTo(Application::class);
    }

    public function signerRole() {
        return $this->belongsToMany(Role::class);
    }
}
