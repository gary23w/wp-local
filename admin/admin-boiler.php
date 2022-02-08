<?php
        ob_start();
        include_once(GARY_PLUGIN_URI . 'includes/analytics/webstatistics.php');
        $content = ob_get_clean();  
        echo $content;
?>