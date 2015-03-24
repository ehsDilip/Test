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

function match_detail($datapath) {

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
        }

        $team1_bat_final_arr = '';
        $team1_bow_final_arr = '';

        $team2_bat_final_arr = '';
        $team2_bow_final_arr = '';
        $team1_detail = array();
        $team2_detail = array();
        $team1_bat = '';
        $team1_bow = '';

        $team2_bat = '';
        $team2_bow = '';
        foreach ($team1_final_array as $val) {

            foreach ($Innings as $ins) {
                $battingteamid = $ins['battingteamid'];

                $batsmen_array = $ins['batsmen'];
                $bowlers_array = $ins['bowlers'];

                if ($battingteamid == $team1) {
                    $team1_run = getBatsmanRun($val, $batsmen_array);
                    $team1_p_run = '';
                    if ($team1_run) {
                        $team1_p_run = $team1_run;
                    } else {
                        $team1_p_run = 0;
                    }

                    $team1_bat[] = $team1_p_run;
                } else {
                    $team1_wicket = getBowlerWicket($val, $bowlers_array);
                    $team1_p_wicket = '';
                    if ($team1_wicket) {
                        $team1_p_wicket = $team1_wicket;
                    } else {
                        $team1_p_wicket = 0;
                    }
                    $team1_bow[] = $team1_p_wicket;
                }
            }
            $team1_bat_final_arr = $team1_bat;
            $team1_bow_final_arr = $team1_bow;
        }
        foreach ($team2_final_array as $val2) {
            foreach ($Innings as $ins) {
                $battingteamid = $ins['battingteamid'];

                $batsmen_array = $ins['batsmen'];
                $bowlers_array = $ins['bowlers'];
                if ($battingteamid != $team1) {

                    $run = getBatsmanRun($val2, $batsmen_array);
                    $team2_p_run = '';
                    if ($run) {
                        $team2_p_run = $run;
                    } else {
                        $team2_p_run = 0;
                    }
                    $team2_bat[] = $team2_p_run;
                } else {

                    $wicket = getBowlerWicket($val2, $bowlers_array);
                    $team2_p_wicket = '';
                    if ($wicket) {
                        $team2_p_wicket = $wicket;
                    } else {
                        $team2_p_wicket = 0;
                    }

                    $team2_bow[] = $team2_p_wicket;
                }
            }
            $team2_bat_final_arr = $team2_bat;
            $team2_bow_final_arr = $team2_bow;
        }
        $team1_detail = array(
            "team_id" => $team1,
            'bat_detail' => $team1_bat_final_arr,
            'bow_detail' => $team1_bow_final_arr
        );
        $team2_detail = array(
            "team_id" => $team2,
            'bat_detail' => $team2_bat_final_arr,
            'bow_detail' => $team2_bow_final_arr
        );
        return $result = array("team1" => $team1_detail, "team2" => $team2_detail);
    } else {
        return FALSE;
    }

    curl_close($ch);
}

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

function getPlayerTotal() {

    $get_data = mysql_query("select * from club_master where club_status= '1'");

    if (mysql_num_rows($get_data) > 0) {

        while ($data = mysql_fetch_array($get_data)) {
            $match_id = $data['match_id'];
            $club_id = $data['id'];

            $cash_of_run = $data['cash_of_run'];
            $run_of_wicket = $data['run_of_wicket'];

            $club_members = $data['club_members'];
            $club_admin = $data['club_admin'];
            $all_club_members = $club_members . ',' . $club_admin;
            $all_club_members_arr = explode(',', $all_club_members);

            $archive_match_id = check_match_status($match_id);

            if ($match_id == $archive_match_id) {

                $data_path = $data['datapath'];
                $team1_id = $data['team1_id'];
                $team2_id = $data['team2_id'];

                $match_details = match_detail($data_path);

                if (count($match_details) > 0 && count($all_club_members_arr) > 0) {

                    foreach ($all_club_members_arr as $member) {

                        $select_player = mysql_query("SELECT * FROM user_player_master WHERE club_id='" . $club_id . "' AND fb_id='" . $member . "' AND team1_id='" . $team1_id . "' AND team2_id='" . $team2_id . "' ");
                        if (mysql_num_rows($select_player) > 0) {
                            $sel_ply_detail = mysql_fetch_array($select_player);
                            $team1_players = $sel_ply_detail['team1_player'];
                            $team2_players = $sel_ply_detail['team2_player'];
                            $team1_players_arr = array();
                            $team2_players_arr = array();
                            if ($team1_players != '') {
                                $team1_players_arr = explode(',', $team1_players);
                                sort($team1_players_arr);
                            }
                            if ($team2_players != '') {
                                $team2_players_arr = explode(',', $team2_players);
                                sort($team2_players_arr);
                            }

                            $run = '';
                            $wicket = '';
                            foreach ($match_details as $teams) {
                                $team_id = $teams['team_id'];
                                $bat_detail_arr = $teams['bat_detail'];
                                $bow_detail_arr = $teams['bow_detail'];

                                if ($team_id == $team1_id) {
                                    for ($index = 0; $index < count($bat_detail_arr); $index++) {
                                        if (count($team1_players_arr) > 0) {
                                            foreach ($team1_players_arr as $t1_player) {
                                                if ($t1_player == $index + 1) {
                                                    $run += $bat_detail_arr[$index];
                                                    $wicket += $bow_detail_arr[$index];
                                                }
                                            }
                                        }
                                    }
                                }

                                if ($team_id == $team2_id) {
                                    for ($j = 0; $j < count($bat_detail_arr); $j++) {
                                        if (count($team2_players_arr) > 0) {
                                            foreach ($team2_players_arr as $t2_player) {
                                                if ($t2_player == $j + 1) {
                                                    $run += $bat_detail_arr[$j];
                                                    $wicket += $bow_detail_arr[$j];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            mysql_query("UPDATE user_player_master SET total_run='" . $run . "',total_wicket='" . $wicket . "' WHERE club_id='" . $club_id . "' AND fb_id='" . $member . "' AND team1_id='" . $team1_id . "' AND team2_id='" . $team2_id . "' ");
                        }
                    }
                } else {
                    echo "No Match Detail Found.";
                }
            } else {
                echo "No Match Found in Archive Matches.";
            }
        }
    }
}

function finalStep() {
    $g_data = mysql_query("select * from club_master where club_status= '1'");

    if (mysql_num_rows($g_data) > 0) {

        while ($data = mysql_fetch_array($g_data)) {
            $match_id = $data['match_id'];
            $club_id = $data['id'];

            $cash_of_run = $data['cash_of_run'];
            $run_of_wicket = $data['run_of_wicket'];

            $club_members = $data['club_members'];
            $club_admin = $data['club_admin'];
            $all_club_members = $club_members . ',' . $club_admin;
            $all_club_members_arr = explode(',', $all_club_members);

            $data_path = $data['datapath'];
            $team1_id = $data['team1_id'];
            $team2_id = $data['team2_id'];

            $select_club = mysql_query("SELECT * FROM user_player_master WHERE club_id='" . $club_id . "' ");

            if (mysql_num_rows($select_club) > 0) {

                $max_run = 0;
                $winner_cash = 0;
                $winner_id = '';
                $winner_old_cash = '';
                while ($c_data = mysql_fetch_array($select_club)) {
                    $fb_id = $c_data['fb_id'];
                    $_run = $c_data['total_run'];
                    $_wicket = $c_data['total_wicket'];

                    $total_run = $_run + ($run_of_wicket * $_wicket);

                    $max_run = max($max_run, $total_run);

                    $add = ($max_run - $total_run) * $cash_of_run;

                    $sel_user = mysql_query("SELECT * FROM user_master WHERE fb_id='" . $fb_id . "' ");
                    $sel_user_detail = mysql_fetch_array($sel_user);
                    $total_cash = $sel_user_detail['total_cash'];

                    if ($max_run != $total_run) {
                        $winner_cash +=$add;
                    }
                    if ($max_run == $total_run) {
                        $winner_id = $fb_id;
                        $winner_old_cash = $total_cash;
                    }

                    $deduct = $total_cash - $add;
                    mysql_query("UPDATE user_master SET total_cash='" . $deduct . "' WHERE fb_id='" . $fb_id . "' ");
                }


                $winner_amount = $winner_old_cash + $winner_cash;
                $b = mysql_query("UPDATE user_master SET total_cash='" . $winner_amount . "' WHERE fb_id='" . $winner_id . "' ");
                $c = mysql_query("UPDATE club_master SET club_status='2',club_winner='" . $winner_id . "' WHERE id='" . $club_id . "' ");
                if (!$b || !$c) {
                    echo "Error in fun 1";
                }
            }
        }
    }
}

echo getPlayerTotal();
echo finalStep();
?>