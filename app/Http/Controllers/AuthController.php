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

    public function getLogin(Request $request)
    {
        $jump = $request->input('jump','');
        if (Sentinel::guest()) {
            return view('auth.login',['jump' => $jump]);
        } else {
            $user = Sentinel::getUser();
            if ($user->hasRole('admin')) {
                # 用户为管理员则跳转到管理中心
                return redirect('/manage');
            } else {
                # 普通用户跳转到用户中心
                return redirect('/home');
            }
        }
    }

    public function postLogin(AuthLoginRequest $request)
    {
        $credentials = [
            'password' => $request->input('password'),
        ];
        
        $jump = $request->input('jump','');

        if (is_numeric($request->input('login'))) {
            $credentials['mobile'] = $request->input('login');
        } else {
            $credentials['email'] = $request->input('login');
        }
        $remember = (bool) $request->input('remember_me', false);
        try {
            if (Sentinel::authenticate($credentials, $remember)) {
                if($jump){
                    return redirect($jump);
                }
                $user = Sentinel::getUser();
                if ($user->hasRole('admin')) {
                    # 用户为管理员则跳转到管理中心
                    return redirect('/manage');
                } else {
                    # 普通用户跳转到用户中心
                    return redirect('/home');
                }
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
        $email = $request->input('email');
        $credentials = compact('email');
        # 查找邮件地址是否已经注册
        $user = Sentinel::findByCredentials($credentials);
        if ($user) {
            # 用户已存在
            if (Activation::completed($user)) {
                # 用户已激活
                $errors = ['email' => '该邮箱已激活，您可以 <a href="/login">登录</a> 或 <a href="/reset?email=' . $email . '">重置密码</a>'];
                return redirect()->back()->withInput()->withErrors($errors);
            }
            # 未激活则重发验证邮件
            Activation::removeExpired();
            $activation = Activation::exists($user);
            if (!$activation) {
                # 激活码过期了，生成新的
                $activation = Activation::create($user);
            }
        } else {
            # 用户不存在，添加新用户
            $credentials = [
                'email' => $email,
                'password' => substr(md5(uniqid()), 0, 12)
            ];
            $user = Sentinel::register($credentials);
            $activation = Activation::create($user);
        }
        try {
            # 发送激活邮件
            Mail::send('emails.activation', ['user' => $user, 'activation' => $activation], function ($mail) use ($email) {
                $mail->from(env('MAIL_FROM_ADDRESS'), sitename());
                $mail->to($email);
                $mail->subject('欢迎您加入' . sitename());
            });
            return view('auth.register_email_send', compact('email'));
        } catch (Exception $e) {
            # 出错可能是发送参数设置不正确，请检查.env文件邮件配置
            $errors = ['email' => '邮件发送失败，请稍后再试'];
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }

    public function getActivateEmailResend(Request $request)
    {
        $email = $request->input('email');
        return view('auth.activate_email_resend', compact('email'));
    }

    public function postActivateEmailResend(AuthActivateEmailResendRequest $request)
    {
        $email = $request->input('email');
        $credentials = compact('email');
        # 查找邮件地址注册的用户，用户不存在的情况已在AuthActivateEmailResendRequest排除
        $user = Sentinel::findByCredentials($credentials);
        # 用户不存在情况已在
        if (Activation::completed($user)) {
            # 用户已激活
            $errors = ['email' => '该邮箱已激活，您可以 <a href="/login">登录</a> 或 <a href="/reset?email=' . $email . '">重置密码</a>'];
            return redirect()->back()->withInput()->withErrors($errors);
        }
        # 未激活则重发验证邮件
        Activation::removeExpired();
        $activation = Activation::exists($user);
        if (!$activation) {
            # 激活码过期了，生成新的
            $activation = Activation::create($user);
        }
        try {
            # 发送激活邮件
            Mail::send('emails.activation', ['user' => $user, 'activation' => $activation], function ($mail) use ($user) {
                $mail->from(env('MAIL_FROM_ADDRESS'), sitename());
                $mail->to($user['email']);
                $mail->subject('欢迎您加入' . sitename());
            });
            return view('auth.register_email_send', compact('email'));
        } catch (Exception $e) {
            # 出错可能是发送参数设置不正确，请检查.env文件邮件配置
            $errors = ['email' => '邮件发送失败，请稍后再试'];
            return redirect()->back()->withInput()->withErrors($errors);
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
        return view('auth.reset', compact('email'));
    }

    public function postEmailReset(EmailResetRequest $request)
    {
        $email = $request->input('email');
        //删除过期数据        
        Reminder::removeExpired();
        $credentials = compact('email');
        $user = Sentinel::findByCredentials($credentials);
        if ($user) {
            $reminder = Reminder::create($user);
            //在这里发送邮件
            Mail::send('auth/emails.reset', ['user' => $user, 'reminder' => $reminder], function ($mail) use ($email) {
                $mail->from(env('MAIL_FROM_ADDRESS'), sitename());
                $mail->to($email);
                $mail->subject('重置' . sitename() . '密码');
            });
            return view('auth.reset_email_send', compact('email'));
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

    public function getResetSuccess()
    {
        return view('auth.reset_success');
    }

}
