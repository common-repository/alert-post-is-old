<?php

/*
 Plugin Name: Alert Post is Old
 Plugin URI:
 Description: Display a notification when a post is older than X-years where X is any number of years.
 Author: Agbonghama Collins
 Version: 1.2
 Author URI: http://tech4sky.com/
 */

/**
 *
 */
class AlertOldPost {

	protected $_notification;
	protected $_years;
	protected $_post;
	protected $_page;

	// hook all plugin action and filter
	function __construct() {
		// Initialize setting options on activation
		register_activation_hook(__FILE__, array($this, 'aop_settings_default_values'));

		// register Menu
		add_action('admin_menu', array($this, 'aop_settings_menu'));

		// hook plugin section and field to admin_init
		add_action('admin_init', array($this, 'pluginOption'));

		// add the plugin stylesheet to header
		add_action('wp_head', array($this, 'stylesheet'));

		// display notification above post
		add_filter('the_content', array($this, 'displayNotification'));

	}

	public function aop_settings_default_values() {
		$aop_plugin_options = array(
		'notification' => 'This post hasn\'t been updated in over 2 years.',
		'years' => 2,
		'post' => 1
		);
		update_option('apo_alert_old_post', $aop_plugin_options);
	}

	// get option value from the database
	public function databaseValues() {
		$options = get_option('apo_alert_old_post');
		$this -> _notification = $options['notification'];
		$this -> _years = $options['years'];
		$this -> _post = $options['post'];
		$this -> _page = $options['page'];
	}

	// Adding Submenu to settings
	public function aop_settings_menu() {
		add_options_page('Alert Post is Old',
		'Alert Post is Old',
		'manage_options',
		'aop-alert-post-old',
		array($this, 'alert_post_old_function')
		);
	}

	// setttings form
	public function alert_post_old_function() {
		echo '<div class="wrap">';
		screen_icon();
		echo '<h2>Alert Post is Old</h2>';
		echo '<form action="options.php" method="post">';
		do_settings_sections('aop-alert-post-old');
		settings_fields('aop_settings_group');
		submit_button();
		?>
		<br>
		<br>
	<table>
		<tr>
			<td>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="HAAAMDMXMSP58">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form></td>
		</td><td><a href="https://twitter.com/tech4sky" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @tech4sky</a>
<script>
			! function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
				if (!d.getElementById(id)) {
					js = d.createElement(s);
					js.id = id;
					js.src = p + '://platform.twitter.com/widgets.js';
					fjs.parentNode.insertBefore(js, fjs);
				}
			}(document, 'script', 'twitter-wjs'); 
</script>
</td>
		<td>
			<div id="fb-root"></div>
<script>
				( function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id))
							return;
						js = d.createElement(s);
						js.id = id;
						js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=399748413426161&version=v2.0";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk')); 
</script>
<div class="fb-like" data-href="https://facebook.com/tech4sky" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>
		</tr>
	</table>
	<br /><br /><br />
	<h2>Built with <3 and coffee by <strong><a href="http://tech4sky.com" target="_blank">Collizo4sky</a></strong></h2>
	
	<?php

	}

	// plugin field and sections
	public function pluginOption() {
		add_settings_section('aop_settings_section',
		'Plugin Options',
		null,
		'aop-alert-post-old'
		);

		add_settings_field('notification',
		'<label for="notification">Notification to display when post is old</label>',
		array($this, 'aop_notification'),
		'aop-alert-post-old',
		'aop_settings_section'
		);

		add_settings_field('years',
		'<label for="years">Number of years for a post to be considered old</label>',
		array($this, 'aop_years'),
		'aop-alert-post-old',
		'aop_settings_section'
		);
		
		add_settings_field('notification_location',
		'<label for="years">Where to display notification?</label>',
		array($this, 'aop_notification_location'),
		'aop-alert-post-old',
		'aop_settings_section'
		);

		// register settings
		register_setting('aop_settings_group', 'apo_alert_old_post');
	}

	// ------------------------------------------------------------------
	// Settings section callback function
	// ------------------------------------------------------------------

	public function aop_notification() {
		// call database values just like global in procedural
		$this -> databaseValues();
		echo '<textarea id="notification" cols="50" rows="3" name="apo_alert_old_post[notification]">';
		echo esc_attr($this -> _notification);
		echo '</textarea>';

	}

	public function aop_years() {
		// call database values
		$this -> databaseValues();
		echo '<input type="number" id="years" name="apo_alert_old_post[years]" value="' . esc_attr($this -> _years) . '">';

	}
	
	public function aop_notification_location()
	{
		$this -> databaseValues();
		echo '<input type="checkbox" id="notification_post" name="apo_alert_old_post[post]" value="1"' . checked($this->_post, 1, false) . '"/>';
		echo '<label for="notification_post">&nbsp;&nbsp;' . Post . '</label>';
		
		echo "<br/><br/>";
		echo '<input type="checkbox" id="notification_post" name="apo_alert_old_post[page]" value="1"' . checked($this->_page, 1, false) . '">';
		echo '<label for="notification_page">&nbsp;&nbsp;' . Page . '</label>';
	}

	// ------------------------------------------------------------------
	// Plugin functions
	// ------------------------------------------------------------------

	// plugin Stylesheet
	public function stylesheet() {
		echo <<<HTML
		<!-- Alert post is old (author: http://tech4sky.com) -->
	<style type="text/css">
	.oldPost {
		padding-top: 8px;
		padding-bottom: 8px;
		background-color: #FEEFB3;
		color: #9F6000;
		border: 1px solid;
		padding: 4px 12px 8px;
		margin-bottom: 20px;
		border-radius: 6px;
		}
		span.oldPost {
    	background-color: #9F6000;
    	color: #fff;
    	padding: 1px 10px 0px;
		border-radius: 20px;
		font-size: 18px;
		font-weight: bold;
		font-family: Verdana;
		float: left;
		margin: 0px 8px 0px 0px;
		}
		span.oldtext  {
		padding-top: 0px;
		color: #9F6000;
		}
	</style>
	<!-- /Alert post is old -->
	
HTML;

	}

	// display notification above post
	public function displayNotification($content) {
		global $post;
		// call database values
		$this -> databaseValues();

		// get settings year
		$postz = $this->_post;
		$pagez = $this->_page;
	    $setYear = $this -> _years;
		

		// get notification text
		$notification = $this -> _notification;
		// calculate post age
		$year = date('Y') - get_post_time('Y', true, $post -> ID);

		// show notification only on post
		if ( $postz == 1 ) {
		if (is_single()) :
			if ($year >= $setYear) {
				echo '<div class="oldPost">';
				echo '<span class="oldPost"> ! </span>';
				echo "<span class='oldtext'>$notification</span>";
				echo '</div>';
			}
			endif;
		}
		
		// display notification in page if configured
		if ( $pagez == 1 ) {
		if (is_page()) :
			if ($year >= $setYear) {
				echo '<div class="oldPost">';
				echo '<span class="oldPost"> ! </span>';
				echo "<span class='oldtext'>$notification</span>";
				echo '</div>';
			}
			endif;
		}
		

		return $content;
	}

}

// instantiate the class
new AlertOldPost;
