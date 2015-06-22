function validatefields(val)
{
	var obj = document.content_form;
	if(obj.title.value=="")
	{
		alert("Sorry! we cannot complete your request, please enter title");
		obj.title.focus();
		return false;
	}
	
	if(obj.page_title.value=="")
	{
		alert("Sorry! we cannot complete your request, please enter page title");
		obj.page_title.focus();
		return false;
	}
	
	if(obj.short_desc.value=="" && val!=4 && val!=5)
	{
		alert("Sorry! we cannot complete your request, please enter short description");
		obj.short_desc.focus();
		return false;
	}
	
}

function validatefields1()
{
	var obj = document.content_form;
	if(obj.title.value=="")
	{
		alert("Sorry! we cannot complete your request, please enter title");
		obj.title.focus();
		return false;
	}
	
	if(obj.page_title.value=="")
	{
		alert("Sorry! we cannot complete your request, please enter page title");
		obj.page_title.focus();
		return false;
	}
	
	if(obj.short_desc.value=="" && val!=4 && val!=5)
	{
		alert("Sorry! we cannot complete your request, please enter short description");
		obj.short_desc.focus();
		return false;
	}
	
}

function funct(val,url)
{
	
	
	var j="F";
	if(document.content_form.chkcount.value==1)
	{
	  if(document.content_form.checkbox.checked == true)
	  {
		 j="T";
	  }
	}
	else
	{
		
		
	  for(var i=0; i < document.content_form.chkcount.value; i++)
	  {
		 
		if(document.content_form.checkbox[i].checked == true)
		{
			//alert('vikas');
		j="T";
		}
	  }
	}
	
	if(j=="F"){
	alert("Error: Select atleast one record!");
	return false;
	}
	else
	{
		document.getElementById('typeval').value=val;
		if(val=='publish')
		{
			if(confirm("Are you sure you want to publish this?"))
			{
				document.content_form.submit();
			}
		}
		if(val=='unpublish')
		{
			if(confirm("Are you sure you want to unpublish this?"))
			{
				document.content_form.submit();
			}
		}
		if(val=='delete')
		{
			if(confirm("Are you sure you want to delete this?"))
			{
				document.content_form.submit();
			}
		}
	}
}

function showhide(id)
{
	if (document.getElementById)
	{
		obj = document.getElementById(id);
		if (obj.style.display == "none")
		{
		obj.style.display = "";
		} 
		else 
		{
		obj.style.display = "none";
		}
	}
}
function is_email(str) 
{

	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	if (str.indexOf(at)==-1){
	   
	   return false;
	}
	
	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
	  // alert("Invalid E-mail ID")
	   return false;
	}
	
	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
	  //  alert("Invalid E-mail ID")
		return false;
	}
	
	if (str.indexOf(at,(lat+1))!=-1){
	//  alert("Invalid E-mail ID")
	return false;
	}
	
	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
	// alert("Invalid E-mail ID")
	return false;
	}
	
	if (str.indexOf(dot,(lat+2))==-1){
	//alert("Invalid E-mail ID")
	return false;
	}
			
	if (str.indexOf(" ")!=-1){
	// alert("Invalid E-mail ID")
	return false;
	}
	else
	 return true;					
}
function IsNumeric(sText)

{

   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;
   for (i = 0; i < sText.length && IsNumber == true; i++) 
   { 
   		 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
      {
		 
         IsNumber = false;
      }
   }
   return IsNumber;
   
}






function CheckAll(chk)
{
	
	if(document.content_form.checkboxall.checked == false)
	{
		if(document.content_form.chkcount.value==1)
		{
		 document.content_form.checkbox.checked = false
		}
		else
		{
			for (i = 0; i < chk.length; i++)
			chk[i].checked = false ;
		}
	}
	else
	{

		if(document.content_form.chkcount.value==1)
		{
		 document.content_form.checkbox.checked = true
		}
		else
		{
			for (i = 0; i < chk.length; i++)
			chk[i].checked = true ;
		}

	}
}

function validationjs11()
{

var j="F";
if(document.content_form.chkcount.value==1)
{
  if(document.content_form.checkbox.checked == true)
  {
 	 j="T";
  }
}
else
{
	
	
  for(var i=0; i < document.content_form.chkcount.value; i++)
  {
	 
  	if(document.content_form.checkbox[i].checked == true)
	{
		//alert('vikas');
  	j="T";
  	}
  }
}

if(j=="F"){
alert("Error: Select atleast one record!");
return false;
}else{
	if(document.content_form.action.value==""){
	alert("Select Any Action!");
	return false;
}
return confirm("Are you sure ?");
}
}


function selectAll(field)
{

for (i = 0; i < field.length; i++)
	field[i].selected = true ;
}
function unselectAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].selected = false ;
}


