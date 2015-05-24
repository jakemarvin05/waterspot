
<form name="payFormCcard" method="post" action="
https://test.paydollar.com/b2cDemo/eng/payment/payForm.jsp">
<input type="hidden" name="merchantId" value="1">
<input type="hidden" name="amount" value="3000.0" >
<input type="hidden" name="orderRef" value="000000000014">
<input type="hidden" name="currCode" value="344" >
<input type="hidden" name="mpsMode" value="NIL" >
<input type="hidden" name="successUrl"
value="http://sgwatersport.checkyourprojects.com/payment.php">
<input type="hidden" name="failUrl" value="http://sgwatersport.checkyourprojects.com/payment.php">
<input type="hidden" name="cancelUrl" value="http://sgwatersport.checkyourprojects.com/payment.php">
<input type="hidden" name="payType" value="N">
<input type="hidden" name="lang" value="E">
<input type="hidden" name="payMethod" value="CC">
<input type="hidden" name="secureHash"
value="44f3760c201d3688440f62497736bfa2aadd1bc0">
<input type="submit" name="submit">
</form>
<? echo "<pre>";print_r($_POST);
 
// the message
$msg = "First line of text\nSecond line of text".json_encode($_POST);

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("niraj.burgeonsoft@gmail.com","payment test",$msg);
?>
