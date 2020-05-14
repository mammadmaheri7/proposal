<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Professor;
use App\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;

class JudgeController extends Controller
{
    public function judge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'proposal_id' => 'required|exists:proposals,id',
            'status' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validator->errors(),
            ], 401);
        }

        $user = Auth::user();
        $professor = Professor::where('user_id',$user->id)->first();
        $proposal = Proposal::where('id',$request->input('proposal_id'))->first();
        $judge_id1 = $proposal->judge1 ? $proposal->judge1->id : null;
        $judge_id2 = $proposal->judge2 ? $proposal->judge2->id : null;
        if ($judge_id1 != $professor->id and $judge_id2 != $professor->id){
            return response()->json(['error'=>'Unauthorised - you should be judge'], 401);
        }


    }
}
