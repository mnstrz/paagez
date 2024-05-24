<?php

namespace Monsterz\Paagez\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Monsterz\Paagez\Mails\TestEmail;

class EmailController extends Controller
{
    public function index()
    {
        $mailers = [
            'smtp' => __("SMTP"),
            'ses' => __("SES"),
            'mailgun' => __("MAILGUN"),
            'postmark' => __("POSTMARK"),
            'sendmail' => __("SENDMAIL"),
            'log' => __("LOG"),
            'array' => __("ARRAY"),
            'failover' => __("FAILOVER"),
        ];
        return view(config('paagez.theme').'::app.email',compact('mailers'));
    }

    public function update(Request $request)
    {
        $mailers = ['smtp','ses','mailgun','postmark','sendmail','log','array','failover'];
        $this->validate($request,[
            'mailer' => 'required|in:'.implode(",",$mailers)
        ],[],[
            'mailer' => __('paagez.mailer'),
            'host' => __('paagez.host'),
            'port' => __('paagez.port'),
            'encryption' => __('paagez.encryption'),
            'username' => __('paagez.username'),
            'password' => __('paagez.password'),
            'local_domain' => __('paagez.local_domain'),
            'path' => __('paagez.path'),
            'channel' => __('paagez.channel'),
            'from_address' => __('paagez.from_address'),
            'from_name' => __('paagez.from_name'),
        ]);
        $request = $request->except("_token");
        if($request['mailer'] == 'smtp')
        {
            $request['path'] = null;
            $request['channel'] = null;
        }elseif($request['mailer'] == 'sendmail')
        {
            $request['host'] = null;
            $request['port'] = null;
            $request['encryption'] = null;
            $request['username'] = null;
            $request['local_domain'] = null;
            $request['channel'] = null;
        }elseif($request['mailer'] == 'log')
        {
            $request['host'] = null;
            $request['port'] = null;
            $request['encryption'] = null;
            $request['username'] = null;
            $request['local_domain'] = null;
            $request['path'] = null;
        }else{
            $request['host'] = null;
            $request['port'] = null;
            $request['encryption'] = null;
            $request['username'] = null;
            $request['local_domain'] = null;
            $request['channel'] = null;
            $request['path'] = null;
        }
        foreach ($request as $key => $value) {
            config('paagez.models.config')::updateOrCreate([
                'name' => "mail_".$key
            ],[
                'name' => "mail_".$key,
                'value' => $value,
            ]);
        }
        return redirect(config('paagez.prefix')."/app/email")->with(["success" => __("Mail updated")]);
    }

    public function reset()
    {
        config('paagez.models.config')::where('name','like',"mail_%")->delete();
        return redirect(config('paagez.prefix')."/app/email")->with(["success" => __("Mail reset")]);
    }

    public function test(Request $request)
    {
        $this->validate($request,[
            'email' => 'email|required',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);
        try {
            \Mail::raw($request->message, function ($mail) use ($request) {
                $mail->to($request->email)
                     ->subject($request->subject);
            });
            return redirect(config('paagez.prefix')."/app/email")->with(["success" => __("Mail sent")]);
        } catch (\Exception $e) {
            return redirect(config('paagez.prefix')."/app/email")->with(["warning" => $e->getMessage()]);
        }
    }
}
