<?php

namespace App\Http\Controllers\API;
use App\Http\Library\apiHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class UserController extends Controller
{
    use apiHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        $user = User::with('role')->paginate($request->get('limit'));
        $response = [
            'success' => true,
            'data' => $user,
        ];
        dd($user);
        // return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $session = $request->user();
        if ($session->role === 2 || $session->role === 3) {
            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->passes()) {
                $user =  User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'nis' => $request->nis,
                    'nisn' => $request->nisn,
                    'role' => $request->role
                ]);

                return $this->onSuccess($user, 'User created');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $session = Auth::check();
        if ($session) {
            $data = User::findOrFail($id);
            if (!$data) {
                return $this->onError(404, 'User not found');
            }
            return $this->onSuccess($data, 'User found');
        }
        return $this->onError(401, 'Unauthorized');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->fails()) {
                return $this->onError(400, $validator->errors()->all()[0]);
            } else {
                $user = User::findOrFail($id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role = $request->role;
                $user->status = $request->status;
                $user->update();

                return $this->onSuccess($user,'Data Updated' ,200);
            }
        }
        return $this->onError(400, 'Unauthenticated');
    }

    public function updateProfileBySession(Request $request) 
    {
        $image  = $request->file('profile_picture');
        if (Auth::check()) {
            $validator = Validator::make($request->all(), $this->userUpdateValidatedRules());
            if ($validator->fails()) {
                return $this->onError(400, $validator->errors()->all()[0]);
            } else {
                $user = User::findOrFail($request->user()->id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->nis = $request->nis;
                $user->nisn = $request->nisn;
                if ($image && $image->isValid()) {
                    $file_name = time()."_".Carbon::now()->format('d-M-Y')."_".Str::slug($request->user()->name).$image->getClientOriginalName();
                    // if($user->profile_picture != $file_name){
                    //     unlink(getcwd().$user->profile_picture);
                    // }
                    $path = "/images/profile-pictures/$file_name";
                    $image->move('img/profile-picture', $file_name);
                    $user->profile_picture = $path;
                }
                $user->update();
                return $this->onSuccess($user,'Data Updated', 200);
            }
        }
        return $this->onError(401, 'Unauthenticated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role == 3) {
            $user = User::find($id); // Find the id of the user passed
            $user->delete(); // Delete the specific user data
            if (!empty($user)) {
                return $this->onSuccess($user, 'User Deleted');
            }
            return $this->onError(404, 'User Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    public function search(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $role = $request->get('role');
        if (Auth::check()) {
            $user = User::where('name', 'like', "%{$name}%")
                 ->orWhere('email', 'like', "%{$email}%")
                //  ->orWhere('role', 'like', "%{$role}%")
                 ->get();
            if (!$user) {
                return $this->onError(404, 'Not found');
            }
            return $this->onSuccess([$user], 200);
        }
        
        return $this->onError(401, 'Unauthorized');
    }
}