<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'level','degree','major_id','user_id'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function major()
    {
        return $this->hasOne('App\Major');
    }

    /**
     * Get the students for the supervisor.
     */
    public function students()
    {
        return $this->hasMany('App\Student');
    }
}
