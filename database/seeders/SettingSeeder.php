<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data['name']              = 'captaincare' ;
        $data['phone']             = '' ;
        $data['email']             = 'info@captaincare.com' ;
        $data['address_en']        = 'Egypt' ;
        $data['address_ar']        = 'مصر' ;
        $data['footerQuote_en']    = '-' ;
        $data['footerQuote_ar']    = '-' ;
        $data['twitter']           = 'https://captaincare.com/' ;
        $data['facebook']          = 'https://captaincare.com/' ;
        $data['linkedin']          = 'https://captaincare.com/' ;
        $data['youtube']           = 'https://captaincare.com/' ;
        $data['mail_driver']       = 'smtp' ;
        $data['mail_host']         = 'smtp.hostinger.com' ;
        $data['mail_port']         = '465' ;
        $data['mail_username']     = 'info@hurryin.co' ;
        $data['mail_password']     = '1?Pc=#jPO>' ;
        $data['mail_encryption']   = 'ssl' ;
        $data['mail_from_Addesss'] = 'info@hurryin.co' ;
        $data['mail_from_name']    = '${APP_NAME}' ;

        $data['PAYMOB_API_KEY']   = 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TVRJME9EVTJMQ0p1WVcxbElqb2lhVzVwZEdsaGJDSjkuWmF6YXYyWEt3NmdZYngzY3BFbkUzWnpNZnJPNDJ4bTI0YnVqeno1NURiaW5YcjczTEFiNVhTemtXQmlCZFpmNXVIQnpianowQ094RzJrYzF4VW9UaVE=' ;
        $data['PAYMOB_CLIENT_ID']   = '124856' ;
        $data['PAYMOB_IFRAME_ID']   = '' ;
        $data['PAYMOB_HMAC']   = 'D1ACB6A1FFBE90FF8DEC8C3EA4CD7FDE' ;
        $data['PAYMOB_CURRENCY']   = 'EG' ;
        $data['PAYMOB_INTEGRATION_ID'] = '1016758';

        $data['s3_access_key']     = '' ;
        $data['s3_secret_key']     = '' ;
        $data['s3_sefault_key']    = '' ;
        $data['s3_bucket']         = '' ;
        

        Setting::create($data);

    }
}
