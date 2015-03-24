<?php
//error_reporting(FALSE);

mysql_connect("localhost", "root", "") or die(mysql_error());
mysql_select_db("crico") or die(mysql_error("<h1>Database Connection Errorr.....</h1>"));

function getBrowser() {
    $b = array(
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.5 (KHTML, like Gecko)", "Chrome/19.0.1084.56 Safari/536.5",
        "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:13.0) Gecko/20100101 Firefox/13.0.1",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2",
        "Opera/9.80 (Windows NT 5.1; U; en) Presto/2.10.229 Version/11.60",
        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)",
        "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; en-GB)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)",
        "Opera/8.52 (X11; Linux i386; U; de)",
        "Opera/5.0 (SunOS 5.8 sun4m; U) [en]",
        "Opera/8.51 (X11; Linux i386; U; de)",
        "Opera/8.51 (Windows NT 5.1; U; en)",
        "Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8.0.1)"
    );
    return $b[array_rand($b)];
}

function check_match_status($matchId) {
    $target_url_get = "http://mapps.cricbuzz.com/cbzandroid/2.0/archivematches.json";

    $useragent = getBrowser();
    $ch = curl_init("");
    curl_setopt($ch, CURLOPT_URL, $target_url_get);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    $response = curl_exec($ch);
    $team = json_decode($response);
    $match_id = '';
    foreach ($team as $row) {
        if ($row->matchId == $matchId) {
            $match_id = $row->matchId;
            break;
        } else {
            $match_id = 0;
        }
    }
    return $match_id;

    curl_close($ch);
}

function match_detail($datapath, $teamid) {

    $target_url_get = "http://synd.cricbuzz.com/iphone/3.0/match/" . $datapath . "scorecard.json";

    $useragent = getBrowser();
    $ch = curl_init("");
    curl_setopt($ch, CURLOPT_URL, $target_url_get);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    $response = curl_exec($ch);
    $detail = json_decode($response, TRUE);
    $result = array();
    if ($detail['datapath'] == $datapath && count($detail) > 0) {

        $Innings = $detail['Innings'];
        $team1 = $detail['team1']['id'];
        $team2 = $detail['team2']['id'];

        $team1_sqd_array = $detail['team1']['squad'];
        $team2_sqd_array = $detail['team2']['squad'];


        $team1_player_run = array();
        $team2_player_run = array();

        $team2_player_wicket = array();


        $team1_final_array = array();
        $team2_final_array = array();


        foreach ($Innings as $ins) {
            $battingteamid = $ins['battingteamid'];

            $batsmen_array = $ins['batsmen'];
            $bowlers_array = $ins['bowlers'];

            if ($battingteamid == $team1) {
                foreach ($batsmen_array as $bat) {
                    $team1_batsmanId_arr[] = $bat['batsmanId'];
                }
                $team1_final_array = array_unique(array_merge($team1_batsmanId_arr, $team1_sqd_array), SORT_REGULAR);
            } 
            if ($battingteamid == $team2) {
                foreach ($batsmen_array as $bat) {
                    $team2_batsmanId_arr[] = $bat['batsmanId'];
                }
                $team2_final_array = array_unique(array_merge($team2_batsmanId_arr, $team2_sqd_array), SORT_REGULAR);
            }


            foreach ($team1_final_array as $val) {

                if ($battingteamid == $team1) {
                    $team1_run = getBatsmanRun($val, $batsmen_array);
                    $team1_p_run = '';
                    if ($team1_run) {
                        $team1_p_run = $team1_run;
                    } else {
                        $team1_p_run = 0;
                    }
//                    echo $team1_p_run . '<br>';
                } else {
                    $team1_wicket = getBowlerWicket($val, $bowlers_array);
                    $team1_p_wicket = '';
                    if ($team1_wicket) {
                        $team1_p_wicket = $team1_wicket;
                    } else {
                        $team1_p_wicket = 0;
                    }
//                    echo $team1_p_wicket . '<br>';
                }
            }

            echo count($team2_final_array) . '<br>';
//            foreach ($team2_final_array as $val2) {
//
//                if ($battingteamid != $team1) {
////                    print_r($bowlers_array);
//                    $run = getBatsmanRun($val2, $batsmen_array);
//                    $team2_p_run = '';
//                    if ($run) {
//                        $team2_p_run = $run;
//                    } else {
//                        $team2_p_run = 0;
//                    }
////                    echo $team2_p_run . '<br>';
//                } else {
//
//                    $wicket = getBowlerWicket($val2, $bowlers_array);
//                    $team2_p_wicket = '';
//                    if ($wicket) {
//                        $team2_p_wicket = $wicket;
//                    } else {
//                        $team2_p_wicket = 0;
//                    }
//
////                    echo $team2_p_wicket . '<br>';
//                }
//            }
        }

        return $team2_final_array;
    } else {
        return FALSE;
    }

    curl_close($ch);
}

echo "<pre>";
$match_details = match_detail('2015/2015_AUS_IND_ENG/AUS_ENG_JAN23/', '4');
//$match_details = match_detail('2014/2014_15_RSA_WI/RSA_WI_JAN21/', '10');
print_r($match_details);
echo "</pre>";

//array(
//    "team_id" => "1323",
//    'bat_detail' => array(
//        "1" => "50",
//        "2" => "100"
//    ),
//    'bow_detail' => array(
//        "1" => "0",
//        "8" => "1"
//    )
//);

/*
  $get_data = mysql_query("select * from club_master where club_status= '2'");

  if (mysql_num_rows($get_data) > 0) {
  while ($data = mysql_fetch_array($get_data)) {
  $match_id = $data['match_id'];
  $archive_match_id = check_match_status($match_id);
  if ($match_id == $archive_match_id) {
  $data_path = $data['datapath'];
  $team1_id = $data['team1_id'];
  $team2_id = $data['team2_id'];

  $match_details = match_detail($data_path);
  foreach ($match_details as $m_detail) {
  foreach ($m_detail as $details) {
  $battingteamid = $details['battingteamid'];
  $bowlingteamid = $details['bowlingteamid'];

  $batsmen_array = $details['batsmen'];
  $bowlers_array = $details['bowlers'];

  //                    $team1=$details['team1'];
  //                    $team2=$details['team2'];
  //                    echo count($team1['squad']);
  //                    for ($t1 = 0; $t1 < count($team1['squad']); $t1++) {
  //
  //                    }

  for ($j = 0; $j < 11; $j++) {
  $player_no = $j + 1;
  if ($battingteamid == $team1_id) {
  $team1_player_run = $batsmen_array[$j]['run'];
  $team2_player_wik = $bowlers_array[$j]['wicket'];
  }
  if ($battingteamid == $team2_id) {
  $team2_player_run = $batsmen_array[$j]['run'];
  $team1_player_wik = $bowlers_array[$j]['wicket'];
  }
  }
  }
  }
  }
  }
  } */

function getBowlerWicket($bowler_id, $bowlers_array) {
    foreach ($bowlers_array as $bow) {
        if ($bow['bowlerId'] == $bowler_id) {
            return $bow['wicket'];
        }
    }
}

function getBatsmanRun($bats_id, $batsman_array) {
    foreach ($batsman_array as $bat) {
        if ($bat['batsmanId'] == $bats_id) {
            return $bat['run'];
        }
    }
}
?>