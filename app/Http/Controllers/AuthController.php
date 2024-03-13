<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Mail\RegistrationEmail;
use App\Models\User;
use App\Services\InforuSMSService;
use App\Notifications\SendSMSNotification;


class AuthController extends Controller
{
    public function register(Request $request)
    {

        
        $request->validate([
            'name' => 'required|string',            
            'email' => 'required|email|unique:users',
            'country_name' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Create the user
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ]);

            // Attach the country
            $user->countries()->create([
                'country_name' => $request->input('country_name'),
            ]);

            // Attach the phone number
            $user->phoneBook()->create([
                'phone_number' => $request->input('phone_number'),
            ]);

            // Instead SMS Sending - Notification;
            $messages['msg'] = "Congratulations! 🎉 {$request->input('name')}, You've successfully registered with us!";
            $user->notify(new SendSMSNotification($messages));
            
            // Send SMS 
            // $smsService = new InforuSMSService();
            // $smsService->sendMessage($request->input('phone_number'), "Successfully registered. Welcome!");

            // Send Email 
            Mail::to($user->email)->send(new RegistrationEmail($user));

            DB::commit();

            return response()->json(['message' => 'User registered successfully']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Registration failed'], 500);
        }
    }
}
