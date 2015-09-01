<?php
$config['Path']['NoImage'] =  WWW_ROOT.'img'.DS.'site'.DS.'noimage.png';
$config['currency'] ='$';
$config['price_range'] =array('0-100000'=>' Any ','0-50'=>'Under $50','51-100'=>'$51-$100','101-200'=>'$101-$200','201-300'=>'$201-$300','301-100000'=>'$300+');
$config['review'] =array('1'=>'Highest to lowest','2'=>'Lowest to highest');
$config['Calender_format'] ='dd-mm-yy';
$config['Calender_format_php'] ='d-m-Y';
$config['Image']['SourcePath'] =  WWW_ROOT.'img'.DS.'service_images'.DS;

$config['payment_status']=array('0'=>'Not Completed','1'=>'Completed','2'=>'Pending','3'=>'Cancelled','4'=>'processing','5'=>'Payment by inviter');

// minimu_time is used for before booking 
$config['Booking']['minmum_time'] = '+2 hours'; 
$config['Booking']['clearEndTime'] = '-30 minutes'; 
$config['Booking']['clearStartTime'] = '-5 days'; 
$config['Activiy']['Limit'] =6; 
// payment is setting 
$config['AsiaPay']['merchantId'] = '12103418';
$config['AsiaPay']['currCode'] = '702';
$config['AsiaPay']['lang'] = 'E';
$config['AsiaPay']['secureHashSecret'] = 'KSkQJmtnAM3O2VGhZUIULMys9lJNCrbf';
$config['AsiaPay']['payment_action'] =  'https://test.paydollar.com/b2cDemo/eng/payment/payForm.jsp';

/* used for Smoov Pay */
$config['Payment']['sandbox_mode'] = '2';
$config['Payment']['test_url']='https://sandbox.smoovpay.com/access';
$config['Payment']['live_url'] = 'https://secure.smoovpay.com/access';
// $config['Payment']['merchant'] = 'pavans@burgeonsoft.net';
// $config['Payment']['secret_key'] = "580251263cfd4da1a28bc7d69e5ef6ac";

$config['Payment']['merchant'] = 'lanceryosuke@gmail.com';
$config['Payment']['secret_key'] = "809d76c6ecbe48a59ff268114ade471e";

$config['Paypal']['email'] = 'admin@waterspot.com.sg';
$config['Paypal']['test_email'] = 'admin-facilitator@waterspot.com.sg';
$config['Paypal']['sandbox_mode'] = 0; // 1 = true, 0 = false
$config['Paypal']['url'] = 'https://www.paypal.com/cgi-bin/webscr';
$config['Paypal']['test_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
?>
