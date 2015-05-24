<?php

/*

	Required Parameter ( with UTF-8 Encoding ) for connect to our payment page
	
	merchantId	:	Number						The merchant ID we provide to you
	
	amount			:	Number(12,2)			The total amount your want to charge the customer for the provided currency
																Remark: For MPS mode set with SCP, the amount should be in the foreign currency.
																	
	orderRef			:	Text(35)						Merchant‘s Order Reference Number
	
	currCode			:	Text(3)						The currency of the payment:
																“344” – HKD 
																“840” – USD
																“702” – SGD
																“156” – CNY (RMB)
																“392” – JPY
																“901” – TWD
																“036” – AUD
																“978” – EUR
																“826” – GBP
																“124” – CAD
																Remark: For MPS mode set with SCP, the currCode should be in the foreign currency.
																	
	successUrl		:	Text(300)					A Web page address you want us to redirect upon the transaction being accepted by us (For display purpose only. DO NOT use this URL to update your system. Please use DataFeed for this purpose.)
	
	failUrl				:	Text(300)					A Web page address you want us to redirect upon the transaction being rejected by us. (For display purpose only. DO NOT use this URL to update your system. Please use DataFeed for this purpose.)
	
	cancelUrl		:	Text(300)					A Web page address you want us to redirect upon the transaction being cancelled by your customer (For display purpose only. DO NOT use this URL to update your system. Please use DataFeed for this purpose.)
	
	payType			:	Text(1);("N","H")		The payment type:
																”N” – Normal Payment (Sales)
																”H” – Hold Payment (Authorize only)
																Hold Payment is not available for 99BILL, ALIPAY, CHINAPAY, PAYPAL, PPS, TENPAY

																	
	lang					:	Text(1)						The language of the payment page i.e.
																“C” – Traditional Chinese
																“E” – English
																“X” – Simplified Chinese
																“K” – Korean
																“J” – Japanese
																“T” – Thai
																	
	mpsMode		:	Text(3)						The Multi-Currency Processing Service (MPS) Mode:
															“NIL” or not provide – Disable MPS (merchant not using MPS)
															“SCP” – Enable MPS with ‘Simple Currency Conversion’
															“DCC” – Enable MPS with ‘Dynamic Currency Conversion’
															“MCP” – Enable MPS with ‘Multi Currency Pricing’
															For merchant who applied MPS function
	
	payMethod	:	Text								The payment method:
																“ALL” – All the available payment method
																“CC” – Credit Card Payment
																“PPS” – PayDollar PPS Payment
																“PAYPAL” – PayPal By PayDollar Payment
																“CHINAPAY” – China UnionPay By PayDollar Payment
																“ALIPAY” – ALIPAY By PayDollar Payment
																“TENPAY” – TENPAY BY PayDollar Payment
																“99BILL” – 99BILL BY PayDollar Payment

	Optional Parameter for connect to our payment page
	
	remark			:	Text(200)					A remark field for you to store additional data that will not show on the transaction web page
	redirect			:	Number						Number of seconds auto-redirection to merchant’s site takes place at PayDollar’s Payment Success / Fail page
	oriCountry		:	Number(3)					Origin Country Code
																	Example:
																	344 – “HK”
																	840 – “US”

	destCountry	:	Number(3)					Destination Country Code
																Example:
																344 – “HK”
																840 – “US”

	Redirect URL (successUrl, failUrl and cancelUrl) Output
	
	Ref					:	Text								Merchant’s Order Reference Number (For display purpose only. DO NOT use this URL to update your system. Please use DataFeed for this purpose.)	
 
*/


require_once('SHAPaydollarSecure.php');
//Required Parameter ( with UTF-8 Encoding ) for connect to our payment page
$merchantId='56100884';
$orderRef=date('YmdHis');
$currCode='344';
$amount=10.0;
$paymentType='N';
$mpsMode="NIL";
$payMethod="ALL";
$lang="E";
$successUrl="http://www.yourdomain.com/Success.html";
$failUrl="http://www.yourdomain.com/fail.html";
$cancelUrl="http://www.yourdomain.com/cancel.html";
//Optional Parameter for connect to our payment page
$remark="";
$redirect="";
$oriCountry="";
$destCountry="";



$secureHashSecret='bZe67kcltBHsrmW9EvnnscrdMuNc1HPU';//offered by paydollar
 //Secure hash is used to authenticate the integrity of the transaction information and the identity of the merchant. It is calculated by hashing the combination of various transaction parameters and the Secure Hash Secret.
$paydollarSecure=new SHAPaydollarSecure(); 
$secureHash=$paydollarSecure->generatePaymentSecureHash($merchantId, $orderRef, $currCode, $amount, $paymentType, $secureHashSecret);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Insert title here</title>
	</head>
	<body>
	<!--  
		<form name="payFormCcard" method="post" action="https://test.paydollar.com/b2cDemo/eng/payment/payForm.jsp">
	-->
			<form name="payFormCcard" method="post" action="https://test.paydollar.com/b2cDemo/eng/payment/payForm.jsp">
	
		<table>
			<tr><td align="center" colspan="2">Required Parameter ( with UTF-8 Encoding ) for connect to our payment page</td></tr>
			<tr><td align="right">merchantId:</td><td align="left"><input type="text" name="merchantId" value="<?php echo $merchantId?>"> </td></tr>
			<tr><td align="right">amount:</td><td align="left"><input type="text" name="amount" value="<?php echo $amount?>" ></td></tr>
			<tr><td align="right">orderRef:</td><td align="left"><input type="text" name="orderRef" value="<?php echo $orderRef?>"></td></tr>
			<tr><td align="right">currCode:</td><td align="left"><input type="text" name="currCode" value="<?php echo $currCode?>" ></td></tr>
			<tr><td align="right">successUrl:</td><td align="left"><input type="text" name="successUrl" value="<?php echo $successUrl?>"></td></tr>
			<tr><td align="right">failUrl:</td><td align="left"><input type="text" name="failUrl" value="<?php echo $failUrl?>"></td></tr>
			<tr><td align="right">cancelUrl:</td><td align="left"><input type="text" name="cancelUrl" value="<?php echo $cancelUrl?>"></td></tr>
			<tr><td align="right">payType:</td><td align="left"><input type="text" name="payType" value="<?php echo $paymentType?>"></td></tr>
			<tr><td align="right">lang:</td><td align="left"><input type="text" name="lang" value="<?php echo $lang?>"></td></tr>
			<tr><td align="right">mpsMode:</td><td align="left"><input type="text" name="mpsMode" value="<?php echo $mpsMode?>"></td></tr>
			<tr><td align="right">payMethod:</td><td align="left"><input type="text" name="payMethod" value="<?php echo $payMethod?>"></td></tr>
			<tr><td align="center" colspan="2">Optional Parameter for connect to our payment page</td></tr>
			
			
			<tr><td align="right">secureHash:</td><td align="left"><input type="text" name="secureHash" value="<?php echo $secureHash?>"></td></tr>
			<tr><td align="right">remark:</td><td align="left"><input type="text" name="remark" value="<?php echo $remark?>"></td></tr>
			<tr><td align="right">redirect:</td><td align="left"><input type="text" name="redirect" value="<?php echo $redirect?>"></td></tr>
			<tr><td align="right">oriCountry:</td><td align="left"><input type="text" name="oriCountry" value="<?php echo $oriCountry?>"></td></tr>
			<tr><td align="right">destCountry:</td><td align="left"><input type="text" name="destCountry" value="<?php echo $destCountry?>"></td></tr>
		

			<tr><td colspan="2" align="center"><input type="submit" name="submit"></td></tr>
		</table>
		</form>
	</body>
</html>