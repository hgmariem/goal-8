<?php

namespace App\Repositories;

// import User Model
use App\Model\User;
use Auth;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
// Import Facade Hash to Encrypt password
use Illuminate\Support\Facades\Mail;
use Session;

class UserRepository implements IUserRepository
{
    // overirde Register function
    public function register($data)
    {
        $fullname = $data['firstname'] . " " . $data['lastname'];
        // dd($data);
        DB::beginTransaction();
        $pwd = Hash::make($data['password']);
        $user = User::create([
            'fullname' => $fullname,
            'email' => $data['email'],
            'country' => $data['country'],
            'city' => $data['city'],
            'street' => $data['street'],
            'post_code' => $data['post_code'],
            'gender' => $data['gender'],
            'telephone' => $data['mobile'],
            'password' => $pwd,
            'activated' => 0,
            'groups' => 'KH/Athena',
        ]);
        $email = $user->email . '';
        $subject = 'Thank you for registration on KeyHabits';
        $name = 'Welcome To KeyHabits';
        $rendered = view('email.register', ['data' => $user])->render();
        // Send Email Register to newly registred User
        Mail::send('email.register', ['data' => $data], function (Message $message) use ($rendered, $email, $subject, $name) {

            $message->to($email, $name)
                ->subject($subject);

            Log::info('MAIL SENT', [
                'to' => $email,
                'subject' => $subject,
                'from' => $message->getFrom(),
                'body' => $rendered,
            ]);

        });

        DB::commit();

        return $user;
    }
    //Override Login function that allow user the authenticate to app
    public function login($request)
    {
        $user = $this->getUserByEmail($request->email);
        // Attempt to log the user in
        $remember_me = isset($request->remember_token) ? true : false;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
           
            if ($remember_me) {
                setcookie('credentials', json_encode($user));
            } else {
                setcookie('credentials', null);
            }
            $request->session()->regenerate();
            return redirect()->intended('/home');
        } else {
            if ($user) {

                if (!Hash::check($request->password, $request->password)) {
                    // Wrong Role
                    return redirect()->back()->withInput($request->only('email', 'remember'))->with('error', 'Wrong password!');
                }
            } else {

                return redirect()->back()->withInput($request->only('email', 'remember'))->with('error', 'Email Not Found');
            }

        }
        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('Email', 'remember'));

    }

    //Override forgotPwd that send an email to reset password if email exist

    public function forgotpwd($email)
    {
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //get citoyen by email
            $user = $this->getUserByEmail($email);
            if ($user != null) {
                do {
                    //Generate a random string.
                    $token = openssl_random_pseudo_bytes(16);
                    //Convert the binary data into hexadecimal representation.
                    $token = bin2hex($token);
                    //dd($token);
                    //dd($token);
                    $user = User::where('user_token', '=', $token)
                        ->where('email', '=', $email)
                        ->get();
                }
                //pas de citoyen avec le meme token et mail
                while ($user == null);
                {
                    $user = $this->getUserByEmail($email);
                    //dd("fdhgs");
                    User::where('email', $email)
                        ->update(['user_token' => $token]);
                    // Send Email Register which contain the link to reset password

                    Mail::send('email.resetpwd', ['data' => $user, 'token' => $token], function ($message) use ($user) {
                        $message->to($user->email, 'Welcome Athena')
                            ->subject('Reset your Athena Password');
                    });
                    return redirect("/login")->with("success", "Check your email for your password!");
                }
            } else {
                return redirect("/forgotpwd")->with("error", "Email Not Found.");
            }
        } else {
            return redirect("/forgotpwd")->with("error", "Email Not Valid.");
        }
        return view('auth.forgot');
    }
    //Override updatePwd that reset the user password after click on reset link
    public function updatePwd($request)
    {
        //get token from url.
        $token = request()->segment(2);
        // dd($token);
        $user = $this->getUserByToken($token);
        if ($user == null) {
            abort(419, 'Page Expirée');
        }
        $this->updateUserPwdByToken($token, $request->password);

        return redirect('/login')->with('success', "Password reset successfuklly. You can login now!");
    }
    //Override logout function wich empty the user credentials, flash session & return to login page
    public function logout()
    {
        $user = Auth::user();

        Log::info('User Logged Out. ', [$user]);

        setcookie('sessionId', null);
        setcookie('userGuid', null);

        Auth::logout();

        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    public function getUserByEmail($email)
    {
        return User::where('email', '=', $email)->get()->first();
    }
    public function getUserByToken($token)
    {
        return User::where('user_token', '=', $token)->get()->first();
    }
    public function updateUserPwdByToken($token, $newPwd)
    {
        return User::where('user_token', $token)
            ->update(['password' => Hash::make($newPwd), 'user_token' => null]);
    }
    public function getUserByParam($key, $value)
    {
        return User::where($key, '=', $value)->get()->first();
    }
    // Profile fonctionnalities
    public function update_profile($request, $user_id)
    {
        //$this->validator($request->all())->validate();
        $User = $this->getUserByParam('id', $user_id);
        if (!$User) {
            return null;
        }
        $User->fullname = $request->fullname;
        $User->name = $request->username;
        $User->email = $request->email;
        $User->gender = $request->gender;
        $User->telephone = $request->telephone;
        $User->country = $request->country;
        $User->city = $request->city;
        $User->street = $request->street;
        $User->post_code = $request->post_code;
        $User->save();

        return $User;
    }
    public function Change_password($request, $user_id, $hashedPassword)
    {
        $User= $this->getUserByParam('id', $user_id);
        $hashed_pwd = Hash::make($request->current_pwd);
        if (\Hash::check($request->current_pwd, $hashedPassword)) {
            $User = User::find(Auth::user()->id);
            $User->password = bcrypt($request->new_pwd);
            $User->save();
            return $User;
        } else {
            return null;
        }

    }
    public function unsubscribe_user($request, $user_id)
    {
        $User = $this->getUserByParam('id', $user_id);
        if(!$User) {
          return null;
        }

        $User->activated=0;
        $User->unsubscription_date=Carbon::now();
        $User->save();

        return $User;
    }


}
