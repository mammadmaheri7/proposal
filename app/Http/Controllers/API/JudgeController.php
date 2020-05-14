<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Professor;
use App\Proposal;
use App\ProposalResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Validator;

class JudgeController extends Controller
{
    public function judge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'proposal_id' => 'required|exists:proposals,id',
            'judge_response' => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validator->errors(),
            ], 401);
        }

        $user = Auth::user();
        $professor = Professor::where('user_id',$user->id)->first();
        $proposal = Proposal::where('id',$request->input('proposal_id'))->with(['proposal_result'])->first();
        $judge_id1 = $proposal->judge1 ? $proposal->judge1->id : null;
        $judge_id2 = $proposal->judge2 ? $proposal->judge2->id : null;
        $supervisor_id = $proposal->professor ? $proposal->professor->id : null;

        if ($judge_id1 != $professor->id and $judge_id2 != $professor->id and $supervisor_id != $professor->id){
            return response()->json(['error'=>'Unauthorised - you should be judge'], 401);
        }

        $proposal_result = $proposal->proposal_result;

        if ($proposal_result==null)
        {
            $proposal_result = ProposalResult::create();
            $proposal->proposal_result()->associate($proposal_result)->save();
        }
        ;

        switch ($professor->id){
            case $supervisor_id:
                $proposal_result->update(['supervisor_response'=>$request->input('judge_response')]);
                break;
            case $judge_id1:
                $proposal_result->update(['judge1_response'=>$request->input('judge_response'),
                                    'judge1_message'=>$request->input('judge_message')]);
                break;
            case $judge_id2:
                $proposal_result->update(['judge2_response'=>$request->input('judge_response'),
                    'judge2_message'=>$request->input('judge_message')]);
        }

        return response()->json(['status'=>'success']);
    }
}
