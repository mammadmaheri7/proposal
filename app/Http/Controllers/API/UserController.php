<?php
namespace App\Http\Controllers\API;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    /**
     * login api
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'national_number' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validator->errors(),
            ], 401);
        }

        if(Auth::guard('web')->attempt(['national_number' => request('national_number'), 'password' => request('password')])){
            $user = Auth::guard('web')->user();
            $token =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['status' => 'success','auth_token'=>$token , 'role_id'=>$user->role->id], $this-> successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'national_number' => 'required|unique:users,national_number',
            'first_name'=>'required',
            'last_name'=>'required',
            'role_id'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validator->errors(),
            ], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $role = Role::find($request->input('role_id'));

        $user = User::create($input);

        //$user->role()->save($request->input('role_id'));
        $response = [
            'status' => 'success',
            'token' => $user->createToken('MyApp')-> accessToken,
        ];

        return response()->json($response, $this-> successStatus);
    }
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
    }
}