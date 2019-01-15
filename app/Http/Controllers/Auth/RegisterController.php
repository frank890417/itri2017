<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

    use Illuminate\Http\Request;
    use Illuminate\Auth\Events\Registered;
    use Jrean\UserVerification\Traits\VerifiesUsers;
    use Jrean\UserVerification\Facades\UserVerification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
use VerifiesUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/registeredByConfirm';  //後來改成不要email認證
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        // Based on the workflow you need, you may update and customize the following lines.
        //$this->middleware('guest', ['except' => ['getVerification', 'getVerificationError']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            //'auth_code' => 'required|in:ITRI2017MONOAME'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

    }

/**
         * Handle a registration request for the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function register(Request $request)
        {
            $this->validator($request->all())->validate();

            $user = $this->create($request->all());

            event(new Registered($user));

            //$this->guard()->login($user);

            UserVerification::generate($user);

            UserVerification::send($user, '[家庭電器用電家計簿網站]會員認證信件');

            //return $this->registered($request, $user)?: redirect($this->redirectPath());
            return redirect($this->redirectPath());
        }

public function showRegistered()
    {
        return view('auth.registeredByConfirm');
        //return View::make('auth.registeredByConfirm');
    }
}
