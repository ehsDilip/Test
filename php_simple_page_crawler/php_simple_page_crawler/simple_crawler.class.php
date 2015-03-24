<?php 
/**
 * Base crawler class
 */
class CrawlerBase {

	/**
	 * remove specified $tag form $content
	 */
	private function removeTag($tag, $content) {
		//$pat = '/<'.$tag.'.*>.*<\/'.$tag.'>/s';
		$pat = '@<'.$tag.'[^>]*?.*?</'.$tag.'>@siu';
		return preg_replace($pat, '',  $content);
	}
	
	/**
	 * remove unwanted tags from content
	 */
	public function cleanContent($content) {
	
		$content = $this->removeTag('script', $content);
		$content = $this->removeTag('css', $content);
		$content = $this->removeTag('object', $content);
		return $content;	
	}

}


/**
 * Read html page content 
 */
class HtmlReader extends CrawlerBase{

	public function getPageContent($url) {
		return file_get_contents($url);
	}
}

/**
 * class for html document
 */
class HtmlDocument extends CrawlerBase {
	/**
	 * Document content
	 */
	private  $content;
	
	public function __construct($content) {
		$this->content = $content;
	}


	public function getBody() {
		return new HtmlDocumentBody($this->content);
	}
	
	public function getHead() {
		return new HtmlDocumentHead($this->content);
	}
	
}

/**
 * html Head section class
 */
class HtmlDocumentHead extends CrawlerBase {

	/**
	 * Document content
	 */
	private  $content;
	
	public function __construct($htmlContent = null) {
		if(!empty($htmlContent)) {		
			$this->findDocumentHead($htmlContent);
		}
	}
	
	/**
	 * find document <head> part
	 */
	public function findDocumentHead($htmlContent) {
		$matches = array();
		preg_match('/(<head>)(.*)(<\/head>)/si', $htmlContent, $matches);
		$this->content =  $matches[2];
	}

}

/**
 * html body section class
 */
class HtmlDocumentBody extends CrawlerBase  {
	
	/**
	 * Document content
	 */
	private $content;

	public function __construct($htmlContent = null) {
		$this->findDocumentBody($htmlContent);
	}

	/**
	 * read links from content
	 */
	public function grabLinks() {
		
		$links = array();
		$matches = array();
		$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>"; 
		preg_match_all("/$regexp/siU", $this->content, $matches, PREG_SET_ORDER);
		if(!empty($matches)) {
			foreach ($matches as $link) {
				$links[] = new ContentLink($link);
			}
		}
		return 	$links;	
	}
	
	/**
	 * find document <body> part
	 */
	public function findDocumentBody($content) {
		$matches = array();
		preg_match('/(<body>)(.*)(<\/body>)/is', $content, $matches);
		$this->content =  $matches[2];
	}	
	
	public function getDocumentBody() {
		return $this->content;
	}
	
	public function getStrippedBody() {
		return new StrippedBody($this->content);
	}
	
}

/**
 * cleaned body (with no tags)
 */
class StrippedBody extends CrawlerBase {

	/**
	 * Document content
	 */
	private  $content;
	
	public function __construct($UnsrtippedBodyContent = null) {
		if(!empty($UnsrtippedBodyContent)) {
			$UnsrtippedBodyContent = $this->cleanContent($UnsrtippedBodyContent);
		
			$this->content = strip_tags($UnsrtippedBodyContent);
		}
	}
	
	public function getContent() {
		return $this->content;
	}
}

/**
 * link class
 */
class ContentLink extends CrawlerBase {
	/**
	 * Document content
	 */
	public $fullUrl;
	public $url;
	public $anchor;
	public $type;
	
	public function __construct(array $linkData) {
		$this->parseLinkData($linkData);
	}
	
	public function parseLinkData(array $linkData) {
		$this->fullUrl = $linkData[0];
		$this->url = $linkData[2];	
		$this->anchor = strip_tags($linkData[3]);	
		if(preg_match('/^http/', $linkData[2])) {
			$this->type = 2; //external		
		} else {
			$this->type = 1; //local
		}	
	}
}

/**
 * body words class. find, count, append
 */
class BodyWords extends CrawlerBase {

	/**
	 * Document content
	 */
	private $words = array();
	
	public function __construct() {

	}
	
	private function countWords($uncountedWordsArray) {
		$wordsArray = array_count_values($uncountedWordsArray);
		$this->removeShortWords($wordsArray);
		asort($wordsArray);
		return $wordsArray;
	}
	
	private function removeShortWords(&$countedWordsArray) {
		if(!empty($countedWordsArray)) {
			foreach($countedWordsArray as $word => $count) {
				if(strlen($word) < 4) {
					unset($countedWordsArray[$word]);
				}
			}
		}
	}
	
	public function findWords($cleanBodyText) {
		$uncountedWordsArray = preg_split("/[\s,.?!]+/", $cleanBodyText);
		return $this->countWords($uncountedWordsArray);
	}
	
	public function appendWords($wordsArray, $page='/') {
		if(!empty($wordsArray)) {
			foreach ($wordsArray as $word => $count) {
				if(array_key_exists($word, $this->words)) {
					$this->words[$page][$word] = $this->words[$page][$word] + $count; 
				} else {
					$this->words[$page][$word] = $count;
				}
			} 
		}
	}
	
	public function getWords() {
		return $this->words;
	}
}
?>