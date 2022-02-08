<?php
function create_html_content($name, $details, $siteurl, $date, $tpe = null) {
    // $site_stats = stats_dashboard_widget_content();
    $fn = GARY_PLUGIN_URI . 'templates/contents.html';
    $fp = fopen($fn, 'w') or die("unable to open template file: " . $fn);
    ob_start();
    include_once(GARY_PLUGIN_URI . 'includes/analytics/webstatistics.php');
    $content = ob_get_clean();  
    fwrite($fp, $content);
    fclose($fp);
}
?>