<?php 
/**
This class helps to perform a timebased fulltext search on a database table .
It is also equipped with an auto correct function, this helps to correct mispelled search word entered (inspired by Google's "Do you mean? "), this helps to increase the chances of getting search result,
This class intelligently uses all method of performing a search on a mysql database so as to increase the chances of getting a search result 
Search result are then displayed in the order of relevance .
More detailed usage and function is described in the example file

you can easily integrate this with the bootstrap pagination class which you can download here


@authour olorunda olaoluwa
if you have any complaint or suggestion on how to make this class better kindly email me@ olorundaolaoluwa@gmail.com
please if this class has proved helpful ..do not hesitate to give rate it as good
This class is enhances search result by conducting a fulltext search on the database ...
and it also uses a fallback alternate 'LIKE' search on database so as to increase the chances of see a result

The class is able to auto correct mispelled search string using a the concept of google's did you mean

**/
include 'SpellCorrector.php';

class fulltextsearch {
public $tablename , $searchcolumn ,$keyword,$dbname,$total_record,$dbusername,$dbpassword, $startfrom , $autocorrect ,$resgroup ,$pdo, $querys,$error ,$result ,$addfulltext , $correctedto , $dates , $datecolumnname , $datquery;

function dbcon($database,$user,$password){

					$database=$database;
					$user=$user;
					$password=$password;
					$dsn="mysql:host=localhost;dbname=$database";

try{
					$this->pdo = new PDO($dsn,$user,$password);
} 
catch(PDOException $e){
					die ('Error connecting to database');
}  
		}
//convert table to supported formart MYISAM and add a FULLTEXT index to column name
function converttable(){

					fulltextsearch::dbcon($this->dbname,$this->dbusername,$this->dbpassword);
					$convtable="ALTER TABLE $this->tablename ENGINE=MyISAM";
					$convertttl=$this->pdo->query($convtable);
					if($convertttl) {
					$addfulltexts="ALTER TABLE $this->tablename  ADD FULLTEXT($this->searchcolumn)";
					$this->addfulltext=$this->pdo->query($addfulltexts);
					return true;
					
		
}
else{
                   $addfulltexts="ALTER TABLE $this->tablename  ADD FULLTEXT($this->searchcolumn)";
					$this->addfulltext=$this->pdo->query($addfulltexts);
					
      if($this->addfulltext){
	  
					return true ;
					}
					else{
			
					echo 'Error optimizing table for search because: a foreign key constraint fails<br>';
					}
}

}

//time based funtion
function timesbased($times){


if ($times=='past24'){

$past24=date("Ymd", strtotime(" -1 day"));
$current=date("Ymd");
$this->datquery="AND `$this->datecolumnname` BETWEEN '$past24' AND '$current' ";

}

elseif($times=='pastweek'){
$pastmonth=date("Ymd", strtotime(" -1 week"));
$current=date("Ymd");
$this->datquery="AND `$this->datecolumnname` BETWEEN '$pastmonth' AND '$current' ";

}
elseif($times=='pastmonth'){
$pastmonth=date("Ymd", strtotime(" -1 month"));
$current=date("Ymd");
$this->datquery="AND `$this->datecolumnname` BETWEEN '$pastmonth' AND '$current' ";

}
elseif($times=='pastyear'){
$pastmonth=date("Ymd", strtotime(" -1 month"));
$current=date("Ymd");
$this->datquery="AND `$this->datecolumnname` BETWEEN '$pastmonth' AND '$current' ";

}
else{

//$current=date("Ymd");
$this->datquery="";

}

}
//function that does the searching

	

function search(){
fulltextsearch::timesbased($this->dates);
//break down words into tokens and correct each word
if($this->autocorrect==1){
				$search=trim($this->keyword);
				$search=explode(" ",$search);
				$count=count($search);
				$correct=" ";
		for($i=0;$i<$count;$i++){
		$correct .=" ".SpellCorrector::correct($search[$i]);

		}
		
//join word together and pass into sql string
				$correct=trim($correct);
						$this->correctedto=$correct;
				}
				else{
							$correct=$this->keyword;
				}
			

				fulltextsearch::dbcon($this->dbname,$this->dbusername,$this->dbpassword);
				$sql="SELECT * FROM $this->tablename WHERE MATCH($this->searchcolumn) AGAINST ('$correct' IN BOOLEAN MODE)  $this->datquery LIMIT $this->startfrom,$this->resgroup";
				$sqlcount="SELECT count('$this->tablename') FROM $this->tablename WHERE MATCH($this->searchcolumn) AGAINST ('$correct' IN BOOLEAN MODE)  $this->datquery";
 
				$this->querys1=$this->pdo->query($sql);
		if($this->querys1){
		//gettotal record for use with pagination
			$count=$this->pdo->query($sqlcount);
				$this->total_record=$count->fetch();
				$this->total_record=$this->total_record[0];
	
			$this->querys=$this->pdo->query($sql);
			$row=$this->querys1->fetch();
			//no result? fall back to  alternative search
		if($row[0]==""){
	//GOTO correct;
		$sqllike="SELECT * FROM `$this->tablename` WHERE  `$this->searchcolumn` LIKE '%$correct%'  $this->datquery LIMIT $this->startfrom,$this->resgroup";
		$sqllikecount="SELECT count('$this->tablename') FROM `$this->tablename` WHERE  `$this->searchcolumn` LIKE '%$correct%'  $this->datquery";
			$count=$this->pdo->query($sqllikecount);
					$this->total_record=$count->fetch();
					$this->total_record=$this->total_record[0];		
					$this->querys=$this->pdo->query($sqllike);
		
				
	}
		

				return true;
}
		else{

				return false;
			}
	}
	
}
