
<?php
$con = mysql_connect('localhost', 'root', ''); //changet the configuration in required
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('wp_setup39');
?>
<div id="dropdowns">
    <div id="center">
        <?php
        $sql = "SELECT * FROM dow_country ORDER BY name";
        $query = mysql_query($sql);
        ?>
        <label>Country:
            <select name="country" id = "drop1">
                <option value="">Please Select Country</option>
                <?php while ($rs = mysql_fetch_array($query)) { ?>
                    <option value="<?php echo $rs["countryId"]; ?>"><?php echo $rs["name"]; ?></option>
                <?php } ?>
            </select>
        </label>
    </div>
    <div>
        <label>Region:
            <select name="region" id ="region">
                
            </select>
    </div>
    <div  id="drop3">
        <label>City:
            <select name="city" id ="city"></select>
        </label>
    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("select#drop1").change(function() {
            var country_id = $("select#drop1 option:selected").attr('value');
            $("#region").html("");
            $("#city").html("");
            if (country_id.length > 0) {

                $.ajax({
                    type: "POST",
                    url: "fetch_region.php",
                    data: "country_id=" + country_id,
                    cache: false,
                    beforeSend: function() {
                        $('#region').html('<img src="image/loader.gif" alt="" width="24" height="24">');
                    },
                    success: function(html) {
                        $("#region").html(html);
                    }
                });
            }
        });
    });
</script>