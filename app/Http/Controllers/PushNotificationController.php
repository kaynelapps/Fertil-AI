<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushNotification;
use App\DataTables\PushNotificationDataTable;
use App\Models\User;
use App\Notifications\CommonNotification;
use App\Notifications\DatabaseNotification;
use App\Models\Notification;
use App\Helpers\AuthHelper;

class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PushNotificationDataTable $dataTable)
    {
        $pageTitle = __('message.list_form_title',['form' => __('message.pushnotification')] );
        $auth_user = authSession();
        // if (!auth()->user()->can('push notification-list')) {
        //     $message = __('message.permission_denied_for_account');
        //     return redirect()->back()->withErrors($message);
        // }
        $assets = ['data-table'];

        $button = $auth_user->can('push notification-add') ? '<a href="'.route('pushnotification.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.set_form_title',['form' => __('message.pushnotification')]).'</a>' : '';
        $delete_checkbox_checkout = $auth_user->can('push notification-delete') ? '<button id="deleteSelectedBtn" checked-title = "pushnotification-checked" class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';


        return $dataTable->render('global.datatable', compact('pageTitle', 'auth_user', 'assets', 'button','delete_checkbox_checkout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!auth()->user()->can('push notification-add')) {
        //     $message = __('message.permission_denied_for_account');
        //     return redirect()->back()->withErrors($message);
        // }
        $pageTitle = __('message.set_form_title',[ 'form' => __('message.pushnotification')]);
        
        return view('push_notification.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('pushnotification.index')->withErrors($message);
        }
        // if (!auth()->user()->can('push notification-add')) {
        //     $message = __('message.permission_denied_for_account');
        //     return redirect()->back()->withErrors($message);
        // }
        // dd($request->all());
        $pushnotification = PushNotification::create($request->all());

        uploadMediaFile($pushnotification, $request->notification_image, 'notification_image');
        
        $notification_data = [
            'id' => $pushnotification->id,
            'push_notification_id' => $pushnotification->id,
            'type' => 'push_notification',
            'subject' => $pushnotification->title,
            'message' => $pushnotification->message,
            'goal_type' => $pushnotification->goal_type,
        ];
        if( getMediaFileExit($pushnotification, 'notification_image') ) {
            $notification_data['image'] = getSingleMedia($pushnotification, 'notification_image');
        } else {
            $notification_data['image'] = null;
        }

        if ($request->has('user')) {
            User::whereIn('id', $request->user)->chunk(20, function ($userdata) use ($notification_data) {
                foreach ($userdata as $user) {
                    $user->notify(new CommonNotification($notification_data['type'], $notification_data));
                    $user->notify(new DatabaseNotification($notification_data));
                }
            });
        }  

        return redirect()->route('pushnotification.index')->withSuccess(__('message.save_form', ['form' => __('message.pushnotification')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = __('message.update_form_title',[ 'form' => __('message.pushnotification')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('pushnotification.index')->withErrors($message);
        }

        $pushnotification = PushNotification::findOrFail($id);
        $status = 'error';
        $message = __('message.not_found_entry', ['name' => __('message.pushnotification')]);

        if($pushnotification != '') {
            Notification::whereJsonContains('data->push_notification_id',$id);
            $pushnotification->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.pushnotification')]);
        }

        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message ]);
        }

        return redirect()->back()->with($status, $message);
    }
}
