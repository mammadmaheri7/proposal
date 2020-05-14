<?php
namespace App\Http\Controllers\API;
use App\Professor;
use App\Role;
use App\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
            $response = [
                'status' => 'success',
                'auth_token'=>$token ,
                'role_id'=>$user->role->id,
                'user_information' => $user
            ];
            return response()->json($response, $this-> successStatus);
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
        switch ($request->input('role_id')){
            case '2':
                $student = Student::create([
                    'user_id'   =>  $user->id
                ]);
                break;
            case '3':
            case '4':
                $professor = Professor::create([
                    'user_id'   =>  $user->id
                ]);
                break;
        }

        //$user->role()->save($request->input('role_id'));
        $response = [
            'status' => 'success',
            'token' => $user->createToken('MyApp')-> accessToken,
            'user_id' => $user->id
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
        return response()->json(['status' => 'success','user' => $user], $this-> successStatus);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modify_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users,email',
            'c_password' => 'same:password',
            'national_number' => 'unique:users,national_number',
            'user_id'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validator->errors(),
            ], 401);
        }

        $user = Auth::user();
        if (Gate::denies('modify-user-admin',$user)){
            return response()->json(['error'=>'Unauthorised - you should be admin'], 401);
        }

        $modify_user = User::where('id',$request->input('user_id'))->first();
        $role = $modify_user->role;
        $modify_user->update(array_filter($request->all()));

        switch ($role->title)
        {
            case 'student':
                $student = Student::where('user_id',$modify_user->id)->first();
                $student->update(array_filter(json_decode($request->input('detail'),true)));
                break;
            case 'group_manager':
            case 'professor' :
                $data = array_filter(json_decode($request->input('detail'),true));
                $professor = Professor::where('user_id',$modify_user->id)->first();
                $professor ->update($data);
                break;
        }

        return response()->json(['status'=>'success']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (Gate::denies('list-users',$user)){
            return response()->json(['error'=>'Unauthorised - you should be admin'], 401);
        }

        $result = [
            'students'=>null,
            'professors'=>null,
        ];
        if(array_key_exists('type',$request->all())) {
            switch ($request->input('type')){
                case 'student':
                    $result['students'] = Student::with(['user','major'])->get();
                    break;
                case 'professor':
                    $result['professors'] = Professor::with(['user','major'])->get();
                    break;
            }
        }
        else{

            $result['students'] = Student::with(['user','major'])->get();
            $result['professors'] = Professor::with(['user','major'])->get();
        }

        return response()->json(['status'=>'success','professors'=>$result['professors'],'students'=>$result['students']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function modify_profile(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $data['password'] = $request->input('password')?bcrypt($request->input('password')):null;
        $data = array_filter($data);
        $user->update($data);
        return response(['status'=>'success'],$this->successStatus);
    }
}