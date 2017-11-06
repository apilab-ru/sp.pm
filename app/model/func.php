<?php

namespace app\model;

class func 
{
    static private $holiDays = null;
    
    static function isWorkDay($day)
    {
        if(!is_string($day)){
            $day = date("Y-m-d",$day);
        }
        
        if(self::$holiDays == null ){
            $list = file_get_contents("http://bazacenter.ru/workdays/index.php");
            self::$holiDays = json_decode($list,1);
        }
        
        if(in_array($day,self::$holiDays)){
            return 0;
        }else{
            return 1;
        }
    }
    
    static function isLocal()
    {
        if (defined('Config::LOCAL')) {
            return Config::LOCAL;
        }else{
            return 0;
        }
    }
    
    static function getIntrumType() 
    {
        if (defined('Config::MY_TYPE')) {
            return Config::MY_TYPE;
        }

        if (in_array($_SERVER['SERVER_NAME'], array("realty.intrumnet.com"))) {
            return "REALTY";
        }
        if (in_array($_SERVER['SERVER_NAME'], array("tour.intrumnet.com", "tour0.intrumnet.com"))) {
            return "TOUR";
        }
        $sContext = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'content' => http_build_query(array(
                    'aKey' => Config::HTTP_KEY,
                    'route' => 'getreginfo'
                ))
            )
        ));
        $response = json_decode(file_get_contents('http://bill.intrumnet.com/intapi/', NULL, $sContext));

        $info = ($response && isset($response->info)) ? $response->info : false;
        if ($info) {
            $res = $info->intrum_type;
        }
        if (!$res) {
            $res = 'REALTY';
        }
        return $res;
    }
    
    /*
     *  full / start
     */
    static function getTarif()
    {
        if (defined('Config::MY_TARIF')) {
            return Config::MY_TARIF;
        }
        if(Model_Bill::getBillOptionsKey('tariff_group')==0){
            return 'full';
        }else{
            return 'start';
        }
    }
    
    static function getDevHideMesage()
    {
        if (defined('Config::DEV_HIDE_MESAGE')) {
            return Config::DEV_HIDE_MESAGE;
        }else{
            return 0;
        }
    }
}

?>