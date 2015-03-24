<?php

/**
 * Plugin Name: Vantiv
 * Plugin URI: http://www.eheuristic.com
 * Description: 
 * Version: The Plugin's Version Number, : 1.0
 * Author: Ehs
 * Author URI: http://www.eheuristic.com
 * License: A "Slug" license name e.g. GPL2
 */


add_action('admin_menu', 'register_my_custom_menu_page');
function register_my_custom_menu_page() {
    add_menu_page('Vantiv', 'Vantiv', 'manage_options', 'vantiv', 'my_custom_menu_page', plugins_url(''));
}
function my_custom_menu_page() {
    $error=array();
    if(isset($_POST['sub_cred'])){
        $acc=$_POST['acc_id'];
        $token=$_POST['token'];
        $open_acc=$_POST['open_acc'];
        $val_card_hold=$_POST['val_card_hold'];
        if($acc==''){$error[]="Please enter Account Id.";}
        if($token==''){$error[]="Please enter Token.";}
        if($open_acc==''){$error[]="Please select Open Account Form.";}
        if($val_card_hold==''){$error[]="Please select Validate Card holder Identity Form.";}
        if(count($error)==0){
            update_option('AccountId', $acc );
            update_option('Token', $token );
            update_option('open_acc_form', $open_acc );
            update_option('val_card_hold_form', $val_card_hold );
            $_SESSION['save_cred']="Credentials saved successfully.";
        }
    }
    ?>
<!--<div style="width: 50%;float: left">-->
    <form method="post">
        <h3>Credentials</h3>
        <p style="color: red"><?php foreach ($error as $err){echo $err.'</br>';}?></p>
        <p style="color: green"><?php if(isset($_SESSION['save_cred'])){echo $_SESSION['save_cred'];unset($_SESSION['save_cred']);}?></p>
        <table>
            <tr>
                <td>Account Id</td>
                <td><input size="50" type="text" name="acc_id" value="<?php echo get_option('AccountId');?>" ></td>
            </tr>
            <tr>
                <td>Token</td>
                <td><input size="50" type="text" name="token" value="<?php echo get_option('Token');?>"></td>
            </tr>
            <?php
            $args = array( 'posts_per_page' => -1, 'post_type' => 'wpcf7_contact_form', 'order'=>"DESC");
            $query = new WP_Query( $args );
            ?>
            <tr>
                <td class="manage-column">Open Account</td>
                <td class="manage-column">
                    <select name="open_acc">
                        <option value="">Select Form</option>
                        <?php
                        if( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();?>
                        <option value="<?php echo get_the_ID();?>" <?php if(get_option('open_acc_form')==get_the_ID()){echo "selected";}?>><?php echo get_the_title();?></option>
                        <?php }
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="manage-column">Validate Card holder Identity</td>
                <td class="manage-column">
                    <select name="val_card_hold">
                        <option value="">Select Form</option>
                        <?php
                        if( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post();?>
                        <option value="<?php echo get_the_ID();?>" <?php if(get_option('val_card_hold_form')==get_the_ID()){echo "selected";}?>><?php echo get_the_title();?></option>
                        <?php }
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="sub_cred" value="Submit Credentials"></td>
            </tr>
        </table>
    </form>
<?php }

include 'vantiv_curl.php';

//add_action('wpcf7_before_send_mail', 'my_wpcf7_save',1);
function my_wpcf7_save($cfdata) {

    $formid = $_POST['form_id'];
    //$formid = $cfdata->id;

    //$formdata = $cfdata->posted_data;
    $formdata = $_POST;
    
    if ( $formid == get_option('open_acc_form')) {
        
        $array_openAcc = array(
            'Credentials'=>array(
                'AccountId'=>get_option('AccountId'),
                'Token'=>get_option('Token')
            ),
            'Request'=>array(
                'AccountType'=>'0',
                'AffiliateAcro'=>$formdata['AffiliateAcro'],
                'CorrelationId'=>$formdata['CorrelationId'],
                'FeeType'=>'1',
                'IntegrationDetail'=>array(
                    'Name'=>$formdata['Name'],
                    'Value'=>$formdata['Value']
                ),
                'OpenAccountItems'=>array(
                    'Address'=>array(
                        'Adwpcf7_before_send_maildressLine1'=>$formdata['AddressLine1'],
                        'AddressLine2'=>$formdata['AddressLine2'],
                        'City'=>$formdata['City'],
                        'Country'=>$formdata['Country'],
                        'PostalCode'=>$formdata['PostalCode'],
                        'State'=>$formdata['State']
                    ),
                    'CardId'=>'9223372036854775807',
                    'CardNumber'=>'',
                    'Cardholder'=>array(
                        'CardId'=>'9223372036854775807',
                        'DOB'=>'\/Date(928164000000-0400)\/',
                        'EmailAddress'=>$formdata['EmailAddress'],
                        'FirstName'=>$formdata['FirstName'],
                        'IntegrationDetail'=>array(
                            'Name'=>$formdata['Name'],
                            'Value'=>$formdata['Value']
                        ),
                        'LastName'=>$formdata['LastName'],
                        'Phone'=>$formdata['Phone'],
                        'SSN'=>$formdata['SSN']
                        ),
                    'IdentityValidationKey'=>get_option('Token'),
                    'ProductId'=>'2147483647',
                    'ValidateIdentity'=>'true'
                ),
            )
        );
        
        $json_openAcc = json_encode($array_openAcc);
    
        $curl = new Vantiv_curl();
        $response = $curl->OpenAccount($json_openAcc);
        echo $response;
        //die();
	}
        
        if ( $formid == get_option('val_card_hold_form')) {
            $array_Validate_Card_holder_Identity = array(
            'Credentials'=>array(
                'AccountId'=>get_option('AccountId'),
                'Token'=>get_option('Token')
            ),
            'Request'=>array(
                'Address'=>array(
                    'Address1'=>$formdata['Address1'],
                    'Address2'=>$formdata['Address2'],
                    'City'=>$formdata['City'],
                    'Country'=>$formdata['Country'],
                    'PostalCode'=>$formdata['PostalCode'],
                    'State'=>$formdata['State']
                ),
                'Answers'=>array(
                    'Answer'=>$formdata['Answer'],
                    'QuestionType'=>$formdata['QuestionType']
                ),
                'CardId'=>'9223372036854775807',
                'Dob'=>'\/Date(928164000000-0400)\/',
                'DocumentImage'=>array('81','109','70','122','90','83','65','50','78','67','66','84','100','72','74','108','89','87','48','61'),
                'Email'=>$formdata['Email'],
                'FirstName'=>$formdata['FirstName'],
                'IdentityValidationKey'=>get_option('Token'),
                'LastName'=>$formdata['LastName'],
                'PhoneNumber'=>$formdata['PhoneNumber'],
                'Ssn'=>$formdata['Ssn']
                ), 
            );
            $json_Validate_Card_holder_Identity = json_encode($array_Validate_Card_holder_Identity);
        
            $curl = new Vantiv_curl();
            $response = $curl->ValidateCardholderIdentity($json_Validate_Card_holder_Identity);
            echo $response;
            //die();
        }
        die(); 
}


function theme_name_scripts() {
  wp_enqueue_style( 'vantiv_css',plugins_url('vantiv/vantiv.css'));
  wp_enqueue_script( 'vantiv_custom', plugins_url('vantiv/vantiv_custom.js'), array('jquery'));
  wp_localize_script( 'vantiv_custom', 'MyAjax', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' )
  ));
  
}

add_action('wp_ajax_my_wpcf7_save', 'my_wpcf7_save');
add_action('wp_ajax_nopriv_my_wpcf7_save', 'my_wpcf7_save');
add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );

//echo "<script type='text/javascript'>$('.wpcf7-response-output').hide();<script>";