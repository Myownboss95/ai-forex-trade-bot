<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SendEmailMailable;
use App\Models\Cms;
use Mail;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;

class CmsController extends Controller
{
    public function index()
    {
        $cms = Cms::get();
        return inertia('admin.cms.index', ['cms' => $cms]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'user' => ['required', 'numeric', 'exists:users,id'],
            'subject' => ['required', 'string'],
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file',],
        ]);

        $user = User::find($request->input('user'));

        Mail::to($user)
            ->send(
                new SendEmailMailable(
                    $request->input('subject'),
                    $request->input('message'),
                    [$request->file('attachment')]
                )
            );
        session()->flash('success', 'Email sent successfully');
        return back();
    }
}
