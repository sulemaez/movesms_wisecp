<?php
    class MOVESMSAPI{
        public $api_token = NULL;
        public $short_code = "";
        public $username = "";
        public $error     = NULL;
       
        public function __construct(){

        }

        public function set_credentials($api_token='',$username='',$shortcode = ""){
            $this->api_token  = $api_token;
            $this->username = $username;
            $this->short_code = $shortcode;
        }


        public function Submit($title = NULL,$message = NULL,$number = 0){   
            $url = "https://sms.movesms.co.ke/api/compose";
            $numbers = "";
            if(is_array($number)){
               foreach($number as $n){
                  $numbers .= $n.",";
                }
                $numbers = rtrim($numbers,',');
            }else{
               $numbers = $number;
            } 

            $data = array(
                'username' => $this->username,
                'api_key'  =>  $this->api_token,
                'sender' =>  $this->short_code,
                'msgtype' => '5',
                'dlr'=> 0,
                'to' => $numbers,
                'message' => $message
            );
            
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data
            
            ));
            
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
            $output = curl_exec($ch);
            
            if (curl_errno($ch)) {
                // echo 'error:' . curl_error($ch);
                $output = curl_error($ch);
                $this->error = $output;
                return false;
            }
            
            curl_close($ch);
            if (strpos($output, '1701') !== false) {
                return true;
            }
            $this->error = $output;
            return false;
        }

        public function Balance(){
            $balanceUrl = "https://sms.movesms.co.ke/api/balance?api_key=".$this->api_token;
 
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'GET',
                )
            );

            $context  = stream_context_create($options);

            $result = file_get_contents($balanceUrl, false, $context);

            if ($result === FALSE) { 
                $this->error = "Could not fetch balance ! ";
                return false;
            }

            return $result;  
        }

        function ReportLook($rid){
            return false;
        }
        
         function get_prices(){
            return false;
        }
}


?>