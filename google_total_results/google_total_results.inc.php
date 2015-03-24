<?php
	
/*
//	GoogleTotalResults
//	Fetches the total number of results from a query on google
//
//	warning: this class depends on source code from the google results page
//	if google changes the syntax on that page this class may not function properly
//	please update the GOOGLE_RESULTS_PATTERN if google makes changes to the results page
//
//	also please remember this is based on the US version of google
//
//	Created By: Sunny Rajpal
//	Version 1.0	2007.05.30
*/

define("GOOGLE_SEARCH_STRING_BASE","http://www.google.com/search?hl=en&q=");
define("GOOGLE_RESULTS_PATTERN","|Results <b>[0-9]*</b> - <b>[0-9]*</b> of about <b>(.*)</b> for|");

class GoogleTotalResults
{
	var $Term="";
	var $Error="";
	var $SearchString="";
	var $TotalResults=0;
	
	function GoogleTotalResults($term)
	{
		$this->Term = $term;
	}
	
	function setSearchTerm($term)
	{
		$this->Term = $term;
	}
	
	function getLastError()
	{
		return $this->Error;
	}
	
	function getResults()
	{
		if ($this->Term == "")
		{
			$this->Error = "Search term not set";
			return false;
		}
		
		$this->TotalResults=0;
		$this->SearchString = GOOGLE_SEARCH_STRING_BASE . urlencode($this->Term);
		
		$fp = fopen($this->SearchString,"r");
		if (!$fp)
		{
			$this->Error = "Error accessing google search url";
			return false;
		}
		
		$retval=false;
		$this->Error = "Could not find results in source";
		while (!feof($fp))
		{
			$data = fgets($fp);
			
			preg_match(GOOGLE_RESULTS_PATTERN,$data,$matches);
			if (count($matches) == 2)
			{
				$this->TotalResults = trim(str_replace(",","",$matches[1]));
				$retval = $this->TotalResults;
				$this->Error = "";
				break;
			}
		}
		fclose($fp);
		
		return $retval;
		
	}
}
	
?>