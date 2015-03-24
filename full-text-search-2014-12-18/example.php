<!-- this is a complete example usage of the phpsearchautocorrect class
To run this example, import the test.sql  database to you mysql server
if you have any problem using this class please do not hesitate to email me @ olorundaolaoluwa@gmail.com
and please dont forget to rate this class if it proves usefull to you
 -->


<form action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="GET"  />
<input type="text" name="search"  />
<select name="time" />
<!--   maintain the values of the time select options  -->
<option value="past24"  >Past 24 hours </option>
<option value="pastweek"  >Past Week </option>
<option value="pastmonth"  >Past Month </option>
<option value="pastyear"  >Past Year </option>
<option value="anytime"  >Any Time </option>
</select>
<input type="submit"  value="Search"  />

</form>


<?php
/**for the spellcorrector to work correctly, go here https://www.mediafire.com/?jmmu7rwj4p90dgx to download the dictionary files. make sure you extract into the same directory as the class**/
if(isset($_GET['search'])){
include 'fulltextsearch.php';

$fulltext=new fulltextsearch();

//if you wan to integrate this with php bootstrap pagination you can download the class here http://www.phpclasses.org/browse/author/1217990.html ,  use this to supply total record to the pagin class $fulltext->total_record 
//provide data for database connection 

$fulltext->dbname='shareandsmile';
$fulltext->dbusername='root';
$fulltext->dbpassword='';

//specify the table name that you want to perrform a search on

$fulltext->tablename='country';

// specify the column name of the table you want to perform the search on

$fulltext->searchcolumn='Name';

//switch on auto correct if your  the search string is goin to be an english word . use $fulltext->autocorrect=1 to enable auto correct and  $fulltext->autocorrect=0 to set autocorrect off
$fulltext->autocorrect=1;



/**make sure the column name for  date is int(8)  i.e integer of eight character long
if you want a date based search then use this  
for the date search to work, you must create a column for date and write the datecolumn here
when inserting a value into the date column make sure you date value should be equals to date("Ymd") for the date based search to work properly
in this case , my date column name is 'date' **/
//$fulltext->datecolumnname='date';



//you need to call this function only the first time you run the script, the function converts the table to supported format for the search and it also add a fulltext index to the table  


//$fulltext->converttable();

//get search keyword

$fulltext->keyword=$_GET['search'];;

/**for a timebased  result  use $fulltext->dates=$_GET['time'];
you dont need to specify date if you dont want a timebased search **/



//this is needed if you need to break result into chunks ...specify the starting point

$fulltext->startfrom=0;

//specify the number of records you wish to display per page   
//download my bootstrap pagin class to make it easier to paginate result. supply the pagination class with this $fulltext->total_record  for the total record

$fulltext->resgroup=15;

//check if operation successfull
if($fulltext->search()==true){
//$row=$fulltext->result;
//foreach($row as $rr){

//loop through result

while($row=$fulltext->querys->fetch()){
echo 'Search result: '.$row[1].'<br><br>';
$check=$row[0];
}

//to show how the what the search string is corrected to echo $fulltext->correctedto;

echo 'Searched For:'.$_GET['search'].'<br>Corrected to: '.$fulltext->correctedto; 

//check if result is found

if(!isset($check)){

echo 'no result found';

}

}
else{
echo 'error';
}
}
