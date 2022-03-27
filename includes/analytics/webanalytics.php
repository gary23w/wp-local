<?php
$web_analytics_db = new web_db_manager(TRUE);
$web_analytics_new = new web_analytics($web_analytics_db, $_SERVER, $_COOKIE);

//include "websettings.php";

class web_db_manager {

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
    // Get the first row that matches the query
    function get_one_row($query) {
        global $wpdb;
        $row = $wpdb->get_row( $query );
        //object to array
        $row = (array) $row;
        return $row;
    }
    // Get the first row that matches the query
    function first($table, $keys, $filter) {
        return $this->get_one_row("SELECT ".$keys." FROM ".$table."".$this->get_filter($filter)." LIMIT 1;");
    }
    // Generate unique identifier
    function generate_id($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $id = '';
        for ($i = 0; $i < $length; $i++) {
            $id .= $characters[rand(0, $charactersLength - 1)];
        }
        return $id;
    }

    // Add row to table
    function add($table, $ary) {
        $keys = "";
        $values = "";
        $i = 1;
        foreach ($ary as $key => $value) {
            if($value != null) {
                if($i != 1) {
                    $keys .= ", ";
                    $values .= ", ";
                }
                $keys .= "`".$key."`";
                $values .= "'".strval($value)."'";
                $i++;
            }
        }
        $this->query("INSERT IGNORE INTO ".$table." (".$keys.") VALUES (".$values.");");
    }

    // Delete rows that match the filter
    function delete($table, $filter) {
        $this->query("DELETE FROM ".$table."".$this->get_filter($filter).";");
    }

    // Execute query
    function query($query) {
        global $wpdb;
        return $wpdb->query($query);
    }

    // Create table with given name and fields
    function create_table($name, $keys) {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        // check to see if anything needs repaired.
        $this->query("REPAIR TABLE " . $name);
        $query = "CREATE TABLE IF NOT EXISTS `".$name."` (";
        foreach ($keys as $key => $value) {
            $query .= "`".$key."` ".$value.", ";
        }
        $query .= "`time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
        $query .= "$charset_collate;";
        //$this->query($query);
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $query );
        // $is_error = empty( $wpdb->last_error );
        // return $is_error;
    }

    // Update given fields of rows that match filter
    function update($table, $values, $filter) {
        global $wpdb;
        $query = "UPDATE `".$table."` SET ";
        $i = 1;
        foreach ($values as $key => $value) {
            $query .= "`".$key."` = '".$value."'";
            if($i != count($values)) {
                $query .= ", ";
            }
            $i++;
        }
        $query .= $this->get_filter($filter).";";
        // $wpdb->get_results($query);
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $query );
    }

    // Constructor
    function __construct($wordpress) {
        $this->wordpress = $wordpress;
        if($this->wordpress) {
            $this->connected = TRUE;
        }
    } 
}

// WebAnalytics
class web_analytics {
    // Instance of database manager
    private $db_manager = null;

    // PHP $_SERVER
    private $s = null;

    // HTTP_HOST of $_SERVER
    private $h = null;

    // Domain of HTTP_HOST
    private $d = null;

    // Unique identifier of profile
    private $profile_id = null;

    // User Agent
    private $ua = null;

    // PHP $_COOKIE
    private $c = null;

    // User country code
    private $u_country_code = null;

    // User ip address
    private $u_ip = null;

    // Two character language code of user
    private $u_language = null;

    // Full string of HTTP_ACCEPT_LANGUAGE
    private $a_language = null;

    // Unique browser identifier
    private $ubid = null;

    // Session identifier
    private $session_id = null;

    // ISP
    private $isp = null;

    // Use hostname to determine origin country
    function get_country_by_host($host) {
        // Make sure host is set and not an ip address
        if(isset($host) && filter_var($host, FILTER_VALIDATE_IP) == false) {

            // Split host by dots
            $domain_parts = explode(".", $host);

            // Get TLD (Last element of array)
            $top_level_domain = $domain_parts[count($domain_parts) - 1];

            // Check if TLD is country code
            if(strlen($top_level_domain) == 2) {
                // Return upper case country code
                return strtoupper($top_level_domain);
            }
        }

        // Return null if determination of host origin fails
        return null;
    }

    // Get country code of ip address using RDAP record
    function get_country_by_rdap($query, $real_time_data = false) {
        // Make sure given query is ip address
        if(filter_var($query, FILTER_VALIDATE_IP)) {
            $ip = $query;

            // Perform request depending on whether ip address is ipv4 or ipv6
            if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $iana_ipv4 = ["description"=>"RDAP bootstrap file for IPv4 address allocations","publication"=>"2019-06-07T19:00:02Z","services"=>[[["41.0.0.0/8","102.0.0.0/8","105.0.0.0/8","154.0.0.0/8","196.0.0.0/8","197.0.0.0/8"],["https://rdap.afrinic.net/rdap/","http://rdap.afrinic.net/rdap/"]],[["1.0.0.0/8", "14.0.0.0/8", "27.0.0.0/8", "36.0.0.0/8", "39.0.0.0/8", "42.0.0.0/8", "43.0.0.0/8", "49.0.0.0/8", "58.0.0.0/8", "59.0.0.0/8", "60.0.0.0/8", "61.0.0.0/8", "101.0.0.0/8", "103.0.0.0/8", "106.0.0.0/8", "110.0.0.0/8", "111.0.0.0/8", "112.0.0.0/8", "113.0.0.0/8", "114.0.0.0/8", "115.0.0.0/8", "116.0.0.0/8", "117.0.0.0/8", "118.0.0.0/8", "119.0.0.0/8", "120.0.0.0/8", "121.0.0.0/8", "122.0.0.0/8", "123.0.0.0/8", "124.0.0.0/8", "125.0.0.0/8", "126.0.0.0/8", "133.0.0.0/8", "150.0.0.0/8", "153.0.0.0/8", "163.0.0.0/8", "171.0.0.0/8", "175.0.0.0/8", "180.0.0.0/8", "182.0.0.0/8", "183.0.0.0/8", "202.0.0.0/8", "203.0.0.0/8", "210.0.0.0/8", "211.0.0.0/8", "218.0.0.0/8", "219.0.0.0/8", "220.0.0.0/8", "221.0.0.0/8", "222.0.0.0/8", "223.0.0.0/8"], ["https://rdap.apnic.net/"]], [["3.0.0.0/8", "4.0.0.0/8", "6.0.0.0/8", "7.0.0.0/8", "8.0.0.0/8", "9.0.0.0/8", "11.0.0.0/8", "12.0.0.0/8", "13.0.0.0/8", "15.0.0.0/8", "16.0.0.0/8", "17.0.0.0/8", "18.0.0.0/8", "19.0.0.0/8", "20.0.0.0/8", "21.0.0.0/8", "22.0.0.0/8", "23.0.0.0/8", "24.0.0.0/8", "26.0.0.0/8", "28.0.0.0/8", "29.0.0.0/8", "30.0.0.0/8", "32.0.0.0/8", "33.0.0.0/8", "34.0.0.0/8", "35.0.0.0/8", "38.0.0.0/8", "40.0.0.0/8", "44.0.0.0/8", "45.0.0.0/8", "47.0.0.0/8", "48.0.0.0/8", "50.0.0.0/8", "52.0.0.0/8", "54.0.0.0/8", "55.0.0.0/8", "56.0.0.0/8", "63.0.0.0/8", "64.0.0.0/8", "65.0.0.0/8", "66.0.0.0/8", "67.0.0.0/8", "68.0.0.0/8", "69.0.0.0/8", "70.0.0.0/8", "71.0.0.0/8", "72.0.0.0/8", "73.0.0.0/8", "74.0.0.0/8", "75.0.0.0/8", "76.0.0.0/8", "96.0.0.0/8", "97.0.0.0/8", "98.0.0.0/8", "99.0.0.0/8", "100.0.0.0/8", "104.0.0.0/8", "107.0.0.0/8", "108.0.0.0/8", "128.0.0.0/8", "129.0.0.0/8", "130.0.0.0/8", "131.0.0.0/8", "132.0.0.0/8", "134.0.0.0/8", "135.0.0.0/8", "136.0.0.0/8", "137.0.0.0/8", "138.0.0.0/8", "139.0.0.0/8", "140.0.0.0/8", "142.0.0.0/8", "143.0.0.0/8", "144.0.0.0/8", "146.0.0.0/8", "147.0.0.0/8", "148.0.0.0/8", "149.0.0.0/8", "152.0.0.0/8", "155.0.0.0/8", "156.0.0.0/8", "157.0.0.0/8", "158.0.0.0/8", "159.0.0.0/8", "160.0.0.0/8", "161.0.0.0/8", "162.0.0.0/8", "164.0.0.0/8", "165.0.0.0/8", "166.0.0.0/8", "167.0.0.0/8", "168.0.0.0/8", "169.0.0.0/8", "170.0.0.0/8", "172.0.0.0/8", "173.0.0.0/8", "174.0.0.0/8", "184.0.0.0/8", "192.0.0.0/8", "198.0.0.0/8", "199.0.0.0/8", "204.0.0.0/8", "205.0.0.0/8", "206.0.0.0/8", "207.0.0.0/8", "208.0.0.0/8", "209.0.0.0/8", "214.0.0.0/8", "215.0.0.0/8", "216.0.0.0/8"], ["https://rdap.arin.net/registry/", "http://rdap.arin.net/registry/"]], [["2.0.0.0/8", "5.0.0.0/8", "25.0.0.0/8", "31.0.0.0/8", "37.0.0.0/8", "46.0.0.0/8", "51.0.0.0/8", "53.0.0.0/8", "57.0.0.0/8", "62.0.0.0/8", "77.0.0.0/8", "78.0.0.0/8", "79.0.0.0/8", "80.0.0.0/8", "81.0.0.0/8", "82.0.0.0/8", "83.0.0.0/8", "84.0.0.0/8", "85.0.0.0/8", "86.0.0.0/8", "87.0.0.0/8", "88.0.0.0/8", "89.0.0.0/8", "90.0.0.0/8", "91.0.0.0/8", "92.0.0.0/8", "93.0.0.0/8", "94.0.0.0/8", "95.0.0.0/8", "109.0.0.0/8", "141.0.0.0/8", "145.0.0.0/8", "151.0.0.0/8", "176.0.0.0/8", "178.0.0.0/8", "185.0.0.0/8", "188.0.0.0/8", "193.0.0.0/8", "194.0.0.0/8", "195.0.0.0/8", "212.0.0.0/8", "213.0.0.0/8", "217.0.0.0/8"], ["https://rdap.db.ripe.net/"]], [["177.0.0.0/8", "179.0.0.0/8", "181.0.0.0/8", "186.0.0.0/8", "187.0.0.0/8", "189.0.0.0/8", "190.0.0.0/8", "191.0.0.0/8", "200.0.0.0/8", "201.0.0.0/8"], ["https://rdap.lacnic.net/rdap/"]]], "version"=> "1.0"];
                if($real_time_data) {
                    $iana_ipv4 = file_get_contents("http://data.iana.org/rdap/ipv4.json");
                    if(is_bool($iana_ipv4)) {
                        return null;
                    }
                    $iana_ipv4 = json_decode($iana_ipv4, true);
                }
                $ip_parts = explode(".", $ip);
                foreach ($iana_ipv4["services"] as $service) {
                    foreach ($service[0] as $ip_range) {
                        if($ip_range == $ip_parts[0].".0.0.0/8") {
                            $service_rdap = file_get_contents(preg_replace("/https/i", "http", $service[1][0])."ip/".$ip);
                            if($service_rdap == FALSE) {
                                return null;
                            }
                            $service_rdap = json_decode($service_rdap, true);
                            if(isset($service_rdap["country"])) {
                                return strtoupper($service_rdap["country"]);
                            } else {
                                return null;
                            }
                        }
                    }
                }
            } else if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $iana_ipv6 = ["description"=> "RDAP bootstrap file for IPv6 address allocations", "publication"=> "2019-11-06T19:00:04Z", "services"=> [[["2001:4200::/23", "2c00::/12"], ["https://rdap.afrinic.net/rdap/", "http://rdap.afrinic.net/rdap/"]], [["2001:200::/23", "2001:4400::/23", "2001:8000::/19", "2001:a000::/20", "2001:b000::/20", "2001:c00::/23", "2001:e00::/23", "2400::/12"], ["https://rdap.apnic.net/"]], [["2001:1800::/23", "2001:400::/23", "2001:4800::/23", "2600::/12", "2610::/23", "2620::/23", "2630::/12"], ["https://rdap.arin.net/registry/", "http://rdap.arin.net/registry/"]], [["2001:1400::/22", "2001:1a00::/23", "2001:1c00::/22", "2001:2000::/19", "2001:4000::/23", "2001:4600::/23", "2001:4a00::/23", "2001:4c00::/23", "2001:5000::/20", "2001:600::/23", "2001:800::/22", "2003::/18", "2a00::/12", "2a10::/12"], ["https://rdap.db.ripe.net/"]], [["2001:1200::/23", "2800::/12"], ["https://rdap.lacnic.net/rdap/"]]], "version"=>"1.0"];
                if($real_time_data) {
                    $iana_ipv6 = file_get_contents("http://data.iana.org/rdap/ipv6.json");
                    if (is_bool($iana_ipv6)) {
                        return null;
                    }
                    $iana_ipv6 = json_decode($iana_ipv6, true);
                }
                $ip_parts = explode(":", $ip);
                foreach ($iana_ipv6["services"] as $service) {
                    foreach ($service[0] as $ip_range) {
                        if(preg_match("/".$ip_parts[0].":".$ip_parts[1]."::\/\d[\d]*/", $ip_range) || preg_match("/".$ip_parts[0]."::\/\d[\d]*/", $ip_range)) {
                            $service_rdap = file_get_contents(preg_replace("/https/i", "http", $service[1][0])."ip/".$ip);
                            if($service_rdap == FALSE) {
                                return null;
                            }
                            $service_rdap = json_decode($service_rdap, true);
                            if(isset($service_rdap["country"])) {
                                return strtoupper($service_rdap["country"]);
                            } else {
                                return null;
                            }
                        }
                    }
                }
            }
        }
        return null;
    }

    // Anonymize ip address
    function anonymize_ip($ip) {
        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip_parts = explode(":", $ip);
            if(count($ip_parts) == 8) {
                $ip = $ip_parts[0].":".$ip_parts[1].":".$ip_parts[2]."::";
            } else {
                if($ip_parts[2] == "") {
                    $ip = $ip_parts[0].":".$ip_parts[1]."::";
                } else if($ip_parts[1] == "") {
                    $ip = $ip_parts[0]."::";
                } else {
                    $ip = $ip_parts[0].":".$ip_parts[1].":".$ip_parts[2]."::";
                }
            }
        } else if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ip_parts = explode(".", $ip);
            if(count($ip_parts) == 4) {
                $ip = $ip_parts[0].".".$ip_parts[1].".".$ip_parts[2].".0";
            }
        }
        return $ip;
    }

    // Create required tables in given database if not existing
    function check_database() {
        $this->db_manager->create_table("wp_gary_ips", [
            "ip" => "VARCHAR(45) PRIMARY KEY",
            "host" => "VARCHAR(253)",
            "country" => "VARCHAR(2)",
            "isp" => "VARCHAR(127)",
            "last_update" => "TIMESTAMP NULL"
        ]);
        $this->db_manager->create_table("wp_gary_profiles", [
            "id" => "VARCHAR(20) PRIMARY KEY",
            "screen_width" => "VARCHAR(9)",
            "screen_height" => "VARCHAR(9)",
            "interface_width" => "VARCHAR(9)",
            "interface_height" => "VARCHAR(9)",
            "color_depth" => "VARCHAR(7)",
            "pixel_depth" => "VARCHAR(7)",
            "cookies_enabled" => "VARCHAR(5)",
            "java_enabled" => "VARCHAR(5)"
        ]);
        $this->db_manager->create_table("wp_gary_trackers", [
            "id" => "VARCHAR(20) PRIMARY KEY",
            "domain" => "TEXT",
            "browser_id" => "VARCHAR(15) NOT NULL",
            "user_agent" => "TEXT",
            "fingerprint" => "TEXT"
        ]);
        if($this->db_manager->get_one_row("SHOW COLUMNS FROM `wp_gary_trackers` LIKE 'fingerprint'") == null) {
            $this->db_manager->query("ALTER TABLE `wp_gary_trackers` ADD `fingerprint` TEXT AFTER `user_agent`;");
        }
        $this->db_manager->create_table("wp_gary_browsers", [
            "id" => "VARCHAR(20) PRIMARY KEY",
            "ip" => "VARCHAR(45) NOT NULL",
            "country" => "VARCHAR(2)",
            "language" => "VARCHAR(2)",
            "accept_language" => "TEXT",
            "user_agent" => "TEXT",
            "profile_id" => "VARCHAR(10)",
            "last_update" => "TIMESTAMP NULL"
        ]);
        $this->db_manager->create_table("wp_gary_sessions", [
            "id" => "VARCHAR(20) PRIMARY KEY",
            "browser_id" => "VARCHAR(15)",
            "last_update" => "TIMESTAMP NULL"
        ]);
        $this->db_manager->create_table("wp_gary_requests", [
            "id" => "VARCHAR(20) PRIMARY KEY",
            "accept" => "TEXT",
            "protocol" => "TEXT",
            "port" => "INT(6)",
            "host" => "VARCHAR(253)",
            "uri" => "TEXT",
            "referrer" => "TEXT",
            "visitor_ip" => "VARCHAR(45)",
            "visitor_country" => "VARCHAR(2)",
            "cf_ray_id" => "TEXT",
            "user_agent" => "TEXT",
            "language" => "VARCHAR(2)",
            "accept_language" => "TEXT",
            "browser_id" => "VARCHAR(15)",
            "session_id" => "VARCHAR(20)"
        ]);
    }
    
    // Get ISP's unique id
    function get_isp($host) {
        if(isset($host) && filter_var($host, FILTER_VALIDATE_IP) == false) {
            $domain_parts = explode(".", $host);
            if(count($domain_parts) >= 2) {
                return $domain_parts[count($domain_parts) - 2] . "." . $domain_parts[count($domain_parts) - 1];
            }
        }
        return null;
    }

    function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
       // $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
    
    
    // Get network's unique id
    function save_ip($ip, $anonymize = FALSE) {
        if(!isset($ip)) {
            return null;
        }
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $host = gethostbyaddr($ip);
        }
        $this->isp = $this->get_isp($host);
        //$this->u_country_code = $this->ip_info($ip, "Country Name");       
        $this->u_country_code = $this->ip_info($ip, "countrycode");       
        if($anonymize) {
            $ip = $this->anonymize_ip($ip);
            $host = null;
        }
        $this->db_manager->add("wp_gary_ips", [
            "ip" => $ip,
            "host" => $host,
            "country" => $this->u_country_code,
            "isp" => $this->isp
        ]);
        return $ip;
    }
    
    // Use cookies set by tracking script to get device's unique profile id
    function get_profile() {
        if(!isset($this->c["device_profile"]) && !isset($this->c["browser_profile"])) {
            return null;
        }
        $c_profile = array_merge(json_decode($this->c["device_profile"], true), json_decode($this->c["browser_profile"], true));
        $search_keys = ["screen_width", "screen_height", "interface_width", "interface_height", "color_depth", "pixel_depth", "cookies_enabled", "java_enabled"];
        $search_query = "";
        $search_count = 0;
        $profile = array("id" => $this->db_manager->generate_id());
        foreach ($search_keys as $key) {
            if($search_count != 0) {
                $search_query .= " AND ";
            }
            if(isset($c_profile[$key]) && $c_profile[$key] != null) {
                $profile[$key] = $c_profile[$key];
                $search_query .= "".$key." = '".strval($profile[$key])."'";
            } else {
                $search_query .= "".$key." IS NULL";
            }
            $search_count++;
        }
        $row = $this->db_manager->get_one_row("SELECT id FROM wp_gary_profiles WHERE ".$search_query." LIMIT 1;");
        if($row != null) {
            return $row["id"];
        }
        $this->db_manager->add("wp_gary_profiles", $profile);
        return $profile["id"];
    }

    // Generate fingerprint based on available data of browser
    function get_fingerprint() {
        return hash("sha256", $this->u_ip.$this->u_country_code.$this->isp.$this->a_language.$this->ua.$this->profile_id);
    }
    
    // Identify the user and update information
    function identify_browser() {
        $row = null;
        if(isset($this->c["garyid"]) && strlen($this->c["garyid"]) == 20) {
            $row = $this->db_manager->first("wp_gary_trackers", "id,browser_id", ["id" => $this->c["garyid"], "domain" => $this->d]);
        }
        if($row == null) $row = $this->db_manager->first("wp_gary_trackers", "id,browser_id", ["fingerprint" => $this->get_fingerprint(), "domain" => $this->d]);
        if($row != null) {
            $this->db_manager->update("wp_gary_trackers", ["time" => date('Y-m-d H:i:s')], ["id" => $row["id"]]);
            if($this->db_manager->first("wp_gary_browsers", "id", ["id" => $row["browser_id"]]) != null) {
                setcookie("garyid", $row["id"], time()+60*60*24*180, "/", $this->d);
                $this->db_manager->update("wp_gary_browsers", [
                    "ip" => $this->u_ip,
                    "profile_id" => $this->profile_id,
                    "language" => $this->u_language,
                    "accept_language" => $this->a_language,
                    "user_agent" => $this->ua,
                    "last_update" => date('Y-m-d H:i:s')
                ], array("id" => $row["browser_id"]));
                return $row["browser_id"];
            }
        }
        $cid = $this->db_manager->generate_id(20);
        $ubid = $this->db_manager->generate_id(15);
        $this->db_manager->add("wp_gary_trackers", [
            "id" => $cid,
            "domain" => $this->d,
            "browser_id" => $ubid,
            "user_agent" => $this->ua,
            "fingerprint" => $this->get_fingerprint()
        ]);
        setcookie("garyid", $cid, time()+60*60*24*180, "/", $this->d);
        $this->db_manager->add("wp_gary_browsers", [
            "id" => $ubid,
            "ip" => $this->u_ip,
            "country" => $this->u_country_code,
            "language" => $this->u_language,
            "accept_language" => $this->a_language,
            "user_agent" => $this->ua,
            "profile_id" => $this->profile_id
        ]);
        return $ubid;
    }

    function get_session($browser_id) {
        $row = $this->db_manager->get_one_row("SELECT id FROM wp_gary_sessions WHERE browser_id = '".$browser_id."' AND (last_update >= '".date('Y-m-d H:i:s', strtotime("-30 minutes"))."' OR `time` >= '".date('Y-m-d H:i:s', strtotime("-30 minutes"))."');");
        if($row != null) {
            $this->db_manager->update("wp_gary_trackers", ["last_update" => date('Y-m-d H:i:s')], ["id" => $row["id"]]);
            return $row["id"];
        }
        $id = $this->db_manager->generate_id(20);
        $this->db_manager->add("wp_gary_sessions", [
            "id" => $id,
            "browser_id" => $browser_id
        ]);
        return $id;
    }
    
    // Get information about the request and add it to the database
    function save_request() {
        $this->db_manager->add("wp_gary_requests", [
            "id" => $this->db_manager->generate_id(20),
            "accept" => isset($this->d['HTTP_ACCEPT']) ? "".explode(",", $this->s['HTTP_ACCEPT'])[0]."" : null,
            "protocol" => isset($this->s['REQUEST_SCHEME']) ? $this->s["REQUEST_SCHEME"] : null,
            "port" => isset($this->s["SERVER_PORT"]) ? $this->s['SERVER_PORT'] : null,
            "host" => strtolower($this->h),
            "uri" => isset($this->s["REQUEST_URI"]) ? $this->s["REQUEST_URI"] : null,
            "referrer" => isset($this->s["HTTP_REFERER"]) ? $this->s["HTTP_REFERER"] : null,
            "visitor_ip" => $this->u_ip,
            "visitor_country" => $this->u_country_code,
            "cf_ray_id" => isset($this->s["HTTP_CF_RAY"]) ? $this->s["HTTP_CF_RAY"] : null,
            "user_agent" => $this->ua,
            "language" => $this->u_language,
            "accept_language" => $this->a_language,
            "browser_id" => $this->ubid,
            "session_id" => $this->session_id
        ]);
    }

    // SET anonymize_ip to TRUE if working out of EU.
    function __construct(web_db_manager $db_manager, $server, $cookies, $anonymize_ip = FALSE) {
        if($db_manager->connected) {
            $this->db_manager = $db_manager;
            $this->s = $server;
            $this->ua = isset($this->s['HTTP_USER_AGENT']) ? strtolower($this->s['HTTP_USER_AGENT']) : null;
            // check if user agent is a bot.
            if(strpos($this->ua, "bot") == true) {
                exit;
            }
            $this->c = $cookies;
            if(isset($this->s["HTTP_HOST"])) {
                $this->h = $this->s["HTTP_HOST"];
                $domain = strtolower($this->h);
                if(filter_var($domain, FILTER_VALIDATE_IP) == false && $domain != "localhost") {
                    $domain_parts = explode(".", $domain);
                    $this->d = $domain_parts[count($domain_parts) - 2] . "." . $domain_parts[count($domain_parts) - 1];
                } else { $this->d = $domain; }
            }
            $this->a_language = isset($this->s["HTTP_ACCEPT_LANGUAGE"]) ? $this->s['HTTP_ACCEPT_LANGUAGE'] : null;
            $this->u_language = isset($this->s["HTTP_ACCEPT_LANGUAGE"]) ? substr($this->s['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
            $this->check_database();
            if(isset($this->s["HTTP_X_FORWARDED_FOR"])) {
                $this->u_ip = $this->save_ip($this->s['HTTP_X_FORWARDED_FOR'], $anonymize_ip);
            } else {
                $this->u_ip = $this->save_ip($this->s['REMOTE_ADDR'], $anonymize_ip);
            }
            $this->profile_id = $this->get_profile();
            $this->ubid = $this->identify_browser();
            $this->session_id = $this->get_session($this->ubid);
            $this->save_request();
        } else {
            error_log("WebAnalytics unable to connect to database\n");
        }
    }
}