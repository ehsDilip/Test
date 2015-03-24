<?
	/*
	// example usage of google_total_results.inc.php
	*/
	
	define("NL","<BR>");
	//define("NL","\n");
	
	include_once("google_total_results.inc.php");

	echo "Search results for: Sunny Rajpal".NL;
	$g = new GoogleTotalResults("Sunny Rajpal");
	$results = $g->getResults();
	if ($results !== false)
		echo "Results: ".number_format($results,0).NL;
	else
		echo "Failed to get Results [".$g->getLastError()."].".NL;
	
	echo "Search results for: \"Sunny Rajpal\"".NL;
	$g->setSearchTerm("\"Sunny Rajpal\"");
	$results = $g->getResults();
	if ($results !== false)
		echo "Results: ".number_format($results,0).NL;
	else
		echo "Failed to get Results [".$g->getLastError()."].".NL;
?>