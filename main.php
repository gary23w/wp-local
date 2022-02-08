<?php
/*
* Plugin Name:       Ortopedisk Utilities
* Version:           v0.1
* Description:       A small plugin to manage daily user analytics.
*/

if(!defined("ABSPATH")) exit;                  
define( 'GARY_PLUGIN_URL', plugins_url('', __FILE__ ) );
define( 'GARY_PLUGIN_URI', plugin_dir_path( __FILE__ ) );
global $ortho_version;
$ortho_version = '1.1';
// Admin
include_once("admin/admin-controller.php");
// Mail
include_once("includes/mail/Mail.php");
// Analytics
include "includes/analytics/webanalytics.php";
include_once("includes/analytics/websettings.php");
// Cron
include "includes/cron/Cron.php";
?>