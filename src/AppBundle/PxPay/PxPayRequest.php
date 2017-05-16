<?php 
namespace AppBundle\PxPay;

use AppBundle\PxPay\PxPayMessage;
#******************************************************************************
# Class for PxPay request messages.
#******************************************************************************
class PxPayRequest extends PxPayMessage
{
	var $UrlFail,$UrlSuccess;
	var $AmountInput;
	var $EnableAddBillCard;
	var $PxPayUserId;
	var $PxPayKey;
	var $Opt;
	
	
	#Constructor
	public function __construct(){
		$this->PxPayMessage();
		
	}
	
	function setEnableAddBillCard($EnableBillAddCard){
		$this->EnableAddBillCard = $EnableBillAddCard;
	}
	function setUrlFail($UrlFail){
		$this->UrlFail = $UrlFail;
	}
	function setUrlSuccess($UrlSuccess){
		$this->UrlSuccess = $UrlSuccess;
	}
	function setAmountInput($AmountInput){
		$this->AmountInput = sprintf("%9.2f",$AmountInput);
	}
	function setUserId($UserId){
		$this->PxPayUserId = $UserId;
	}
	function setKey($Key){
		$this->PxPayKey = $Key;
	}
	function setOpt($Opt){
		$this->Opt = $Opt;
	}
	
	
	#******************************************************************
	#Data validation
	#******************************************************************
	function validData(){
		$msg = "";
		if($this->TxnType != "Purchase")
			if($this->TxnType != "Auth")
				$msg = "Invalid TxnType[$this->TxnType]<br>";
				
		if(strlen($this->MerchantReference) > 64)
			$msg = "Invalid MerchantReference [$this->MerchantReference]<br>";
		
		if(strlen($this->TxnId) > 16)
			$msg = "Invalid TxnId [$this->TxnId]<br>";
		if(strlen($this->TxnData1) > 255)
			$msg = "Invalid TxnData1 [$this->TxnData1]<br>";
		if(strlen($this->TxnData2) > 255)
			$msg = "Invalid TxnData2 [$this->TxnData2]<br>";
		if(strlen($this->TxnData3) > 255)
			$msg = "Invalid TxnData3 [$this->TxnData3]<br>";
			
		if(strlen($this->EmailAddress) > 255)
			$msg = "Invalid EmailAddress [$this->EmailAddress]<br>";
			
		if(strlen($this->UrlFail) > 255)
			$msg = "Invalid UrlFail [$this->UrlFail]<br>";
		if(strlen($this->UrlSuccess) > 255)
			$msg = "Invalid UrlSuccess [$this->UrlSuccess]<br>";
		if(strlen($this->BillingId) > 32)
			$msg = "Invalid BillingId [$this->BillingId]<br>";
			
		if ($msg != "") {
			trigger_error($msg,E_USER_ERROR);
			return false;
		}
		return true;
	}
	
}
?>