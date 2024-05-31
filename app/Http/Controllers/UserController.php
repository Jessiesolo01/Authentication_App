<?php

namespace App\Http\Controllers;

use id;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
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
    public function register(Request $request){
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
    public function login(Request $request){
        $incomingFields = $request->validate([
            "loginemail"=>"required",
            "loginpassword"=> "required"
        ]);
        if(auth()->attempt(['email'=>$incomingFields['loginemail'], 'password'=>$incomingFields['loginpassword']])){
            $request->session()->regenerate();
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
    public function dashboard(){
        return view("dashboard");
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

    public function resetPassword($token, Request $request){
        // $request->validate([
        //     "email"=> $request->resetemail,
        // ]);
        $email = $request->resetemail;
        return view("reset-password", compact("token","email"));
    }


    // public function resetPasswordPost(Request $request){
    //     $request->validate([
    //         "resetpassword_email"=>["required","email","exists:users,email"],
    //         // 'resetemail'=>'required|email|exists:users,email'.$this->id,
    //         // "resetemail"=>$request->resetemail,
    //         "resetpassword"=>["required","string", "password", "min:4", "confirmed"],
    //         "resetpassword_confirm"=>["required"],
    //     ]);  
    //     // $email = $request->resetemail;
    //     $updatePassword = DB::table("password_reset")->where([
    //         "email"=>$request->resetpassword_email,
    //         "token"=>$request->token,
    //     ])->first();

    //     if(!$updatePassword){
    //         return redirect()->to(route("reset.password"))->with("error","Invalid");
    //     }

    //     User::where("email",$request->resetpassword_email)->update(["password"=>Hash::make($request->resetpassword)]);
    //     // User::where("email",$request->resetemail)->update(["password"=>bcrypt($request->resetpassword)]);
    //     DB::table("password_reset")->where(["email"=> $request->resetpassword_email])->delete();

    //     return redirect()->to(route("login"))->with("success","Password reset successful");
    // }



    public function resetPasswordPost(Request $request)
{
  
    $request->validate([
        'resetpassword_email' => 'required|email|exists:users,email',
        'resetpassword' => 'required|string|min:4|confirmed',
        'resetpassword_confirmation' => 'required',
    ]);

    
    $updatePassword = DB::table('password_resets')
        ->where([
            'email' => $request->resetpassword_email,
            'token' => $request->token
        ])
        ->first();
        // dd($updatePassword);
    
    if (!$updatePassword) {
        return redirect()->to(route('reset.password'))
            ->with('error', 'Invalid token or email');
    }
    
    User::where('email', $request->resetpassword_email)
        ->update([
            'password' => Hash::make($request->resetpassword)
        ]);

    
    DB::table('password_resets')
        ->where(['email' => $request->resetpassword_email])
        ->delete();

    
    return redirect()->to(route('login'))
        ->with('success', 'Password reset successful');
}
}
