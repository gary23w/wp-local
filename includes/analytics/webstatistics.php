<?php

$web_analytics_db = new web_db_manager_stats(TRUE);

if (!function_exists("array_key_last")) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }
        return array_keys($array)[count($array)-1];
    }
}
    $country_to_continent = array ("AD"=>"EU","AE"=>"AS","AF"=>"AS","AG"=>"NA","AI"=>"NA","AL"=>"EU","AM"=>"AS","AN"=>"NA","AO"=>"AF","AP"=>"AS","AR"=>"SA","AS"=>"OC","AT"=>"EU","AU"=>"OC","AW"=>"NA","AX"=>"EU","AZ"=>"AS","BA"=>"EU","BB"=>"NA","BD"=>"AS","BE"=>"EU","BF"=>"AF","BG"=>"EU","BH"=>"AS","BI"=>"AF","BJ"=>"AF","BL"=>"NA","BM"=>"NA","BN"=>"AS","BO"=>"SA","BR"=>"SA","BS"=>"NA","BT"=>"AS","BV"=>"AN","BW"=>"AF","BY"=>"EU","BZ"=>"NA","CA"=>"NA","CC"=>"AS","CD"=>"AF","CF"=>"AF","CG"=>"AF","CH"=>"EU","CI"=>"AF","CK"=>"OC","CL"=>"SA","CM"=>"AF","CN"=>"AS","CO"=>"SA","CR,NA","CU"=>"NA","CV"=>"AF","CX"=>"AS","CY"=>"AS","CZ"=>"EU","DE"=>"EU","DJ"=>"AF","DK"=>"EU","DM"=>"NA","DO"=>"NA","DZ"=>"AF","EC"=>"SA","EE"=>"EU","EG"=>"AF","EH"=>"AF","ER"=>"AF","ES"=>"EU","ET"=>"AF","EU"=>"EU","FI"=>"EU","FJ"=>"OC","FK"=>"SA","FM"=>"OC","FO"=>"EU","FR"=>"EU","FX"=>"EU","GA"=>"AF","GB"=>"EU","GD"=>"NA","GE"=>"AS","GF"=>"SA","GG"=>"EU","GH"=>"AF","GI"=>"EU","GL"=>"NA","GM"=>"AF","GN"=>"AF","GP"=>"NA","GQ"=>"AF","GR"=>"EU","GS"=>"AN","GT"=>"NA","GU"=>"OC","GW"=>"AF","GY"=>"SA","HK"=>"AS","HM"=>"AN","HN"=>"NA","HR"=>"EU","HT"=>"NA","HU"=>"EU","ID"=>"AS","IE"=>"EU","IL"=>"AS","IM"=>"EU","IN"=>"AS","IO"=>"AS","IQ"=>"AS","IR"=>"AS","IS"=>"EU","IT"=>"EU","JE"=>"EU","JM"=>"NA","JO"=>"AS","JP"=>"AS","KE"=>"AF","KG"=>"AS","KH"=>"AS","KI"=>"OC","KM"=>"AF","KN"=>"NA","KP"=>"AS","KR"=>"AS","KW"=>"AS","KY"=>"NA","KZ"=>"AS","LA"=>"AS","LB"=>"AS","LC"=>"NA","LI"=>"EU","LK"=>"AS","LR"=>"AF","LS"=>"AF","LT"=>"EU","LU"=>"EU","LV"=>"EU","LY"=>"AF","MA"=>"AF","MC"=>"EU","MD"=>"EU","ME"=>"EU","MF"=>"NA","MG"=>"AF","MH"=>"OC","MK"=>"EU","ML"=>"AF","MM"=>"AS","MN"=>"AS","MO"=>"AS","MP"=>"OC","MQ"=>"NA","MR"=>"AF","MS"=>"NA","MT"=>"EU","MU"=>"AF","MV"=>"AS","MW"=>"AF","MX"=>"NA","MY"=>"AS","MZ"=>"AF","NA"=>"AF","NC"=>"OC","NE"=>"AF","NF"=>"OC","NG"=>"AF","NI"=>"NA","NL"=>"EU","NO"=>"EU","NP"=>"AS","NR"=>"OC","NU"=>"OC","NZ"=>"OC","OM"=>"AS","PA"=>"NA","PE"=>"SA","PF"=>"OC","PG"=>"OC","PH"=>"AS","PK"=>"AS","PL"=>"EU","PM"=>"NA","PN"=>"OC","PR"=>"NA","PS"=>"AS","PT"=>"EU","PW"=>"OC","PY"=>"SA","QA"=>"AS","RE"=>"AF","RO"=>"EU","RS"=>"EU","RU"=>"EU","RW"=>"AF","SA"=>"AS","SB"=>"OC","SC"=>"AF","SD"=>"AF","SE"=>"EU","SG"=>"AS","SH"=>"AF","SI"=>"EU","SJ"=>"EU","SK"=>"EU","SL"=>"AF","SM"=>"EU","SN"=>"AF","SO"=>"AF","SR"=>"SA","ST"=>"AF","SV"=>"NA","SY"=>"AS","SZ"=>"AF","TC"=>"NA","TD"=>"AF","TF"=>"AN","TG"=>"AF","TH"=>"AS","TJ"=>"AS","TK"=>"OC","TL"=>"AS","TM"=>"AS","TN"=>"AF","TO"=>"OC","TR"=>"EU","TT"=>"NA","TV"=>"OC","TW"=>"AS","TZ"=>"AF","UA"=>"EU","UG"=>"AF","UM"=>"OC","US"=>"NA","UY"=>"SA","UZ"=>"AS","VA"=>"EU","VC"=>"NA","VE"=>"SA","VG"=>"NA","VI"=>"NA","VN"=>"AS","VU"=>"OC","WF"=>"OC","WS"=>"OC","YE"=>"AS","YT"=>"AF","ZA"=>"AF","ZM"=>"AF","ZW"=>"AF");
    $total_requests = $web_analytics_db->count("wp_gary_requests");
    $total_visitors = $web_analytics_db->count("wp_gary_browsers");
    $total_networks = $web_analytics_db->count("wp_gary_ips");

    // daily
    $daily_visits = $web_analytics_db->count_day();
    $is_mobile_ = 0;
    $is_machine_ = 0;
    $ip_list = array();
    $daily_countrys = array();
    foreach($web_analytics_db->results("SELECT `user_agent`, `ip`, `country` FROM wp_gary_browsers WHERE `time` >= CURDATE()") as $mobileCheck) {
        $is_mobile = analyse_user_agent($mobileCheck["user_agent"]);
            if(!in_array($mobileCheck["ip"], $ip_list)) {
                if(!strpos($mobileCheck["user_agent"], "bot")) {
                    if(strpos($mobileCheck["user_agent"], "mobile")) {
                            $is_mobile_ += 1;
                    } else {
                            $is_machine_ += 1;
                    }
                    
                    array_push($daily_countrys, $mobileCheck["country"]);
            }
        }
        array_push($ip_list, $mobileCheck["ip"]);
    } // not a bot?
    $daily_country_count = array_count_values($daily_countrys);
    $top_countries = array();
    $top_continents = array();
    $total_continents = 0;
    foreach($web_analytics_db->results("SELECT `visitor_country`, COUNT(*) FROM wp_gary_requests GROUP BY `visitor_country` ORDER BY COUNT(*) DESC;") as $country) {
        if($country[0] != "" && $country[0] != null) {
            $top_countries[$country[0]] = $country[1];
            $continent = $country_to_continent[strtoupper($country[0])];
            if(!array_key_exists($continent, $top_continents)) {
                $top_continents[$continent] = $country[1];
                $total_continents = $total_continents + 1;
            } else {
                $top_continents[$continent] = $top_continents[$continent] + $country[1];
            }
        } else {
            $top_countries["?"] = $country[1];
            $top_continents["?"] = $country[1];
        }
    }
    $top_origins = array_merge($top_countries, $top_continents);
    asort($top_origins);
    arsort($top_continents);
    $total_countries = 0;
    $top_countriesvo = array();
    $top_continentsvo = array();
    foreach($web_analytics_db->results("SELECT `country`, COUNT(*) FROM wp_gary_browsers GROUP BY `country` ORDER BY COUNT(*) DESC;") as $country) {
        if($country[0] != "" && $country[0] != null) {
            $top_countriesvo[$country[0]] = $country[1];
            $continent = $country_to_continent[strtoupper($country[0])];
            if(!array_key_exists($continent, $top_continentsvo)) {
                $top_continentsvo[$continent] = $country[1];
            } else {
                $top_continentsvo[$continent] = $top_continentsvo[$continent] + $country[1];
            }
            $total_countries = $total_countries + 1;
        } else {
            $top_countriesvo["?"] = $country[1];
        }
    }
    $top_originsvo = array_merge($top_countriesvo, $top_continentsvo);
    $top_languages = array();
    $total_languages = 0;
    foreach($web_analytics_db->results("SELECT `language`, COUNT(*) FROM wp_gary_browsers GROUP BY `language` ORDER BY COUNT(*) DESC;") as $language) {
        if($language[0] != "" && $language[0] != null) {
            $top_languages[$language[0]] = $language[1];
            $total_languages = $total_languages + 1;
        } else {
            $top_languages["?"] = $language[1];
        }
    }
    $top_useragents = array();
    $top_browsers = array();
    $top_oss = array();
    foreach($web_analytics_db->results("SELECT `user_agent`, COUNT(*) FROM wp_gary_browsers GROUP BY `user_agent` ORDER BY COUNT(*) DESC LIMIT 10;") as $useragent) {
        $top_useragents[$useragent[0]] = $useragent[1];
        $uaa = analyse_user_agent($useragent[0]);
        if(isset($top_browsers[$uaa["browser"]["name"]])) {
            $top_browsers[$uaa["browser"]["name"]] += $useragent[1];
        } else {
            $top_browsers[$uaa["browser"]["name"]] = $useragent[1];
        }
        if(isset($top_oss[$uaa["os"]["name"]])) {
            $top_oss[$uaa["os"]["name"]] += $useragent[1];
        } else {
            $top_oss[$uaa["os"]["name"]] = $useragent[1];
        }
    }

    $total_isps = 0;
    $top_isps = array();
    foreach($web_analytics_db->results("SELECT `isp`, COUNT(*) FROM wp_gary_ips GROUP BY `isp` ORDER BY COUNT(*) DESC LIMIT 10;") as $isp) {
        if($isp[0] != "" && $isp[0] != null) {
            $top_isps[$isp[0]] = $isp[1];
            $total_isps++;
        } else {
            $top_isps["?"] = $isp[1];
        }
    }
    $top_uris = array();
    foreach($web_analytics_db->results("SELECT `uri`, COUNT(*) FROM wp_gary_requests GROUP BY `uri` ORDER BY COUNT(*) DESC LIMIT 10;") as $uri) {
        $top_uris[$uri[0]] = $uri[1];
    }
    $last_requests = array();
    $last_requests_by_daytime = array();
    $last_requests_by_day = array();
    $last_requests_by_weekday = array();
    $last_visitors = array();
    $last_visitors_by_daytime = array();
    $last_visitors_by_day = array();
    $last_visitors_by_weekday = array();
    foreach($web_analytics_db->results("SELECT `time`, `browser_id` FROM wp_gary_requests ORDER BY `time` LIMIT 1000;") as $request) {
        $time = $request[0];
        $daytime = date("[H, 0, 0]", strtotime($time));
        $day = date("Y, m, d", strtotime($time));
        $weekday = date("l", strtotime($time));
        if(isset($last_requests[$time])) {
            $last_requests[$time] += 1;
        } else {
            $last_requests[$time] = 1;
        }
        if(isset($last_requests_by_day[$day])) {
            $last_requests_by_day[$day] += 1;
        } else {
            $last_requests_by_day[$day] = 1;
        }
        if(isset($last_requests_by_weekday[$weekday])) {
            $last_requests_by_weekday[$weekday] += 1;
        } else {
            $last_requests_by_weekday[$weekday] = 1;
        }
        if(isset($last_requests_by_daytime[$daytime])) {
            $last_requests_by_daytime[$daytime] += 1;
        } else {
            $last_requests_by_daytime[$daytime] = 1;
        }
        if(isset($last_visitors[$time])) {
            if(!isset($last_visitors[$time][$request[1]])) {
                $last_visitors[$time][$request[1]] = 1;
            }
        } else {
            $last_visitors[$time] = array($request[1] => 1);
        }
        if(isset($last_visitors_by_day[$day])) {
            if(!isset($last_visitors_by_day[$day][$request[1]])) {
                $last_visitors_by_day[$day][$request[1]] = 1;
            }
        } else {
            $last_visitors_by_day[$day] = array($request[1] => 1);
        }
        if(isset($last_visitors_by_weekday[$weekday])) {
            if(!isset($last_visitors_by_weekday[$weekday][$request[1]])) {
                $last_visitors_by_weekday[$weekday][$request[1]] = 1;
            }
        } else {
                $last_visitors_by_weekday[$weekday] = array($request[1] => 1);
        }
        if(isset($last_visitors_by_daytime[$daytime])) {
            if(!isset($last_visitors_by_daytime[$daytime][$request[1]])) {
                $last_visitors_by_daytime[$daytime][$request[1]] = 1;
            }
        } else {
            $last_visitors_by_daytime[$daytime] = array($request[1] => 1);
        }
    }
    ksort($last_requests_by_daytime);
    ksort($last_visitors_by_daytime); 
    //working
    ?>
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="robots" content="noindex,nofollow">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <style>
                .daily_countrys, table, th, td {
                    margin-top: 15px; 
                    border: 1px solid black;
                }
                .card {
                    position: relative;
                    width: 100%;
                    top: 5%;
                    left: 5%;
                }
                </style>
                </head>
                <body>
                <h1 class="ortho_admin_header">&nbsp;</h1>
                <div class="card text-center">
                <div class="card-header">
                    Daily Analytics. <?php echo date('m-d-Y', time()); ?>
                </div>
                <div class="card-body">
                <h2>Today.</h2>
                                            <ul class="list-group">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Requests
                                                    <span class="badge badge-primary badge-pill"><?php echo $daily_visits; ?></span>
                                                </li>
                                            </ul>
                                            <ul class="list-group">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Desktop
                                                    <span class="badge badge-primary badge-pill"><?php echo $is_machine_; ?></span>
                                                </li>
                                            </ul>
                                            <ul class="list-group">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Mobile
                                                    <span class="badge badge-primary badge-pill"><?php echo $is_mobile_; ?></span>
                                                </li>
                                            </ul>
                                            <div class="daily_countrys" align="center">
                                            <table>
                                                <th width="100px">Country</th>
                                                <th width="100px">Visits</th>
                                            <tbody>
                                                    <?php
                                                        foreach($daily_country_count as $key => $value) {
                                                            echo "<tr>";
                                                            echo "<td>" . $key . "</td>";
                                                            echo "<td>" . $value . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    ?>
                                            </tbody>
                                            </table>
                                            </div>
                </div>
                <div class="card-footer text-muted">
                    <hr />
                </div>
                </div>
                <div class="card text-center">
                <div class="card-body">
                <h2>More Statistics.</h2>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Requests(no filters)
                                    <span class="badge badge-primary badge-pill"><?php echo $total_requests; ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Networks
                                    <span class="badge badge-primary badge-pill"><?php echo $total_networks; ?></span>
                                </li>
                            </ul>
                            </div>
                            <div class="card-footer text-muted">
                        <hr />
                    </div>
                </div>
        </body>
    </html>
    <?php

/* UAA */

function analyse_user_agent($user_agent) {
    $result = array();
    $gecko = preg_match("/Mozilla\/\d[\d.]* \([A-Za-z0-9_.\- ;:\/]*\) Gecko\/\d+/i", $user_agent);
    $webkit = preg_match("/Mozilla\/\d[\d.]* \([A-Za-z0-9_.\- ;:\/]*\) AppleWebKit\/\d[\d.]* \(KHTML, like Gecko\)/i", $user_agent);
    if(preg_match_all("/\w+\/\d[\d.]*/", $user_agent, $matches)) {
        $browser = preg_split("/\//",$matches[0][array_key_last($matches[0])]);
        $trident = (preg_match("/trident/i", $browser[0]) && !$gecko && !$webkit);
        if($webkit) {
            if(preg_match("/safari/i", $browser[0])) {
                $browser = preg_split("/\//",$matches[0][2]);
                $i = 3;
                while((preg_match("/version/i", $browser[0]) || preg_match("/mobile/i", $browser[0])) && isset($matches[0][$i])) {
                    $browser = preg_split("/\//",$matches[0][$i]);
                    $i++;
                }
            }
        }
    }
    if(preg_match("/\([A-Za-z0-9_.\- ;:\/]*\)/", $user_agent, $match)) {
        $platforms = preg_split("/; /", preg_replace("/\)/", "", preg_replace("/\(/", "", $match[0])));
        if($trident) {
            $browser = preg_split("/ /",$platforms[1]);
            if(preg_match("/msie/i", $browser[0])) {
                $os = preg_split("/ \d/", preg_replace("/ nt/i", "",$platforms[2]));
                $osv = preg_split("/ /",$platforms[2]);
                if(preg_match("/xbox/i", $platforms[array_key_last($platforms)])) {
                    $result["device"]["name"] = $platforms[array_key_last($platforms)];
                }
            } else {
                $browser[0] = "msie";
                $version = preg_split("/:/", $platforms[array_key_last($platforms)]);
                $browser[1] = $version[1];
            }
        }
        if(preg_match("/windows/i", $platforms[0])) {
            $os = preg_split("/ \d/", preg_replace("/ nt/i", "",$platforms[0]));
            $osv = preg_split("/ /",$platforms[0]);
            if(preg_match("/phone/i", $os[0])) {
                $result["device"]["name"] = $platforms[array_key_last($platforms)-1]." ".$platforms[array_key_last($platforms)];
            }
            if(preg_match("/xbox/i", $platforms[array_key_last($platforms)])) {
                $result["device"]["name"] = $platforms[array_key_last($platforms)];
            }
            if(isset($platforms[2]) && preg_match("/x\d[\d]*/", $platforms[2])) {
                $result["device"]["cpu"] = $platforms[2];
            }
        } else if(preg_match("/linux/i", $platforms[0])) {
            $i = preg_match("/u/i", $platforms[1]) ? 2 : 1;
            $os = preg_split("/ \d/",$platforms[$i]);
            if(preg_match("/android/i", $os[0])) {
                $osv = preg_split("/ /",$platforms[$i]);
            } else {
                $os = preg_split("/ /",$platforms[0]);
                if(isset($os[1])) {
                    $result["device"]["cpu"] = $os[1];
                }
            }
            foreach ($platforms as $property) {
                if(preg_match("/build/i", $property)) {
                    $device = preg_split("/ build/i", $property);
                    $result["device"]["name"] = $device[0];
                }
            }
        } else if(preg_match("/linux/i", $platforms[1]) || preg_match("/cros/i", $platforms[1]) || preg_match("/ubuntu/i", $platforms[1])) {
            $os = preg_split("/ /",$platforms[1]);
            if(isset($os[1])) {
                $result["device"]["cpu"] = $os[1];
            }
        } else if(preg_match("/macintosh/i", $platforms[0])) {
            $os = preg_split("/ \d/",preg_replace("/intel /i", "", $platforms[1]));
            $osv = preg_split("/ /",$platforms[1]);
            $result["device"]["name"] = $platforms[0];
        } else if(preg_match("/iphone/i", $platforms[0]) || preg_match("/ipad/i", $platforms[0]) || preg_match("/ipod/i", $platforms[0])) {
            $os = preg_split("/ \d/",preg_replace("/cpu /i", "", $platforms[1]));
            $osv = preg_split("/ /", preg_replace("/ like mac os x/i", "", $platforms[1]));
            $result["device"]["name"] = $platforms[0];
        } else if(preg_match("/android/i", $platforms[0])) {
            $os = preg_split("/ \d/",$platforms[0]);
            $osv = preg_split("/ /",$platforms[0]);
            $result["device"]["name"] = $platforms[1];
        }
        if(isset($os)) {
            $result["os"]["name"] = $os[0];
        }
        if(isset($osv)) {
            $result["os"]["version"] = $osv[array_key_last($osv)];
        }
    }
    if(isset($browser)) {
        $result["browser"]["name"] = $browser[0];
        $result["browser"]["version"] = $browser[1];
    }

    $result["is_mobile"] = (preg_match('/mobile/i', $$user_agent)) ? 1 : 0;
    $result["is_bot"] = (preg_match('/bot/i', $user_agent) || preg_match('/crawler/i', $user_agent)) ? 1 : 0;
    return $result;
}
// mobile
class web_db_manager_stats {

    public $connected = false;

    // Database Connection
    private $connection = null;

    // Convert the given array to a SQL filter
    function get_filter($filter) {
        if($filter == null) {
            return "";
        }
        $query = " WHERE ";
        $i = 1;
        foreach ($filter as $key => $value) {
            if(isset($value)) {
                $query .= "`".$key."` = '".strval($value)."'";
            } else {
                $query .= "`".$key."` IS NULL";
            }
            if($i != count($filter)) {
                $query .= " AND ";
            }
            $i++;
        }
        return $query;
    }

    function count($table, $filter = null) {
        global $wpdb;
        $result = $wpdb->get_var("SELECT COUNT(*) FROM `".$table."`".$this->get_filter($filter).";");
        return $result;
    }
    function count_to_date($table, $filter = null) {
        global $wpdb;
        $result = $wpdb->get_var("SELECT COUNT(*) FROM `".$table."`;");
        return $result;
    }

    function count_day() {
        global $wpdb;
        $result = $wpdb->get_var("SELECT COUNT(*) FROM wp_gary_ips WHERE `time` >= CURDATE()");
        return $result;
    }

    function count_day_browser($query) {
        global $wpdb;
        $result = $wpdb->get_results($query);
        return $result;
    }

    function count_custom($query) {
        global $wpdb;
        $result = $wpdb->get_var($query);
        return $result;
    }


    // Get the first row that matches the query
    function get_one_row($query) {
        global $wpdb;
        try {
            return $wpdb->get_row($query);            
        } catch (Exception $e) {
            return null;
        }
    }

    // Get the first row that matches the query
    function first($table, $keys, $filter) {
        return $this->get_one_row("SELECT ".$keys." FROM ".$table."".$this->get_filter($filter)." LIMIT 1;");
    }

    // Execute query
    function query($query) {
        global $wpdb;
        return $wpdb->query($query);
    }

    function results($query) {
        global $wpdb;
        $objStd = $wpdb->get_results($query);
        return json_decode(json_encode($objStd), true);
    }

    // Constructor
    function __construct($wordpress) {
        $this->wordpress = $wordpress;
        if($this->wordpress) {
            $this->connected = TRUE;
        }
    } 
}
?>