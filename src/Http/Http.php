<?php
namespace Bot\Http;

use Bot\Config\Config;

class Http{
    protected $url = NULL;

    public function __construct( $url ){
       $this->url = $url; 
    }

    public function http($target, $ref, $method, $data_array, $incl_head){
        $ch = curl_init();

        if(is_array($data_array)){
            $temp_string = [];
            foreach ($data_array as $key => $value) {
                if(strlen(trim($value))>0)
                    $temp_string[] = $key . "=" . urlencode($value);
                else
                    $temp_string[] = $key;
            }
            $query_string = join('&', $temp_string);
        }
        if($method == Config::HEAD){
            curl_setopt($ch, CURLOPT_HEADER, TRUE);                // No http head
            curl_setopt($ch, CURLOPT_NOBODY, TRUE);                // Return body
        }
        else{
            if($method == Config::GET){
                if(isset($query_string)){
                    $target = $target . "?" . $query_string;
                }
                curl_setopt ($ch, CURLOPT_HTTPGET, TRUE); 
                curl_setopt ($ch, CURLOPT_POST, FALSE); 
            }
            if($method == Config::POST){
                if(isset($query_string)){
                    curl_setopt ($ch, CURLOPT_POSTFIELDS, $query_string);
                }
                curl_setopt ($ch, CURLOPT_POST, TRUE); 
                curl_setopt ($ch, CURLOPT_HTTPGET, FALSE); 
            }
            curl_setopt($ch, CURLOPT_HEADER, $incl_head);   // Include head as needed
            curl_setopt($ch, CURLOPT_NOBODY, FALSE);        // Return body
        }
        curl_setopt($ch, CURLOPT_COOKIEJAR, Config::COOKIE_FILE);   // Cookie management.
        curl_setopt($ch, CURLOPT_COOKIEFILE, Config::COOKIE_FILE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);    // Timeout
        curl_setopt($ch, CURLOPT_USERAGENT, Config::BOT_NAME);   // Webbot name
        curl_setopt($ch, CURLOPT_URL, $target);             // Target site
        curl_setopt($ch, CURLOPT_REFERER, $ref);            // Referer value
        curl_setopt($ch, CURLOPT_VERBOSE, FALSE);           // Minimize logs
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // No certificate
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);     // Follow redirects
        curl_setopt($ch, CURLOPT_MAXREDIRS, 4);             // Limit redirections to four
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);     // Return in string
           
        $return_array['FILE']   = curl_exec($ch); 
        $return_array['STATUS'] = curl_getinfo($ch);
        $return_array['ERROR']  = curl_error($ch);

        curl_close($ch);

        return $return_array;

       
    }
    function split_string($string, $delineator, $desired, $type){
        # Case insensitive parse, convert string and delineator to lower case
        $lc_str = strtolower($string);
        $marker = strtolower($delineator);
        
        # Return text BEFORE the delineator
        if($desired == Config::BEFORE){
            if($type == Config::EXCL)  // Return text ESCL of the delineator
                $split_here = strpos($lc_str, $marker);
            else               // Return text INCL of the delineator
                $split_here = strpos($lc_str, $marker)+strlen($marker);
            
            $parsed_string = substr($string, 0, $split_here);
        }
        # Return text AFTER the delineator
        else{
            if($type==Config::EXCL)    // Return text ESCL of the delineator
                $split_here = strpos($lc_str, $marker) + strlen($marker);
            else               // Return text INCL of the delineator
                $split_here = strpos($lc_str, $marker) ;
            
            $parsed_string =  substr($string, $split_here, strlen($string));
        }
        return $parsed_string;
    }

    function return_between($string, $start, $stop, $type){
        $temp = split_string($string, $start, Config::AFTER, $type);
        return split_string($temp, $stop, Config::BEFORE, $type);
    }

}

?>
