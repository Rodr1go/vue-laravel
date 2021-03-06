<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $this->authorize('isAdmin');
       if(\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
           return User::latest()->paginate(7);
       }
    }

    public function countUsers() 
    {
       if(\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
           return User::count();
       }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6'
        ]);

        return User::create([
            "name" => $request['name'],
            "email" => $request['email'],
            "type" => $request['type'],
            "bio" => $request['bio'],
            "password" => $request['password'],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);

        $currentPhoto = $user->photo;
        
        if($request->photo != $currentPhoto) {    
            $imageName = preg_match_all('/data\:image\/([a-zA-Z]+)\;base64/',$request->photo,$matched);
            $ext = isset($matched[1][0]) ? $matched[1][0] : false;
            $imageName = sha1(time()) . '.' .$ext;

            \Image::make($request->photo)->save(public_path('img/profile/').$imageName);
            $request->merge(['photo' => $imageName]);

            $userPhoto = public_path('img/profile/').$currentPhoto;
            
            // remove a foto atual
            if(file_exists($userPhoto)) {
                if($userPhoto != public_path('img/profile/profile.png')) {
                    @unlink($userPhoto);
                }
            } 
        } 
        
        $user->update($request->all());
        return ["message" => "Success"];
    }

    public function profile()
    {
        return auth('api')->user();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6'
        ]);
        
        $user->update($request->all());
        return ['message' => 'Atualização de usuário'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);

        $user->delete();
        
        return ['message' => 'Usuário deletado'];
    }

    public function search() {
        if($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                        ->orwhere('email', 'LIKE', "%$search%")
                          ->orwhere('type', 'LIKE', "%$search%");
            })->paginate(7);
        } else {
            $users = User::latest()->paginate(7); 
        }

        return $users;
    }
}
