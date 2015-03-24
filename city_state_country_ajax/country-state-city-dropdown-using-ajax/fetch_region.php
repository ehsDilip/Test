<?php
$con = mysql_connect('localhost', 'root', ''); //changet the configuration in required
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('wp_setup39');
?>
<?php
$sql = "SELECT * FROM dow_region WHERE countryId='".$_REQUEST['country_id']."' ORDER BY name";
$query = mysql_query($sql);
?>

        <option value="">Please Select Region</option>
        <?php while ($rs = mysql_fetch_array($query)) { ?>
            <option value="<?php echo $rs["regionId"]; ?>"><?php echo $rs["name"]; ?></option>
        <?php } ?>


<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("select#region").change(function() {
            var regionId = $("select#region option:selected").attr('value');
            $("#city").html("");
            if (regionId.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "fetch_city.php",
                    data: "region_id=" + regionId,
                    cache: false,
                    beforeSend: function() {
                        $('#city').html('<img src="image/loader.gif" alt="" width="24" height="24">');
                    },
                    success: function(html) {
                        $("#city").html(html);
                    }
                });
            }
        });
    });
</script>