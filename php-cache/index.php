<?
$keyword = $_POST['keyword'];

if(!$keyword){
echo "<form method='post' action='index.php'>
<input type='text' name='keyword'>
<input type='submit' value='Scrape'>
</form>";
}

if($keyword){
echo "We are now going to scrape keyword: ".$keyword."<br /><br />";
$data = file_get_contents("http://www.ask.com/web?q=".urlencode($keyword)."&qsrc=0&o=333&l=dir");

preg_match_all("/ec:'(.+?)'}\)\">(.+?)<\/a><\/td>/", $data, $keywords);

foreach($keywords[2] as $keyword){
$keyword = strip_tags($keyword);
echo $keyword."<br />";
}

}

?>