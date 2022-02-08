<?php
$ortho_available_pages = array('home', 'boiler', 'utils');
$home_page = "no_highlight";
$stat_page = "no_highlight";
$settings_page = "no_highlight";
$currentPage = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
switch(true) {
	case strpos($currentPage, "&o=boiler") !== false:
		$home_page = "active_button";
		break;
	case strpos($currentPage, "&o=home") !== false:
		$stat_page = "active_button";
		break;
}
?>
<script>
function loadBug() {
  jQuery(".ortho_admin_settings").toggle(200);
  //jQuery(".ortho_admin_settings").toggle(50);
}
setTimeout(loadBug, 20);
</script>
<div class="ortho_admin_settings clearfix" style="display: none;">
	<div class="ortho_admin_menu">
		<h1 class="ortho_admin_header">&nbsp;</h1>
		<ul class="ad_menu_">
				<li class="<?php print $stat_page; ?>"><a class="menu_button" href="<?php print ortho_options_save_url('&o=home');?>">Settings</a></li>
				<li class="<?php print $home_page; ?>"><a class="menu_button" href="<?php print ortho_options_save_url('&o=boiler');?>">Stats</a></li>
				<li class="<?php print $home_page; ?>"><a class="menu_button" href="<?php print ortho_options_save_url('&o=utils');?>">Testing</a></li>
		</ul>
	</div>
	<div class="ortho_admin_content">
		<?php
		
		$ortho_current = isset($_REQUEST['o']) ? $_REQUEST['o'] : 'home';
		$ortho_current = strtolower($ortho_current);

		if( $ortho_current != '' && in_array($ortho_current,$ortho_available_pages)) {
			include_once('admin_func.php');
			define('ortho_OPTPANEL',true);
			include_once('admin-' . $ortho_current . '.php');
			do_action('ortho_admin_panel');	
			
		} else {
			?>
            <p>//silence is golden</p>
			<?php
		}
		?>
		
		<!-- / END ADMIN CONTENT -->
	
	</div>
	
</div>
<!-- / END ADMIN PANEL -->