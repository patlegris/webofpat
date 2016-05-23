<?php
/**
 * Plugin Name: WordPress Portfolio Plugin
 * Plugin URI: http://wordpress.org/extend/plugins/wp-portfolio/
 * Description: A plugin that allows you to show off your portfolio through a single page on your WordPress website with automatically generated thumbnails. To show your portfolio, create a new page and paste [wp-portfolio] into it. The plugin requires you to have a free account with <a href="http://www.shrinktheweb.com/">Shrink The Web</a> to generate the thumbnails.
 * Version: 1.38
 * Author: The WordPress Doctors
 * Author URI: http://www.wpdoctors.co.uk 
 
 * This plugin is licensed under the Apache 2 License
 * http://www.apache.org/licenses/LICENSE-2.0
 */


// Admin Only
if (is_admin()) 
{
	include_once('wplib/utils_pagebuilder.inc.php');
	include_once('wplib/utils_formbuilder.inc.php');
	include_once('wplib/utils_tablebuilder.inc.php');
		
	include_once('lib/admin_only.inc.php');
}

// Common 
include_once('wplib/utils_sql.inc.php');

// Common
include_once('lib/thumbnailer.inc.php');
include_once('lib/widget.inc.php');
include_once('lib/utils.inc.php');


/* Load translation files */
load_plugin_textdomain('wp-portfolio', false, basename( dirname( __FILE__ ) ) . '/languages' );


/** Constant: The current version of the database needed by this version of the plugin.  */
define('WPP_VERSION', 							'1.38');



/** Constant: The string used to determine when to render a group name. */
define('WPP_STR_GROUP_NAME', 					'%GROUP_NAME%');

/** Constant: The string used to determine when to render a group description. */
define('WPP_STR_GROUP_DESCRIPTION', 	 		'%GROUP_DESCRIPTION%');

/** Constant: The string used to determine when to render a website name. */
define('WPP_STR_WEBSITE_NAME', 	 				'%WEBSITE_NAME%');

/** Constant: The string used to determine when to render a website thumbnail image. */
define('WPP_STR_WEBSITE_THUMBNAIL', 	 		'%WEBSITE_THUMBNAIL%');

/** Constant: The string used to determine when to render a website thumbnail image URL. */
define('WPP_STR_WEBSITE_THUMBNAIL_URL', 	 	'%WEBSITE_THUMBNAIL_URL%');

/** Constant: The string used to determine when to render a website url. */
define('WPP_STR_WEBSITE_URL', 	 				'%WEBSITE_URL%');

/** Constant: The string used to determine when to render a website description. */
define('WPP_STR_WEBSITE_DESCRIPTION', 	 		'%WEBSITE_DESCRIPTION%');

/** Constant: The string used to determine when to render a custom field value. */
define('WPP_STR_WEBSITE_CUSTOM_FIELD', 	 		'%WEBSITE_CUSTOM_FIELD%');

/** Constant: Default HTML to render a group. */
define('WPP_DEFAULT_GROUP_TEMPLATE', 			
"<h2>%GROUP_NAME%</h2>
<p>%GROUP_DESCRIPTION%</p>");

/** Constant: Default HTML to render a website. */
define('WPP_DEFAULT_WEBSITE_TEMPLATE', 			
"<div class=\"portfolio-website\">
    <div class=\"website-thumbnail\"><a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_THUMBNAIL%</a></div>
    <div class=\"website-name\"><a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_NAME%</a></div>
    <div class=\"website-url\"><a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_URL%</a></div>
    <div class=\"website-description\">%WEBSITE_DESCRIPTION%</div>
    <div class=\"website-clear\"></div>
</div>");

/** Constant: Default HTML to render a website in the widget area. */
define('WPP_DEFAULT_WIDGET_TEMPLATE', 			
"<div class=\"widget-portfolio\">
    <div class=\"widget-website-thumbnail\">
    	<a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_THUMBNAIL%</a>
    </div>
    <div class=\"widget-website-name\">
    	<a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_NAME%</a>
    </div>
    <div class=\"widget-website-description\">
    	%WEBSITE_DESCRIPTION%
    </div>
    <div class=\"widget-website-clear\"></div>
</div>");

/** Constant: Default HTML to render the paging for the websites. */
define('WPP_DEFAULT_PAGING_TEMPLATE', '
<div class="portfolio-paging">
	<div class="page-count">Showing page %PAGING_PAGE_CURRENT% of %PAGING_PAGE_TOTAL%</div>
	%LINK_PREVIOUS% %PAGE_NUMBERS% %LINK_NEXT%
</div>
');


define('WPP_DEFAULT_PAGING_TEMPLATE_PREVIOUS', 	__('Previous', 'wp-portfolio'));
define('WPP_DEFAULT_PAGING_TEMPLATE_NEXT', 		__('Next', 'wp-portfolio'));

/** Constant: Default CSS to style the portfolio. */
define('WPP_DEFAULT_CSS',"
.portfolio-website {
	padding: 10px;
	margin-bottom: 10px;
}
.website-thumbnail {
	float: left;
	margin: 0 20px 20px 0;
}
.website-thumbnail img {
	border: 1px solid #555;
	margin: 0;
	padding: 0;
}
.website-name {
	font-size: 12pt;
	font-weight: bold;
	margin-bottom: 3px;
}
.website-name a,.website-url a {
	text-decoration: none;
}
.website-name a:hover,.website-url a:hover {
	text-decoration: underline;
}
.website-url {
	font-size: 9pt;
	font-weight: bold;
}
.website-url a {
	color: #777;
}
.website-description {
	margin-top: 15px;
}
.website-clear {
	clear: both;
}");

/** Constant: Default CSS to style the paging feature. */
define('WPP_DEFAULT_CSS_PAGING',"
.portfolio-paging {
	text-align: center;
	padding: 4px 10px 4px 10px;
	margin: 0 10px 20px 10px;
}
.portfolio-paging .page-count {
	margin-bottom: 5px;
}
.portfolio-paging .page-jump b {
	padding: 5px;
}
.portfolio-paging .page-jump a {
	text-decoration: none;
}");


/** Constant: Default CSS to style the widget feature. */
define('WPP_DEFAULT_CSS_WIDGET',"
.wp-portfolio-widget-des {
	margin: 8px 0;
	font-size: 110%;
}
.widget-website {
	border: 1px solid #AAA;
	padding: 3px 10px;
	margin: 0 5px 10px;
}
.widget-website-name {
	font-size: 120%;
	font-weight: bold;
	margin-bottom: 5px;
}
.widget-website-description {
	line-height: 1.1em;
}
.widget-website-thumbnail {
	margin: 10px auto 6px auto;
	width: 102px;
}
.widget-website-thumbnail img {
	width: 100px;
	border: 1px solid #555;
	margin: 0;
	padding: 0;
}
.widget-website-clear {
	clear: both;
	height: 1px;
}");


/** Constant: The name of the table to store the website information. */
define('TABLE_WEBSITES', 						'WPPortfolio_websites');

/** Constant: The name of the table to store the custom site information. */
define('TABLE_WEBSITES_META', 						TABLE_WEBSITES.'_meta');

/** Constant: The name of the table to store the website information. */
define('TABLE_WEBSITE_GROUPS', 					'WPPortfolio_groups');

/** Constant: The name of the table to store the debug information. */
define('TABLE_WEBSITE_DEBUG', 					'WPPortfolio_debuglog');

/** Contstant: The path to use to store the cached thumbnails. */
define('WPP_THUMBNAIL_PATH',					'wp-portfolio/cache');

/** Contstant: The name of the setting with the cache setting. */
define('WPP_CACHE_SETTING', 					'WPPortfolio_cache_location');

/** Contstant: The name of the setting with the thumbnail refresh time. */
define('WPP_STW_REFRESH_TIME', 					'WPPortfolio_thumbnail_refresh_time');


/** Contstant: The path to use to store the cached thumbnails. */
define('WPP_THUMB_DEFAULTS',					'wp-portfolio/imgs/thumbnail_');

/** Constant: URL location for settings page. */
define('WPP_SETTINGS', 							'admin.php?page=WPP_show_settings');

/** Constant: URL location for settings page. */
define('WPP_DOCUMENTATION', 					'admin.php?page=WPP_show_documentation');

/** Constant: URL location for website summary. */
define('WPP_WEBSITE_SUMMARY', 					'admin.php?page=wp-portfolio/wp-portfolio.php');

/** Constant: URL location for modifying a website entry. */
define('WPP_MODIFY_WEBSITE', 					'admin.php?page=WPP_modify_website');

/** Constant: URL location for showing the list of groups in the portfolio. */
define('WPP_GROUP_SUMMARY', 					'admin.php?page=WPP_website_groups');

/** Constant: URL location for modifying a group entry. */
define('WPP_MODIFY_GROUP', 						'admin.php?page=WPP_modify_group');



/**
 * Function: WPPortfolio_menu()
 *
 * Creates the menu with all of the configuration settings.
 */

function WPPortfolio_menu()
{
	add_menu_page('WP Portfolio - Summary of Websites in your Portfolio', 'WP Portfolio', 'manage_options', __FILE__, 'WPPortfolio_show_websites');
	
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Modify Website', 'wp-portfolio'), 		'Add New Website', 		'manage_options', 'WPP_modify_website', 'WPPortfolio_modify_website');
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Modify Group', 'wp-portfolio'), 		'Add New Group', 		'manage_options', 'WPP_modify_group', 'WPPortfolio_modify_group');
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Groups', 'wp-portfolio'), 				'Website Groups', 		'manage_options', 'WPP_website_groups', 'WPPortfolio_show_website_groups');		
	
	// Spacer
	add_submenu_page(__FILE__, false, '<span class="wpp_menu_section" style="display: block; margin: 1px 0 1px -5px; padding: 0; height: 1px; line-height: 1px; background: #CCC;"></span>', 'manage_options', '#', false);	
	
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('General Settings', 'wp-portfolio'), 	'Portfolio Settings', 	'manage_options', 'WPP_show_settings', 'WPPortfolio_pages_showSettings');
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Layout Settings', 'wp-portfolio'), 	'Layout Settings', 		'manage_options', 'WPP_show_layout_settings', 'WPPortfolio_pages_showLayoutSettings');
	
	// Spacer
	add_submenu_page(__FILE__, false, '<span class="wpp_menu_section" style="display: block; margin: 1px 0 1px -5px; padding: 0; height: 1px; line-height: 1px; background: #CCC;"></span>', 'manage_options', '#', false);
	
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Refresh Thumbnails', 'wp-portfolio'), 	__('Refresh Thumbnails', 'wp-portfolio'), 	'manage_options', 'WPP_show_refreshThumbnails', 'WPPortfolio_pages_showRefreshThumbnails');
	
	// Spacer
	add_submenu_page(__FILE__, false, '<span class="wpp_menu_section" style="display: block; margin: 1px 0 1px -5px; padding: 0; height: 1px; line-height: 1px; background: #CCC;"></span>', 'manage_options', '#', false);
	
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Documentation', 'wp-portfolio'), 		'Documentation', 		'manage_options', 'WPP_show_documentation', 'WPPortfolio_pages_showDocumentation');

	$errorCount = WPPortfolio_errors_getErrorCount();
	$errorCountMsg = false;
	if ($errorCount > 0) {
		$errorCountMsg = sprintf('<span title="%d Error" class="update-plugins"><span class="update-count">%d</span></span>', $errorCount, $errorCount);
	}
	
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Error Logs', 'wp-portfolio'), 		__('Error Logs', 'wp-portfolio').$errorCountMsg, 'manage_options', 'WPP_show_error_page', 'WPPortfolio_showErrorPage');
}


/**
 * Functions called when plugin initialises with WordPress.
 */
function WPPortfolio_init()
{	
	// Backend
	if (is_admin())
	{
		// Warning boxes in admin area only
		// Not needed, no messages currently.
		//add_action('admin_notices', 'WPPortfolio_messages');
		
		// Menus
		add_action('admin_menu', 'WPPortfolio_menu');
		
		// Scripts and styles
		add_action('admin_print_scripts', 'WPPortfolio_scripts_Backend'); 
		add_action('admin_print_styles',  'WPPortfolio_styles_Backend');	
	}
	
	// Frontend
	else {
		
		// Scripts and styles
		add_action('wp_head', 'WPPortfolio_styles_frontend_renderCSS');
		WPPortfolio_scripts_Frontend();
	}
	
	// Common
	// Add settings link to plugins page
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'WPPortfolio_plugin_addSettingsLink');
}
add_action('init', 'WPPortfolio_init');



/**
 * Messages to show the user in the admin area.
 */
function WPPortfolio_messages()
{
}


/**
 * Determine if we're on a page just related to WP Portfolio in the admin area.
 * @return Boolean True if we're on a WP Portfolio admin page, false otherwise.
 */
function WPPortfolio_areWeOnWPPPage()
{
	if (isset($_GET) && isset($_GET['page']))
	{ 
		$currentPage = $_GET['page'];
		
		// This handles any WPPortfolio page.
		if ($currentPage == 'wp-portfolio/wp-portfolio.php' || substr($currentPage, 0, 4) == 'WPP_') {
			return true;
		}	
	}
	 
	return false;
}







/**
 * Return the list of settings for this plugin.
 * @return Array The list of settings and their default values.
 */
function WPPortfolio_getSettingList($general = true, $style = true)
{
	$generalSettings = array(
		'setting_stw_access_key' 		=> false,
		'setting_stw_secret_key' 		=> false,
		'setting_stw_account_type'		=> false,
		'setting_stw_render_type'		=> 'embedded',
		'setting_stw_thumb_size' 		=> 'lg',
		'setting_stw_thumb_size_type'	=> 'standard',
		'setting_stw_thumb_size_custom' => '300',
		'setting_cache_days'	 		=> 7,
		'setting_fetch_method' 			=> 'curl',
		'setting_show_credit' 			=> 'on',	
		'setting_enable_debug'			=> false,
		'setting_scale_type'			=> 'scale-both',
	);
	
	$styleSettings = array(
		'setting_template_website'			=> WPP_DEFAULT_WEBSITE_TEMPLATE,
		'setting_template_group'			=> WPP_DEFAULT_GROUP_TEMPLATE,
		'setting_template_css'				=> WPP_DEFAULT_CSS,
		'setting_template_css_paging'		=> WPP_DEFAULT_CSS_PAGING,
		'setting_template_css_widget'		=> WPP_DEFAULT_CSS_WIDGET,
		'setting_disable_plugin_css'		=> false,
		'setting_template_paging'			=> WPP_DEFAULT_PAGING_TEMPLATE,
		'setting_template_paging_previous'	=> WPP_DEFAULT_PAGING_TEMPLATE_PREVIOUS,
		'setting_template_paging_next'		=> WPP_DEFAULT_PAGING_TEMPLATE_NEXT,
	);
	
	$settingsList = array();
	
	// Want to add general settings?
	if ($general) {
		$settingsList = array_merge($settingsList, $generalSettings);
	}
	
	// Want to add style settings?
	if ($style) {
		$settingsList = array_merge($settingsList, $styleSettings);
	}
	
	return $settingsList;
}


/**
 * Install the WP Portfolio plugin, initialise the default settings, and create the tables for the websites and groups.
 */
function WPPortfolio_install()
{
	// ### Create Default Settings
	$settingsList = WPPortfolio_getSettingList();
	
	// Check the current version of the database
	$installed_ver  = get_option("WPPortfolio_version") + 0;
	$current_ver    = WPP_VERSION + 0;
	$upgrade_tables = ($current_ver > $installed_ver);
	
	// Are we upgrading an old version? If so, then we change
	// the default render type to cache locally as this is a new 
	// setting.
	if ($current_ver > 0 && $current_ver < 1.36) 
	{
		$settingsList['setting_stw_render_type'] = 'cache_locally';
	}
	
	
	// Initialise all settings in the database
	foreach ($settingsList as $settingName => $settingDefault) 
	{
		if (get_option('WPPortfolio_'.$settingName) === FALSE)
		{
			// Set the default option
			update_option('WPPortfolio_'.$settingName, $settingDefault);
		}
	}
	
	
	// Upgrade tables
	WPPortfolio_install_upgradeTables($upgrade_tables);		
	
			
	// Update the version regardless
	update_option("WPPortfolio_version", WPP_VERSION);
	
	// Create cache directory
	WPPortfolio_createCacheDirectory(); 
}
register_activation_hook(__FILE__,'WPPortfolio_install');


/**
 * On deactivation, remove all functions from the scheduled action hook.
 */
function WPPortfolio_plugin_cleanupForDeactivate() {
	wp_clear_scheduled_hook('wpportfolio_schedule_refresh_thumbnails');
}
register_deactivation_hook( __FILE__, 'WPPortfolio_plugin_cleanupForDeactivate');


/**
 * The cron job to refresh thumbnails.
 */
function WPPortfolio_plugin_runThumbnailRefresh()
{ 
	WPPortfolio_thumbnails_refreshAll(0, false, false);
}
add_action('wpportfolio_schedule_refresh_thumbnails', 'WPPortfolio_plugin_runThumbnailRefresh');


/**
 * Function to upgrade tables.
 * @param Boolean $upgradeNow If true, upgrade tables now.
 */
function WPPortfolio_install_upgradeTables($upgradeNow, $showErrors = false, $addSampleData = true)
{
	global $wpdb;
		
	// Table names
	$table_websites		 = $wpdb->prefix . TABLE_WEBSITES;
	$table_websites_meta = $wpdb->prefix . TABLE_WEBSITES_META;
	$table_groups 		 = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$table_debug    	 = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	
	if ($showErrors) {
		$wpdb->show_errors();
	}	
				
	// Check tables exist
	$table_websites_exists		= ($wpdb->get_var("SHOW TABLES LIKE '$table_websites'") == $table_websites);
	$table_websites_meta_exists	= ($wpdb->get_var("SHOW TABLES LIKE '$table_websites_meta'") == $table_websites_meta);
	$table_groups_exists		= ($wpdb->get_var("SHOW TABLES LIKE '$table_groups'") == $table_groups);
	$table_debug_exists			= ($wpdb->get_var("SHOW TABLES LIKE '$table_debug'") == $table_debug);
	
	// Only enable if debugging	
	//$wpdb->show_errors();

	// #### Create Tables - Websites
	if (!$table_websites_exists || $upgradeNow) 
	{
		$sql = "CREATE TABLE `$table_websites` (
  				   siteid INT(10) unsigned NOT NULL auto_increment,
				   sitename varchar(150),
				   siteurl varchar(255),
				   sitedescription TEXT,
				   sitegroup int(10) unsigned NOT NULL,
				   customthumb varchar(255),
				   customfield varchar(255),
				   siteactive TINYINT NOT NULL DEFAULT '1',
				   displaylink varchar(10) NOT NULL DEFAULT 'show_link',
				   siteorder int(10) unsigned NOT NULL DEFAULT '0',
				   specificpage TINYINT NOT NULL DEFAULT '0',	
				   siteadded datetime default NULL,
				   last_updated datetime default NULL,
				   PRIMARY KEY  (siteid) 
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
				
	}
	
	// Set default date if there isn't one
	$results = $wpdb->query("UPDATE `$table_websites` SET `siteadded` = NOW() WHERE `siteadded` IS NULL OR `siteadded` = '0000-00-00 00:00:00'");
	
	if (!$table_websites_meta_exists || $upgradeNow)
	{
		$sql = "CREATE TABLE `$table_websites_meta` (
		tagid INT(10) unsigned NOT NULL auto_increment,
		siteid INT(10) unsigned NOT NULL,
		tagname VARCHAR(150) NOT NULL,
		templatetag VARCHAR(150),
		tagvalue text,
		PRIMARY KEY  (tagid),
		FOREIGN KEY	 (siteid) REFERENCES $table_websites
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	
	}
	
	
	// #### Create Tables - Groups
	if (!$table_groups_exists || $upgradeNow)
	{
		$sql = "CREATE TABLE `$table_groups` (
  				   groupid int(10) UNSIGNED NOT NULL auto_increment,
				   groupname varchar(150),
				   groupdescription TEXT,
				   grouporder INT(1) UNSIGNED NOT NULL DEFAULT '0',
				   PRIMARY KEY  (groupid)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		// Creating new table? Add default group that has ID of 0
		if ($addSampleData)
		{
			$SQL = "INSERT INTO `$table_groups` (groupid, groupname, groupdescription) VALUES (1, 'My Websites', 'These are all my websites.')";
	 		$results = $wpdb->query($SQL);
		}
	}	
	
	// Needed for hard upgrade - existing method of trying to update
	// the table seems to be failing.
	$wpdb->query("DROP TABLE IF EXISTS $table_debug");
	
	// #### Create Tables - Debug Log
	if (!$table_debug_exists || $upgradeNow) 
	{
		$sql = "CREATE TABLE $table_debug (
  				  `logid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  `request_url` varchar(255) NOT NULL,
				  `request_param_hash` varchar(35) NOT NULL,
				  `request_result` tinyint(4) NOT NULL DEFAULT '0',
				  `request_error_msg` varchar(30) NOT NULL,
				  `request_detail` text NOT NULL,
				  `request_type` varchar(25) NOT NULL,
				  `request_date` datetime NOT NULL,
  				  PRIMARY KEY  (logid)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}


/**
 * Add the custom stylesheet for this plugin.
 */
function WPPortfolio_styles_Backend()
{
	// Only show our stylesheet on a WP Portfolio page to avoid breaking other plugins.
	if (!WPPortfolio_areWeOnWPPPage()) {
		return;
	}
		
	wp_enqueue_style('wpp-portfolio', 			WPPortfolio_getPluginPath() . 'portfolio.css', false, WPP_VERSION);
}



/** 
 * Add the scripts needed for the page for this plugin.
 */
function WPPortfolio_scripts_Backend()
{
	if (!WPPortfolio_areWeOnWPPPage()) 
		return;
		
	// Plugin-specific JS
	wp_enqueue_script('wpl-admin-js', WPPortfolio_getPluginPath() .  'js/wpp-admin.js', array('jquery'), WPP_VERSION);
}


/**
 * Scripts used on front of website.
 */
function WPPortfolio_scripts_Frontend()
{		
}    




/**
 * Get the URL for the plugin path including a trailing slash.
 * @return String The URL for the plugin path.
 */
function WPPortfolio_getPluginPath() {
	return trailingslashit(trailingslashit(WP_PLUGIN_URL) . plugin_basename(dirname(__FILE__)));
}


/**
 * Method called when we want to uninstall the portfolio plugin to remove the database tables.
 */
function WPPortfolio_uninstall() 
{
	// Remove all options from the database
	delete_option('WPPortfolio_setting_stw_access_key');
	delete_option('WPPortfolio_setting_stw_secret_key');	
	delete_option('WPPortfolio_setting_stw_thumb_size');
	delete_option('WPPortfolio_setting_cache_days');
	
	delete_option('WPPortfolio_setting_template_website');
	delete_option('WPPortfolio_setting_template_group');
	delete_option('WPPortfolio_setting_template_css');
	delete_option('WPPortfolio_setting_template_css_paging');
	delete_option('WPPortfolio_setting_template_css_widget');
			
	delete_option('WPPortfolio_version');
		
	
	// Remove all tables for the portfolio
	global $wpdb;
	$table_name    = $wpdb->prefix . TABLE_WEBSITES;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);
	
	$table_name    = $wpdb->prefix . TABLE_WEBSITES_META;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);
	
	$table_name    = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);
		
	$table_name    = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);
	
	
	// Remove cache
	$actualThumbPath = WPPortfolio_getThumbPathActualDir();
	WPPortfolio_unlinkRecursive($actualThumbPath);		
		
	WPPortfolio_showMessage(__("Deleted WP Portfolio database entries.", 'wp-portfolio'));
}




/**
 * This method is called just before the <head> tag is closed. We inject our custom CSS into the 
 * webpage here.
 */
function WPPortfolio_styles_frontend_renderCSS() 
{
	// Only render CSS if we've enabled the option
	$setting_disable_plugin_css = strtolower(trim(get_option('WPPortfolio_setting_disable_plugin_css')));
	
	// on = disable, anything else is enable
	if ($setting_disable_plugin_css != 'on')
	{
		$setting_template_css 		 = trim(stripslashes(get_option('WPPortfolio_setting_template_css')));
		$setting_template_css_paging = trim(stripslashes(get_option('WPPortfolio_setting_template_css_paging')));
		$setting_template_css_widget = trim(stripslashes(get_option('WPPortfolio_setting_template_css_widget')));
	
		echo "\n<!-- WP Portfolio Stylesheet -->\n";
		echo "<style type=\"text/css\">\n";
		
		echo $setting_template_css;
		echo $setting_template_css_paging;
		echo $setting_template_css_widget;
		
		echo "\n</style>";
		echo "\n<!-- WP Portfolio Stylesheet -->\n";
	}
}



/**
 * Turn the portfolio of websites in the database into a single page containing details and screenshots using the [wp-portfolio] shortcode.
 * @param $atts The attributes of the shortcode.
 * @return String The updated content for the post or page.
 */
function WPPortfolio_convertShortcodeToPortfolio($atts)
{	
	// Process the attributes
	extract(shortcode_atts(array(
		'groups' 		=> '',
		'hidegroupinfo' => 0,
		'sitesperpage'	=> 0,
		'orderby' 		=> 'asc',
		'ordertype'		=> 'normal',
		'single'		=> 0,
	), $atts));
	
	// Check if single contains a valid item ID
	if (is_numeric($single) && $single > 0) 
	{	
		$websiteDetails = WPPortfolio_getWebsiteDetails($single, OBJECT);
		
		// Portfolio item not found, abort
		if (!$websiteDetails) {
			return sprintf('<p>'.__('Portfolio item <b>ID %d</b> does not exist.', 'wp-portfolio').'</p>', $single); 
		}
		
		// Item found, so render it
		else  {
			return WPPortfolio_renderPortfolio(array($websiteDetails), false, false, false, false);
		}
	
	}
	
	// If hidegroupinfo is 1, then hide group details by passing in a blank template to the render portfolio function
	$grouptemplate = false; // If false, then default group template is used.
	if (isset($atts['hidegroupinfo']) && $atts['hidegroupinfo'] == 1) {
		$grouptemplate = "&nbsp;";
	}
	
	// Sort ASC or DESC?
	$orderAscending = true;
	if (isset($atts['orderby']) && strtolower(trim($atts['orderby'])) == 'desc') {
		$orderAscending = false;
	}
	
	// Convert order type to use normal as default
	$orderType = strtolower(trim(WPPortfolio_getArrayValue($atts, 'ordertype')));
	if ($orderType != 'dateadded') {
		$orderType = 'normal';
	}
	
	// Groups 
	$groups = false;
	if (isset($atts['groups'])) {
		$groups = $atts['groups'];
	}
	
	// Sites per page
	$sitesperpage = 0;
	if (isset($atts['sitesperpage'])) {
		$sitesperpage = $atts['sitesperpage'] + 0;
	}
	
	return WPPortfolio_getAllPortfolioAsHTML($groups, false, $grouptemplate, $sitesperpage, $orderAscending, $orderType);
}
add_shortcode('wp-portfolio', 'WPPortfolio_convertShortcodeToPortfolio');



/**
 * Method to get the portfolio using the specified list of groups and return it as HTML.
 * 
 * @param $groups The comma separated string of group IDs to show.
 * @param $template_website The template used to render each website. If false, the website template defined in the settings is used instead.
 * @param $template_group The template used to render each group header. If false, the group template defined in the settings is used instead.
 * @param $sitesperpage The number of sites to show per page, or false if showing all sites at once. 
 * @param $orderAscending Order websites in ascending order, or if false, order in descending order.
 * @param $orderBy How to order the results (choose from 'normal' or 'dateadded'). Default option is 'normal'. If 'dateadded' is chosen, group names are not shown.
 * @param $count If > 0, only show the specified number of websites. This overrides $sitesperpage.
 * @param $isWidgetTemplate If true, then we're rendering this as a widget layout. 
 * 
 * @return String The HTML which contains the portfolio as HTML.
 */
function WPPortfolio_getAllPortfolioAsHTML($groups = '', $template_website = false, $template_group = false, $sitesperpage = false, $orderAscending = true, $orderBy = 'normal', $count = false, $isWidgetTemplate = false)
{
	// Get portfolio from database
	global $wpdb;
	$websites_table = $wpdb->prefix . TABLE_WEBSITES;
	$groups_table   = $wpdb->prefix . TABLE_WEBSITE_GROUPS;		
		
	// Determine if we only want to show certain groups
	$WHERE_CLAUSE = "";
	if ($groups)
	{ 
		$selectedGroups = explode(",", $groups);
		foreach ($selectedGroups as $possibleGroup)
		{
			// Some matches might be empty strings
			if ($possibleGroup > 0) {
				$WHERE_CLAUSE .= "$groups_table.groupid = '$possibleGroup' OR ";
			}
		}
	} // end of if ($groups)
		
	// Add initial where if needed
	if ($WHERE_CLAUSE)
	{
		// Remove last OR to maintain valid SQL
		if (substr($WHERE_CLAUSE, -4) == ' OR ') {
			$WHERE_CLAUSE = substr($WHERE_CLAUSE, 0, strlen($WHERE_CLAUSE)-4);
		}				
		
		// Selectively choosing groups.
		$WHERE_CLAUSE = sprintf("WHERE (siteactive = 1) AND (%s)", $WHERE_CLAUSE);
	} 
	// Showing whole portfolio, but only active sites.
	else {
		$WHERE_CLAUSE = "WHERE (siteactive = 1)";
	}

	$ORDERBY_ORDERING = "";
	if (!$orderAscending) {
		$ORDERBY_ORDERING = 'DESC';
	}
	
	// How to order the results
	if (strtolower($orderBy) == 'dateadded') {
		$ORDERBY_CLAUSE = "ORDER BY siteadded $ORDERBY_ORDERING, sitename ASC";
		$template_group = ' '; // Disable group names
	} else {
		$ORDERBY_CLAUSE = "ORDER BY grouporder $ORDERBY_ORDERING, groupname $ORDERBY_ORDERING, siteorder $ORDERBY_ORDERING, sitename $ORDERBY_ORDERING";
	}
			
	// Get website details, merge with group details
	$SQL = "SELECT * FROM $websites_table
			LEFT JOIN $groups_table ON $websites_table.sitegroup = $groups_table.groupid
			$WHERE_CLAUSE
		 	$ORDERBY_CLAUSE
		 	";			
					
	$wpdb->show_errors();
	
	$paginghtml = false; 
	
	
	$LIMIT_CLAUSE = false;
	
	// Convert to a number
	$count = $count + 0;
	$sitesperpage = $sitesperpage + 0; 
	
	// Show a limited number of websites	
	if ($count > 0) {
		$LIMIT_CLAUSE = 'LIMIT '.$count;
	}
	
	// Limit the number of sites shown on a single page.	
	else if ($sitesperpage)
	{
		// How many sites do we have?
		$websites = $wpdb->get_results($SQL, OBJECT);
		$website_count = $wpdb->num_rows;
		
		// Paging is needed, as we have more websites than sites/page.
		if ($website_count > $sitesperpage)
		{
			$numofpages = ceil($website_count / $sitesperpage);
			
			// Pick up the page number from the GET variable
			$currentpage = 1;
			if (isset($_GET['portfolio-page']) && ($_GET['portfolio-page'] + 0) > 0) {
				$currentpage = $_GET['portfolio-page'] + 0;
			}			

			// Load paging defaults from the DB
			$setting_template_paging 			= stripslashes(get_option('WPPortfolio_setting_template_paging'));
			$setting_template_paging_next 		= stripslashes(get_option('WPPortfolio_setting_template_paging_next'));
			$setting_template_paging_previous 	= stripslashes(get_option('WPPortfolio_setting_template_paging_previous'));
			

			// Add Previous Jump Links
			if ($numofpages > 1 && $currentpage > 1) { 
				$html_previous = sprintf('&nbsp;<span class="page-jump"><a href="?portfolio-page=%s"><b>%s</b></a></span>&nbsp;', $currentpage-1, $setting_template_paging_previous);
			} else {
				$html_previous = sprintf('&nbsp;<span class="page-jump"><b>%s</b></span>&nbsp;', $setting_template_paging_previous);
			}			
			
			
			// Render the individual pages
			$html_pages = false;
			for ($i = 1; $i <= $numofpages; $i++) 
			{								
				// No link for current page.
				if ($i == $currentpage) {
					$html_pages .= sprintf('&nbsp;<span class="page-jump page-current"><b>%s</b></span>&nbsp;', $i, $i);
				} 
				// Link for other pages 
				else  {
					// Avoid parameter if first page
					if ($i == 1) {
						$html_pages .= sprintf('&nbsp;<span class="page-jump"><a href="?"><b>%s</b></a></span>&nbsp;', $i, $i);
					} else {
						$html_pages .= sprintf('&nbsp;<span class="page-jump"><a href="?portfolio-page=%s"><b>%s</b></a></span>&nbsp;', $i, $i);
					}
				}				
			}
			// Add Next Jump Links
			if ($currentpage < $numofpages) {
				$html_next = sprintf('&nbsp;<span class="page-jump"><a href="?portfolio-page=%s"><b>%s</b></a></span>&nbsp;', $currentpage+1, $setting_template_paging_next);
			} else {
				$html_next = sprintf('&nbsp;<span class="page-jump"><b>%s</b></span>&nbsp;', $setting_template_paging_next);
			}

			

			// Update the SQL for the pages effect
			// Show first page and set limit to start at first record.
			if ($currentpage <= 1) {
				$firstresult = 1;
				$LIMIT_CLAUSE = sprintf("LIMIT 0, %s", $sitesperpage);
			} 
			// Show websites only for current page for inner page
			else
			{
				$firstresult = (($currentpage - 1) * $sitesperpage);
				$LIMIT_CLAUSE = sprintf("LIMIT %s, %s", $firstresult, $sitesperpage);
			}
			
			// Work out the number of the website being shown at the end of the range. 
			$website_endNum = ($currentpage * $sitesperpage);
			if ($website_endNum > $website_count) {
				$website_endNum = $website_count;
			}
			
			
			// Create the paging HTML using the templates.
			$paginghtml = $setting_template_paging;
			
			// Summary info			
			$paginghtml = str_replace('%PAGING_PAGE_CURRENT%', 		$currentpage, 		$paginghtml);
			$paginghtml = str_replace('%PAGING_PAGE_TOTAL%', 		$numofpages, 		$paginghtml);

			$paginghtml = str_replace('%PAGING_ITEM_START%', 		$firstresult, 		$paginghtml);
			$paginghtml = str_replace('%PAGING_ITEM_END%', 			$website_endNum, 	$paginghtml);
			$paginghtml = str_replace('%PAGING_ITEM_TOTAL%', 		$website_count, 	$paginghtml);
			
			// Navigation
			$paginghtml = str_replace('%LINK_PREVIOUS%', 			$html_previous, 	$paginghtml);
			$paginghtml = str_replace('%LINK_NEXT%', 				$html_next, 		$paginghtml);
			$paginghtml = str_replace('%PAGE_NUMBERS%', 			$html_pages, 		$paginghtml);
			
		} // end of if ($website_count > $sitesperpage)
	}
	
	
	// Add the limit clause.
	$SQL .= $LIMIT_CLAUSE;
		
	$websites = $wpdb->get_results($SQL, OBJECT);

	// Get the current list of custom data fields
	$custom_data = WPPortfolio_websites_getCustomData();
	
	// If there are custom custom data fields (is array but not empty array)
	if(is_array($custom_data) && ($custom_data != array()))
	{
		// Create string of tags to retrieve
		$wanted_data = "";
		foreach($custom_data as $field_data) {
			$wanted_data .= $wpdb->prepare("%s, ", $field_data['name']);
		}
		$wanted_data = rtrim($wanted_data, ", ");
	
		// Extracts the custom field data for each site
		foreach($websites as $websitedetails)
		{
			// Get the custom fields from the database
			$websitedetails->customData = WPPortfolio_getCustomDetails($websitedetails->siteid, $wanted_data);
				
			// Ensure that most recent template tags are assigned
			foreach($custom_data as $field_data)
			{
				$websitedetails->customData[$field_data['name']]['templatetag'] = $field_data['template_tag'];
			}
				
		}
			
	}

	// If we've got websites to show, then render into HTML
	if ($websites) {
		$portfolioHTML = WPPortfolio_renderPortfolio($websites, $template_website, $template_group, $paginghtml, $isWidgetTemplate);
	} else {
		$portfolioHTML = false;
	}
	
	return $portfolioHTML;
}




/**
 * Method to get a random selection of websites from the portfolio using the specified list of groups and return it as HTML. No group details are 
 * returned when showing a random selection of the portfolio.
 * 
 * @param $groups The comma separated string of group IDs to use to find which websites to show. If false, websites are selected from the whole portfolio.
 * @param $count The number of websites to show in the output.
 * @param $template_website The template used to render each website. If false, the website template defined in the settings is used instead.
 * @param $isWidgetTemplate If true, then we're rendering this as a widget layout. 
 * 
 * @return String The HTML which contains the portfolio as HTML.
 */
function WPPortfolio_getRandomPortfolioSelectionAsHTML($groups = '', $count = 3, $template_website = false, $isWidgetTemplate = false)
{
	// Get portfolio from database
	global $wpdb;
	$websites_table = $wpdb->prefix . TABLE_WEBSITES;
	$groups_table   = $wpdb->prefix . TABLE_WEBSITE_GROUPS;		
		
	// Validate the count is a number
	$count = $count + 0;
	
	// Determine if we only want to get websites from certain groups
	$WHERE_CLAUSE = "";
	if ($groups)
	{ 
		$selectedGroups = explode(",", $groups);
		foreach ($selectedGroups as $possibleGroup)
		{
			// Some matches might be empty strings
			if ($possibleGroup > 0) {
				$WHERE_CLAUSE .= "$groups_table.groupid = '$possibleGroup' OR ";
			}
		}
	} // end of if ($groups)
		
	// Add initial where if needed
	if ($WHERE_CLAUSE)
	{
		// Remove last OR to maintain valid SQL
		if (substr($WHERE_CLAUSE, -4) == ' OR ') {
			$WHERE_CLAUSE = substr($WHERE_CLAUSE, 0, strlen($WHERE_CLAUSE)-4);
		}				
		
		$WHERE_CLAUSE = "WHERE siteactive != '0' AND (". $WHERE_CLAUSE . ")";
	}
	// Always hide inactive sites
	else {
		$WHERE_CLAUSE = "WHERE siteactive != '0'";
	}
	
		
	// Limit the number of websites if requested
	$LIMITCLAUSE = false;
	if ($count > 0) {
		$LIMITCLAUSE = 'LIMIT '.$count;
	}
	
			
	// Get website details, merge with group details
	$SQL = "SELECT * FROM $websites_table
			LEFT JOIN $groups_table ON $websites_table.sitegroup = $groups_table.groupid
			$WHERE_CLAUSE
		 	ORDER BY RAND()
		 	$LIMITCLAUSE
		 	";			
					
	$wpdb->show_errors();
	$websites = $wpdb->get_results($SQL, OBJECT);

	// Get the current list of custom data fields
	$custom_data = WPPortfolio_websites_getCustomData();
	
	// If there are custom custom data fields (is array but not empty array)
	if(is_array($custom_data) && ($custom_data != array()))
	{
		// Create string of tags to retrieve
		$wanted_data = "";
		foreach($custom_data as $field_data) {
			$wanted_data .= $wpdb->prepare("%s, ", $field_data['name']);
		}
		$wanted_data = rtrim($wanted_data, ", ");
	
		// Extracts the custom field data for each site
		foreach($websites as $websitedetails)
		{
			// Get the custom fields from the database
			$websitedetails->customData = WPPortfolio_getCustomDetails($websitedetails->siteid, $wanted_data);
				
			// Ensure that most recent template tags are assigned
			foreach($custom_data as $field_data)
			{
				$websitedetails->customData[$field_data['name']]['templatetag'] = $field_data['template_tag'];
			}
				
		}
			
	}

	// If we've got websites to show, then render into HTML. Use blank group to avoid rendering group details.
	if ($websites) {
		$portfolioHTML = WPPortfolio_renderPortfolio($websites, $template_website, ' ', false, $isWidgetTemplate);
	} else {
		$portfolioHTML = false;
	}
	
	return $portfolioHTML;
}



/**
 * Convert the website details in the database object into the HTML for the portfolio.
 * 
 * @param Array $websites The list of websites as objects.
 * @param String $template_website The template used to render each website. If false, the website template defined in the settings is used instead.
 * @param String $template_group The template used to render each group header. If false, the group template defined in the settings is used instead.
 * @param String $paging_html The HTML used for paging the portfolio. False by default.
 * @param Boolean $isWidgetTemplate If true, then we're rendering this as a widget layout.
 * 
 * @return String The HTML for the portfolio page.
 */
function WPPortfolio_renderPortfolio($websites, $template_website = false, $template_group = false, $paging_html = false, $isWidgetTemplate = false)
{
	if (!$websites)
		return false;
			
	// Just put some space after other content before rendering portfolio.	
	$content = "\n\n";			

	// Used to track what group we're working with.
	$prev_group = "";
	
	// Get templates to use for rendering the website details. Use the defined options if the parameters are false.
	if (!$template_website) {
		$setting_template_website = stripslashes(get_option('WPPortfolio_setting_template_website'));
	} else {
		$setting_template_website = $template_website;		
	}

	if (!$template_group) {
		$setting_template_group = stripslashes(get_option('WPPortfolio_setting_template_group'));						
	} else {
		$setting_template_group = $template_group;	
			
	}
	 	
	
	// Render all the websites, but look after different groups
	foreach ($websites as $websitedetails)
	{
		// If we're rendering a new group, then show the group name and description 
		if ($prev_group != $websitedetails->groupname)
		{
			// Replace group name and description.					
			$renderedstr = WPPortfolio_replaceString(WPP_STR_GROUP_NAME, stripslashes($websitedetails->groupname), $setting_template_group);
			$renderedstr = WPPortfolio_replaceString(WPP_STR_GROUP_DESCRIPTION, stripslashes($websitedetails->groupdescription), $renderedstr);
			
			// Update content with templated group details
			$content .= "\n\n$renderedstr\n";
		}
		
		// Render the website details
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_NAME, 		 	stripslashes($websitedetails->sitename), $setting_template_website);
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_DESCRIPTION, 	stripslashes($websitedetails->sitedescription), $renderedstr);
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_CUSTOM_FIELD, 	stripslashes($websitedetails->customfield), $renderedstr);
		
		if(isset($websitedetails->customData))
		{
			// Add the custom data to it's given tags
			foreach($websitedetails->customData as $field_data) {
				$renderedstr = WPPortfolio_replaceString($field_data['templatetag'], WPPortfolio_getArrayValue($field_data, 'tagvalue'), $renderedstr);
			}
		}
		
		// Remove website link if requested to
		if ($websitedetails->displaylink == 'hide_link')
		{		
			$renderedstr = preg_replace('/<a\shref="%WEBSITE_URL%"[^>]+>%WEBSITE_URL%<\/a>/i', '', $renderedstr);
		}
		
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_URL, 		 	stripslashes($websitedetails->siteurl), $renderedstr);
		
				
		
		// Handle the thumbnails - use custom if provided.
		$imageURL = false;
		if ($websitedetails->customthumb) 
		{
			$imageURL = WPPortfolio_getAdjustedCustomThumbnail($websitedetails->customthumb);
			$imagetag = sprintf('<img src="%s" alt="%s"/>', $imageURL, stripslashes($websitedetails->sitename));
		} 
		// Standard thumbnail
		else {
			$imagetag = WPPortfolio_getThumbnailHTML($websitedetails->siteurl, false, ($websitedetails->specificpage == 1), stripslashes($websitedetails->sitename)); 			
		}
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_THUMBNAIL_URL, $imageURL, $renderedstr); /// Just URLs		
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_THUMBNAIL, $imagetag, $renderedstr);  // Full image tag
		
		// Handle any shortcodes that we have in the template
		$renderedstr = do_shortcode($renderedstr);
		
		
		$content .= "\n$renderedstr\n";
		
		// If fetching thumbnails, this might take a while. So flush.
		flush();
		
		// Track the groups
		$prev_group = $websitedetails->groupname;
	}
	
	$content .= $paging_html;
	
	// Credit link on portfolio. 
	if (!$isWidgetTemplate && get_option('WPPortfolio_setting_show_credit') == "on") {				
		$content .= sprintf('<div style="clear: both;"></div><div class="wpp-creditlink" style="font-size: 8pt; font-family: Verdana; float: right; clear: both;">'.__('Created using %s by %s</div>', 'wp-portfolio'), '<a href="http://wordpress.org/extend/plugins/wp-portfolio" target="_blank">WP Portfolio</a>', '<a href="http://www.shrinktheweb.com/" target="_blank">ShrinkTheWeb</a>');
	} 
				
	// Add some space after the portfolio HTML 
	$content .= "\n\n";
	
	return $content;
}



/**
 * Create the cache directory if it doesn't exist.
 * $pathType If specified, the particular cache path to create. If false, use the path stored in the settings.
 */
function WPPortfolio_createCacheDirectory($pathType = false)
{
	// Cache directory
	$actualThumbPath = WPPortfolio_getThumbPathActualDir($pathType);
			
	// Create cache directory if it doesn't exist	
	if (!file_exists($actualThumbPath)) {
		@mkdir($actualThumbPath, 0777, true);		
	} else {
		// Try to make the directory writable
		@chmod($actualThumbPath, 0777);
	}
}

/**
 * Gets the full directory path for the thumbnail directory with a trailing slash.
 * @param $pathType The type of directory to fetch, or just return the one specified in the settings if false. 
 * @return String The full directory path for the thumbnail directory.
 */
function WPPortfolio_getThumbPathActualDir($pathType = false) 
{
	// If no path type is specified, then get the setting from the options table.
	if ($pathType == false) {
		$pathType = WPPortfolio_getCacheSetting();
	}
	
	switch ($pathType)
	{
		case 'wpcontent':
			return trailingslashit(trailingslashit(WP_CONTENT_DIR).WPP_THUMBNAIL_PATH);
			break;
			
		default:
			return trailingslashit(trailingslashit(WP_PLUGIN_DIR).WPP_THUMBNAIL_PATH);
			break;
	}	
}


/**
 * Gets the full URL path for the thumbnail directory with a trailing slash.
 * @param $pathType The type of directory to fetch, or just return the one specified in the settings if false.
 * @return String The full URL for the thumbnail directory.
 */
function WPPortfolio_getThumbPathURL($pathType = false) 
{
	// If no path type is specified, then get the setting from the options table.
	if ($pathType == false) {
		$pathType = WPPortfolio_getCacheSetting();
	}
	
	switch ($pathType)
	{
		case 'wpcontent':
			return trailingslashit(trailingslashit(WP_CONTENT_URL).WPP_THUMBNAIL_PATH);
			break;
			
		default:
			return trailingslashit(trailingslashit(WP_PLUGIN_URL).WPP_THUMBNAIL_PATH);
			break;
	}
}


/**
 * Get the type of cache that we need to use. Either 'wpcontent' or 'plugin'.
 * @return String The type of cache we need to use.
 */
function WPPortfolio_getCacheSetting()
{
	$cacheSetting = get_option(WPP_CACHE_SETTING);
	
	if ($cacheSetting == 'setting_cache_wpcontent') {
		return 'wpcontent';
	}
	return 'plugin';
}


/**
 * Get the full URL path of the pending thumbnails.
 * @return String The full URL path of the pending thumbnails.
 */
function WPPortfolio_getPendingThumbURLPath() {
	return trailingslashit(WP_PLUGIN_URL).WPP_THUMB_DEFAULTS;
}






/**
 * Get the details for the specified Website ID.
 * @param $siteid The ID of the Website to get the details for.
 * @return Array An array of the Website details.
 */
function WPPortfolio_getWebsiteDetails($siteid, $dataType = ARRAY_A) 
{
	global $wpdb;
	$table_name = $wpdb->prefix . TABLE_WEBSITES;
	
	$SQL = $wpdb->prepare("
			SELECT * FROM $table_name 
			WHERE siteid = %d
			LIMIT 1
		", $siteid);

	// We need to strip slashes for each entry.
	if (ARRAY_A == $dataType) {
		$data = WPPortfolio_cleanSlashesFromArrayData($wpdb->get_row($SQL, $dataType));
	} else {
		$data = $wpdb->get_row($SQL, $dataType);
	}
	
	// Get data for custom elements from meta table
	$custom_fields = WPPortfolio_getCustomDetails($siteid);
	if($dataType == ARRAY_A)
	{
		foreach($custom_fields as $field_name=>$field_data) {
				$data[$field_name] = $field_data;
		}
	} elseif ($dataType == OBJECT) {
		$data->customData = $custom_fields;
	}
	
	return $data;
}

/**
 * Grab details of custom fields for a given site
 * @param $siteid site to get data for
 * @param $wanted_data array of custom fields to extract
 * @return Associative array tagname=>tagvalue
 */
function WPPortfolio_getCustomDetails($siteid, $wanted_data = false) {
	global $wpdb;

	$table_name = $wpdb->prefix . TABLE_WEBSITES_META;

	$custom_data = WPPortfolio_websites_getCustomData();

	// Query the information for the given site
	$SQL = $wpdb->prepare("
			SELECT tagname, templatetag, tagvalue
			FROM $table_name
			WHERE (siteid = %d)
		", $siteid);

	// If particular tags requested don't bother with others
	if(is_string($wanted_data))
	{
	// Add clause for tags
	$SQL .=  "
	AND (tagname
	IN($wanted_data))
	";
	}

	$custom_data = $wpdb->get_results($SQL, ARRAY_A);

	// Initilise return value
	$data = array();

	// Jiggle output around (index by tagname)
	foreach($custom_data as $field_data) {
	$field_name = stripslashes($field_data['tagname']);
	unset($field_data['tagname']);
	$data[$field_name] = WPPortfolio_cleanSlashesFromArrayData($field_data);
	}

	return $data;
}

/**
 * AJAX callback function that refreshes a thumbnail.
 */
function WPPortfolio_ajax_handleForcedThumbnailRefresh() 
{
	$siteid = false;
	if (isset($_POST['siteid'])) {
		$siteid = $_POST['siteid'];
	}
	
	echo WPPortfolio_refresh_forceThumbnailRefresh($siteid);
	die();
}
add_action('wp_ajax_thumbnail_refresh', 'WPPortfolio_ajax_handleForcedThumbnailRefresh');





/**
 * Function that removes the physical cached files of the specified URL.
 * @param $fileurl The URL of the file that has been cached.
 */
function WPPortfolio_removeCachedPhotos($fileurl)
{
	$allCached = md5($fileurl).'*';
	$cacheDir = trailingslashit(WPPortfolio_getThumbPathActualDir());
	
	foreach (glob($cacheDir.$allCached) AS $filename) {
		unlink($filename);
	}
}


/**
 * Determine if an account has a specific account feature using the STW Account API to check. This 
 * will cache the settings found through the Account API in the WordPress transients database.
 * 
 * @param String $featureNeeded The field name of the feature to check for. 
 * @param Mixed $expectedValue The expected value for this feature that will determine if it exists or not.
 * 
 * @return Boolean True if the feature exists, false otherwise.
 */
function WPPortfolio_hasCustomAccountFeature($featureNeeded, $expectedValue = 1)
{
	// See if we have the account details in the database already to use.
	$aResponse = get_transient('WPPortfolio_account_api_status');
	
	// No account details, fetch them
	if ($aResponse === FALSE || empty($aResponse))
	{
		$args = array(
			'stwaccesskeyid' 	=> stripslashes(get_option('WPPortfolio_setting_stw_access_key')),
			'stwu'				=> stripslashes(get_option('WPPortfolio_setting_stw_secret_key'))
		);
		
		// Fetch details about this account
		$accountAPIURL = 'http://images.shrinktheweb.com/account.php?' . http_build_query($args);
		$resp = wp_remote_get($accountAPIURL);
		
		$gotAccountData = false;
		
		// Check HTTP Success
		if (200 == $resp['response']['code']) 
		{
			if (!$resp['body'] || 'offline' == $resp['body'])
			{
				// Maintenance Mode or offline
				return false;
			}
			
			// All worked, got raw XML to process.
			else {
				$gotAccountData = $resp['body'];
			}
		}
		
		// Process the return data.
		$oDOM = new DOMDocument;
		$oDOM->loadXML($gotAccountData);
		$sXML = simplexml_import_dom($oDOM);
		$sXMLLayout = 'http://www.shrinktheweb.com/doc/stwacctresponse.xsd';
		
		
		$aResponse = array();
			
		// Pull response codes from XML feed
		$aResponse['stw_response_status'] = (String)$sXML->children($sXMLLayout)->Response->Status->StatusCode; // Response Code
		$aResponse['stw_account_level'] = (Integer)$sXML->children($sXMLLayout)->Response->Account_Level->StatusCode; // Account level
	        
		// check for enabled upgrades
		$aResponse['stw_inside_pages'] 			= (Integer)$sXML->children($sXMLLayout)->Response->Inside_Pages->StatusCode; // Inside Pages
		$aResponse['stw_custom_size'] 			= (Integer)$sXML->children($sXMLLayout)->Response->Custom_Size->StatusCode; // Custom Size
		$aResponse['stw_full_length'] 			= (Integer)$sXML->children($sXMLLayout)->Response->Full_Length->StatusCode; // Full Length
		$aResponse['stw_refresh_ondemand'] 		= (Integer)$sXML->children($sXMLLayout)->Response->Refresh_OnDemand->StatusCode; // Refresh OnDemand
		$aResponse['stw_custom_delay'] 			= (Integer)$sXML->children($sXMLLayout)->Response->Custom_Delay->StatusCode; // Custom Delay
		$aResponse['stw_custom_quality'] 		= (Integer)$sXML->children($sXMLLayout)->Response->Custom_Quality->StatusCode; // Custom Quality
		$aResponse['stw_custom_resolution'] 	= (Integer)$sXML->children($sXMLLayout)->Response->Custom_Resolution->StatusCode; // Custom Resolution
		$aResponse['stw_custom_messages'] 		= (Integer)$sXML->children($sXMLLayout)->Response->Custom_Messages->StatusCode; // Custom Messages
		
		// Cache this data in the database.
		set_transient('WPPortfolio_account_api_status', json_encode($aResponse), 60 * 60 * 24);
	}
	
	// Decode the settings back into an array
	else 
	{
		$aResponse = json_decode($aResponse, true);
	}
	
	// Return if the feature exists, and is valid.
	return (isset($aResponse[$featureNeeded]) && $aResponse[$featureNeeded] == $expectedValue);
}




/**
 * Determine if there's a custom size option that's been selected.
 * @return The custom size, or false.
 */
function WPPortfolio_getCustomSizeOption()
{
	// Feature not present
	if (!WPPortfolio_hasCustomAccountFeature('stw_custom_size')) {
		return false;
	}

    // Do we want to use custom thumbnail types?
    if (get_option('WPPortfolio_setting_stw_thumb_size_type') != 'custom') {
    	return false;
    }
        
	return get_option('WPPortfolio_setting_stw_thumb_size_custom') + 0;
}


/**
 * Delete all error messages relating to this URL.
 * @param String $url The URL to purge from the error logs.
 */
function WPPortfolio_errors_removeCachedErrors($url)
{
	global $wpdb;
	$wpdb->show_errors;
				
	$table_debug = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	$SQL = $wpdb->prepare("
		DELETE FROM $table_debug
		WHERE request_url = %s
		", $url);
	
	$wpdb->query($SQL);
}


/**
 * Function checks to see if there's been an error in the last 12 hours for
 * the requested thumbnail. If there has, then return the error associated
 * with that fetch.
 * 
 * @param Array $args The arguments used to fetch the thumbnail
 * @param String $pendingThumbPath The path for images when a thumbnail cannot be loaded. 
 * @return String The URL to the error image, or false if there's no cached error.
 */
function WPPortfolio_errors_checkForCachedError($args, $pendingThumbPath)
{
	global $wpdb;
	$wpdb->show_errors;
		
	$argHash = md5(serialize($args));
		
	$table_debug    = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	$SQL = $wpdb->prepare("
		SELECT * 
		FROM $table_debug
		WHERE request_param_hash = %s
		  AND request_date > NOW() - INTERVAL 12 HOUR
		  AND request_result = 0
		ORDER BY request_date DESC
		", $argHash);
	
	$errorCache = $wpdb->get_row($SQL);
	
	if ($errorCache)  {
		return WPPortfolio_error_getErrorStatusImg($args, $pendingThumbPath, $errorCache->request_error_msg);
	}
	
	return false;
}

/**
 * Get a total count of the errors currently logged.
 */
function WPPortfolio_errors_getErrorCount()
{
	global $wpdb;
	$wpdb->show_errors;
	$table_debug    = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	
	return $wpdb->get_var("SELECT COUNT(*) FROM $table_debug WHERE request_result = 0");
}

/**
 * Adds a link to the plugin page to click through straight to the plugin page.
 */
function WPPortfolio_plugin_addSettingsLink($links) 
{ 
	$settings_link = sprintf('<a href="%s">Settings</a>', admin_url('admin.php?page=WPP_show_settings')); 
	array_unshift($links, $settings_link); 
	return $links; 
}
/**
 * Cleans unauthorised characters from a template tag
 * @param String $inString The string to make safe.
 * @return String A safe string for internal use
 */
function WPPortfolio_cleanInputData($inString)
{
	$inString = trim(strtoupper($inString));

	// Remove brackets and quotes completely
	$inString = preg_replace('%[\(\[\]\)\'\"]%', '', $inString);

	// Remove non-alpha characters
	$inString = preg_replace('%[^0-9A-Z\_]+%', '_', $inString);

	// Remove the first and last underscores (if there is one)
	$inString = trim($inString, '_');

	return '%'.$inString.'%';
}

/**
 * Retrieves and validates data from the filter for custom data
 * @param Boolean $warn The warning
 * @return list of custom data elements
 */
function WPPortfolio_websites_getCustomData($warn = true)
{
	$custom_fields = apply_filters('wpportfolio_filter_portfolio_custom_fields', array());

	// Sanity check. have we been given an array?
	if(empty($custom_fields) || !is_array($custom_fields)) {
		return array();
	}

	$problems = "";
	// Sanity check for each array element
	foreach($custom_fields as $field_key=>$field_data)
	{
		// Does the field have a name and template-tag?
		if(!empty($field_data['name']) && !empty($field_data['template_tag']))
		{
			// Special sanitization for name and template_tag
			$custom_fields[$field_key]['name']			= preg_replace("/[^A-Za-z0-9_-]/", "", $field_data['name']);
				
			// Generate full template tag
			$custom_fields[$field_key]['template_tag']	= WPPortfolio_cleanInputData($field_data['template_tag']);
				
			// Only display errors if we are an admin (clean front-end)
		} else
		{
			if(is_admin() && ($warn !== false))
			{
				if(empty($field_data['name'])) {
					$problems .= '<br/>'.sprintf(__('Field %d doesn\'t have a name.', 'wp-portfolio'), ($field_key+1));
				} else {
					$problems .= '<br/>'.sprintf(__('Field %d doesn\'t have a template tag.', 'wp-portfolio'), ($field_key+1));
				}
			}
			unset($custom_fields[$field_key]);
		}
	}
	if($problems != "")
	{
		WPPortfolio_showMessage(__("You have added some custom fields but we've had a problem, here's what we found:", 'wp-portfolio')
		.$problems, true);
	}

	return $custom_fields;
}

?>