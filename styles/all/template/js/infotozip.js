var req;
var url;

// Reload Object
function Reload_Body(url,name)
{
	Processing(name);
	SendQuery(url,'DisplayContent("'+name+'")');
	return false;
}

// Init Xmlhttp Object
function Initialize()
{
	try
	{
		req=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			req=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(oc)
		{
			req=null;
		}
	}

	if(!req&&typeof XMLHttpRequest!="undefined")
	{
		req=new XMLHttpRequest();
	}

}

// Display loading while tranfering data , call it before SendQuery function
// name parameter is the name of div tag
function Processing(name)
{
	obj = document.getElementById(name);
	obj.innerHTML = '<table style="WIDTH : 660px;"><tr><td><center><img border=0 src="ext/dmzx/infotozip/images/icons/loading.gif" align="absbottom"></center></td></tr></table>';
}

// callbackFunction parameter is the function will process returned data
function SendQuery(url,callbackFunction)
{
	// Init Object
	Initialize();

	if ( (req!=null) )
	{
		req.onreadystatechange = function()
		{
			// only if req shows "complete"
			if (req.readyState == 4)
			{
				// only if "OK"
				if (req.status == 200)
				{
					// Process
					eval(callbackFunction);
				}
			}
		};
		url += "&rand="+Math.random()*1000;
		req.open("GET", url , true);
		req.send(null);
	}
}

// Display content after data is received
// name parameter is the name of div tag
function DisplayContent(name)
{
	obj = document.getElementById(name);
	obj.innerHTML = req.responseText;
}