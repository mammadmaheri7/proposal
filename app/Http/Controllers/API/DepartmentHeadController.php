<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Professor;
use App\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class DepartmentHeadController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_professor_information(Request $request)
    {
        $user = Auth::user();
        if (Gate::denies('department-head',$user) ){
            return response()->json(['error'=>'Unauthorised - you should be head of apartment'], 401);
        }
        $professor = Professor::where('user_id',$user->id)->first();
        $related_professors = Professor::where('major_id',$professor->major_id)->get();
        return response()->json(['status'=>'success','professors'=>$related_professors]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_proposals_information(Request $request)
    {
        $user = Auth::user();
        if (Gate::denies('department-head',$user) and Gate::denies('professor')){
            return response()->json(['error'=>'Unauthorised - you should be head of apartment or judge'], 401);
        }
        $professor = Professor::where('user_id',$user->id)->first();
        

        $proposals = Proposal::
                when($user->role_id==3,function ($query) use ($professor){
                    $query
                        ->whereHas('student' , function ($query) use ($professor) {
                        $query->where('major_id', $professor->major_id)
                            ->orWhereNull('major_id');
                    });
                },function ($query) use ($professor){
                    $query
                        ->where('judge1_id',$professor->id)
                        ->orWhere('judge2_id',$professor->id);
                })

            ->with(['student','professor','judge1','judge2','student.user','student.major'])
            ->get();

        /*
        $proposals = Proposal::has('student')
            ->whereHas('student')
                                get();
        */

        return response()->json(['status'=>'success','proposals'=>$proposals]);
    }

    public function choose_judge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judge1_id' => 'required|exists:professors,id',
            'judge2_id' => 'required|exists:professors,id',
            'proposal_id' => 'required|exists:proposals,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validator->errors(),
            ], 401);
        }

        $user = Auth::user();
        if (Gate::denies('department-head',$user)){
            return response()->json(['error'=>'Unauthorised - you should be head of apartment'], 401);
        }
        $proposal = Proposal::with(['student','judge1','judge2'])->where('id',$request->input('proposal_id'))->first();
        $proposal->judge1()->associate($request->input('judge1_id'));
        $proposal->judge2()->associate($request->input('judge2_id'));
        $proposal->save();

        return response()->json(['status'=>'success','proposal'=>$proposal]);
    }
}
