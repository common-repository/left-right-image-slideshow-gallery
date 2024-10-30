function Lrisg_submit()
{
	if(document.Lrisg_form.Lrisg_path.value=="")
	{
		alert(Lrisg_adminscripts.Lrisg_path);
		document.Lrisg_form.Lrisg_path.focus();
		return false;
	}
	else if(document.Lrisg_form.Lrisg_link.value=="")
	{
		alert(Lrisg_adminscripts.Lrisg_link);
		document.Lrisg_form.Lrisg_link.focus();
		return false;
	}
	else if(document.Lrisg_form.Lrisg_target.value=="")
	{
		alert(Lrisg_adminscripts.Lrisg_target);
		document.Lrisg_form.Lrisg_target.focus();
		return false;
	}
	//	else if(document.Lrisg_form.Lrisg_title.value=="")
	//	{
	//		alert("Please enter the image title.");
	//		document.Lrisg_form.Lrisg_title.focus();
	//		return false;
	//	}
	else if(document.Lrisg_form.Lrisg_order.value=="")
	{
		alert(Lrisg_adminscripts.Lrisg_order);
		document.Lrisg_form.Lrisg_order.focus();
		return false;
	}
	else if(isNaN(document.Lrisg_form.Lrisg_order.value))
	{
		alert(Lrisg_adminscripts.Lrisg_order);
		document.Lrisg_form.Lrisg_order.focus();
		return false;
	}
	else if(document.Lrisg_form.Lrisg_status.value=="")
	{
		alert(Lrisg_adminscripts.Lrisg_status);
		document.Lrisg_form.Lrisg_status.focus();
		return false;
	}
	else if(document.Lrisg_form.Lrisg_type.value=="")
	{
		alert(Lrisg_adminscripts.Lrisg_type);
		document.Lrisg_form.Lrisg_type.focus();
		return false;
	}
}

function Lrisg_delete(id)
{
	if(confirm(Lrisg_adminscripts.Lrisg_delete))
	{
		document.frm_Lrisg_display.action="options-general.php?page=left-right-image-slideshow-gallery&ac=del&did="+id;
		document.frm_Lrisg_display.submit();
	}
}	

function Lrisg_redirect()
{
	window.location = "options-general.php?page=left-right-image-slideshow-gallery";
}

function Lrisg_help()
{
	window.open("http://www.gopiplus.com/work/2011/04/25/wordpress-plugin-left-right-image-slideshow-gallery/");
}