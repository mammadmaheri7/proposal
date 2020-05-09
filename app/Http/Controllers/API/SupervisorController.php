<?php

namespace App\Http\Controllers\API;


use App\Proposal;
use App\Student;
use App\User;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SupervisorController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function define_supervisor_for_student(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'professor_id' => 'required|exists:professors,id',
            'proposal_id' => 'required|exists:proposals,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validator->errors(),
            ], 401);
        }

        $user = Auth::user();
        if (Gate::denies('define-supervisor',$user)){
            return response()->json(['error'=>'Unauthorised - you should be admin'], 401);
        }

        $student = Student::find($request->input('student_id'));
        $professor = Proposal::find($request->input('professor_id'));
        $proposal = Proposal::find($request->input('proposal_id'));

        $proposal->professor()->associate($professor);
        $proposal->student()->associate($student);

        return response()->json(['status'=>'success']);
    }
}
