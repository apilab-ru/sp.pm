<?php

namespace app\model;

class vk {
    
    public $id     = 6142082;
    public $secret = 'zd3lB5ZchMplGFTBBENt';
    public $token  = '6de122046de122046de122049a6dbc9a8666de16de12204346b31873b82cd81c2817993';
    public $url = 'https://api.vk.com/method/';
    
    public function getAuthLink()
    {
        return "https://oauth.vk.com/authorize?" . http_build_query([
                    'client_id'     => $this->id,
                    'redirect_uri'  => "http://" . $_SERVER['SERVER_NAME'] . "/ajax/instagram/authvkcode",
                    'response_type' => "code",
                    'v'             => "5.67",
                    'state'         => $_SESSION['client']['id']
        ]);
    }
    
    public function getToken($code)
    {
        $res = file_get_contents("https://oauth.vk.com/access_token?" . http_build_query([
           'client_id'     => $this->id,
           'client_secret' => $this->secret,
           'redirect_uri'  => "http://" . $_SERVER['SERVER_NAME'] . "/ajax/instagram/authvkcode",
           'code'          => $code
        ]));
        
        $res = json_decode($res,1);
        
        return $res;
    }
    
    public function getInfoUser($name) 
    {
        $param = array(
            'user_ids' => $name,
            'fields'   => 'photo_50,screen_name'
        );
        $re = $this->callMethod("users.get", $param);
        
        return ($re['response'][0]) ? $re['response'][0] : null;
    }
    
    public function callMethod($method, $param) 
    {
        $ch = curl_init();
        $param['access_token'] = $this->token;
        $param['v'] = '5.65';
        curl_setopt($ch, CURLOPT_URL, $this->url . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        $ret = curl_exec($ch);
        curl_close($ch);
        return json_decode($ret, 1);
    }

}
