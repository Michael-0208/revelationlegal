<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * 
     * index
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index () {
        return view('support.index');
    }
    /**
     * 
     * Invitation Settings
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function contact () {
        $ticketData = $this->request->all();

        $user = Auth::user();

        if (filter_var($ticketData['email'], FILTER_VALIDATE_EMAIL)) {
            Mail::send([], [], function ($message) use ($user, $ticketData) {
                $message->from('support@revelationlegal.com', "{$user->first_name} {$user->last_name} ($user->username)");
                $message->to('support@revelationlegal.com', 'RevelationLegal Support');
                $message->subject('New Support Request');
                $mailText = "<b>From: </b> $user->first_name $user->last_name ($user->username)<br>" . 
                    "<b>Contact Email: </b> {$ticketData['email']} <br>" . 
                    "<b>Phone: </b> {$ticketData['phone']} <br>" . 
                    "<b>Message: </b> {$ticketData['message']}";
                $message->setBody($mailText, 'text/html');
            });
            $data['sent'] = 1;
        } else {
            $data['error'] = 1;
        }

        return view('support.index')->with('data', $data);
    }
}
