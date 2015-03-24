<?php
error_reporting(E_ALL^E_NOTICE);
include('pagination_class.php');
mysql_connect('host name', 'user', 'password') or die(mysql_error());
mysql_select_db('dbname');


/*
-- Table structure for table `students`

CREATE TABLE `students` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

INSERT INTO `students` VALUES (1, 'Reneesh');
INSERT INTO `students` VALUES (2, 'Aniesh');
INSERT INTO `students` VALUES (3, 'Babu');
INSERT INTO `students` VALUES (4, 'Antony');
INSERT INTO `students` VALUES (5, 'Praveesh');
INSERT INTO `students` VALUES (6, 'Dixon');
INSERT INTO `students` VALUES (7, 'Sanju');
INSERT INTO `students` VALUES (8, 'Neeraj');
INSERT INTO `students` VALUES (9, 'Siju');
INSERT INTO `students` VALUES (10, 'Noble');
INSERT INTO `students` VALUES (11, 'Bibin');
INSERT INTO `students` VALUES (12, 'Febin');
INSERT INTO `students` VALUES (13, 'Binu');
INSERT INTO `students` VALUES (14, 'Charles');
INSERT INTO `students` VALUES (15, 'jaggu');
INSERT INTO `students` VALUES (16, 'mani');
INSERT INTO `students` VALUES (17, 'milu');
INSERT INTO `students` VALUES (18, 'aravind');
INSERT INTO `students` VALUES (19, 'jay');
INSERT INTO `students` VALUES (20, 'hari');
*/
?>
<script language="JavaScript" src="pagination.js"></script>
<link rel="stylesheet" type="text/css" href="style.css" />
<?
$qry = "SELECT * FROM students";
$searchText = "";
if($_REQUEST['search_text']!=""){
	$searchText = $_REQUEST['search_text'];
	$qry .=" where name like '$searchText%'";
}
//for pagination
$starting=0;
$recpage = 2;//number of records per page
	
$obj = new pagination_class($qry,$starting,$recpage);		
$result = $obj->result;

			
			?><form name="form1" action="testpage.php" method="POST">
			
			<table border="1" align="center" width="40%">
			<tr>
			  <TD colspan="2">
				Search <input type="text" name="search_text" value="<?php echo $searchText; ?>">
					<input type="submit" value="Search">
			  </TD> 
			</tr>
			<tr><TD colspan="2">
			
			<div id="page_contents">
				<table border="1" align="center" width="100%">
				<tr><TD>Sl no</TD><TD>Name</TD></tr>
				<?if(mysql_num_rows($result)!=0){
					$counter = $starting + 1;
					while($data = mysql_fetch_array($result)) {?>
						<tr>
						<TD><? echo $counter; ?></TD>
						<TD><? echo $data['name']; ?></TD>
						</tr><?
						$counter ++;
					} ?>
				
						
					<tr><TD colspan="2"><? echo $obj->anchors; ?></TD></tr>
					<tr><TD colspan="2"><? echo $obj->total; ?></TD></tr>
				<?}else{?>
					<tr><TD align="center" colspan="2">No Data Found</TD></tr>
				<?}?>
				</TD></tr>
				</table>
			</div>
			</TD></tr>
		</table></form>
			