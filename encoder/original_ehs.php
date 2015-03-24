<?php

/*******************************************************************************
  This File is protected by eHeuristic Solutions
 *******************************************************************************/

//error_reporting(0);

function get_couponsDotCom() {
    $url = "http://www.coupons.com/coupon-codes";

    $useragent = getBrowser();
    $ch = curl_init("");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    $response = curl_exec($ch);

    $html = str_get_dom($response);
    $data_B = array();
    $B = 0;

    foreach ($html->find('div#original') as $original) {
        foreach ($original->find('div.ccpod') as $ccpod) {
            $class = $ccpod->getAttribute('class');
            $strArray = explode(' ', $class);
            $b_content['offerType'] = $strArray[1];
            foreach ($ccpod->find('div.left-pod') as $left_pod) {
                foreach ($left_pod->find('div.clicks') as $clicks) {
                    $uses = $clicks->plaintext;

                    preg_match_all('!\d+!', $uses, $matches);

                    $b_content['usesToday'] = $matches[0][0];
                }
                foreach ($left_pod->find('div.store-icon') as $store_icon) {
                    foreach ($store_icon->find('a') as $store_icon_a) {
                        foreach ($store_icon_a->find('img') as $store_icon_img) {
                            $b_content['companyLogo'] = $store_icon_img->getAttribute('src');
                        }
                    }
                }
            }
            foreach ($ccpod->find('div.top') as $top) {
                foreach ($top->find('div.coupon-info') as $coupon_info) {
                    foreach ($coupon_info->find('h3.summary') as $summary) {
                        $b_content['offerTitle'] = $summary->plaintext;
                    }
                    $b_content['offerDetails'] = $coupon_info->find('p.desc', 0)->plaintext;
                }
                foreach ($top->find('div.bottom') as $bottom) {
                    $b_content['offerExpiration'] = $bottom->find('span[itemprop=validThrough]', 0)->plaintext;
                    $b_content['companyName'] = $bottom->find('span[itemprop=seller]', 0)->plaintext;
                }
            }

            $data_B[$B] = $b_content;
            $B++;
        }
    }
    return $data_B;
    curl_close($ch);
}

function get_grouponDotCom() {
    $url = "https://www.groupon.com/coupons";

    $useragent = getBrowser();

    $context = stream_context_create(Array(
        "http" => Array(
            "method" => "GET",
            "header" => "User-agent: $useragent",
            'request_fulluri' => True /* without this option we get an HTTP error! */
        )
            )
    );

    $response = file_get_contents($url, false, $context);

    $html = str_get_dom($response);
    $data_B = array();

    foreach ($html->find('div#coupons-grid') as $coupons_grid) {
        foreach ($coupons_grid->find('div[class=columns four end]') as $columns) {
            foreach ($columns->find('div.coupon') as $coupon) {
                $b_content['companyName'] = $coupon->getAttribute('data-merchant');
                foreach ($coupon->find('div.image-contain') as $image_contain) {
                    $b_content['companyLogo'] = $image_contain->find('a img', 0)->getAttribute('src');
                }
                foreach ($coupon->find('div.title-contain div.title-inner') as $title_contain) {
                    $b_content['offerTitle'] = $title_contain->find('h4 a', 0)->plaintext;
                    $b_content['offerDetails'] = $title_contain->find('meta[itemprop=description]', 0)->getAttribute('content');
                    $b_content['offerExpiration'] = $title_contain->find('p span[itemprop=validThrough]', 0)->plaintext;
                }
                foreach ($coupon->find('div.button-contain div.btn-cta') as $btn_cta) {
                    $type = $btn_cta->getAttribute('data-bhw');
                    if ($type == 'ActivateSaleButton') {
                        $b_content['offerType'] = 'Sale';
                    }
                    if ($type == 'GetCouponCodeButton') {
                        $b_content['offerType'] = 'Coupon';
                    }
                    if ($type == 'getCouponButton') {
                        $b_content['offerType'] = 'Printable';
                    }
                }
            }
            $data_B[] = $b_content;
        }
    }
    return $data_B;
    curl_close($ch);
}

function get_retailmenotDotCom() {
    $url = "http://www.retailmenot.com";
    $useragent = getBrowser();
    $ch = curl_init("");

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    $response = curl_exec($ch);
    curl_close($ch);
    $html = str_get_dom($response);
    $data_B = array();
    $company_name = array();

    foreach ($html->find("div.top-coupons") as $content) {
        foreach ($content->find("div.grid-row") as $grid_row) {
            foreach ($grid_row->find("div.grid-unit") as $grid) {
                foreach ($grid->find("div.coupon-square") as $coupon_square) {
                    $comp_name = $coupon_square->getAttribute('data-merchant-title');
                    $data['companyName'] = isset($comp_name) ? $comp_name : '';
                    
                    $comp_url = $coupon_square->getAttribute('data-merchant-name');
                    $data['companyUrl'] = isset($comp_url) ? $comp_url : '';
                    
                    foreach ($coupon_square->find("div.coupon-square-logo") as $coupon_square_logo) {
                        foreach ($coupon_square_logo->find('img') as $logo) {
                            $logo = $logo->getAttribute('data-path');
                            $data['companyLogo'] = isset($logo) ? $logo : '';
                        }
                    }
                    foreach ($coupon_square->find("div.coupon-square-body") as $coupon_square_body) {
                        foreach ($coupon_square_body->find("div.coupon-square-type") as $coupon_square_type) {
                            $type = $coupon_square_type->plaintext;
                            $data['offerType'] = isset($type) ? $type : '';
                        }
                        foreach ($coupon_square_body->find("div.coupon-square-title") as $coupon_square_title) {
                            $title = $coupon_square_title->plaintext;
                            $data['offerTitle'] = isset($title) ? $title : '';
                        }
                        foreach ($coupon_square_body->find("div.coupon-square-details") as $coupon_square_details) {
                            foreach ($coupon_square_details->find("div") as $div) {
                                preg_match_all('!\d+!', $div->plaintext, $uses);
                                $data['usesToday'] = isset($uses[0][0]) ? $uses[0][0] : '';
                            }
                        }
                    }
                }
                $data_B[] = $data;
            }
        }
    }
    return $data_B;
}

function get_livingSocialDotCom() {
    $url = "https://www.livingsocial.com/coupons";

    $useragent = getBrowser();

    $context = stream_context_create(Array(
        "http" => Array(
            "method" => "GET",
            "header" => "User-agent: $useragent",
            'request_fulluri' => True /* without this option we get an HTTP error! */
        )
            )
    );

    $response = file_get_contents($url, false, $context);

    $html = str_get_dom($response);
    $data_B = array();
    $B = 0;

    foreach ($html->find('ul.offers li.offer') as $offer) {
        foreach ($offer->find('div.img img') as $divimg) {
            $b_content['companyLogo'] = $divimg->getAttribute('src');
        }
        foreach ($offer->find('p.offer-meta') as $offer_meta) {
            $b_content['companyName'] = $offer_meta->find('span.offer-brand', 0)->plaintext;

            $offer_lab = $offer_meta->find('span.offer-label', 0)->plaintext;
            if ($offer_lab) {
                $offerType = explode(' ', $offer_lab);
                $b_content['offerType'] = $offerType[2];
            } else {
                $b_content['offerType'] = '';
            }
            $b_content['offerSubmitted'] = $offer_meta->find('span.date-posted', 0)->plaintext;
        }
        foreach ($offer->find('h2.offer-name') as $offer_meta) {
            $b_content['offerTitle'] = $offer_meta->find('a', 0)->plaintext;
        }
        $b_content['offerExpiration'] = $offer->find('p.date-ends', 0)->plaintext;

        if ($b_content['offerType'] == 'coupon') {
            foreach ($offer->find('div.promo-code div.btn-promo-code') as $promo_code) {
                $code_text = $promo_code->find('a', 0)->plaintext;
                $b_content['couponCode'] = str_replace('show code', '', $code_text);
            }
        } else {
            $b_content['couponCode'] = '';
        }

        $data_B[$B] = $b_content;
        $B++;
    }
    return $data_B;
    curl_close($ch);
}

function get_yandex($qry) {
    $qry = str_replace(' ', '+', $qry);
    $data_B = array();
//    if (($_SERVER['HTTP_HOST'] != 'designofweb.net') && ($_SERVER['HTTP_HOST'] != 'www.designofweb.net')) {
//        echo 'Error.. Occurred';
//        exit;
//    }
    $B = 0;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, getBrowser());
    curl_setopt($ch, CURLOPT_URL, "http://www.yandex.com/yandsearch?text=$qry&lr=87");
    $cookie_file_path = "cookie.txt"; // Please set your Cookie File path	
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);


    $response = curl_exec($ch);
    //echo $response;	

    $html = str_get_dom($response);
    foreach ($html->find("div.serp-list") as $content) {
        foreach ($content->find("div.serp-item") as $list) {
//            foreach ($list->find("div.sa_mc") as $des) 
            {
                foreach ($list->find(".b-link") as $href) {
                    $b_content['href'] = $href->getAttribute('href');
                    break;
                }
                foreach ($list->find("h2") as $title) {
                    $b_content['title'] = $title->plaintext;
                    break;
                }
                foreach ($list->find("div.serp-item__text") as $desc) {
                    $b_content['desc'] = $desc->plaintext;
                    break;
                }
                $data_B[$B] = $b_content;
                $B++;
            }
        }
    }
    return $data_B;
}

function algo_Y($qry) {
    $qry = str_replace(' ', '+', $qry);
    // $qry .='%20site:blogspot.com';
    $data_B = array();

    $B = 0;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, getBrowser());
    curl_setopt($ch, CURLOPT_URL, "http://clusty.com/search?input-form=clusty-simple&v%3Asources=webplus-ns-uf&v%3Aproject=clusty-original&query=$qry");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);

    $response = curl_exec($ch);
    //echo $response;	

    $html = str_get_dom($response);
    foreach ($html->find("div[id=results-list-container]") as $content) {
        foreach ($content->find("li.document") as $list) {
//            foreach ($list->find("div.sa_mc") as $des) 
            {
                foreach ($list->find("a") as $href) {
                    $b_content['href'] = $href->getAttribute('href');
                    break;
                }
                foreach ($list->find("span.title") as $title) {
                    $b_content['title'] = $title->plaintext;
                    break;
                }
                foreach ($list->find("span.snippet") as $desc) {
                    $b_content['desc'] = $desc->plaintext;
                    break;
                }
                $data_B[$B] = $b_content;
                $B++;
            }
        }
    }
    return $data_B;
}

function get_yahoo($qry) {
    $qry = str_replace(' ', '+', $qry);
    // $qry .='%20site:blogspot.com';
    $data_B = array();

    $B = 0;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, getBrowser());
    curl_setopt($ch, CURLOPT_URL, "http://search.yahoo.com/search?p=$qry&toggle=1&cop=mss&ei=UTF-8&fr=yfp-t-402");
    $cookie_file_path = "cookie.txt"; // Please set your Cookie File path	
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);


    $response = curl_exec($ch);
    //echo $response;	

    $html = str_get_dom($response);
    foreach ($html->find("div[id=web]") as $content) {
        foreach ($content->find("li") as $list) {
//            foreach ($list->find("div.sa_mc") as $des) 
            {
                foreach ($list->find(".yschttl") as $href) {
                    $b_content['href'] = $href->getAttribute('href');
                    break;
                }
                foreach ($list->find("h3") as $title) {
                    $b_content['title'] = $title->plaintext;
                    break;
                }
                foreach ($list->find("div.abstr") as $desc) {
                    $b_content['desc'] = $desc->plaintext;
                    break;
                }
                $data_B[$B] = $b_content;
                $B++;
            }
        }
    }
    return $data_B;
}

//For algo_G or algo_B you have to pass var(without special Character) as Qry like gtu-exam-papers
function algo_G($qry, $G_link = "http://google.com/complete/search?q=") {
    $qry = str_replace("-", "+", $qry);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $G_link . trim($qry));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    $resp = curl_exec($ch);

    $suggest = explode("\"", $resp);
    $keywordChunk = array();
    for ($i = 3; $i < count($suggest); $i+=6)
        $keywordChunk[] = $suggest[$i];

    if (count($keywordChunk) == 0) {

        $keywordList = $qry;
    } else {
        $keywordList = $keywordChunk;
    }
    return $keywordList;
}

function getKeywordSuggestionsFromGoogle($keyword) {
    $keywords = array();
//    if (($_SERVER['HTTP_HOST'] != 'designofweb.net') && ($_SERVER['HTTP_HOST'] != 'www.designofweb.net')) {
//        echo 'Error.. Occurred';
//        exit;
//    }
    $data = file_get_contents('http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en-US&q=' . urlencode($keyword));
    if (($data = json_decode($data, true)) !== null) {
        $keywords = $data[1];
    }

    return $keywords;
}

function getSynonym($word) {
    $synlist = '';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://www.synonym.com/synonyms/' . $word . '/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    $response = curl_exec($ch);
    $html = str_get_dom($response);
    foreach ($html->find('.equals') as $syn) {
        $synlist .= $syn;
    }
    return $synlist;
}

function getBrowser() {
//    $b = array("Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.5 (KHTML, like Gecko)", "Chrome/19.0.1084.56 Safari/536.5",
//        "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:13.0) Gecko/20100101 Firefox/13.0.1",
//        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2",
//        "Opera/9.80 (Windows NT 5.1; U; en) Presto/2.10.229 Version/11.60",
//        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)",
//        "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; en-GB)",
//        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
//        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");


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

function get_qwant($qry) {
    $qry = str_replace(' ', '+', $qry);
    // $qry .='%20site:blogspot.com';
//    if (($_SERVER['HTTP_HOST'] != 'designofweb.net') && ($_SERVER['HTTP_HOST'] != 'www.designofweb.net')) {
//        echo 'Error.. Occurred';
//        exit;
//    }

    $data_B = array();
    $B = 0;
    $useragent = getBrowser();
    $ch = curl_init("");
    curl_setopt($ch, CURLOPT_URL, "http://www.qwant.com/search/web?q=$qry");
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent); // set user agent
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);


    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    $response = curl_exec($ch);

    curl_close($ch);

    $results = json_decode($response);
    foreach ($results->result->items as $result) {
        $b_content['title'] = $result->title;
        $b_content['desc'] = $result->desc;
        $b_content['href'] = $result->url;
        $data_B[$B] = $b_content;
        $B++;
    }

    return $data_B;
}

function strip_tags_content($text, $tags = '', $invert = FALSE) {
    /*
      This function removes all html tags and the contents within them
      unlike strip_tags which only removes the tags themselves.
     */
    //removes <br> often found in google result text, which is not handled below
    $text = str_ireplace('<br>', '', $text);

    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    $tags = array_unique($tags[1]);

    if (is_array($tags) AND count($tags) > 0) {
        //if invert is false, it will remove all tags except those passed a
        if ($invert == FALSE) {
            return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            //if invert is true, it will remove only the tags passed to this function
        } else {
            return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
        }
        //if no tags were passed to this function, simply remove all the tags
    } elseif ($invert == FALSE) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }

    return $text;
}

function get_google($qry) {
    $qry = str_replace(' ', '+', $qry);
    // $qry .='%20site:blogspot.com';
    $data_B = array();

    $B = 0;
    $useragent = getBrowser();
    $ch = curl_init("");

    curl_setopt($ch, CURLOPT_URL, "http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q=" . $qry);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent); // set user agent
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    $response = curl_exec($ch);
    curl_close($ch);

    $html = str_get_dom($response);
    foreach ($html->find("div[id=ires]") as $content) {
        foreach ($content->find("li.g") as $list) {
            $h3 = $list->find('h3', 0);
            $s = $list->find('span.st', 0);
            if ($h3 != '') {
                $a = $h3->find('a', 0);
            }
            $href = $a->getAttribute('href');
            if (strpos('url?q=', $href) === FALSE) {
                $href_1 = explode('&', $href);
                $href_2 = explode('=', $href_1[0]);
                $href = $href_2[1];
            }

            $b_content['title'] = strip_tags($a->innertext);
            $b_content['href'] = $href;
            $b_content['desc'] = strip_tags_content($s->innertext);

            /*
              foreach ($list->find("h3") as $title) {
              $b_content['title'] = $title->plaintext;
              foreach ($list->find('a', 0) as $href) {
              $b_content['href'] = $href->getAttribute('href');
              break;
              }
              break;
              }
              foreach ($list->find("span.st") as $desc) {
              $b_content['desc'] = $desc->plaintext;
              break;
              }
             * 
             */
            $data_B[$B] = $b_content;
            $B++;
        }
    }

    return $data_B;
}

function algo_B($qry, $first = 1) {
    $qry = str_replace(' ', '+', $qry);
    // $qry .='%20site:blogspot.com';
    $data_B = array();
//    if (($_SERVER['HTTP_HOST'] != 'designofweb.net') && ($_SERVER['HTTP_HOST'] != 'www.designofweb.net')) {
//        echo 'Error.. Occurred';
//        exit;
//    }
    return algo_Y($qry);
}

// Other Then Algorithm Logic


define('HDOM_TYPE_ELEMENT', 1);
define('HDOM_TYPE_COMMENT', 2);
define('HDOM_TYPE_TEXT', 3);
define('HDOM_TYPE_ENDTAG', 4);
define('HDOM_TYPE_ROOT', 5);
define('HDOM_TYPE_UNKNOWN', 6);
define('HDOM_QUOTE_DOUBLE', 0);
define('HDOM_QUOTE_SINGLE', 1);
define('HDOM_QUOTE_NO', 3);
define('HDOM_INFO_BEGIN', 0);
define('HDOM_INFO_END', 1);
define('HDOM_INFO_QUOTE', 2);
define('HDOM_INFO_SPACE', 3);
define('HDOM_INFO_TEXT', 4);
define('HDOM_INFO_INNER', 5);
define('HDOM_INFO_OUTER', 6);
define('HDOM_INFO_ENDSPACE', 7);

// helper functions
// -----------------------------------------------------------------------------
// get html dom form file
function file_get_html() {
    $dom = new simple_html_dom;
    $args = func_get_args();
    $dom->load(call_user_func_array('file_get_contents', $args), true);
    return $dom;
}

// get html dom form string
function str_get_html($str, $lowercase = true) {
    $dom = new simple_html_dom;
    $dom->load($str, $lowercase);
    return $dom;
}

// dump html dom tree
function dump_html_tree($node, $show_attr = true, $deep = 0) {
    $lead = str_repeat('    ', $deep);
    echo $lead . $node->tag;
    if ($show_attr && count($node->attr) > 0) {
        echo '(';
        foreach ($node->attr as $k => $v)
            echo "[$k]=>\"" . $node->$k . '", ';
        echo ')';
    }
    echo "\n";

    foreach ($node->nodes as $c)
        dump_html_tree($c, $show_attr, $deep + 1);
}

// get dom form file (deprecated)
function file_get_dom() {
    $dom = new simple_html_dom;
    $args = func_get_args();
    $dom->load(call_user_func_array('file_get_contents', $args), true);
    return $dom;
}

// get dom form string (deprecated)
function str_get_dom($str, $lowercase = true) {
    $dom = new simple_html_dom;
    $dom->load($str, $lowercase);
    return $dom;
}

// simple html dom node
// -----------------------------------------------------------------------------
class simple_html_dom_node {

    public $nodetype = HDOM_TYPE_TEXT;
    public $tag = 'text';
    public $attr = array();
    public $children = array();
    public $nodes = array();
    public $parent = null;
    public $_ = array();
    private $dom = null;

    function __construct($dom) {
        $this->dom = $dom;
        $dom->nodes[] = $this;
    }

    function __destruct() {
        $this->clear();
    }

    function __toString() {
        return $this->outertext();
    }

    // clean up memory due to php5 circular references memory leak...
    function clear() {
        $this->dom = null;
        $this->nodes = null;
        $this->parent = null;
        $this->children = null;
    }

    // dump node's tree
    function dump($show_attr = true) {
        dump_html_tree($this, $show_attr);
    }

    // returns the parent of node
    function parent() {
    return $this->parent;








































































































































    }

// returns children of node
function children($idx = -1) {
    if ($idx === -1)
        return $this->children;
    if (isset($this->children[$idx]))
        return $this->children[$idx];
    return null;
}

// returns the first child of node
function first_child() {
    if (count($this->children) > 0)
        return $this->children[0];
    return null;
}

// returns the last child of node
function last_child() {
    if (($count = count($this->children)) > 0)
        return $this->children[$count - 1];
    return null;
}

// returns the next sibling of node    
function next_sibling() {
    if ($this->parent === null)
        return null;
    $idx = 0;
    $count = count($this->parent->children);
    while ($idx < $count && $this !== $this->parent->children[$idx])
        ++$idx;
    if (++$idx >= $count)
        return null;
    return $this->parent->children[$idx];
}

// returns the previous sibling of node
function prev_sibling() {
    if ($this->parent === null)
        return null;
    $idx = 0;
    $count = count($this->parent->children);
    while ($idx < $count && $this !== $this->parent->children[$idx])
        ++$idx;
    if (--$idx < 0)
        return null;
    return $this->parent->children[$idx];
}

// get dom node's inner html
function innertext() {
    if (isset($this->_[HDOM_INFO_INNER]))
        return $this->_[HDOM_INFO_INNER];
    if (isset($this->_[HDOM_INFO_TEXT]))
        return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);

    $ret = '';
    foreach ($this->nodes as $n)
        $ret .= $n->outertext();
    return $ret;
}

// get dom node's outer text (with tag)
function outertext() {
    if ($this->tag === 'root')
        return $this->innertext();

    // trigger callback
    if ($this->dom->callback !== null)
        call_user_func_array($this->dom->callback, array($this));

    if (isset($this->_[HDOM_INFO_OUTER]))
        return $this->_[HDOM_INFO_OUTER];
    if (isset($this->_[HDOM_INFO_TEXT]))
        return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);

    // render begin tag
    $ret = $this->dom->nodes[$this->_[HDOM_INFO_BEGIN]]->makeup();

    // render inner text
    if (isset($this->_[HDOM_INFO_INNER]))
        $ret .= $this->_[HDOM_INFO_INNER];
    else {
        foreach ($this->nodes as $n)
            $ret .= $n->outertext();
    }

    // render end tag
    if (isset($this->_[HDOM_INFO_END]) && $this->_[HDOM_INFO_END] != 0)
        $ret .= '</' . $this->tag . '>';
    return $ret;
}

// get dom node's plain text
function text() {
    if (isset($this->_[HDOM_INFO_INNER]))
        return $this->_[HDOM_INFO_INNER];
    switch ($this->nodetype) {
        case HDOM_TYPE_TEXT: return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);
        case HDOM_TYPE_COMMENT: return '';
        case HDOM_TYPE_UNKNOWN: return '';
    }
    if (strcasecmp($this->tag, 'script') === 0)
        return '';
    if (strcasecmp($this->tag, 'style') === 0)
        return '';

    $ret = '';
    foreach ($this->nodes as $n)
        $ret .= $n->text();
    return $ret;
}

function xmltext() {
    $ret = $this->innertext();
    $ret = str_ireplace('<![CDATA[', '', $ret);
    $ret = str_replace(']]>', '', $ret);
    return $ret;
}

// build node's text with tag
function makeup() {
    // text, comment, unknown
    if (isset($this->_[HDOM_INFO_TEXT]))
        return $this->dom->restore_noise($this->_[HDOM_INFO_TEXT]);

    $ret = '<' . $this->tag;
    $i = -1;

    foreach ($this->attr as $key => $val) {
        ++$i;

        // skip removed attribute
        if ($val === null || $val === false)
            continue;

        $ret .= $this->_[HDOM_INFO_SPACE][$i][0];
        //no value attr: nowrap, checked selected...
        if ($val === true)
            $ret .= $key;
        else {
            switch ($this->_[HDOM_INFO_QUOTE][$i]) {
                case HDOM_QUOTE_DOUBLE: $quote = '"';
                    break;
                case HDOM_QUOTE_SINGLE: $quote = '\'';
                    break;
                default: $quote = '';
            }
            $ret .= $key . $this->_[HDOM_INFO_SPACE][$i][1] . '=' . $this->_[HDOM_INFO_SPACE][$i][2] . $quote . $val . $quote;
        }
    }
    $ret = $this->dom->restore_noise($ret);
    return $ret . $this->_[HDOM_INFO_ENDSPACE] . '>';
}

// find elements by css selector
function find($selector, $idx = null) {
    $selectors = $this->parse_selector($selector);
    if (($count = count($selectors)) === 0)
        return array();
    $found_keys = array();

    // find each selector
    for ($c = 0; $c < $count; ++$c) {
        if (($levle = count($selectors[0])) === 0)
            return array();
        if (!isset($this->_[HDOM_INFO_BEGIN]))
            return array();

        $head = array($this->_[HDOM_INFO_BEGIN] => 1);

        // handle descendant selectors, no recursive!
        for ($l = 0; $l < $levle; ++$l) {
            $ret = array();
            foreach ($head as $k => $v) {
                $n = ($k === -1) ? $this->dom->root : $this->dom->nodes[$k];
                $n->seek($selectors[$c][$l], $ret);
            }
            $head = $ret;
        }

        foreach ($head as $k => $v) {
            if (!isset($found_keys[$k]))
                $found_keys[$k] = 1;
        }
    }

    // sort keys
    ksort($found_keys);

    $found = array();
    foreach ($found_keys as $k => $v)
        $found[] = $this->dom->nodes[$k];

    // return nth-element or array
    if (is_null($idx))
        return $found;
    else if ($idx < 0)
        $idx = count($found) + $idx;
    return (isset($found[$idx])) ? $found[$idx] : null;
}

// seek for given conditions
protected function seek($selector, &$ret) {
    list($tag, $key, $val, $exp, $no_key) = $selector;

    // xpath index
    if ($tag && $key && is_numeric($key)) {
        $count = 0;
        foreach ($this->children as $c) {
            if ($tag === '*' || $tag === $c->tag) {
                if (++$count == $key) {
                    $ret[$c->_[HDOM_INFO_BEGIN]] = 1;
                    return;
                }
            }
        }
        return;
    }

    $end = (!empty($this->_[HDOM_INFO_END])) ? $this->_[HDOM_INFO_END] : 0;
    if ($end == 0) {
        $parent = $this->parent;
        while (!isset($parent->_[HDOM_INFO_END]) && $parent !== null) {
            $end -= 1;
            $parent = $parent->parent;
        }
        $end += $parent->_[HDOM_INFO_END];
    }

    for ($i = $this->_[HDOM_INFO_BEGIN] + 1; $i < $end; ++$i) {
        $node = $this->dom->nodes[$i];
        $pass = true;

        if ($tag === '*' && !$key) {
            if (in_array($node, $this->children, true))
                $ret[$i] = 1;
            continue;
        }

        // compare tag
        if ($tag && $tag != $node->tag && $tag !== '*') {
            $pass = false;
        }
        // compare key
        if ($pass && $key) {
            if ($no_key) {
                if (isset($node->attr[$key]))
                    $pass = false;
            }
            else if (!isset($node->attr[$key]))
                $pass = false;
        }
        // compare value
        if ($pass && $key && $val && $val !== '*') {
            $check = $this->match($exp, $val, $node->attr[$key]);
            // handle multiple class
            if (!$check && strcasecmp($key, 'class') === 0) {
                foreach (explode(' ', $node->attr[$key]) as $k) {
                    $check = $this->match($exp, $val, $k);
                    if ($check)
                        break;
                }
            }
            if (!$check)
                $pass = false;
        }
        if ($pass)
            $ret[$i] = 1;
        unset($node);
    }
}

protected function match($exp, $pattern, $value) {
    switch ($exp) {
        case '=':
            return ($value === $pattern);
        case '!=':
            return ($value !== $pattern);
        case '^=':
            return preg_match("/^" . preg_quote($pattern, '/') . "/", $value);
        case '$=':
            return preg_match("/" . preg_quote($pattern, '/') . "$/", $value);
        case '*=':
            if ($pattern[0] == '/')
                return preg_match($pattern, $value);
            return preg_match("/" . $pattern . "/i", $value);
    }
    return false;
}

protected function parse_selector($selector_string) {
    // pattern of CSS selectors, modified from mootools
    $pattern = "/([\w-:\*]*)(?:\#([\w-]+)|\.([\w-]+))?(?:\[@?(!?[\w-]+)(?:([!*^$]?=)[\"']?(.*?)[\"']?)?\])?([\/, ]+)/is";
    preg_match_all($pattern, trim($selector_string) . ' ', $matches, PREG_SET_ORDER);
    $selectors = array();
    $result = array();
    //print_r($matches);

    foreach ($matches as $m) {
        $m[0] = trim($m[0]);
        if ($m[0] === '' || $m[0] === '/' || $m[0] === '//')
            continue;
        // for borwser grnreated xpath
        if ($m[1] === 'tbody')
            continue;

        list($tag, $key, $val, $exp, $no_key) = array($m[1], null, null, '=', false);
        if (!empty($m[2])) {
            $key = 'id';
            $val = $m[2];
        }
        if (!empty($m[3])) {
            $key = 'class';
            $val = $m[3];
        }
        if (!empty($m[4])) {
            $key = $m[4];
        }
        if (!empty($m[5])) {
            $exp = $m[5];
        }
        if (!empty($m[6])) {
            $val = $m[6];
        }

        // convert to lowercase
        if ($this->dom->lowercase) {
            $tag = strtolower($tag);
            $key = strtolower($key);
        }
        //elements that do NOT have the specified attribute
        if (isset($key[0]) && $key[0] === '!') {
            $key = substr($key, 1);
            $no_key = true;
        }

        $result[] = array($tag, $key, $val, $exp, $no_key);
        if (trim($m[7]) === ',') {
            $selectors[] = $result;
            $result = array();
        }
    }
    if (count($result) > 0)
        $selectors[] = $result;
    return $selectors;
}

function __get($name) {
    if (isset($this->attr[$name]))
        return $this->attr[$name];
    switch ($name) {
        case 'outertext': return $this->outertext();
        case 'innertext': return $this->innertext();
        case 'plaintext': return $this->text();
        case 'xmltext': return $this->xmltext();
        default: return array_key_exists($name, $this->attr);
    }
}

function __set($name, $value) {
    switch ($name) {
        case 'outertext': return $this->_[HDOM_INFO_OUTER] = $value;
        case 'innertext':
            if (isset($this->_[HDOM_INFO_TEXT]))
                return $this->_[HDOM_INFO_TEXT] = $value;
            return $this->_[HDOM_INFO_INNER] = $value;
    }
    if (!isset($this->attr[$name])) {
        $this->_[HDOM_INFO_SPACE][] = array(' ', '', '');
        $this->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_DOUBLE;
    }
    $this->attr[$name] = $value;
}

function __isset($name) {
    switch ($name) {
        case 'outertext': return true;
        case 'innertext': return true;
        case 'plaintext': return true;
    }
    //no value attr: nowrap, checked selected...
    return (array_key_exists($name, $this->attr)) ? true : isset($this->attr[$name]);
}

function __unset($name) {
    if (isset($this->attr[$name]))
        unset($this->attr[$name]);
}

// camel naming conventions
function getAllAttributes() {
    return $this->attr;
}

function getAttribute($name) {
    return $this->__get($name);
}

function setAttribute($name, $value) {
    $this->__set($name, $value);
}

function hasAttribute($name) {
    return $this->__isset($name);
}

function removeAttribute($name) {
    $this->__set($name, null);
}

function getElementById($id) {
    return $this->find("#$id", 0);
}

function getElementsById($id, $idx = null) {
    return $this->find("#$id", $idx);
}

function getElementByTagName($name) {
    return $this->find($name, 0);
}

function getElementsByTagName($name, $idx = null) {
    return $this->find($name, $idx);
}

function parentNode() {
    return $this->parent();
}

function childNodes($idx = -1) {
    return $this->children($idx);
}

function firstChild() {
    return $this->first_child();
}

function lastChild() {
    return $this->last_child();
}

function nextSibling() {
    return $this->next_sibling();
}

function previousSibling() {
    return $this->prev_sibling();
}

}

// simple html dom parser
// -----------------------------------------------------------------------------
class simple_html_dom {

public $root = null;
public $nodes = array();
public $callback = null;
public $lowercase = false;
protected $pos;
protected $doc;
protected $char;
protected $size;
protected $cursor;
protected $parent;
protected $noise = array();
protected $token_blank = " \t\r\n";
protected $token_equal = ' =/>';
protected $token_slash = " />\r\n\t";
protected $token_attr = ' >';
// use isset instead of in_array, performance boost about 30%...
protected $self_closing_tags = array('img' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'link' => 1, 'hr' => 1, 'base' => 1, 'embed' => 1, 'spacer' => 1);
protected $block_tags = array('root' => 1, 'body' => 1, 'form' => 1, 'div' => 1, 'span' => 1, 'table' => 1);
protected $optional_closing_tags = array(
    'tr' => array('tr' => 1, 'td' => 1, 'th' => 1),
    'th' => array('th' => 1),
    'td' => array('td' => 1),
    'li' => array('li' => 1),
    'dt' => array('dt' => 1, 'dd' => 1),
    'dd' => array('dd' => 1, 'dt' => 1),
    'dl' => array('dd' => 1, 'dt' => 1),
    'p' => array('p' => 1),
    'nobr' => array('nobr' => 1),
);

function __construct($str = null) {
    if ($str) {
        if (preg_match("/^http:\/\//i", $str) || is_file($str))
            $this->load_file($str);
        else
            $this->load($str);
    }
}

function __destruct() {
    $this->clear();
}

// load html from string
function load($str, $lowercase = true) {
    // prepare
    $this->prepare($str, $lowercase);
    // strip out comments
    $this->remove_noise("'<!--(.*?)-->'is");
    // strip out cdata
    $this->remove_noise("'<!\[CDATA\[(.*?)\]\]>'is", true);
    // strip out <style> tags
    $this->remove_noise("'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is");
    $this->remove_noise("'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is");
    // strip out <script> tags
    $this->remove_noise("'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is");
    $this->remove_noise("'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is");
    // strip out preformatted tags
    $this->remove_noise("'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is");
    // strip out server side scripts
    $this->remove_noise("'(<\?)(.*?)(\)'s", true);
    // strip smarty scripts
    $this->remove_noise("'(\{\w)(.*?)(\})'s", true);

    // parsing
    while ($this->parse());
    // end
    $this->root->_[HDOM_INFO_END] = $this->cursor;
}

// load html from file
function load_file() {
    $args = func_get_args();
    $this->load(call_user_func_array('file_get_contents', $args), true);
}

// set callback function
function set_callback($function_name) {
    $this->callback = $function_name;
}

// remove callback function
function remove_callback() {
    $this->callback = null;
}

// save dom as string
function save($filepath = '') {
    $ret = $this->root->innertext();
    if ($filepath !== '')
        file_put_contents($filepath, $ret);
    return $ret;
}

// find dom node by css selector
function find($selector, $idx = null) {
    return $this->root->find($selector, $idx);
}

// clean up memory due to php5 circular references memory leak...
function clear() {
    foreach ($this->nodes as $n) {
        $n->clear();
        $n = null;
    }
    if (isset($this->parent)) {
        $this->parent->clear();
        unset($this->parent);
    }
    if (isset($this->root)) {
        $this->root->clear();
        unset($this->root);
    }
    unset($this->doc);
    unset($this->noise);
}

function dump($show_attr = true) {
    $this->root->dump($show_attr);
}

// prepare HTML data and init everything
protected function prepare($str, $lowercase = true) {
    $this->clear();
    $this->doc = $str;
    $this->pos = 0;
    $this->cursor = 1;
    $this->noise = array();
    $this->nodes = array();
    $this->lowercase = $lowercase;
    $this->root = new simple_html_dom_node($this);
    $this->root->tag = 'root';
    $this->root->_[HDOM_INFO_BEGIN] = -1;
    $this->root->nodetype = HDOM_TYPE_ROOT;
    $this->parent = $this->root;
    // set the length of content
    $this->size = strlen($str);
    if ($this->size > 0)
        $this->char = $this->doc[0];
}

// parse html content
protected function parse() {
    if (($s = $this->copy_until_char('<')) === '')
        return $this->read_tag();

    // text
    $node = new simple_html_dom_node($this);
    ++$this->cursor;
    $node->_[HDOM_INFO_TEXT] = $s;
    $this->link_nodes($node, false);
    return true;
}

// read tag info
protected function read_tag() {
    if ($this->char !== '<') {
        $this->root->_[HDOM_INFO_END] = $this->cursor;
        return false;
    }
    $begin_tag_pos = $this->pos;
    $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
    // end tag
    if ($this->char === '/') {
        $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
        $this->skip($this->token_blank_t);
        $tag = $this->copy_until_char('>');

        // skip attributes in end tag
        if (($pos = strpos($tag, ' ')) !== false)
            $tag = substr($tag, 0, $pos);

        $parent_lower = strtolower($this->parent->tag);
        $tag_lower = strtolower($tag);

        if ($parent_lower !== $tag_lower) {
            if (isset($this->optional_closing_tags[$parent_lower]) && isset($this->block_tags[$tag_lower])) {
                $this->parent->_[HDOM_INFO_END] = 0;
                $org_parent = $this->parent;

                while (($this->parent->parent) && strtolower($this->parent->tag) !== $tag_lower)
                    $this->parent = $this->parent->parent;

                if (strtolower($this->parent->tag) !== $tag_lower) {
                    $this->parent = $org_parent; // restore origonal parent
                    if ($this->parent->parent)
                        $this->parent = $this->parent->parent;
                    $this->parent->_[HDOM_INFO_END] = $this->cursor;
                    return $this->as_text_node($tag);
                }
            }
            else if (($this->parent->parent) && isset($this->block_tags[$tag_lower])) {
                $this->parent->_[HDOM_INFO_END] = 0;
                $org_parent = $this->parent;

                while (($this->parent->parent) && strtolower($this->parent->tag) !== $tag_lower)
                    $this->parent = $this->parent->parent;

                if (strtolower($this->parent->tag) !== $tag_lower) {
                    $this->parent = $org_parent; // restore origonal parent
                    $this->parent->_[HDOM_INFO_END] = $this->cursor;
                    return $this->as_text_node($tag);
                }
            } else if (($this->parent->parent) && strtolower($this->parent->parent->tag) === $tag_lower) {
                $this->parent->_[HDOM_INFO_END] = 0;
                $this->parent = $this->parent->parent;
            } else
                return $this->as_text_node($tag);
        }

        $this->parent->_[HDOM_INFO_END] = $this->cursor;
        if ($this->parent->parent)
            $this->parent = $this->parent->parent;

        $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
        return true;
    }

    $node = new simple_html_dom_node($this);
    $node->_[HDOM_INFO_BEGIN] = $this->cursor;
    ++$this->cursor;
    $tag = $this->copy_until($this->token_slash);

    // doctype, cdata & comments...
    if (isset($tag[0]) && $tag[0] === '!') {
        $node->_[HDOM_INFO_TEXT] = '<' . $tag . $this->copy_until_char('>');

        if (isset($tag[2]) && $tag[1] === '-' && $tag[2] === '-') {
            $node->nodetype = HDOM_TYPE_COMMENT;
            $node->tag = 'comment';
        } else {
            $node->nodetype = HDOM_TYPE_UNKNOWN;
            $node->tag = 'unknown';
        }

        if ($this->char === '>')
            $node->_[HDOM_INFO_TEXT].='>';
        $this->link_nodes($node, true);
        $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
        return true;
    }

    // text
    if ($pos = strpos($tag, '<') !== false) {
        $tag = '<' . substr($tag, 0, -1);
        $node->_[HDOM_INFO_TEXT] = $tag;
        $this->link_nodes($node, false);
        $this->char = $this->doc[--$this->pos]; // prev
        return true;
    }

    if (!preg_match("/^[\w-:]+$/", $tag)) {
        $node->_[HDOM_INFO_TEXT] = '<' . $tag . $this->copy_until('<>');
        if ($this->char === '<') {
            $this->link_nodes($node, false);
            return true;
        }

        if ($this->char === '>')
            $node->_[HDOM_INFO_TEXT].='>';
        $this->link_nodes($node, false);
        $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
        return true;
    }

    // begin tag
    $node->nodetype = HDOM_TYPE_ELEMENT;
    $tag_lower = strtolower($tag);
    $node->tag = ($this->lowercase) ? $tag_lower : $tag;

    // handle optional closing tags
    if (isset($this->optional_closing_tags[$tag_lower])) {
        while (isset($this->optional_closing_tags[$tag_lower][strtolower($this->parent->tag)])) {
            $this->parent->_[HDOM_INFO_END] = 0;
            $this->parent = $this->parent->parent;
        }
        $node->parent = $this->parent;
    }

    $guard = 0; // prevent infinity loop
    $space = array($this->copy_skip($this->token_blank), '', '');

    // attributes
    do {
        if ($this->char !== null && $space[0] === '')
            break;
        $name = $this->copy_until($this->token_equal);
        if ($guard === $this->pos) {
            $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
            continue;
        }
        $guard = $this->pos;

        // handle endless '<'
        if ($this->pos >= $this->size - 1 && $this->char !== '>') {
            $node->nodetype = HDOM_TYPE_TEXT;
            $node->_[HDOM_INFO_END] = 0;
            $node->_[HDOM_INFO_TEXT] = '<' . $tag . $space[0] . $name;
            $node->tag = 'text';
            $this->link_nodes($node, false);
            return true;
        }

        // handle mismatch '<'
        if ($this->doc[$this->pos - 1] == '<') {
            $node->nodetype = HDOM_TYPE_TEXT;
            $node->tag = 'text';
            $node->attr = array();
            $node->_[HDOM_INFO_END] = 0;
            $node->_[HDOM_INFO_TEXT] = substr($this->doc, $begin_tag_pos, $this->pos - $begin_tag_pos - 1);
            $this->pos -= 2;
            $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
            $this->link_nodes($node, false);
            return true;
        }

        if ($name !== '/' && $name !== '') {
            $space[1] = $this->copy_skip($this->token_blank);
            $name = $this->restore_noise($name);
            if ($this->lowercase)
                $name = strtolower($name);
            if ($this->char === '=') {
                $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
                $this->parse_attr($node, $name, $space);
            } else {
                //no value attr: nowrap, checked selected...
                $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_NO;
                $node->attr[$name] = true;
                if ($this->char != '>')
                    $this->char = $this->doc[--$this->pos]; // prev
            }
            $node->_[HDOM_INFO_SPACE][] = $space;
            $space = array($this->copy_skip($this->token_blank), '', '');
        } else
            break;
    } while ($this->char !== '>' && $this->char !== '/');

    $this->link_nodes($node, true);
    $node->_[HDOM_INFO_ENDSPACE] = $space[0];

    // check self closing
    if ($this->copy_until_char_escape('>') === '/') {
        $node->_[HDOM_INFO_ENDSPACE] .= '/';
        $node->_[HDOM_INFO_END] = 0;
    } else {
        // reset parent
        if (!isset($this->self_closing_tags[strtolower($node->tag)]))
            $this->parent = $node;
    }
    $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
    return true;
}

// parse attributes
protected function parse_attr($node, $name, &$space) {
    $space[2] = $this->copy_skip($this->token_blank);
    switch ($this->char) {
        case '"':
            $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_DOUBLE;
            $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
            $node->attr[$name] = $this->restore_noise($this->copy_until_char_escape('"'));
            $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
            break;
        case '\'':
            $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_SINGLE;
            $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
            $node->attr[$name] = $this->restore_noise($this->copy_until_char_escape('\''));
            $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
            break;
        default:
            $node->_[HDOM_INFO_QUOTE][] = HDOM_QUOTE_NO;
            $node->attr[$name] = $this->restore_noise($this->copy_until($this->token_attr));
    }
}

// link node's parent
protected function link_nodes(&$node, $is_child) {
    $node->parent = $this->parent;
    $this->parent->nodes[] = $node;
    if ($is_child)
        $this->parent->children[] = $node;
}

// as a text node
protected function as_text_node($tag) {
    $node = new simple_html_dom_node($this);
    ++$this->cursor;
    $node->_[HDOM_INFO_TEXT] = '</' . $tag . '>';
    $this->link_nodes($node, false);
    $this->char = ( ++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
    return true;
}

protected function skip($chars) {
    $this->pos += strspn($this->doc, $chars, $this->pos);
    $this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
}

protected function copy_skip($chars) {
    $pos = $this->pos;
    $len = strspn($this->doc, $chars, $pos);
    $this->pos += $len;
    $this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
    if ($len === 0)
        return '';
    return substr($this->doc, $pos, $len);
}

protected function copy_until($chars) {
    $pos = $this->pos;
    $len = strcspn($this->doc, $chars, $pos);
    $this->pos += $len;
    $this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
    return substr($this->doc, $pos, $len);
}

protected function copy_until_char($char) {
    if ($this->char === null)
        return '';

    if (($pos = strpos($this->doc, $char, $this->pos)) === false) {
        $ret = substr($this->doc, $this->pos, $this->size - $this->pos);
        $this->char = null;
        $this->pos = $this->size;
        return $ret;
    }

    if ($pos === $this->pos)
        return '';
    $pos_old = $this->pos;
    $this->char = $this->doc[$pos];
    $this->pos = $pos;
    return substr($this->doc, $pos_old, $pos - $pos_old);
}

protected function copy_until_char_escape($char) {
    if ($this->char === null)
        return '';

    $start = $this->pos;
    while (1) {
        if (($pos = strpos($this->doc, $char, $start)) === false) {
            $ret = substr($this->doc, $this->pos, $this->size - $this->pos);
            $this->char = null;
            $this->pos = $this->size;
            return $ret;
        }

        if ($pos === $this->pos)
            return '';

        if ($this->doc[$pos - 1] === '\\') {
            $start = $pos + 1;
            continue;
        }

        $pos_old = $this->pos;
        $this->char = $this->doc[$pos];
        $this->pos = $pos;
        return substr($this->doc, $pos_old, $pos - $pos_old);
    }
}

// remove noise from html content
protected function remove_noise($pattern, $remove_tag = false) {
    $count = preg_match_all($pattern, $this->doc, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

    for ($i = $count - 1; $i > -1; --$i) {
        $key = '___noise___' . sprintf('% 3d', count($this->noise) + 100);
        $idx = ($remove_tag) ? 0 : 1;
        $this->noise[$key] = $matches[$i][$idx][0];
        $this->doc = substr_replace($this->doc, $key, $matches[$i][$idx][1], strlen($matches[$i][$idx][0]));
    }

    // reset the length of content
    $this->size = strlen($this->doc);
    if ($this->size > 0)
        $this->char = $this->doc[0];
}

// restore noise to html content
function restore_noise($text) {
    while (($pos = strpos($text, '___noise___')) !== false) {
        $key = '___noise___' . $text[$pos + 11] . $text[$pos + 12] . $text[$pos + 13];
        if (isset($this->noise[$key]))
            $text = substr($text, 0, $pos) . $this->noise[$key] . substr($text, $pos + 14);
    }
    return $text;
}

function __toString() {
    return $this->root->innertext();
}

function __get($name) {
    switch ($name) {
        case 'outertext': return $this->root->innertext();
        case 'innertext': return $this->root->innertext();
        case 'plaintext': return $this->root->text();
    }
}

// camel naming conventions
function childNodes($idx = -1) {
    return $this->root->childNodes($idx);
}

function firstChild() {
    return $this->root->first_child();
}

function lastChild() {
    return $this->root->last_child();
}

function getElementById($id) {
    return $this->find("#$id", 0);
}

function getElementsById($id, $idx = null) {
    return $this->find("#$id", $idx);
}

function getElementByTagName($name) {
    return $this->find($name, 0);
}

function getElementsByTagName($name, $idx = -1) {
    return $this->find($name, $idx);
}

function loadFile() {
    $args = func_get_args();
    $this->load(call_user_func_array('file_get_contents', $args), true);
}

}
?>