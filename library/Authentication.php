<?php
/**
 * @desc Authenticate requests using public/private key asynchronous encryption
 * @author Paul Doelle
 */

class Authentication {
    private $private_key = 'k23j87BGsdHBJFd2fVI';
    public $public_key;
    public $public_hash;

    public function __construct($p_key, $p_hash) {
        $this->public_key = $p_key;
        $this->public_hash = $p_hash;
        return true;
    }
    
    public function is_valid() {
    	$comp_hash = $this->EncryptSHA256($this->public_key . $this->private_key);
    	if ($comp_hash == $this->public_hash)
    		return true;
    	else
    		return false;
    }

    private function EncryptSHA256($str) {
        return hash('sha256', $str);
	}
}
