<?php
/**
 * @desc Helper class - generic functions for reuse
 * @author Paul Doelle
 */

class Helper {  
	// convert an object into a specific class  
   static function cast($instance, $className) {
    	return unserialize(sprintf(
    			'O:%d:"%s"%s',
    			strlen($className),
    			$className,
    			strstr(strstr(serialize($instance), '"'), ':')
    	));
    }
    
    // call an API
    static function CallAPI($method, $url, $data = false) {
    	$curl = curl_init ();
    
    	switch ($method) {
    		case "POST" :
    			curl_setopt($curl, CURLOPT_POST, 1);
    
    			if ($data)
    				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    			break;
    		case "PUT" :
    			curl_setopt($curl, CURLOPT_PUT, 1);
    			break;
    		default :
    			if ($data)
    				$url = sprintf("%s?%s", $url, http_build_query($data));
    	}
    
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
    	$result = curl_exec($curl);
    
    	//$error = curl_errno($curl);
    	//$info = curl_getinfo($curl);
    
    	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    	curl_close($curl);
    		
    	if ($http_status == 200) {
    		$result = json_decode($result, true);
    		return $result;
    	} else {
    		return $result;
    	}
    }
    
    //check if a string is valid JSON
    static function isJson($string) {
    	json_decode($string);
    	return (json_last_error() == JSON_ERROR_NONE);
    }
    
}
