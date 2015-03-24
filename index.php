<?php

echo get_working_hours("2015-03-16 08:00:00", "2015-03-16 18:00:00");

function get_working_hours($from, $to) {
    // timestamps
    $from_timestamp = strtotime($from);
    $to_timestamp = strtotime($to);

    // work day seconds
    $workday_start_hour = 8;
    $workday_end_hour = 18;
    $workday_seconds = ($workday_end_hour - $workday_start_hour) * 3600;

    // work days beetwen dates, minus 1 day
    $from_date = date('Y-m-d', $from_timestamp);
    $to_date = date('Y-m-d', $to_timestamp);
    $workdays_number = count(get_workdays($from_date, $to_date)) - 1;
    $workdays_number = $workdays_number < 0 ? 0 : $workdays_number;

    // start and end time
    $start_time_in_seconds = date("H", $from_timestamp) * 3600 + date("i", $from_timestamp) * 60;
    $end_time_in_seconds = date("H", $to_timestamp) * 3600 + date("i", $to_timestamp) * 60;

    // final calculations
    $working_hours = ($workdays_number * $workday_seconds + $end_time_in_seconds - $start_time_in_seconds) / 86400 * 24;

    return $working_hours;
}

function get_workdays($from, $to) {
    // arrays
    $days_array = array();
    $skipdays = array("Saturday", "Sunday");
    $skipdates = get_holidays();

    // other variables
    $i = 0;
    $current = $from;

    if ($current == $to) { // same dates
        $timestamp = strtotime($from);
        if (!in_array(date("l", $timestamp), $skipdays) && !in_array(date("Y-m-d", $timestamp), $skipdates)) {
            $days_array[] = date("Y-m-d", $timestamp);
        }
    } elseif ($current < $to) { // different dates
        while ($current < $to) {
            $timestamp = strtotime($from . " +" . $i . " day");
            if (!in_array(date("l", $timestamp), $skipdays) && !in_array(date("Y-m-d", $timestamp), $skipdates)) {
                $days_array[] = date("Y-m-d", $timestamp);
            }
            $current = date("Y-m-d", $timestamp);
            $i++;
        }
    }

    return $days_array;
}

function get_holidays() {
    // arrays
    $days_array = array("2015-03-20");

    // You have to put there your source of holidays and make them as array...
    // For example, database in Codeigniter:
    // $days_array = $this->my_model->get_holidays_array();

    return $days_array;
}

//The function returns the no. of business days between two dates and it skips the holidays
function getWorkingDays($startDate, $endDate, $holidays) {
    // do strtotime calculations just once
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);


    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);


    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week)
            $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week)
            $no_remaining_days--;
    }
    else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)
        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        } else {
            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
            // so we skip an entire weekend and subtract 2 days
            $no_remaining_days -= 2;
        }
    }


    $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0) {
        $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    foreach ($holidays as $holiday) {
        $time_stamp = strtotime($holiday);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N", $time_stamp) != 6 && date("N", $time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays * 24;
}

//Example:

$holidays = array("2015-03-20");

//echo getWorkingDays("2015-03-19 08:00:00", "2015-03-23 06:00:00", $holidays);
// => will return 7


$request = array(
    'start' => '3PM Nov 29 2013',
    'end' => '3PM Dec 6 2013'
);

//echo calculate_work($request);

/**
 * Calculate work time by looping through every minute
 * @param  array $request start to end time
 * @return int  work time in minutes
 */
function calculate_work($request) {
    $start = strtotime($request['start']);
    $end = strtotime($request['end']);
    $work_time = 0;

    /* Add 1 minute to the start so that we don't count 0 as a minute  */
    for ($time = $start + 60; $time <= $end; $time += 60) {
        // Weekends
        if (date('D', $time) == 'Sat' OR date('D', $time) == 'Sun')
            continue;

        // Non Working Hours
        if (date('Hi', $time) <= '0800' OR date('Hi', $time) > '1700')
            continue;

        // On Hold
        if ($time > strtotime('3PM Dec 2 2013') AND $time <= strtotime('3PM Dec 3 2013'))
            continue;

        $work_time++;
    }

    // Divide by 60 to turn minutes into hours
    return $work_time / 60;
}

exit;

$a = "<tt>some</tt><b>html</b>";
preg_match("/<\w?>(\w*?)<\/\w?>/", $a, $b);
echo $b[1];

function getDivContent($divClass, $filepath) {
    include 'original_ehs.php';
    $html = file_get_contents($filepath);
    $dom_html = str_get_dom($html);
    $out = '';
    foreach ($dom_html->find('div' . $divClass) as $htm) {
        $out .= $htm;
    }
    return $out;
}

//echo getDivContent('.tcontent', 'f516f46ea5ccb34e5f33d0ab3a734681.html');
//function str_get_dom($str, $lowercase = true) {
//    $dom = new simple_html_dom;
//    $dom->load($str, $lowercase);
//    return $dom;
//}

/*
  error_reporting(0);
  include 'original_ehs.php';


  function searchResults($keyword) {
  $lottery = rand(1, 5);
  switch ($lottery) {
  case 1: $search_result = get_google($keyword);
  break;
  case 2: $search_result = get_yahoo($keyword);
  break;
  case 3:  //$search_result= algo_B($qry);break;
  case 4: $search_result = get_qwant($keyword);
  break;
  case 5: $search_result = get_yandex($keyword);
  break;
  default: algo_B($keyword);
  }

  if (count($search_result) == 0) {
  $search_result = algo_B($keyword);
  }
  return $search_result;
  }

  $key = "android";

  $search_result = searchResults($key);
  echo $search_result_count = count($search_result);
  echo "<br>";
  echo "<pre>";
  print_r($search_result);
 */


//$data = file_get_contents('http://search.msn.com/results.aspx?q=site%3Afroogle.com');
//$regex = '/Page 1 of (.+?) results/';
//preg_match($regex,$data,$match);
//var_dump($match); 
//echo $match[1];
//    Scraping number of results for Ask.Com


/*
  $array_Validate_Card_holder_Identity = array(
  'Credentials' => array(
  'AccountId' => "",
  'Token' => ""
  ),
  'Request' => array(
  'Address' => array(
  'Address1' => "",
  'City' => "",
  'PostalCode' => "",
  'Country' => "",
  'State' => ""
  ),
  "Answers" => array(array(
  "Answer" => "Skip Question",
  "QuestionType" => "current.county.b"
  )),
  "Answers" => array(array(
  "Answer" => "Skip Question",
  "QuestionType" => "time.at.current.address"
  )),
  "Answers" => array(array(
  "Answer" => "Skip Question",
  "QuestionType" => "geo.closest.hospital"
  )),
  "Answers" => array(array(
  "Answer" => "Skip Question",
  "QuestionType" => "city.of.residence"
  )),
  'CardholderIdInfo' => array(
  'IdIssuer' => "",
  'IdNumber' => "",
  'IdType' => 1
  ),
  'Dob' => "",
  'Email' => "",
  'FirstName' => "",
  'LastName' => "",
  'ProductId' => ""
  )
  );
  $json_arr_validate_card = json_encode($array_Validate_Card_holder_Identity);
 * 
 * 0
 */
/*
  function get_snippet( $str, $wordCount = 450 ) {
  return implode(
  '',
  array_slice(
  preg_split(
  '/([\s,\.;\?\!]+)/',
  $str,
  $wordCount*2+1,
  PREG_SPLIT_DELIM_CAPTURE
  ),
  0,
  $wordCount*2-1
  )
  );
  }
  $a='Art Institute Online, adobe offer web design program. (e-learning).

  The Art Institute Online, a division of The Art Institute of Pittsburgh, is partnering with Adobe Systems Inc. to develop and market certificate programs in Web design as part of the Art Institute Online Center for Professional Development. The center will offer intensive, accelerated modules in the Web design certificate programs in an online format that is specifically designed around targeted professional areas. The two programs that will initially be offered are an introduction to Web design and Web site development. Each program consists of five courses in six-week modules. These courses include: fundamentals of design for the Web; architecture and information management; Web authoring; defining and debugging specs; designing a Web site; introduction to multimedia and Web design; interactive design; Web authoring tools; user interface design; and developing a Web a site. …
  Art Institute Online, adobe offer web design program. (e-learning).

  The Art Institute Online, a division of The Art Institute of Pittsburgh, is partnering with Adobe Systems Inc. to develop and market certificate programs in Web design as part of the Art Institute Online Center for Professional Development. The center will offer intensive, accelerated modules in the Web design certificate programs in an online format that is specifically designed around targeted professional areas. The two programs that will initially be offered are an introduction to Web design and Web site development. Each program consists of five courses in six-week modules. These courses include: fundamentals of design for the Web; architecture and information management; Web authoring; defining and debugging specs; designing a Web site; introduction to multimedia and Web design; interactive design; Web authoring tools; user interface design; and developing a Web a site. … ';
  echo str_word_count($a); */
/*
  //$a=array ( "administrator" => 1 ) ;
  // serialize($a);
  //$s=  unserialize('s:28:"a:1:{s:10:"subscriber";i:1;}"');
  //print_r($s);
  //$temp_html='<div class="box-jobdetails-main" style="width: 278px; height: auto; float: left;">
  //	<div class="jobdetai-toptext" style="width: 268px; height: 22px; float: left;font-family: Arial; font-size: 14px; font-weight: bold; color: #477db8; padding: 6px 0px 0px 10px; background-position: 0% 0%; background-repeat: no-repeat no-repeat;">
  //		&nbsp;</div>
  //	<div class="Jobdetail-inner-main" style="width: 261px; height: auto; float: left;padding: 15px 0px 5px 15px;">
  //		<div class="Text-boxes ArialL13" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 13px; font-weight: normal; color: #477db8;">
  //			From</div>
  //		<div class="Text-boxes ArialL12 paddT5" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 12px; font-weight: normal; color: #000000; padding-top: 3px;">
  //			{{from_user}}</div>
  //		<br />
  //		<div class="Text-boxes ArialL13 PaddT10" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 13px; font-weight: normal; color: #477db8; padding-top: 10px;">
  //			Receive_date</div>
  //		<div class="Text-boxes ArialL12 paddT5" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 12px; font-weight: normal; color: #000000; padding-top: 3px;">
  //			{{msg_date}}</div>
  //		<div class="Text-boxes ArialL13 PaddT10" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 13px; font-weight: normal; color: #477db8; padding-top: 10px;">
  //			Case No.</div>
  //		<div class="Text-boxes ArialL12 paddT5" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 12px; font-weight: normal; color: #000000; padding-top: 3px;">
  //			{{ticket_number}}</div>
  //		<div class="Text-boxes ArialL13 PaddT10" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 13px; font-weight: normal; color: #477db8; padding-top: 10px;">
  //			Message</div>
  //		<div class="Text-boxes ArialL12 paddT5" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 12px; font-weight: normal; color: #000000; padding-top: 3px;">
  //			{{message}}</div>
  //	</div>
  //</div>
  //<p>
  //	&nbsp;</p>
  //';
  $temp_html = '<div class="box-jobdetails-main" style="width: 278px; height: auto; float: left;">
  <div class="jobdetai-toptext" style="width: 268px; height: 22px; float: left;font-family: Arial; font-size: 14px; font-weight: bold; color: #477db8; padding: 6px 0px 0px 10px; background-position: 0% 0%; background-repeat: no-repeat no-repeat;">
  &nbsp;</div>
  <div class="Jobdetail-inner-main" style="width: 261px; height: auto; float: left;padding: 15px 0px 5px 15px;">
  <div class="Text-boxes ArialL13" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 13px; font-weight: normal; color: #477db8;">
  From</div>
  <div class="Text-boxes ArialL12 paddT5" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 12px; font-weight: normal; color: #000000; padding-top: 3px;">
  {{from_user}}</div>
  <br />
  <div class="Text-boxes ArialL13 PaddT10" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 13px; font-weight: normal; color: #477db8; padding-top: 10px;">
  Receive_date</div>
  <div class="Text-boxes ArialL12 paddT5" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 12px; font-weight: normal; color: #000000; padding-top: 3px;">
  {{msg_date}}</div>
  <div class="Text-boxes ArialL13 PaddT10" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 13px; font-weight: normal; color: #477db8; padding-top: 10px;">
  Case No.</div>
  <div class="Text-boxes ArialL12 paddT5" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 12px; font-weight: normal; color: #000000; padding-top: 3px;">
  {{ticket_number}}</div>
  <div class="Text-boxes ArialL13 PaddT10" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 13px; font-weight: normal; color: #477db8; padding-top: 10px;">
  Message</div>
  <div class="Text-boxes ArialL12 paddT5" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 12px; font-weight: normal; color: #000000; padding-top: 3px;text-align:justify;">
  Withdraw Your Offers.</div>
  <div class="Text-boxes ArialL12 paddT5" style="width: 261px; height: auto; float: left; font-family: Arial; font-size: 12px; font-weight: normal; color: #000000; padding-top: 3px;">
  <p>
  <b>Best Regards,</b><br />
  <b>Courtgenie Support Team</b></p>
  </div>
  </div>
  </div>
  <p>
  &nbsp;</p>

  ';

  if (strpos($temp_html,'Message') !== false) {
  echo 'Message';
  }

  //$replacements1 = array(
  //                'from_user' =>$username,
  //                'msg_date' => date('Y-m-d h:i:s'),
  //                'ticket_number'=>$decline_ids['1'],
  //                'message'=>'Updated amount to $ 3,000.00 for the case number CG004'
  //            );
  ////
  //            foreach ($replacements1 as $find => $replace) {
  //                $temp_html = preg_replace('/\{\{' . preg_quote($find, '/') . '\}\}/', $replace, $temp_html);
  //            }
  //          echo "<pre>";
  //            print_r($temp_html);
  //exit;
  ?>

 */
?>
