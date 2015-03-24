<?php
/**
* Author: Zachary Biles
* Website: zachbiles.com
* Date: 5/5/2012
*/

include_once('simple_html_dom.php');

class Pinterest{
	private $pinterestURL = "";
	private $localFileURL = "";
	private $pageHTML = "";
	private $user = "";
	private $pinboardImages = array();
	private $_links = array();
	private $_covers = array();
	private $_thumbs = array(); #An array of arrays.

	/**
	* Get the users pinboard data from Pinterest & store in class variables.
	*/
	public function scrapeUser($user){
		$this->user = $user;
		$this->pinterestURL = "http://www.pinterest.com/" . $this->user . "/";
		$this->localFileURL = $this->user . "Pinterest.html";
		$html = $this->loadHTML();
	}

	/**
	* Pulls the user's data from Pinterest if there is no locally stored copy available or if the local file is over 1hr old.
	*/
	private function loadHTML(){		
		date_default_timezone_set('UTC');

		if(!file_exists($this->localFileURL)){
			$this->updatePinterestFile();
		}
		else if (strtotime("-1 hour") >= filemtime($this->localFileURL)) {
			//If the file is over an hour old, pull a new copy from Pinterest
	        		$this->updatePinterestFile();
		}else{
			//Else, load content from locally cached file.
			$this->pageHTML = file_get_contents($this->localFileURL);
		}

		$this->parseHTML();
	}

	private function updatePinterestFile(){
		$fh = fopen($this->localFileURL, 'w') or die("Could not open local HTML file.");	
  		$this->pageHTML = file_get_contents($this->pinterestURL);
  		fwrite($fh, $this->pageHTML);
	}

	private function parseHTML(){
		$html = new simple_html_dom();
		$html->load($this->pageHTML);

		# retrieve all of the pinboards
		$pinBoards = $html->find(".pinBoard");

		foreach($pinBoards as $board) {
			#Loads the cover shots
			foreach ($board->find("h3 a") as $link ) {
		    		$this->_links[] = "http://www.pinterest.com" . $link->href;
		    	}

		    	#Loads the cover shots
			foreach ($board->find(".cover img") as $cover ) {
		    		$this->_covers[] = $cover->src;
		    	}

			#Loads the thumbnails
			$tempThumbs = array();
			foreach ($board->find(".thumbs img") as $thumbs) {
		    		$tempThumbs[] = $thumbs->src;
		    	}
		    	$this->_thumbs[] = $tempThumbs;
		}
	}


	public function getCovers(){
		return $this->_covers;
	}

	public function getThumbs(){
		return $this->_thumbs;
	}

	public function getLinks(){
		return $this->_links;
	}
}
?>
