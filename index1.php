<?php

/**
 * remove_empty_tags_recursive ()
 * Remove the nested HTML empty tags from the string.
 *
 * @author    Junaid Atari <mj.atari@gmail.com>
 * @version    1.0
 * @param    string    $str    String to remove tags.
 * @param    string    $repto    Replace empty string with.
 * @return    string    Cleaned string.
 */
function remove_empty_tags_recursive($str, $repto = NULL) {
    //** Return if string not given or empty.
    if (!is_string($str) || trim($str) == '')
        return $str;

    //** Recursive empty HTML tags.
    return preg_replace(
            //** Pattern written by Junaid Atari.
            '/<([^<\/>]*)>([\s]*?|(?R))<\/\1>/imsU',
            //** Replace with nothing if string empty.
            !is_string($repto) ? '' : $repto,
            //** Source string
            $str
    );
}

function cleaning($string, $tidyConfig = null) {
    $out = array();
    $config = array(
        'indent' => true,
        'show-body-only' => false,
        'clean' => true,
        'output-xhtml' => true,
        'preserve-entities' => true
    );
    if ($tidyConfig == null) {
        $tidyConfig = &$config;
    }
    $tidy = new tidy ();
    $out ['full'] = $tidy->repairString($string, $tidyConfig, 'UTF8');
    unset($tidy);
    unset($tidyConfig);
    $out ['body'] = preg_replace("/.*<body[^>]*>|<\/body>.*/si", "", $out ['full']);
    $out ['style'] = '<style type="text/css">' . preg_replace("/.*<style[^>]*>|<\/style>.*/si", "", $out ['full']) . '</style>';
    return ($out);
}

/*
  +=====================================
  | EXAMPLE
  +=====================================
 */
$str = <<<EOF
<p></p>
<div id="paralax">
        <div>
    <div>
        <p></p>
        Hello User,
        <strong></strong>
    </div>
    <div id="contents">Welcome to our domain.</div>
    <div></div>
    <p><p><p></p></p></p>
    <p><p><div></div></p></p>
</div>
EOF;

function crl_remove_empty_tags($string, $replaceTo = null) {
    // Return if string not given or empty
    if (!is_string($string) || trim($string) == '')
        return $string;

    // Recursive empty HTML tags
    return preg_replace(
            '/<(\w+)\b(?:\s+[\w\-.:]+(?:\s*=\s*(?:"[^"]*"|"[^"]*"|[\w\-.:]+))?)*\s*\/?>\s*<\/\1\s*>/', !is_string($replaceTo) ? '' : $replaceTo, $string
    );
}

//echo cleaning($str);
//echo remove_empty_tags_recursive($str);

/*
  function crawl_page($url, $depth = 5) {
  static $seen = array();
  if (isset($seen[$url]) || $depth === 0) {
  return;
  }

  $seen[$url] = true;

  $dom = new DOMDocument('1.0');
  @$dom->loadHTMLFile($url);

  $anchors = $dom->getElementsByTagName('a');
  foreach ($anchors as $element) {
  $href = $element->getAttribute('href');
  if (0 !== strpos($href, 'http')) {
  $path = '/' . ltrim($href, '/');
  if (extension_loaded('http')) {
  $href = http_build_url($url, array('path' => $path));
  } else {
  $parts = parse_url($url);
  $href = $parts['scheme'] . '://';
  if (isset($parts['user']) && isset($parts['pass'])) {
  $href .= $parts['user'] . ':' . $parts['pass'] . '@';
  }
  $href .= $parts['host'];
  if (isset($parts['port'])) {
  $href .= ':' . $parts['port'];
  }
  $href .= $path;
  }
  }
  crawl_page($href, $depth - 1);
  }
  echo "URL:", $url.'<br>';
  }
 */

//    crawl_page("http://cg.alpcube.com/", 2);


/*
  $json_arr_openAcc = '{
  "AppServiceBeginTimestamp":"\/Date(928164000000-0400)\/",
  "AppServiceDuration":1.26743233E+15,
  "AppServiceEndTimestamp":"\/Date(928164000000-0400)\/",
  "DbBeginTimestamp":"\/Date(928164000000-0400)\/",
  "DbDuration":1.26743233E+15,
  "DbEndTimestamp":"\/Date(928164000000-0400)\/",
  "ErrorMessage":"String content",
  "ExitState":0,
  "ValidationErrors":[{
  "ErrorMessage":"String content",
  "ErrorType":0,
  "ObjectPath":"String content",
  "PropertyName":"String content"
  }],
  "WebBeginTimestamp":"\/Date(928164000000-0400)\/",
  "WebDuration":1.26743233E+15,
  "WebEndTimestamp":"\/Date(928164000000-0400)\/",
  "AccountId":9223372036854775807,
  "CardItems":[{
  "AlternateActivationCode":"String content",
  "CardId":9223372036854775807,
  "CardNumber":"String content",
  "ExitState":0,
  "IdentityValidationSuccessful":true,
  "ValidationResultStatus":0
  }],
  "CorrelationId":"String content"
  }';
  $open_response_json = $json_arr_openAcc;
  $open_response = json_decode($open_response_json);
  $CardItems = $open_response->CardItems;
  $AccountId = $open_response->AccountId;

  echo $card_number = $CardItems[0]->CardNumber;
  $openValidationResultStatus = $CardItems[0]->ValidationResultStatus;
  $open_error_msg = $open_response->ErrorMessage;
  $open_exit_state = $open_response->ExitState;
  //echo "<pre>";
  //print_r($open_response);

 */
?>
