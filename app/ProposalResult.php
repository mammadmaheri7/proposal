<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProposalResult extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'judge1_response','judge2_response','supervisor_response','status','judge2_message','judge1_message'
    ];
}
