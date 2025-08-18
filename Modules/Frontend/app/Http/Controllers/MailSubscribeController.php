<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Frontend\Http\Requests\SubscribeRequest;
use Modules\Frontend\Models\MailSubscribe;

use Illuminate\Http\Request;

class MailSubscribeController extends Controller
{
    public function subscribe(SubscribeRequest $request)
    {
        $existing_subscribe = MailSubscribe::where('email', $request->email)->first();

        if ($existing_subscribe) {
            if ($existing_subscribe->is_subscribe == '1') {
                return redirect()->back()->withErrors(['email' => __('frontend::message.email_exists')]);
            }
    
            $existing_subscribe->update(['is_subscribe' => '1', 'reason' => null]);
            return redirect()->back()->withSuccess(__('frontend::message.subscribe_success'));
        }
    
        MailSubscribe::create([
            'email' => $request->email,
            'is_subscribe' => '1',
        ]);
        
        return redirect()->back()->withSuccess(__('frontend::message.subscribe_success'));
    }

    public function showUnsubscribeForm(Request $request)
    {
        $email = encryptDecryptId($request->email, 'decrypt');

        $subscriber = MailSubscribe::where('email', $email)->firstOrFail();
        if ($subscriber->is_subscribe == 0) {
            return view('frontend::frontend.unsubscribe.resubscribed', ['email' => $subscriber->email]);
        }
        return view('frontend::frontend.unsubscribe.form', compact('subscriber'));
    }

    public function unsubscribe(Request $request)
    {
        $email = encryptDecryptId($request->email, 'decrypt');

        $subscriber = MailSubscribe::where('email', $email)->firstOrFail();
        
        $subscriber->is_subscribe = 0;
        $subscriber->reason = $request->reason == 'other' ? request('other_reason') : $request->reason;
        $subscriber->save();

        return redirect()->route('unsubscribe.success');
    }

    public function unsubscribeSuccess()
    {
        return view('frontend::frontend.unsubscribe.feedback');
    }
    
    public function resubscribe(Request $request)
    {
        $email = encryptDecryptId($request->email, 'decrypt');
        $subscriber = MailSubscribe::where('email', $email)->firstOrFail();

        $subscriber->is_subscribe = 1;
        $subscriber->reason = null;
        $subscriber->save();

        return view('frontend::frontend.unsubscribe.success-resubscribed');

    }

}
