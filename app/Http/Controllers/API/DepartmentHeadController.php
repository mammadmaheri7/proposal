<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class DepartmentHeadController extends Controller
{
    public function get_professor_information(Request $request)
    {
        $user = Auth::user();
        if (Gate::denies('department-head',$user)){
            return response()->json(['error'=>'Unauthorised - you should be head of apartment'], 401);
        }

        $professor = Professor::where('user_id',$user->id)->first();

        $related_professors = Professor::where('major_id',$professor->major_id)->get();
        return response()->json(['status'=>'success','professors'=>$related_professors]);
    }
}
