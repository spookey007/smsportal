<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Smtp;
use Illuminate\Http\Request;


class SmtpController extends Controller
{
    public function index()
    {
        $pageTitle                       = 'All SMTP';
        $allSmtp                         = Smtp::orderBy('id','DESC')->paginate(getPaginate());
        return view('admin.smtp.index', compact('pageTitle', 'allSmtp'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'host'                         => 'required',
            'port'                         => 'required',
            'encryption'                   => 'required|in:ssl,tls',
            'username'                     => 'required',
            'password'                     => 'required',
        ]);

        if ($id) {
            $smtp                          = Smtp::findOrFail($id);
            $message                       = "Smtp updated successfully";
            $smtp->status                  = $request->status ? 1 : 0;
        } else {
            $smtp                          = new Smtp();
            $message                       = "Smtp added successfully";
        }
        $smtp->host                        = $request->host;
        $smtp->port                        = $request->port;
        $smtp->encryption                  = $request->encryption;
        $smtp->username                    = $request->username;
        $smtp->password                    = $request->password;
        $smtp->save();
        $notify[]                          = ['success', $message];
        return back()->withNotify($notify);
    }
}
