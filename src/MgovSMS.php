<?php
namespace Uithread\MgovSMS;
use Illuminate\Support\Facades\DB;
class MgovSMS
{
    /**
     * Send single SMS
     * @param string $url
     * @param string $data
     * @return int
     */
    public function post_to_url($url, $data) {
        $fields = '';
        foreach($data as $key => $value) {
            $fields .= $key . '=' . urlencode($value) . '&';
        }
        rtrim($fields, '&');
        $post = curl_init();
        //curl_setopt($post, CURLOPT_SSLVERSION, 5); // uncomment for systems supporting TLSv1.1 only
        curl_setopt($post, CURLOPT_SSLVERSION, 6); // use for systems supporting TLSv1.2 or comment the line
        curl_setopt($post,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($post); //result from mobile seva server
        //echo $result; //output from server displayed
        curl_close($post);
        return $result;
    }

    /**
     * Send unicode SMS by making http connection
     * @param string $url
     * @param string $data
     * @return int
     */
	public function post_to_url_unicode($url, $data) {
        $fields = '';
        foreach($data as $key => $value) {
            $fields .= $key . '=' . urlencode($value) . '&';
        }
        rtrim($fields, '&');
        
        $post = curl_init();
        //curl_setopt($post, CURLOPT_SSLVERSION, 5); // uncomment for systems supporting TLSv1.1 only
        curl_setopt($post, CURLOPT_SSLVERSION, 6); // use for systems supporting TLSv1.2 or comment the line
        curl_setopt($post,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($post, CURLOPT_URL, $url);	 
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_HTTPHEADER, array("Content-Type:application/x-www-form-urlencoded"));
        curl_setopt($post, CURLOPT_HTTPHEADER, array("Content-length:"
        . strlen($fields) ));
        curl_setopt($post, CURLOPT_HTTPHEADER, array("User-Agent:Mozilla/4.0 (compatible; MSIE 5.0; Windows 98; DigExt)"));
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($post); //result from mobile seva server
        //echo $result; //output from server displayed
        curl_close($post);
        return $result;
    }

    /**
     * Convert unicode text in UTF-8 format
     * @param string $message
     * @return string $finalmessage
     */
    public function string_to_finalmessage($message){
        $finalmessage="";
        $sss = "";
        for($i=0;$i<mb_strlen($message,"UTF-8");$i++) {
            $sss=mb_substr($message,$i,1,"utf-8");
            $a=0;
            $abc="&#".$this->ordutf8($sss,$a).";";
            $finalmessage.=$abc;
        }
        return $finalmessage;
    }

    /**
     * Convert utf8 to html entity
     * @param string $message
     * @param string $offset
     * @return string $finalmessage
     */
    //function to convet utf8 to html entity
    public function ordutf8($string, &$offset){
        $code=ord(substr($string, $offset,1));
        if ($code >= 128)
        { //otherwise 0xxxxxxx
            if ($code < 224) $bytesnumber = 2;//110xxxxx
            else if ($code < 240) $bytesnumber = 3; //1110xxxx
            else if ($code < 248) $bytesnumber = 4; //11110xxx
            $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) -
            ($bytesnumber > 3 ? 16 : 0);
            for ($i = 2; $i <= $bytesnumber; $i++) {
                $offset ++;
                $code2 = ord(substr($string, $offset, 1)) - 128;//10xxxxxx
                $codetemp = $codetemp*64 + $code2;
            }
            $code = $codetemp;

        }
        return $code;
    }
 
	//Function to send single sms
	public function sendSingleSMS($message,$mobileno){
        $key=hash('sha512',config('mgov-sms.userName').config('mgov-sms.senderId').trim($message).config('mgov-sms.secureKey'));
        
        $data = array(
            "username" => config('mgov-sms.userName'),
            "password" => sha1(config('mgov-sms.password')),
            "senderid" => config('mgov-sms.senderId'),
            "content" => trim($message),
            "smsservicetype" =>"singlemsg",
            "mobileno" =>trim($mobileno),
            "key" => trim($key)
        );
        return $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequest",$data); //calling post_to_url to send sms
    }

    //Function to send otp sms
    public function sendOtpSMS($message,$mobileno){
        $key=hash('sha512',config('mgov-sms.userName').config('mgov-sms.senderId').trim($message).config('mgov-sms.secureKey'));
        
        $data = array(
            "username" => config('mgov-sms.userName'),
            "password" => sha1(config('mgov-sms.password')),
            "senderid" => config('mgov-sms.senderId'),
            "content" => trim($message),
            "smsservicetype" =>"otpmsg",
            "mobileno" =>trim($mobileno),
            "key" => trim($key)
        );
        //var_dump($data);
        $result = $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequest",$data); //calling post_to_url to send otp sms

        $this->logSMS([
            'vchType' => 'OTP',
            'vchMobile' => $mobileno,
            'txtSmsContent' => $message,
            'booleanStatus' => 1,
            'txtStatus' => $result,
        ]);
        return $result;
    }
 

	//function to send bulk sms
	public function sendBulkSMS($message,$mobileNos){
        $key=hash('sha512', config('mgov-sms.userName').config('mgov-sms.senderId').trim($message).config('mgov-sms.secureKey'));	 
        
        $data = array(
            "username" => config('mgov-sms.userName'),
            "password" => sha1(config('mgov-sms.password')),
            "senderid" => config('mgov-sms.senderId'),
            "content" => trim($message),
            "smsservicetype" =>"bulkmsg",
            "bulkmobno" =>trim($mobileNos),
            "key" => trim($key)
        );
        $insertData = [];
        $result = $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequest",$data); //calling post_to_url to send bulk sms
        foreach(explode(',', $mobileNos) as $mobile){
            $insertData[] = [
                'vchType' => 'BULK',
                'vchMobile' => $mobile,
                'txtSmsContent' => $message,
                'booleanStatus' => 1,
                'txtStatus' => $result,
            ];
        }
        $this->logSMS($insertData);
        return $result;
    }

	//function to send single unicode sms
    public function sendSingleUnicode($messageUnicode,$mobileno){
		$finalmessage=$this->string_to_finalmessage(trim($messageUnicode));
        $key=hash('sha512',config('mgov-sms.userName').config('mgov-sms.senderId').trim($finalmessage).config('mgov-sms.secureKey'));
        
        $data = array(
            "username" => config('mgov-sms.userName'),
            "password" => sha1(config('mgov-sms.password')),
            "senderid" => config('mgov-sms.senderId'),
            "content" => trim($finalmessage),
            "smsservicetype" =>"unicodemsg",
            "mobileno" =>trim($mobileno),
            "key" => trim($key)
        );
        $result = $this->post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequest",$data); //calling post_to_url_unicode to send single unicode sms
        
        $this->logSMS([
            'vchType' => 'OTP',
            'vchMobile' => $mobileno,
            'txtSmsContent' => $message,
            'booleanStatus' => 1,
            'txtStatus' => $result,
        ]);
        return $result;
    }

    //function to send bulk unicode sms
    public function sendBulkUnicode($messageUnicode,$mobileNos){
        $finalmessage=$this->string_to_finalmessage(trim($messageUnicode));
        $key=hash('sha512',config('mgov-sms.userName').config('mgov-sms.senderId').trim($finalmessage).config('mgov-sms.secureKey'));
        
        $data = array(
            "username" => config('mgov-sms.userName'),
            "password" => sha1(config('mgov-sms.password')),
            "senderid" => config('mgov-sms.senderId'),
            "content" => trim($finalmessage),
            "smsservicetype" =>"unicodemsg",
            "bulkmobno" =>trim($mobileNos),
            "key" => trim($key)
        );
        $result =  $this->post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequest",$data); //calling post_to_url_unicode to send bulk unicode sms
        $insertData = [];
        foreach(explode(',', $mobileNos) as $mobile){
            $insertData[] = [
                'vchType' => 'BULK',
                'vchMobile' => $mobile,
                'txtSmsContent' => $message,
                'booleanStatus' => 1,
                'txtStatus' => $result,
            ];
        }
        $this->logSMS($insertData);
        return $result;
	}
	
	//function to send single unicode otp sms
	public function sendUnicodeOtpSMS($messageUnicode,$mobileno){
		$finalmessage=$this->string_to_finalmessage(trim($messageUnicode));
        $key=hash('sha512',config('mgov-sms.userName').config('mgov-sms.senderId').trim($finalmessage).config('mgov-sms.secureKey'));
        
        $data = array(
            "username" => config('mgov-sms.userName'),
            "password" => sha1(config('mgov-sms.password')),
            "senderid" => config('mgov-sms.senderId'),
            "content" => trim($finalmessage),
            "smsservicetype" =>"unicodeotpmsg",
            "mobileno" =>trim($mobileno),
            "key" => trim($key)
        );
        $result = $this->post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequest", $data); 
        //calling post_to_url_unicode to send single unicode sms

        $this->logSMS([
            'vchType' => 'OTP',
            'vchMobile' => $mobileno,
            'txtSmsContent' => $message,
            'booleanStatus' => 1,
            'txtStatus' => $result,
        ]);
        return $result;
    }

    private function logSMS($data){
        DB::table('t_sms_logs')->insert($data);
    }
}


