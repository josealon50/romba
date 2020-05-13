<?php
namespace Bot\Http;

class Http{
    protected $url = NULL;

    public function __construct( $url ){
       $this->url = $url; 
    }

    public function http($target, $ref, $method, $data_array, $incl_head){
        $ch = curl_init();

        if(is_array($data_array)){
            foreach ($data_array as $key => $value) 
                {
                if(strlen(trim($value))>0)
                    $temp_string[] = $key . "=" . urlencode($value);
                else
                    $temp_string[] = $key;
                }
            $query_string = join('&', $temp_string);
        }
        if($method == HEAD){
            curl_setopt($ch, CURLOPT_HEADER, TRUE);                // No http head
            curl_setopt($ch, CURLOPT_NOBODY, TRUE);                // Return body
        }
        else{
            if($method == GET){
                if(isset($query_string)){
                    $target = $target . "?" . $query_string;
                }
                curl_setopt ($ch, CURLOPT_HTTPGET, TRUE); 
                curl_setopt ($ch, CURLOPT_POST, FALSE); 
            }
            if($method == POST){
                if(isset($query_string)){
                    curl_setopt ($ch, CURLOPT_POSTFIELDS, $query_string);
                }
                curl_setopt ($ch, CURLOPT_POST, TRUE); 
                curl_setopt ($ch, CURLOPT_HTTPGET, FALSE); 
            }
            curl_setopt($ch, CURLOPT_HEADER, $incl_head);   // Include head as needed
            curl_setopt($ch, CURLOPT_NOBODY, FALSE);        // Return body
        }

        curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE_FILE);   // Cookie management.
        curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE);
        curl_setopt($ch, CURLOPT_TIMEOUT, CURL_TIMEOUT);    // Timeout
        curl_setopt($ch, CURLOPT_USERAGENT, WEBBOT_NAME);   // Webbot name
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

}

?>
