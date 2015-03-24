<?php
/*
@author: Shahrukh Khan
@website: http://www.thesoftwareguy.in
@date published: 1st December, 2013
*/
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="http://www.thesoftwareguy.in/favicon.ico" type="image/x-icon" />
<!--iOS/android/handheld specific -->
<link rel="apple-touch-icon" href="apple-touch-icon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="description" content="Ajax pagination with php and mysql">
<meta name="keywords" content="Ajax pagination with php and mysql">
<meta name="author" content="Shahrukh Khan">
<title>Ajax pagination with php and mysql - thesoftwareguy7</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<div id="container">
  <div id="body">
    <div class="mainTitle" >Ajax pagination with php and mysql</div>
    <div class="height10"></div>
    <div class="height10"></div>
    <article>
      <div class="height10"></div>
	  <div id="results">
	      <div id="content"></div>
		  <div id="paging"></div>
      </div>
    </article>
    <div class="height30"></div>
    <footer>
      <div class="copyright"> &copy; 2013 <a href="http://www.thesoftwareguy.in" target="_blank">thesoftwareguy</a>. All rights reserved </div>
      <div class="footerlogo"><a href="http://www.thesoftwareguy.in" target="_blank"><img src="http://www.thesoftwareguy.in/thesoftwareguy-logo-small.png" width="200" height="47" alt="thesoftwareguy logo" /></a> </div>
    </footer>
  </div>
</div>
<script src="jquery-1.9.0.min.js"></script>
<script type="text/javascript">
function displayRecords(numRecords, pageNum ) {
	$.ajax({
		type: "GET",
		url: "getrecords.php",
		data: "show="+numRecords+"&pagenum="+pageNum,
		cache: false,
		beforeSend: function () { 
			$('#content').html('<img src="loader.gif" alt="" width="24" height="24" style=" padding-left:469px;">');
		},
		success: function(html) {    
			$("#results").html( html );
		}
	});
}

function changeDisplayRowCount(numRecords)  {
	displayRecords(numRecords, 1);
}

$( document ).ready(function() {
  displayRecords(10, 1);
});  
</script>
</body>
</html>
