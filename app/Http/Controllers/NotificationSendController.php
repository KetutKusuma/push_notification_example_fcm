<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class NotificationSendController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        Auth::user()->device_token =  $request->token;

        Auth::user()->save();

        return response()->json(['Token successfully stored.']);
    }

    public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        // $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        /**
         * Test push notif to android
         *
         * Test Token android
         */
        $FcmToken = 'eLML06EqRDOMuOTadF_qf6:APA91bGLle7jRiMpxk5KRPJUDug24kf0QGvJx-cgKjScmknTpHfOKeTKxLECfmVAKBiWULUrUKa1yvT24AFuv8MpGY4BrDaUzbuYns3NaVo4SyrQ7RclZ_ljqlEI-Zx40P8vA7_TjFba';
        $FcmToken2 = 'cc4oONwEQC-qpBdgFKJTA_:APA91bExl3xkzkj4JuNlx7yIPfKPe3p9TCr65G__ZwF3xWvmjoBMqhQdCiwfl4WezKW0B_IlkyiI0w3b2zDtli6GMj8-cK6Zht-rLxupm0uGp4mlmr0101ASkMTYHV9rsnb-5WOoc66B';
        // $serverKey = 'AAAARlegrVQ:APA91bGRiYFWcd8ETogYuxFzaSth3FWywfKTi4cl411IvrZOpKtWIpn1HQk1kJ0bshfKFlOKh1FkYAnGErX2nIVuquzN9WIez2cD4u_c_4dL8gJkfoQfAPwjlLraQo0SSGw3OeFjnfUL'; // ADD SERVER KEY HERE PROVIDED BY FCM rumbleisback
        $serverKey = 'AAAAWk-2FO8:APA91bF3rb7H8gXdvd1LudqBrnfRPmwVRkpQ9ghKZrDtUmjUCS9VuRyZh5_hXhw_CoB2twshSXrefAzC8xd1qhwF9h0ZOhZ6MKi2gxjG0sfM0ReqFzmQ4uZCMPBGqAh-aM7EpmS_w60p'; // ADD SERVER KEY HERE PROVIDED BY FCM ketutkusuma
        $alamatMama = "jalan majapahit";
        $dataMessage = array(
            "alamat mama" => $alamatMama,
            "alamat papa" => "jl. mama",
        );
        $dataMessageTest = "WOII"."\n"."CUCI LAHHH";
        $arrayToken = array(
            $FcmToken,
            $FcmToken2
        );


        $data = [
            // "registration_ids" => [
            //     $FcmToken,
            // ],
            "registration_ids" => $arrayToken,
            "notification" => [
                "title" => $request->title,
                // "body" => $request->body,

                "body" => $dataMessageTest
            ]
        ];

        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }
}
