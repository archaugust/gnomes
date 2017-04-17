<?php
namespace AppBundle;

class MiscFunctions {
    public function slug($str)
    {
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);
        if ($str == '-')
            $str = time();
        return $str;
    }

    public function unslug($str)
    {
        $str = str_replace('-', ' ', $str);
        $str = ucwords(trim($str));
        return $str;
    }

    public function generatePassword() {
    	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
    	srand((double)microtime()*1000000);
    	$i = 0;
    	$pass = '' ;
    	
    	while ($i <= 10) {
    		$num = rand() % 33;
    		$tmp = substr($chars, $num, 1);
    		$pass = $pass . $tmp;
    		$i++;
    	}
    	
    	return $pass;
    }
}