<?php
include('pagination_class.php');
mysql_connect('host name', 'user', 'password') or die(mysql_error());
mysql_select_db('dbname');
?>
    <?php
$qry = "SELECT * FROM students";

if($_REQUEST['search_text']!=""){
	$searchText = $_REQUEST['search_text'];
	$qry .=" where name like '$searchText%'";
}

//for pagination
if(isset($_GET['starting'])&& !isset($_REQUEST['submit'])){
	$starting=$_GET['starting'];
}else{
	$starting=0;
}
$recpage = 2;//number of records per page
	
$obj = new pagination_class($qry,$starting,$recpage);		
$result = $obj->result;
?>
<table border="1" align="center" width="100%">
<tr><TD>Sl no</TD><TD>Name</TD></tr>
<?php if(mysql_num_rows($result)!=0){
	$counter = $starting + 1;
	while($data = mysql_fetch_array($result)) {?>
		<tr>
		<TD><?php echo $counter; ?></TD>
		<TD><?php echo $data['name']; ?></TD>
		</tr><?php
		$counter ++;
	} ?>

		
	<tr><TD colspan="2"><?php echo $obj->anchors; ?></TDcolspan="2"></tr>
	<tr><TD colspan="2"><?php echo $obj->total; ?></TD></tr>
<?php } else{ ?>
	<tr><TD align="center" colspan="2">No Data Found</TD></tr>
<?php }?>
</TD></tr>
</table>