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
    
    public function getLabel($field, $value) {
    	switch ($field) {
    		case 'tags':
    			$label = "Tagged with '". substr($value, 1, -1) ."'";
    			break;
    		case 'product_type':
    			$label = 'Product type is '. $value;
    			break;
    		case 'brand_name':
    			$label = 'Brand name is '. $value;
    			break;
    		case 'supplier_name':
    			$label = 'Supplier is '. $value;
    			break;
    		case 'is_active':
    			$label = ($value == 1 ? 'Visible in store' : 'Not visible in store');
    			break;
    		case 'vend_active':
    			$label = ($value == 1 ? 'Active in Vend' : 'Not active in Vend');
    			break;
    		case 'pre_sell':
    			$label = ($value == 1 ? 'Pre-selling' : 'Not pre-selling');
    			break;
    	}

    	return $label;
    }
}