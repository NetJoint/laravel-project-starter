<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterEmailRequest;
use App\Http\Requests\AuthActivateEmailResendRequest;
use App\Http\Requests\AuthActivateRequest;

//use App\Http\Requests\AuthEmailResetRequest;
//use App\Http\Requests\AuthEmailBindRequest;
//use App\Http\Requests\AuthEmailResetCompleteRequest;

//use App\Http\Requests\AuthActivateClubRequest;
//use App\Http\Requests\AuthMobileRegisterRequest;
//use App\Http\Requests\AuthMobileVerifyRequest;
//use App\Http\Requests\AuthMobileResetRequest;

class AuthController extends Controller
{

    public function getLogin()
    {
        if (Sentinel::guest()) {
            return view('auth.login');
        } else {
            return redirect('/');
        }
    }

    public function postLogin(AuthLoginRequest $request)
    {
        $credentials = [
            'password' => $request->input('password'),
        ];

        if (is_numeric($request->input('login'))) {
            $credentials['mobile'] = $request->input('login');
        } else {
            $credentials['email'] = $request->input('login');
        }
        $remember = (bool) $request->input('remember_me', false);
        try {
            if (Sentinel::authenticate($credentials, $remember)) {
                    return redirect('/');
            }
            $error = '用户名或密码错误。';
        } catch (NotActivatedException $e) {
            if (is_numeric($request->input('login'))) {
                $error = '账号尚未激活，您可以<a href="/activate/mobile/resend?mobile=' . $credentials['mobile'] . '">重发验证短信</a>';
            } else {
                $error = '账号尚未激活，您可以<a href="/activate/email/resend?email=' . $credentials['email'] . '">重发验证邮件</a>';
            }
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            $error = "您尝试登录次数过多，为了保护账号安全，请您等待 {$delay} 秒后再试。";
        }
        $errors = array('msg' => $error);
        return redirect()->back()->withInput()->withErrors($errors);
    }

    public function getLogout()
    {
        Sentinel::logout();
        return redirect('login');
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function postRegisterEmail(AuthRegisterEmailRequest $request)
    {
        $credentials = array_only($request->all(), ['email']);
        $credentials['password'] = substr(md5(uniqid()), 0, 12);

        try {
            //注册用户
            $user = Sentinel::register($credentials);
            //生成激活码
            $activation = Activation::create($user);

            //在这里发送邮件
            Mail::send('emails.activation', ['user' => $user, 'activation' => $activation], function ($mail) use ($user) {
                $mail->from(env('MAIL_FROM_ADDRESS'), sitename());
                $mail->to($user['email']);
                $mail->subject('欢迎您加入' . sitename());
            });

            return view('auth.register_email_send')->with('email', $user['email']);
        } catch (Exception $e) {
            return redirect()->back()->withInput();
        }
    }
    
    public function getActivateEmailResend(Request $request)
    {
        $email = $request->input('email');
        return view('auth.activate_email_resend')->with('email', $email);
    }

    public function postActivateEmailResend(AuthActivateEmailResendRequest $request)
    {
        $credentials = [
            'email' => $request->input('email')
        ];
        //用户已注册
        if ($user = Sentinel::findByCredentials($credentials)) {
            if (Activation::completed($user)) {
            //如果用户已经激活，提示提示已经激活，跳转到登录页面
                $errors = ['msg' => '您的账户已经激活，请登录！'];
                return redirect()->route('login')->withErrors($errors);
            } else {
                Activation::removeExpired();
                $activation = Activation::exists($user);
                if (!$activation) {
                    //过期了
                    $activation = Activation::create($user);
                }
                //在这里发送邮件
                Mail::send('emails.activation', ['user' => $user, 'activation' => $activation], function ($mail) use ($user) {
                    $mail->from(env('MAIL_FROM_ADDRESS'), sitename());
                    $mail->to($user['email']);
                    $mail->subject('欢迎您加入' . sitename());
                });
                return view('auth.register_email_send')->with('email', $user['email']);
            }
        } else {//如果用户未注册，提示请注册，并跳转到注册页面
            $errors = ['email' => '您的邮箱未注册，请注册！'];
            return redirect()->route('register')->withErrors($errors);
        }
    }

    public function getActivateEmail($userId, $code)
    {
        $user = Sentinel::findById((int) $userId);
        if ($user) {
            if (Activation::completed($user)) {//如果用户已经激活，提示提示已经激活，跳转到登录页面
                $errors = ['msg' => '您的账户已经激活，请登录！'];
                return redirect('login')->withErrors($errors);
            } else {
                //设置初始密码
                return view('auth.init_password', ['userid' => $userId, 'code' => $code]);
            }
        } else {//如果用户未注册，提示请注册，并跳转到注册页面   
            $errors = ['email' => '您的邮箱未注册，请注册！'];
            return redirect('register')->withErrors($errors);
        }
    }
    
    public function postActivate(AuthActivateRequest $request)
    {
        //移除过期的激活码
        Activation::removeExpired();
        $user = Sentinel::findById($request->input('id'));
        if (Activation::complete($user, $request->input('code'))) {
            //设置新密码
            Sentinel::update($user, ['name' => $request->input('name'), 'password' => $request->input('password')]);
            //添加普通用户角色
            $user->roles()->attach(2);
            //跳转
            Sentinel::login($user);
            return redirect('activate/success');
        } else {
            $msg = '验证码不正确或已过期，';
            if ($user['email']) {
                $msg .= '<a href="/activate/email/resend?email=' . $user['email'] . '">重发验证邮件</a>';
            } else {
                $msg .= '<a href="/activate/mobile/resend?mobile=' . $user['mobile'] . '">重发验证短信</a>';
            }
            return redirect()->back()->withInput()->withErrors(array('code' => $msg));
        }
    }

    public function getActivateSuccess()
    {
        return view('auth.activate_success');
    }
    
    
    

    public function getReset(Request $request)
    {
        $email = $request->input('email', '');
        $mobile = $request->input('mobile', '');
        return view('auth.reset')->with(compact('email', 'mobile'));
    }

    public function postEmailReset(EmailResetRequest $request)
    {
        //删除过期数据
        Reminder::removeExpired();
        $credentials = [
            'email' => $request->input('email')
        ];
        if ($user = Sentinel::findByCredentials($credentials)) {
            $reminder = Reminder::create($user);
            //在这里发送邮件
            Mail::send('auth/emails.reset', ['user' => $user, 'reminder' => $reminder], function ($mail) use ($user) {
                $mail->from(env('MAIL_FROM_ADDRESS'), sitename());
                $mail->to($user['email']);
                $mail->subject('重置' . sitename() . '密码');
            });
            return view('auth.reset_email_send')->with('email', $user['email']);
        } else {//如果用户不存在，提示注册
            $errors = ['email' => '您的邮箱未注册，请注册！'];
            return redirect()->route('register')->withErrors($errors);
        }
    }

    public function getEmailResetComplete($userId, $code)
    {
        //移除过期的密码重置
        Reminder::removeExpired();
        //设置密码
        return view('auth.set_password', ['userid' => $userId, 'code' => $code]);
    }

    public function postEmailResetComplete(EmailResetCompleteRequest $request)
    {
        $user = Sentinel::findById($request->input('id'));
        if (Reminder::complete($user, $request->input('code'), $request->input('password'))) {
            //跳转
            $errors = ['msg' => '密码重置成功，请登录！'];
            return redirect()->route('login')->withErrors($errors);
        } else {
            $errors = ['msg' => '密码重置失败，重置链接无效。'];
            return redirect()->route('reset')->withErrors($errors);
        }
    }

    public function getEmailBindVerify($userId, $code)
    {
        $email = \Input::get('email');
        $user = Sentinel::findById($userId);
        if (!$user) {
            return \Response::view('errors.404', array(), 404);
        }
        return view('auth.email_bind_verify', ['user' => $user, 'user_id' => $userId, 'code' => $code, 'email' => $email]);
    }

    public function postEmailBindVerify(EmailBindRequest $request)
    {
        $user_id = $request->input('user_id');
        $user = Sentinel::findById($user_id);
        if (!$user) {
            $errors = array('email' => '用户不存在');
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $email = $request->input('email');
        if ($user->binding_email !== $email) {
            $errors = array('email' => '该用户未申请绑定该邮箱');
            return redirect()->back()->withInput()->withErrors($errors);
        }
        if ($user->email !== null) {
            $errors = array('email' => '用户已经绑定其他邮箱了');
            return redirect()->back()->withInput()->withErrors($errors);
        }
        Reminder::removeExpired();
        $reminder = Reminder::exists($user);
        if (!$reminder) {
            $errors = array('email' => '邮箱绑定码错误或已过期，请重新申请');
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $user->email = $email;
        $user->save();
        return view('auth.email_bind_verify_success', ['user' => $user, 'email' => $email]);
    }

    //mobile register:

    public function getMobileRegister($mobile)
    {
        return view('auth.register_mobile')->with('mobile', $mobile);
    }

    public function postMobileRegister(MobileRegisterRequest $request)
    {
        $credentials = array_only($request->all(), ['mobile']);
        $credentials['password'] = substr(md5(uniqid()), 0, 12);

        //注册用户
        $user = Sentinel::register($credentials);
        //生成激活码
        $activation = MobileActivation::create($user);
        return redirect('/register/mobile/' . $user['mobile']);
    }

    public function getResendMobileActivationCode(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                    'mobile' => 'required|digits:11',
        ]);
        if ($validator->fails()) {
            $errors = array('mobile' => '请填写正确的手机号码');
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $mobile = $request->input('mobile');
        return view('auth.register_mobile')->with('mobile', $mobile);
    }

    public function getMobileReset($mobile)
    {
        return view('auth.reset_mobile')->with('mobile', $mobile);
    }

    public function postMobileReset(MobileResetRequest $request)
    {
        MobileReminder::removeExpired();
        $credentials = array_only($request->all(), ['mobile']);
        $user = Sentinel::findByCredentials($credentials);
        MobileReminder::create($user);
        return redirect('/reset/mobile/' . $user['mobile']);
    }

    public function postMobileResetVerify(MobileVerifyRequest $request)
    {
        MobileReminder::removeExpired();
        $credentials = array_only($request->all(), ['mobile']);
        $user = Sentinel::findByCredentials($credentials);
        $reminder = MobileReminder::exists($user);
        if (!$reminder) {
            //过期了
            $reminder = MobileReminder::create($user);
            $errors = array('code' => '手机验证码已过期，请重新发送');
            return redirect()->back()->withInput()->withErrors($errors);
        } else {
            $code = $request->input('code');
            if ($code == $reminder['code']) {
                return view('auth.set_password', ['userid' => $user['id'], 'code' => $code]);
            } else {
                $errors = array('code' => '手机验证码不正确');
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
    }

    public function postActivateMobileVerify(MobileVerifyRequest $request)
    {
        Activation::removeExpired();
        $credentials = [
            'login' => $request->input('mobile'),
        ];
        $user = Sentinel::findByCredentials($credentials);
        if (!$user) {
            $errors = array('code' => '手机号码未注册');
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $activation = Activation::exists($user);
        if (!$activation) {
            //过期了
            $activation = MobileActivation::create($user);
            $errors = array('code' => '手机验证码已过期，请重新发送');
            return redirect()->back()->withInput()->withErrors($errors);
        } else {
            if ($request->input('code') == $activation['code']) {
                return redirect("/activate/mobile/{$user['id']}/{$activation['code']}");
            } else {
                $errors = array('code' => '手机验证码不正确');
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }
    }

    public function getMobileActivate($userId, $code)
    {
        $user = Sentinel::findById((int) $userId);
        if ($user) {
            if (Activation::completed($user)) {//如果用户已经激活，提示提示已经激活，跳转到登录页面
                $errors = ['msg' => '您的账户已经激活，请登录！'];
                return redirect()->route('login')->withErrors($errors);
            } else {
                //设置初始密码
                return view('auth.init_password', ['userid' => $userId, 'code' => $code]);
            }
        } else {//如果用户未注册，提示请注册，并跳转到注册页面   
            $errors = ['email' => '您的邮箱未注册，请注册！'];
            return redirect()->route('register')->withErrors($errors);
        }
    }

}
