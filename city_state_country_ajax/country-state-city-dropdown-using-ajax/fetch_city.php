<?php
$con = mysql_connect('localhost', 'root', ''); //changet the configuration in required
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('wp_setup39');
?>
<?php
$sql = "SELECT * FROM dow_cities WHERE regionId='".$_REQUEST['region_id']."' ORDER BY name";
$query = mysql_query($sql);
?>

        <option value="">Please Select City</option>
        <?php while ($rs = mysql_fetch_array($query)) { ?>
            <option value="<?php echo $rs["cityId"]; ?>"><?php echo $rs["name"]; ?></option>
        <?php } ?>
    