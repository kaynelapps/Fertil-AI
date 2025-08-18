<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HealthExpertRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HealthExpertDetailResource;
use App\Http\Resources\PaidUserResource;
use App\Models\subscriptions;
use App\Traits\EncryptionTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    use EncryptionTrait;

    public function register(Request $request)
    {
        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['status' => false,'message' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }
        
        if($decryptedData['login_type'] != 'social'){
      
            $validatedData = Validator::make($decryptedData, [
                'email' => 'required|max:191|email|unique:users,email',
                'goal_type' => 'required|numeric|in:0,1,2',
                'user_type' => 'required|in:anonymous_user,app_user',
            ]);

            if ($validatedData->fails()) {
                return response()->json([
                    'responseData' => $this->encryptData(['status' => false,'message' =>  $validatedData->errors()->first() ])
                ]);
            }
          }
        $input = $decryptedData;

       $user = User::where('email', $input['email'])->first();
        if (!$user) {
            $password = $input['password'];
            $input['user_type'] = $input['user_type'] ?? 'anonymous_user';
            $input['period_start_date'] = $input['period_start_date'] ?? null;
            $input['password'] = Hash::make($password);
            $input['display_name'] = $input['first_name'] . " " . $input['last_name'];
            $input['age'] = (int)$input['age'];

            $user = User::create($input);
            $user->assignRole($input['user_type']);

            $message = __('message.save_form', ['form' => __('message.' . $input['user_type'])]);
        }else{
            $message = __('message.login_success');
        }

        $user->api_token = $user->createToken('auth_token')->plainTextToken;
        $user->profile_image = getSingleMedia($user, 'profile_image', null);
        $response = [
            'message' => $message,
            'data' => $user,
            'status' => true
        ];
            return response()->json(['responseData' => $this->encryptData($response)]);
    }

    public function forgetPassword(Request $request)
    {
        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);

            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }

        // Validate data
        $validatedData = Validator::make($decryptedData, [
            'email' => 'required|email',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'responseData' => $this->encryptData(['errors' => $validatedData->errors()])
            ], 422);
        }

        $response = Password::sendResetLink([
            'email' => $decryptedData['email']
        ]);

        // return $response == Password::RESET_LINK_SENT
        //     ? response()->json(['message' => __($response), 'status' => true], 200)
        //     : response()->json(['message' => __($response), 'status' => false], 400);
        $responseData = [
            'message' => __($response),
            'status' => $response == Password::RESET_LINK_SENT
        ];

        return response()->json(['responseData' => $this->encryptData($responseData) ]);
    }

    public function userDetail(Request $request)
    {
        $auth_user = auth()->user();
        if ($auth_user->hasRole('doctor')) {
            $response = [
                'message' => __('message.demo_permission_denied'),
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
        }

        $user = User::whereNotIn('user_type', ['admin', 'doctor'])->where('id', $auth_user->id)->first();
        if (empty($user)) {
            $response = [
                'message' => __('message.not_found_entry', ['name' => __('message.user')]),
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
        }

        $user_detail = new UserResource($user);
        $response = [
            'data' => $user_detail,
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);

    }

    public function doctorDetail(Request $request)
    {
        $auth_user = auth()->user();
        if ($auth_user->user_type == ['anonymous_user', 'app_user']) {
            $message = __('message.demo_permission_denied');

            return response()->json(['responseData' => $this->encryptData(['message' => $message]) ]);
        }

        $user = User::where('user_type', 'doctor')->where('id', $auth_user->id)->first();
        if (empty($user)) {
            $message = __('message.not_found_entry', ['name' => __('message.health_experts')]);
            return response()->json(['responseData' => $this->encryptData(['message' => $message]) ]);
        }
        $health_expert = optional($user->health_expert);

        $user_detail = new HealthExpertDetailResource($health_expert);
        $response = ['data' => $user_detail];
        return response()->json(['responseData' => $this->encryptData($response) ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id', auth()->id())->first();

        if ($user == null) {
            $response = [
                'status' => false,
                'message' => __('message.no_record_found')
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
        }

        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['status' => false, 'error' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        if (isset($input['email']) && User::where('email', $input['email'])->where('id', '!=', $user->id)->exists()) {
            $response = [
                'status' => false,
                'message' => __('message.email_already_exists')
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
        }
        $input['user_type'] = isset($input['user_type']) ? $input['user_type'] : 'anonymous_user';

        if (!empty($input['password']) && isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        if(isset($input['first_name']) && isset( $input['last_name'])){
            $input['display_name'] = $input['first_name'] . " " . $input['last_name'];
        }
        if(isset($input['user_type'])){
            $user->assignRole($input['user_type']);
        }
        if(isset($input['age'])){
            $user->age = (int)$input['age'];
        }
        $user->fill($input)->update();

        $message = __('message.save_form', ['form' => __('message.' . $input['user_type'])]);
        $user->api_token = $user->createToken('auth_token')->plainTextToken;

        if (isset($request->profile_image) && $request->profile_image != null) {
            $user->clearMediaCollection('profile_image');
            $user->addMediaFromRequest('profile_image')->toMediaCollection('profile_image');
        }

        $user_data = User::find($user->id);

        $message = __('message.updated');
        unset($user_data['media']);

        $user_resource = new UserResource($user_data);

        $response = [
            'status' => true,
            'data' => $user_resource,
            'message' => $message
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response( $response );
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($request->is('api*')) {
            $user->tokens('auth_token')->delete();
            $user->update([
                'player_id' => null,
            ]);

            $response = [
                'message' => json_message_response(__('message.logout'))
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
        }
    }

    public function login(Request $request)
    {
        $now = now()->tz('Asia/Kolkata');

        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'], 'user_type' => $input['user_type']])) {

            $user = Auth::user();

            if ($user->status == 'banned') {
                $message = __('message.account_banned');
                return json_message_response($message, 400);
            }

            $user->last_actived_at = $now->format('Y-m-d H:i:s');
            $user->save();
            $user->tokens('auth_token')->delete();
            if ($user->user_type == 'doctor') {
                $success = $user->health_expert;
                $success['name'] = $user->display_name;
                $success['email'] = $user->email;
                $success['status'] = $user->status;
                unset($success['user_id'], $success['created_at'], $success['updated_at'], $success['deleted_at']);
                $success['health_experts_image'] = getSingleMedia($user->health_expert, 'health_experts_image', null);
            } else {
                $success = $user;
                $success['profile_image'] = getSingleMedia($user, 'profile_image', null);
            }
            $success['api_token'] = $user->createToken('auth_token')->plainTextToken;

            unset($success['media']);

            $response = [
                'status' => true,
                'data' => $success,
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
        } else {
            $message = __('auth.failed');
            $response = [
                'status' => false,
                'data' => null,
                'message' => $message,
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
        }
    }

    public function updateUserStatus(Request $request)
    {

        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        $user_id = auth()->user()->id;

        $user = User::where('id', $user_id)->first();

        if ($user == "") {
            $message = __('message.user_not_found');
            $response = [
                'message' => $message,
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
            // return json_message_response($message,400);
        }
        if (isset($input['status'])) {
            $user->status = $input['status'];
        }

        if (isset($input['app_version'])) {
            $user->app_version = $input['app_version'];
        }

        if (isset($input['app_source'])) {
            $user->app_source = $input['app_source'];
        }
        if (isset($input['goal_type'])) {
            $user->goal_type = $input['goal_type'];
        }

        if (isset($input['last_actived_at'])) {
            $user->last_actived_at = $input['last_actived_at'];
        }

        $user->save();
        $message = __('message.update_form', ['form' => __('message.status')]);
        $response = ['message' => $message ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }

    public function updateAppSetting(Request $request)
    {
        $data = $request->all();
        AppSetting::updateOrCreate(['id' => $request->id], $data);
        $message = __('message.save_form', ['form' => __('message.app_setting')]);
        $response = [
            'data' => AppSetting::first(),
            'message' => $message
        ];
        return json_custom_response($response);
    }

    public function getAppSetting(Request $request)
    {
        if ($request->has('id') && isset($request->id)) {
            $data = AppSetting::where('id', $request->id)->first();
        } else {
            $data = AppSetting::first();
        }

        return json_custom_response($data);
    }


    public function changePassword(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();

        if ($user == "") {
            $message = __('message.user_not_found');
            return response()->json(['responseData' => $this->encryptData(['message' => $message, 400]) ]);
            // return json_message_response($message, 400);
        }

        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        $hashedPassword = $user->password;

        $match = Hash::check($input['old_password'], $hashedPassword);

        $same_exits = Hash::check($input['new_password'], $hashedPassword);
        if ($match) {
            if ($same_exits) {
                $message = __('message.old_new_pass_same');
                $response = [
                    'status' => false,
                    'message' => $message
                ];
                return response()->json(['responseData' => $this->encryptData($response) ]);
                // return json_message_response($message, 400);
            }

            $user->fill([
                'password' => Hash::make($input['new_password'])
            ])->save();

            $message = __('message.password_change');
            $response = [
                'status' => true,
                'message' => $message
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
            // return json_message_response($message, 200);
        } else {
            $message = __('message.valid_password');
            $response = [
                'status' => false,
                'message' => $message
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
            // return json_message_response($message, 400);
        }
    }

    public function deleteUserAccount(Request $request)
    {
        $id = auth()->id();
        $user = User::whereNotIn('user_type', ['admin'])->where('id', $id)->first();
        $message = __('message.not_found_entry', ['name' => __('message.account')]);

        if ($user != '') {
            $user->delete();
            $message = __('message.account_deleted');
        }

        $response = [
            'message' => $message,
            'status' => true
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);

        // return json_custom_response(['message'=> $message, ]);
    }

    public function updateGoalType(Request $request)
    {
        $user = auth()->user();

        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }

        $validatedData = Validator::make($decryptedData, [
            'goal_type' => 'required|numeric|in:0,1,2',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['responseData' => $this->encryptData(['errors' => $validatedData->errors()]) ]);
        }
        if (empty($user)) {
            $message = __('message.not_found_entry', ['name' => __('message.user')]);
            $response = [
                'message' => $message,
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
            // return json_message_response($message,400);
        }

        $input = $decryptedData;
        $user->goal_type = $input['goal_type'];
        $user->save();

        $message = __('message.update_form', ['form' => __('message.goal_type')]);
        $response = [
            'message' => $message,
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_message_response( $message );
    }

    public function createPairing(Request $request)
    {
        $user = auth()->user();
        if (empty($user)) {
            $message = __('message.not_found_entry', ['name' => __('message.user')]);
            return response()->json(['responseData' => $this->encryptData(['message' => $message,]) ]);
            // return json_message_response($message,400);
        }

        $user->is_linked = 0;
        $user->pairing_code = generateRandomCode();
        $user->save();

        $message = __('message.pairing_code_set');
        $response = [
            'pairing_code' => $user->pairing_code,
            'message' => $message,
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response([ 'pairing_code' => $user->pairing_code,'message' => $message ]);
    }

    public function verifyPairing(Request $request)
    {
        $user = auth()->user();
        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        $userData = User::where('pairing_code', $input['pairing_code'])->find($user->id);
        if (empty($userData)) {
            $message = __('message.invalid_pairing_code');
            $response = [
                'message' => $message,
            ];
            return response()->json(['responseData' => $this->encryptData($response) ]);
            // return json_custom_response(['status' => false,'message' => $message ]);
        }
        $userData->is_linked = 1;
        $userData->partner_name = $input['partner_name'];
        $userData->pairing_code = $input['pairing_code'];
        $userData->save();

        $message = __('message.pairing_success');
        $response = [
            'message' => $message,
            'status' => true,
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response(['message' => $message ,'status' => true]);
    }

    public function cancelInvitation(Request $request)
    {
        $user = auth()->user();
        if (empty($user)) {
            $message = __('message.not_found_entry', ['name' => __('message.user')]);
            return response()->json(['responseData' => $this->encryptData(['message' => $message]) ]);
            // return json_message_response($message,400);
        }

        $user->is_linked = 0;
        $user->pairing_code = NULL;
        $user->partner_name = NULL;
        $user->save();

        $message = __('message.cancel_invitation');
        return response()->json(['responseData' => $this->encryptData(['message' => $message]) ]);
        // return json_message_response( $message );
    }

    public function removeLinking(Request $request)
    {
        $user = auth()->user();
        if (empty($user)) {
            $message = __('message.not_found_entry', ['name' => __('message.user')]);
            return response()->json(['responseData' => $this->encryptData(['message' => $message]) ]);
            // return json_message_response($message,400);
        }
        if ($user->is_linked == 1) {
            $user->is_linked = 0;
            $user->pairing_code = NULL;
            $user->partner_name = NULL;
            $user->save();
            $message = __('message.pairing_removed');
        } else {
            $message = __('message.not_pairing');
        }

        $response = ['message' => $message ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_message_response( $message );
    }

    public function deleteUserData(Request $request)
    {
        $id = auth()->id();
        $user = User::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.user')]);

        if ($user != '') {
            if ($user->user_type == 'doctor') {
                $user->health_expert->article()->delete();
                $user->health_expert->educational_session()->forceDelete();
                $user->health_expert->healthExpertSession()->forceDelete();
            } else {
                $user->log_period()->delete();
                $user->bookmark_insights()->delete();
                $user->session_registration()->forceDelete();
                $user->user_symptom()->delete();
            }
            $message = __('message.delete_form', ['form' => __('message.user_data')]);
        }

        $response = [
            'message' => $message,
            'status' => true,
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response(['message'=> $message, ]);
    }

    public function updateHealthExpertProfile(HealthExpertRequest $request)
    {
        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        $user = auth()->user();

        if (!$user->hasRole('doctor')) {
            $message = __('message.demo_permission_denied');
            return response()->json(['responseData' => $this->encryptData(['message' => $message]) ]);
            // return json_message_response($message, 403);
        }

        if (empty($user)) {
            $message = __('message.not_found_entry', ['name' => __('message.health_expert')]);
            return response()->json(['responseData' => $this->encryptData(['message' => $message]) ]);
            // return json_message_response($message,400);
        }

        $health_expert = optional($user->health_expert);
        $user->fill(['first_name' => $input['name'], 'display_name' => $input['name'], 'status' => $input['status'] ?? $user->status])->update();

        if ($health_expert == null) {
            $message = __('message.not_found_entry', ['name' => __('message.health_expert')]);
            return response()->json([
                'responseData' => $this->encryptData(['message' => $message]),
            ]);
            // return json_message_response($message,400);
        }

        // Health Expert data...
        unset($input['name']);
        unset($input['email']);
        unset($input['status']);

        $health_expert->fill($input)->update();

        if (isset($input['health_experts_image']) && $input['health_experts_image'] != null) {
            $health_expert->clearMediaCollection('health_experts_image');
            $health_expert->addMediaFromRequest('health_experts_image')->toMediaCollection('health_experts_image');
        }

        $message = __('message.update_form', ['form' => __('message.health_experts')]);
        return response()->json(['responseData' => $this->encryptData(['message' => $message, 'data' => new HealthExpertDetailResource($health_expert)]) ]);
        // return json_custom_response([ 'message'=> $message , 'data' => new HealthExpertDetailResource($health_expert) ]);
    }

    public function sendCode(Request $request)
    {
        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        $validator = Validator::make($input, ['email' => 'required|email',]);

        if ($validator->fails()) {
            return response()->json(['responseData' => $this->encryptData(['message' => $validator->errors()]) ]);
            // return response()->json(['message' => $validator->errors()], 400);
        }

        $code = rand(1000, 9999);
        $email = $input['email'];

        try {
            Mail::send('emails_view.send_code', ['code' => $code], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your Verification Code');
            });
        } catch (\Throwable $e) {
            $message = 'Failed to send email: ' . $e->getMessage();
            return response()->json(['responseData' => $this->encryptData(['message' => $validator->errors()]) ]);
            // return json_message_response($message, 400);
        }

        return response()->json(['responseData' => $this->encryptData(['message' => 'Verification code sent successfully!', 'code' => $code]) ]);
        // return response()->json(['message' => 'Verification code sent successfully!' , 'code' => $code]);
    }

    public function backupData(Request $request)
    {
        try {
            $request->validate([
                'is_backup' => 'required|in:on,off',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid choice.',
            ], 422);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }
        $user->is_backup = $request->is_backup;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => $user->is_backup === 'on' ? 'Backup is enabled.' : 'Backup is disabled.',
        ]);
    }

    public function restoreData(Request $request)
    {
        $user = auth()->user();
        if (empty($user)) {
            $message = __('message.not_found_entry', ['name' => __('message.user')]);
            return response()->json(['responseData' => $this->encryptData(['message' => $message]) ]);
        }

        if ((!$user->encrypted_user_data && !$user->last_sink_date) && (empty($user->encrypted_user_data) && empty($user->last_sink_date))) {
            return response()->json([
                'status' => false,
                'message' => __('message.no_backup_found_user')
            ], 404);
        }
        if ($user->is_backup == 'on') {
            return response()->json([
                'status' => true,
                'message' => 'Backup restored successfully.',
                'data' => [
                    'is_backup' => $user->is_backup,
                    'encrypted_user_data' => $user->encrypted_user_data,
                    'last_sync_date' => $user->last_sync_date,
                ],
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => __('message.application_data_backup')
        ], 404);

    }

    public function manualBackup(Request $request)
    {
        $user = auth()->user();
        $now = now()->tz('Asia/Kolkata');

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.'
            ], 404);
        }

        if ($user->is_backup !== 'on') {
            return response()->json([
                'status' => false,
                'message' => __('message.application_data_backup_proceed')
            ], 403);
        }

        $incomingData = $request->encrypted_user_data;

        if (empty($incomingData)) {
            if (!empty($user->encrypted_user_data)) {
                return response()->json([
                    'status' => true,
                    'message' => __('message.new_backup_provided')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => __('message.new_backup_provided_existing')
            ], 400);
        }

        $user->encrypted_user_data = $incomingData;
        $user->last_sync_date = $now->format('Y-m-d H:i:s');
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Backup saved successfully.'
        ]);
    }
}
