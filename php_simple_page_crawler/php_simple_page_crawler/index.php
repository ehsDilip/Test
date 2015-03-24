<?php 

require_once 'simple_crawler.class.php';

$reader = new HtmlReader();

$page = 'http://www.phpmoot.com/category/php-classes/page/2/';

//read  content from url
$html = $reader->getPageContent($page);

//document content object
$htmlDoc = new HtmlDocument($html);

//document body part object
$body = $htmlDoc->getBody();

//objects array of page links 
$links = $body->grabLinks();

//clean text version of document body object
$cleanBody = $body->getStrippedBody();

//counted words from cleaned document body (word=>count)
$words = new BodyWords();
$pageWords = $words->findWords($cleanBody->getContent());
$words->appendWords($pageWords);


//follow front page links with recursive=1 
foreach($links as $link) {
	if($link->url == '/') continue;
	if($link->type == 1) {
		$pageLink = $page.$link->url;
	} else {
		continue; //no follow external links
		//$pageLink = $link->url;
	}
	$html = $reader->getPageContent($pageLink);
	
	$htmlDoc = new HtmlDocument($html);
	$body = $htmlDoc->getBody();
	$cleanBody = $body->getStrippedBody();
	
	$pageWords = $words->findWords($cleanBody->getContent());
	$words->appendWords($pageWords, $link->url);
}

//display words:count per page
echo "<pre>";
print_r($words->getWords());
//here you may do something with this words

?>