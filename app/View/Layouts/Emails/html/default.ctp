<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SG WaterSport</title>
<style type="text/css">
h2 {
	font-family: 'Arial', Helvetica, sans-serif;
	font-size: 24px;
	line-height: normal;
	color: #0C5388;
	margin: 10px 0;
	padding: 0;
	border: 0;
}
h3 {
	font-family: 'Arial', Helvetica, sans-serif;
	font-size: 20px;
	line-height: normal;
	color: #F7941E;
	margin: 10px 0;
	padding: 0;
	border: 0;
}
h4 {
	font-family: 'Arial', Helvetica, sans-serif;
	font-size: 18px;
	line-height: normal;
	color: #00B6F0;
	margin: 10px 0;
	padding: 0;
	border: 0;
}
h6 {
	font-family: 'Arial', Helvetica, sans-serif;
	font-size: 16px;
	line-height: normal;
	color: #F7941E;
	margin: 10px 0;
	padding: 0;
	border: 0;
}
h1 span, h2 span, h3 span, h4 span, h5 span, h6 span {
	color: #0C5388;
}
p {
	font-family: 'Arial', Helvetica, sans-serif;
	font-size: 13px;
	line-height: normal;
	color: #242424;
	margin: 0 0 10px 0;
	padding: 0;
	border: 0;
}
ul, ol {
	font-family: 'Arial', Helvetica, sans-serif;
	font-size: 13px;
	line-height: normal;
	color: #242424;
	margin: 0 0 10px 10px;
	padding: 0;
	border: 0;
	list-style: square;
}
ul li, ol li {
	font-family: 'Arial', Helvetica, sans-serif;
	font-size: 13px;
	line-height: normal;
	color: #242424;
	margin: 0;
	padding: 0;
	border: 0;
	list-style: square;
}
.mailer table {
	float: left;
	width: 100%;
	padding: 0;
	margin: 0 0 15px 0;
	border: 0;
	border-collapse: collapse;
	background: none;
	color: #787878;
	font-family: 'Arial', Helvetica, sans-serif;
	font-size: 13px;
	line-height: normal;
	font-weight: normal;
}
.mailer table tr {
	background: #FCFCFC;
}
.mailer table tr:nth-child(odd) {
	background: #F3F3F3;
}
.mailer table th {
	background: #CECECE;
	border: solid 1px #E2E2E2;
	color: #212121;
	padding: 3px 5px;
	text-align: left;
	font-weight: 500;
}
.mailer table td {
	border: solid 1px #E2E2E2;
	color: #787878;
	padding: 2px 5px;
	text-align: left;
}
th.subtotal {
	background: #F2F2F2;
}
th.subtotal td {
	text-align: right;
}
</style>
</head>

<body style="background: #F2F2F2;">  

<table cellpadding="0" cellspacing="0" border="0" style="background:#FFFFFF; border-collapse:collapse; width: 100%;" width="100%">
	<tr style="background: #FFFFFF; border-bottom:ridge 2px #F2F2F2; margin: 0;">
		<td style="text-align: center; padding: 5px 10px;">
			<img src="<?php echo $logo;?>" alt="" />
		</td>
	</tr>
	<tr style="background: #00B6F0; border-bottom:ridge 2px #F2F2F2; margin: 10px 0;">
		<td style="text-align: left; padding: 10px 15px;">
			<h2 style="color: #FFFFFF;">Welcome to WaterSpot</p>
		</td>
	</tr>
	<tr style="background: #FFFFFF; border-bottom:ridge 2px #F2F2F2; margin: 10px 0;">
		<td style="text-align: left; padding: 10px;" class="mailer">
			<?php echo $this->fetch('content'); ?>
		</td>
	</tr>
	<tr style="background: #00B6F0; border-bottom:ridge 2px #F2F2F2; margin: 0;">
		<td style="text-align: left; padding: 5px 10px;">
			<p style="color: #FFFFFF;">Regards,<br /><a style="color: #F2F2F2;" title="" href="<?=$url; ?>" target="_blank">WaterSpot</a></p>
		</td>
	</tr>
</table>

</body>

</html>