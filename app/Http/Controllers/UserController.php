<?php

namespace App\Http\Controllers;

use id;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function signup(){
        return view("register");
    }
    public function signin(){
        return view("login");
    }
    public function signupPost(Request $request){
        $incomingFields = $request->validate([
            "name"=>["required","min:2"],
            "email"=> ["required", "email", Rule::unique("users", "email")],
            "password"=> ["required","min:4", "max:250"],
        ]);
        $incomingFields["password"] = bcrypt($incomingFields["password"]);
        
        $user = User::create($incomingFields);
        auth()->login($user);

        return redirect("/dashboard");
    }
    public function signinPost(Request $request){
        
        $incomingFields = $request->validate([
            "loginemail"=>"required",
            "loginpassword"=> "required"
        ]);
        if(auth()->attempt(['email'=>$incomingFields['loginemail'], 'password'=>$incomingFields['loginpassword']])){
            $request->session()->regenerate();
            $user = Auth::user();
            return redirect("/dashboard");
        }
        else{
            return redirect("/signin");
        }
    }
    public function logout(){
        auth()->logout();
        return redirect("/");
    }
    public function dashboard(Request $request, $user){
        return view("dashboard", compact("user"));
    }
    // public function forgotPassword(){

    //     return view("forgot-password");
    // }
    public function forgotPassword(){
        return view("forgot-password");
    }
    // public function forgotPasswordPost(Request $request){
    //     $email = $request->validate(["resetemail"=>"email"]);
    //     $user = User::where($email, $request->email)->first();
    //     // if ($user->exists() && $user->password != $request->password){
    //     if ($user){
    //         Session::flash('message', "A password reset link has already been sent to the provided email");
    //         return Redirect::back('forgot-password');
    //         // return Redirect::back();
    //         // return redirect('/forgot-password')->with('message', 'A password reset link has already been sent to your email');//->guest('forgot-password');
    //     }else{
    //         return view('forgot-password');
    //     }
    // }

    public function forgotPasswordPost(Request $request){
        $request->validate([
            "resetemail"=>["required","email", "exists:users,email"],
        ]);
        $email = $request->resetemail;
        $token = Str::random(64);
        
        DB::table('password_reset')->insert([
            "email"=>$email,
            "token"=>$token,
            "created_at"=>Carbon::now(),
        ]);
        Mail::send("emails.forgot-password", ['token'=>$token, 'email'=>$email], function($message) use ($request){
            $message->to($request->resetemail);
            // $message->to("jessiesolo01@gmail.com");
            $message->subject('Reset Password');
        });

        return redirect()->to(route('forgot.password'))->with('success','We have sent you an email to reset password');
    }

    public function resetPassword($token){
        // $request->validate([
        //     "email"=> $request->resetemail,
        // ]);
        // $email = $request->resetemail;
        return view("reset-password", compact("token"));
    }


    // public function resetPasswordPost(Request $request){
    // public function resetPasswordPost(Request $request, User $user){
  
        // $data = $request->validate([
        //     'token' => 'required',
        //     'resetpassword_email' => ['required', 'email', 'exists:users,email'],
        //     'resetpassword' => 'required|min:4|confirmed',
        // ]);
        // $request->validate([
        //     'token' => 'required',
        //     'resetpassword_email' => 'required|email|exists:users,email',
        //     'resetpassword' => 'required|min:4|max:200|confirmed',
        // ]);

    public function resetPasswordPost(Request $request){
        $request->validate([
            'token' => 'required',
            'resetpassword_email' => 'required|email|exists:users,email',
            'resetpassword' => 'required|min:4|max:200|confirmed',
            'resetpassword_confirmation' => 'required'
        ]);
// };
        $token = $request->token;

        // dd($request->all());
        $updatePassword = DB::table('password_reset')
            ->where([
                'email' => $request->resetpassword_email,
                'token' => $request->token
                // 'token' => $token
            ])
            ->first();
            // dd($updatePassword);
        
        if (!$updatePassword) {
            return redirect()->to(route('reset.password', ['token'=>$token]))
                ->with('error', 'Invalid token or email');
            // return redirect()->route('reset.password');
                
        }
        // #######----INITIAL SOLUTION
        User::where('email', $request->resetpassword_email)
            ->update([
                'password' => bcrypt($request->resetpassword)
            ]);

        // #############NO 1 SOLUTION
        // $updateUser = User::where('email', $request->resetpassword_email)
        // $updateUser->save();
        ///

        // ###########NO 2 SOLUTION
        // DB::table('users')
        //     ->where(['email'=>$request->resetpassword_email])
        //     ->update(['password' => bcrypt($request->resetpassword)]);
        
        // ###########NO 3 SOLUTION
        // User::update(['password'=>bcrypt($request->resetpassword)])::where('email', $request->resetpassword_email)->first();
        
        // #############NO 4 SOLUTION
        // $updateUserPassword = User::where('email', $request->resetpassword_email);
        // $newPassword = bcrypt($request->resetpassword);
        // $updateUserPassword->password = $request->input($newPassword);
        // $updateUserPassword->save();

        // #############NO 5 SOLUTION
        // $user->update([$user->password = bcrypt($data['resetpassword'])]);
        // 
        // $user->update([$user['password'] => bcrypt($data['resetpassword'])]);

        DB::table('password_reset')
            ->where(['email' => $request->resetpassword_email])
            ->delete();

        return redirect()->to(route('signin'))
            ->with('success', 'Password reset successful');
        // return redirect()->route('signin');

        // return redirect('/signin');
    }
}


