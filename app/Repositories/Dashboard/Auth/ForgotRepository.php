<?php

namespace App\Repositories\Dashboard\Auth;

use App\Models\Admin;
use Ichtrojan\Otp\Otp;
use Ichtrojan\Otp\Models\Otp as OtpModel;
use Carbon\Carbon;

class ForgotRepository
{
    /**
     * Create a new class instance.
     */
    protected $otp2;
    public function __construct()
    {
        $this->otp2 = new Otp();
    } // End constructor

    public function getAdminByEmail($email)
    {
        $admin = Admin::where('email', $email)->first();
        return $admin;
    } // End sendOTP method

    public function verifyOtp($data)
    {
        // Custom validation to fix Carbon type error
        $identifier = $data['email'];
        $code = $data['token'];

        $otp = OtpModel::where('identifier', $identifier)->where('token', $code)->first();

        if ($otp instanceof OtpModel) {
            if ($otp->valid) {
                $now = Carbon::now();
                // Fix: Cast validity to integer (Carbon requires int|float, not string)
                $validityMinutes = (int) $otp->validity;
                $validity = $otp->created_at->copy()->addMinutes($validityMinutes);

                // Check if OTP has expired
                if ($validity->lt($now)) {
                    $otp->update(['valid' => false]);
                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                }

                // OTP is valid, mark as used
                $otp->update(['valid' => false]);

                return (object)[
                    'status' => true,
                    'message' => 'OTP is valid'
                ];
            }

            // OTP exists but is already invalid/used
            return (object)[
                'status' => false,
                'message' => 'OTP is not valid'
            ];
        } else {
            return (object)[
                'status' => false,
                'message' => 'OTP does not exist'
            ];
        }
    } // End verifyOtp method
}