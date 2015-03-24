<?php

define('WPCG', 'wpcg_');
define('WPCG_CACHE_DIR', dirname(__FILE__) . '/cache');

function wp_content_guard_execute($post_content) {

    $filter = array("&nbsp;");

    $wpcg_kw = 1;
    $wpcg_individual = '';
    $wpcg_ss = '@';
    // if Protected Post title Feature selected
    if ($wpcg_kw == 1) {
        //  $filter[] = $post->post_title;
        // $filter[] = get_bloginfo( 'name' );
    } // endif
    // if Protected Post title Feature selected
    if ($wpcg_individual && ( strlen($wpcg_individual) > 1 )) {
        $filter = array_merge($filter, explode(',', str_replace(', ', ',', trim(get_option(WPCG . 'individual')))));
    } // endif


    if (!empty($filter)) {
        foreach ($filter as $phrases) {
            $phrase = explode(' ', $phrases);

            foreach ($phrase as $keywords) {
                $keyword[] = $keywords;
            } //$phrase as $keywords
        } //$filter as $phrases

        $filter = array_unique(array_merge($keyword, $filter));


        $strtolower = array_map('strtolower', $filter);
        $strtoupper = array_map('strtoupper', $filter);
        $ucfirst = array_map('ucfirst', $filter);
        $ucwords = array_map('ucwords', $filter);

        $filter = array_unique(array_merge($keyword, $filter, $strtolower, $strtoupper, $ucfirst, $ucwords));
    } //!empty

    $filter = array_merge($filter, array(
        ' '
    ));

    //Matches any opening or closing HTML tag, without its contents.
    preg_match_all('~</?[a-z][a-z0-9]*[^<>]*>~', $post_content, $tags);

    if (is_array($tags[0])) {
        $filter = array_merge($filter, $tags[0]);
    } //is_array( $tags[0] )

    preg_match_all("/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU", $post_content, $href, PREG_SET_ORDER);



    if (is_array($href[0])) {
        $filter = array_merge($filter, $href[0]);
    } //is_array( $href[3] )
    //<>=±+×÷-­_¬·¯~´`¨¸^¶µ'"«»()[]{}/\|¦:;,.!?¿¡¹²³¼½¾*#%°$¢£¥¤§©®@ªº
    /* preg_match_all('/[\'£&$%&*()}–”{@#~?><>.’,|=_+¬-]/',$post_content,$special ); */
    preg_match_all('/[^a-zA-Z0-9\s]/', $post_content, $special);
    if (is_array($special[0])) {
        $filter = array_merge($filter, $special[0]);
    }
    $ss = $wpcg_ss;
    if (strlen($ss) > 0) {
        preg_match_all('/[' . $ss . ']/', $post_content, $special);
        if (is_array($special[0])) {
            $filter = array_merge($filter, $special[0]);
        }
    }
    preg_match_all("/<iframe\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/iframe>/siU", $post_content, $src, PREG_SET_ORDER);

    if (is_array($src[2])) {
        $filter = array_merge($filter, $href[3]);
    } //is_array( $href[3] )
    preg_match_all('@<h([1-6])>*(.+?)</h\1>@is', $post_content, $heading);
    //preg_match_all("/<(h\d*)(\w[^<]*)/i",$post_content, $heading );

    if (is_array($heading[2])) {
        $filter = array_merge($filter, $heading[0]);
    } //is_array( $heading[2] )

    $replacements = sortByLength($filter);



    return get_wpcg($post_content, $replacements);
}

function sortByLengthReverse($a, $b) {
    return strlen($b) - strlen($a);
}

function sortByLength($filter) {
    $filter = array_flip($filter);

    uksort($filter, 'sortByLengthReverse');


    $filter2 = array_flip($filter);
    //sort($filter);
    foreach ($filter2 as $filters) {
        $filter3[] = $filters;
    } //$filter2 as $filters
    return $filter3;
}

function wpcg_it($content) {
    $content = '&#' . join(';&#', array_map('ord', str_split($content))) . ';';
    return $content;
//return str_replace('&#32;',' ',$content);
}

function get_wpcg($post_content, $replacements = array()) {
    $percent = 10; //get_option( WPCG . 'percent' );
    $replacements = array_filter($replacements);
    $content = wpcg_it($post_content);
    $patterns = array();
    foreach ($replacements as $pattern) {
        $pattern = trim($pattern);
        $patterns[] = '~(' . wpcg_it($pattern) . ')~';
        $content = preg_replace('~(' . wpcg_it($pattern) . ')~', $pattern, $content);
    } //$replacements as $pattern
    //$content = preg_replace( $patterns, $replacements, $content );


    $content = explode('&#', $content);

    //$content[0] = substr($content[0],0,2);
    //array_shift($content);

    $post_contents = array();

    foreach ($content as $contents) {

        $post_contents[] = '&#' . $contents;
    } //$content as $contents
    $post_contents[0] = substr($post_contents[0], 2);

    $even = array();

    for ($i = 0; $i < count($post_contents); $i++) {

        if ($percent == 'default') {
            $percent = str_replace('default', array_rand(array_flip(range(6, 12)), 1), $percent);
        }
        //  if(is_letter(html_entity_decode($post_contents[$i])) || is_numeric(html_entity_decode($post_contents[$i]))){
        if ($i % $percent == 0) {
            $even[] = $post_contents[$i];
        } //$i % $percent == 0
        else {
            $even[] = html_entity_decode($post_contents[$i], ENT_QUOTES, 'UTF-8');
        }
        //}
    } //$i = 0; $i < count( $post_contents ); $i++

    $new_content = implode('', $even);
    //$new_content = utf8_decode($new_content);
//    $cache_file = WPCG_CACHE_DIR . '/' . $post_id;
//    $fp = fopen($cache_file, 'w'); // open the cache file for writing
//    fwrite($fp, $new_content); // save the contents of output buffer to the file
//    fclose($fp); // close the file

    return $new_content;
}

$str = <<<EOF

		<p>hello</p>
<div>
<p>How are You</p>
</div>

EOF;
$string = '<b>Is 1 < 4?</b>è<br><i>"then"</i> <div style="color:#fff123"><p>gain some <strong>€</strong></p></div>';

//echo get_wpcg($str);
//echo get_wpcg($str, array('</br>', '<br>'));
echo get_wpcg($str);
