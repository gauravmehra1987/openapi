<?php
function token($length = 32) {
	// Create token to login with
	$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	$token = '';
	
	for ($i = 0; $i < $length; $i++) {
		$token .= $string[mt_rand(0, strlen($string) - 1)];
	}	
	
	return $token;
}

function tmthumb($src,$w=0,$h=0){
    if($w && $h)
        return HTTP_IMAGE . $src . "&w=$w&h=$h";
    if($w && !$h)
        return HTTP_IMAGE . $src . "&w=$w";
    if($h && $w)
        return HTTP_IMAGE . $src . "&h=$h";
}