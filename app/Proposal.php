<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'professor_id','student_id','field_id','persian_title','persian_keywords','english_title','english_keywords',
        'type','filename','judge1_id','judge2_id','proposal_result_id',
    ];

    /**
     * Get the supervisor that supervises the student.
     */
    public function professor()
    {
        return $this->belongsTo('App\Professor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }




}
