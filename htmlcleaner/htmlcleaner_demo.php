<?php
/*
 *    changed : 16. oct. 03
 *    author  : troels@kyberfabrikken.dk
 *    download: http://www.phpclasses.org/browse.html/package/1020.html
 *
 *    description :
 *        demo of class htmlcleaner
 *
 *    disclaimer :
 *        this piece of code is freely usable by anyone. if it makes your life better,
 *        remeber me in your eveningprayer. if it makes your life worse, try doing it any
 *        better yourself.
 */
require_once('htmlcleaner.php');
$post = array_merge($HTTP_POST_VARS, $_POST);
if (isset($post['htmlCode'])) {
	if (get_magic_quotes_runtime() == 0) $post['htmlCode'] = stripslashes($post['htmlCode']);
	$body = htmlcleaner::cleanup($post['htmlCode']);
} else {
	$body = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>example for htmlcleaner</title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1" />
</head>
<script>
var d = null;
function init()
{
	var iframes = document.all.tags("IFRAME");
	iframes[0].frameWindow = document.frames[iframes[0].id];
	if (iframes[0].value == null)
		iframes[0].value = iframes[0].innerHTML;
	d = iframes[0].frameWindow.document;
	d.designMode = "On";
	d.open();
	d.write(iframes[0].value);
	d.close();

}
</script>
<body onload="init()">
<form method="POST" name="theform" onsubmit="document.theform.htmlCode.value=d.body.innerHTML;">
	<iframe id="edit" frameborder="0" style="border: 1px solid black;">
		<h1>Paste something from word in here</h1>
		<hr>
		<p>mucking fagic</p>
	</iframe>
<br />
	<input type="hidden" name="htmlCode" value="" />
	<input type="submit" />
</form>
<?php if (isset($post['htmlCode'])) { ?>
	<div style="padding:4px;border:1px solid black">
	<?=nl2br(htmlentities($post['htmlCode']))?>
	</div>
	<br />
	<div style="padding:4px;border:1px solid black">
	<?=nl2br(htmlentities($body))?>
	</div>
<?php } ?>
</body>
</html>