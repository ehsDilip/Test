<!DOCTYPE html>
<html>
    <head>
        <title>title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <script src="jquery.min.js" type="text/javascript"></script>
        <script src="jquery.maskedinput-1.3.1.min.js" type="text/javascript"></script>
        
        <script>
            jQuery(function($) {
//                $.mask.definitions['~'] = '[+-]';
//                $('#date').mask('99/99/9999');
                $('#phone_no').mask('(999) 999-9999');
//                $('#phoneext').mask("(999) 999-9999? x99999");
//                $("#tin").mask("99-9999999");
                
                $("#ssn").mask("999-99-9999");
//                $("#product").mask("a*-999-a999", {placeholder: " ", completed: function() {
//                        alert("You typed the following: " + this.val());
//                    }});
//                $("#eyescript").mask("~9.99 ~9.99 999");
                
            });
        </script>
    </head>
    <?php
    if(isset($_POST['submit_first'])){
        echo "<pre>";
        print_r($_POST);
    }
    ?>
    <body>
        <div>
            <form method="post">
                <!-- #first_step -->
                <div id="first_step">
                    <h1>Fill All Details</h1>

                    <div class="form">
                        <input type="text" name="firstname" id="firstname" placeholder="First Name"/>
                        <label for="firstname">Your First Name. </label>

                        <input type="text" name="lastname" id="lastname" placeholder="Last Name"/>
                        <label for="lastname">Your Last Name. </label>

                        <input type="text" name="email" id="email" placeholder="Email"/>
                        <label for="email">Your email address.</label>

                        <input type="text" name="date" id="date" placeholder="MM/DD/YYYY"/>
                        <label for="date">Your Birth Date.</label>


                        <select id="country" name="country">
                            <option selected="selected" value="">Select Country</option>
                            <option value="CN">Canada</option>
                            <option value="US">United States</option>
                        </select>
                        <label for="country">Your Country. </label>

                        <select id="state" name="state">
                            <option value="">Select State</option>
                            <optgroup label="CANADA STATES">
                                <option value="AB">Alberta</option>
                                <option value="BC">British Columbia</option>
                                <option value="MB">Manitoba</option>
                                <option value="NB">New Brunswick</option>
                                <option value="NL">Newfoundland & Labrador</option>
                                <option value="NT">Northwest Territories</option>
                                <option value="NS">Nova Scotia</option>
                                <option value="NU">Nunavut</option>
                                <option value="ON">Ontario</option>
                                <option value="PE">Prince Edward Island</option>
                                <option value="QC">Quebec</option>
                                <option value="SK">Saskatchewan</option>
                                <option value="YT">Yukon</option>
                            </optgroup>
                            <optgroup label="US STATESS">
                                <option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option>
                            </optgroup>
                        </select>
                        <label for="state">Your State. </label> 


                        <input type="text" name="city" id="city" placeholder="City"/>
                        <label for="city">Your City.</label>

                        <input type="text" name="phone_no"  id="phone_no" placeholder="Phone Number"/>
                        <label for="phone_no">Your Phone No.</label>

                        <textarea id="address" style="width: 300px;height: 50px;margin: 10px 0px;float: left" name="address"></textarea>
                        <label for="address">Your Address.</label>

                        <input type="text" name="postal" id="postal" placeholder="Postal Code"/>
                        <label for="postal">Your Postal Code.</label>

                        <input type="password"  name="ssn" id="ssn" placeholder="SSN"/>
                        <label for="ssn">Your SSN.</label>
                    </div>
                    <div class="clear"></div>
                    <input class="submit a13-button" style="background-color: #1abc9c;border: medium none; padding: 5px 10px; border-radius: 3px; color: #fff; font-weight: bold;" type="submit" name="submit_first" id="submit_first" value="Next" />
                </div>
            </form>
        </div>
    </body>
</html>
