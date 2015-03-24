<?php
/*
 *    changed : 13. apr. 03
 *    author  : troels@kyberfabrikken.dk
 *    download: http://www.phpclasses.org/browse.html/package/1020.html
 *
 *    description :
 *        demo of class htmlcleaner
 *        this example opens a file named $filename in the current dir,
 *        processes it and saves the contents
 *    disclaimer :
 *        this piece of code is freely usable by anyone. if it makes your life better,
 *        remeber me in your eveningprayer. if it makes your life worse, try doing it any
 *        better yourself.
 */
	require_once('htmlcleaner.php');
	if (!isset($filename))
		$content='
<div id="paralax">
        <div>
    <div>
        <p></p>
        Hello User,
        <strong></strong>
    </div>
    <div id="contents">Welcome to our domain.</div>
    <div></div>
    <p><p><p></p></p></p>
    <p><p><div></div></p></p>
</div>';
	echo $content_cleaned = htmlcleaner::cleanup($content);
	
?>
