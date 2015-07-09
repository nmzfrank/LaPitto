var xmlHttp

function test(){
	document.getElementById("hint").innerHTML= "some";
}
function showHint(name, pw)
{
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="login/login.php"
	url=url+"?name="+name
	url=url+"&password="+pw
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
} 

function stateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 	{ 
		var inner = xmlHttp.responseText
		if(inner == '验证成功')
			window.location.href = "/LaPitto/Main"
		else
			document.getElementById("hint").innerHTML= inner
 	} 
}

function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
 	{
 	// Firefox, Opera 8.0+, Safari
 	xmlHttp=new XMLHttpRequest();
 	}
	catch (e)
 	{
 	// Internet Explorer
 		try
  		{
  			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  		}
 		catch (e)
  		{
  		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
 	}
	return xmlHttp;
}