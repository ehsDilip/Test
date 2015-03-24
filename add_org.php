<?php

include 'config.php';
$error = array();
if (isset($_POST['add_org'])) {
    extract($_POST);
    if ($business_name == '') {
        $error[] = "Please enter Business Name.";
    }
//if($agency_category==''){
//    $error[]="Please enter Agency Category.";
//}
//if($agency_types==''){
//    $error[]="Please enter Agency Types.";
//}
    if ($street_address == '') {
        $error[] = "Please enter Street Address.";
    }
    if ($city == '') {
        $error[] = "Please enter City.";
    }
    if ($state == '') {
        $error[] = "Please enter State/Prov.";
    }
//if($email==''){
//    $error[]="Please enter Email Address.";
//}
//if($website==''){
//    $error[]="Please enter Website.";
//}
    if ($phone_area_code == '') {
        $error[] = "Please enter Primary Phone Area Code.";
    }
    if ($primary_phone == '') {
        $error[] = "Please enter Primary Phone.";
    }

    if (count($error) == 0) {
            
        $ins_org = "insert into organization (business_name,agency_category,agency_types,street_address,city,state,email,website,phone_area_code,pri_phone,lat,lng)
                values ('$business_name','$agency_category','$agency_types','$street_address','$city','$state','$email','$website','$phone_area_code','$primary_phone','$latitude','$longitude')";
        $ins_org_qur = mysql_query($ins_org);
        $last_id = mysql_insert_id();
        if ($last_id != '') {
            $_SESSION['succ_org'] = 'Organization Added Successfully.';
        }
    }
}
?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
        
<style>
    .alert{margin-bottom: 5px !important;padding: 7px!important}
</style>
<div class="row">
    <div class="col-sm-9 col-md-9">
        <div class="block-flat">
            <div class="header">							
                <h3>Add New Organization</h3>
            </div>
            <div class="content">
                <form role="form" method="post" class="form-horizontal"> 
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Business Name</label> 
                        <div class="col-sm-9">
                            <input type="text" name="business_name" class="form-control" placeholder="Enter Business Name">
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label class="col-sm-3 control-label">Agency Category</label> 
                        <div class="col-sm-9">
                            <input type="text" name="agency_category" class="form-control" id="agency_category" placeholder="Enter Agency Category">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Agency Types</label> 
                        <div class="col-sm-9">
                            <input type="text" name="agency_types" class="form-control" placeholder="Enter Agency Types">
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label class="col-sm-3 control-label">Street Address</label> 
                        <div class="col-sm-9">
                            <textarea class="form-control" name="street_address"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">City</label> 
                        <div class="col-sm-9">
                            <input type="text" name="city" class="form-control" placeholder="Enter City">
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label class="col-sm-3 control-label">State/Prov.</label> 
                        <div class="col-sm-9">
                            <input type="text" name="state" class="form-control" placeholder="Enter State/Prov.">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email Address</label> 
                        <div class="col-sm-9">
                            <input type="email" name="email" class="form-control" placeholder="Enter Email Address">
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label class="col-sm-3 control-label">Website</label> 
                        <div class="col-sm-9">
                            <input type="text" name="website" class="form-control" placeholder="Enter Website">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Primary Phone Area Code</label> 
                        <div class="col-sm-9">
                            <input type="text" name="phone_area_code" class="form-control" placeholder="Enter Primary Phone Area Code">
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label class="col-sm-3 control-label">Primary Phone</label> 
                        <div class="col-sm-9">
                            <input type="text" name="primary_phone" class="form-control" placeholder="Enter Primary Phone">
                        </div>
                    </div>
                    <div class="form-group"> 
                        <div class="col-sm-offset-9 col-sm-6">
                            <button type="submit" name="add_org" class="btn btn-primary">Submit</button>
                            <button class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>				
    </div>
    <div class="col-sm-3 col-md-3">
        <?php
        if (count($error) > 0) {
            showError($error);
        }
        if (isset($_SESSION['succ_org'])) {
            showSuccess($_SESSION['succ_org']);
            unset($_SESSION['succ_org']);
        }
        ?>
    </div>
</div>
<!--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> -->
<!--        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>-->
<script type="text/javascript">
    $(document).ready(function() {
        //autocomplete
        $("#agency_category").autocomplete({
            source: "agency_category_search.php",
            minLength: 1,
            width: 448
        });
    });
</script>
<?php include 'footer.php'; ?>