<?php
/*
Plugin Name: Twitter Like Box Widget. Like facebook box but for twitter
Version: 1.3.5.2
Description: Display your Twitter followers anywhere in the site or use the widget to display it on the sidebar.Like Faebook's Like Box show your followers and a button to follow you. Also you can display people YOU follow instead of followers
Author: Damian Logghe
Author URI: http://www.timersys.com
License: GPL
Text Domain: tlb
Domain Path: languages
*/


// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

require(dirname (__FILE__).'/WP_Plugin_Base.class.php');

require(dirname (__FILE__).'/Twitter_Like_Box_Widget.class.php');

if( !class_exists('OAuthException') && !class_exists('TwitterOAuth'))
{
	require(dirname (__FILE__).'/twitteroauth/twitteroauth.php');
}	
  
class Twitter_Like_Box extends WP_Plugin_Base
{

	
	var $_options;
	var $_credits;
	var $_defaults;
	var $connection;
	var $error;
	protected $sections;
	function __construct() {
		
		$this->WPB_PREFIX		=	'tlb';
		$this->WPB_SLUG			=	'twitter-like-box-reloaded'; // Need to match plugin folder name
		$this->WPB_PLUGIN_NAME	=	'Twitter Like Box';
		$this->WPB_VERSION		=	'1.3.5.2';
		$this->PLUGIN_FILE		=   plugin_basename(__FILE__);
		$this->options_name		=   'tlb_settings';
		
		$this->sections['general']      		= __( 'Main Settings', $this->WPB_PREFIX );
		$this->sections['oauth']      		= __( 'OAuth Settings', $this->WPB_PREFIX );
		
		$version = get_option($this->WPB_PREFIX.'_version');
		
		//activation hook
		register_activation_hook( __FILE__, array(&$this,'activate' ));        
		
		
		//deactivation hook
		register_deactivation_hook( __FILE__, array(&$this,'deactivate' ));   
		
		//admin menu
		add_action( 'admin_menu',array(&$this,'register_menu' ) );
		
		//load scripts
		add_action( 'init',array(&$this,'load_scripts' ) ,50);
		
		
		$this->upgradePlugin();
			
		$this->setDefaults();
		
		$this->loadOptions();
		
		//Info boxes
		add_action('general_wpb_print_box' ,array(&$this,'print_general_box'));
		add_action('oauth_wpb_print_box' ,array(&$this,'print_oauth_box'));
		
		add_action('widgets_init', array(&$this,'init_widget' ) );	
		
		parent::__construct();
		
		
		$this->error = get_option($this->WPB_PREFIX.'_error');
		if( version_compare($this->WPB_VERSION,'1.3', '<') || $this->error != '' )
		{
			add_action( 'admin_notices', array( $this, 'plugin_activation_message' ) ) ;
		}
	
		
	}	
	
	/**
	* Return domain
	*/
	function get_domain(){
		return $this->WPB_PREFIX;
	}
		
	/**
	* Check technical requirements before activating the plugin. 
	* Wordpress 3.0 or newer required
	*/
	function activate()
	{

		
		parent::activate();
		update_option($this->WPB_PREFIX.'_version', $this->WPB_VERSION);
		
		do_action( $this->WPB_PREFIX.'_activate' );
		
		
	}	
	
	/**
	* Show admin notice when plugin is activated
	*/

	function plugin_activation_message()
	{
	
		
			$html = '<div class="error">';
			$html .= '<h1>'.$this->WPB_PLUGIN_NAME.'</h1>';
	        $html .= '<p>';
	          $html .= sprintf(__( 'Please go to the <a href="'.admin_url('options-general.php?page='.$this->WPB_SLUG).'">settings page</a> to enable OAuth in order to make the plugin work. For more detailed instructions, please consult the <a href="%s" target="_blank">Twitter Like Box documentation</a>.',$this->WPB_PREFIX), $this->WPB_PLUGIN_URL.'/docs/index.html#quickstart');
	        $html .= '</p>';
	        $html .= '</div><!-- /.updated -->';
 
			echo $html;
		
		
	}
	/**
	* Run when plugin is deactivated
	* Wordpress 3.0 or newer required
	*/
	function deactivate()
	{
		

		do_action( $this->WPB_PREFIX.'_deactivate' );
	}
	


	/**
	* function that register the menu link in the settings menu	and editor section inside the option page
	*/
	 function register_menu()
	{
		add_options_page( $this->WPB_PLUGIN_NAME, $this->WPB_PLUGIN_NAME, 'manage_options', $this->WPB_SLUG ,array(&$this, 'display_page') );

	}

	/**
	* Function to upgrade old plugin settings
	*/
	
	function upgradePlugin(){
	
		$options = get_option('tlb_option');
		
		if( !$options ) return;
		
		$new_options['border-color'] 	= $options['border-color'];
		$new_options['bordertop-color'] = $options['bordertop-color'];
		$new_options['bg-color'] 		= $options['bg-color'];
		$new_options['font-color']		= $options['font-color'];
		
		update_option($this->WPB_PREFIX.'_settings',$new_options);
		delete_option('tlb_option');
		
	
	}
	
	/**
	* Load options to use later
	*/	
	function loadOptions()
	{

		$this->_options = get_option($this->WPB_PREFIX.'_settings',$this->_defaults);

	}
	/**
	* Get options 	
	*/	
	function getOptions()
	{

		return $this->_options;

	}
	
		
	/**
	* loads plugins defaults
	*/
	function setDefaults()
	{
		$this->_defaults = array( 'version' => $this->WPB_VERSION,'border-color' => '#AAA','font-color' => '#3B5998','bordertop-color' => '#315C99','bg-color' => 'transparent','credits' => 'off' );		
	}
	
	/**
	* Load scripts
	*/
	function load_scripts(){
		
		if( is_admin() && isset($_GET['page']) && $_GET['page'] == $this->WPB_SLUG )
		{
			
			wp_enqueue_script('codemirror');
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script('colorpicker-handle');
		}
		if( is_admin() && isset($_GET['page']) && $_GET['page'] == $this->WPB_SLUG && isset($_GET['settings-updated']))
		{
			$this->connect();
		}
		//we use this bit to check connection when settings are saved
	}
	
	/**
	* Create Widget
	*/
	function init_widget(){
	
		register_widget('Twitter_Like_Box_Widget');
	
	}
	
	/**
	* Print general section Box
	*/
	function print_general_box(){
	
	?>
		<div class="info-box">
		
		<p><?php _e('Here you can change style and colors of the widget. To use the widget go to',$this->WPB_PREFIX);?> <a href="'.admin_url('widgets.php').'"><?php _e('Appearance -> Widgets',$this->WPB_PREFIX);?></a></p>
		
		<p><?php _e('To call the WP Twitter like box anyplace on your theme use:',$this->WPB_PREFIX);?></p>

			<pre>&lt;?php twitter_like_box($username=&quot;chifliiiii&quot;) ?&gt;</pre>

		<p><?php _e('Also you can change the total users to display and show users you follow by applying false to show followers',$this->WPB_PREFIX);?></p>

			<pre>&lt;?php twitter_like_box($username='chifliiiii', $total=25, $show_followers = 'false') ?&gt;</pre>
		
		<p><?php echo sprintf(__('Please check the extra options in the <a href="%s" target="_blank">documentation</a>',$this->WPB_PREFIX), $this->WPB_PLUGIN_URL.'/docs/index.html');?></p>
		
		<p>Also you can call the widget in any page by using shortcodes:</p>
		
			<pre>[TLB username="chifliiiii" total="33" width="50%"]</pre>
		
		<p><?php echo sprintf(__('Please check the extra options in the <a href="%s" target="_blank">documentation</a>',$this->WPB_PREFIX), $this->WPB_PLUGIN_URL.'/docs/index.html');?></p>
		
		</div><?php
	}

	/**
	* Print general section Box
	*/
	function print_oauth_box(){
	
		?>
		<div class="info-box">
		
		<p><?php echo sprintf(__('To use Twitter\'s REST API, you are required to authenticate with Twitter using OAuth as of version 1.1. You can acquire your OAuth details by registering with <a href="https://dev.twitter.com/" target="_blank">Twitter Developers</a> and creating a <a href="https://dev.twitter.com/apps/" target="_blank">Twitter application</a>. For more detailed instructions, please consult the <a href="%s" target="_blank">Twitter Like Box documentation</a>.',$this->WPB_PREFIX), $this->WPB_PLUGIN_URL.'/docs/index.html#quickstart');?></p>
		</div>
		<?php
	}
	
	/**
	* Function that handles oath connection
	*/
	function connect()
	{
		if( ! isset( $this->connection ) ) {
			// Create a TwitterOauth object with consumer/user tokens.
		    $this->connection = new TwitterOAuth(
		    	$this->_options['consumer-key'],
		    	$this->_options['consumer-secret'],
		    	$this->_options['access-token'],
		    	$this->_options['access-token-secret']
		    );
			$this->connection->host = "https://api.twitter.com/1.1/";
			
			
			if( $this->_options['consumer-key'] == '' || $this->_options['consumer-secret'] == '' || $this->_options['access-token'] == '' || $this->_options['access-token-secret'] == '') 
			{
				$this->log_error('Wrong consumer o access tokens');
				update_option($this->WPB_PREFIX .'_error', 'Error in connection');					
				
			}
			else
			{
				delete_option($this->WPB_PREFIX .'_error');
			}
		}
	}
	
	/**
	* Check for http errors
	*/
	function is_http_error( $http_code )
	{
		$error = false;
		switch( $http_code ) {
			case 400 :
			case 401 :
			case 403 :
			case 404 :
			case 406 :
			case 420 :
			case 422 :
			case 429 :
			case 500 :
			case 502 :
			case 503 :
			case 504 :
			case 'NULL':
			    $error = true;
			break;
		}
		return $error;
	}
	
	/**
	* Function that save Errors to db
	*/
	
	function log_error($error_code)
	{
		$error = $error_code .' - ' . date_i18n( 'm-d-Y @ H:i:s' );
		update_option('tlb_errors',$error);
	}
}

$tlb = new Twitter_Like_Box();

/**
* PHP Widget function
*/
function twitter_like_box($username = 'chifliiiii', $total = 10 ,$show_followers = 'followers', $width = "100%", $link_followers = "yes", $id = "tlb_php")
{
	global $tlb;
	$options = $tlb->_options;
	
	$widget = array('id' => $id,'username' => $username ,'total' => $total, 'show_followers' => $show_followers, 'width' => $width,'link_followers' => $link_followers, 'options' => $options);
	Twitter_Like_Box_Widget::display_widget($widget);
}

/**
* SHORTCODE
*/

add_shortcode('TLB','wp_tlb_shortcode');

function wp_tlb_shortcode($atts){
	extract( shortcode_atts( array(
		'id'				=> 'tlb_shortcode',
		'username' 			=> 'chifliiiii',
		'total'	 			=> 10,
		'show_followers' 	=> 'followers',
		'width' 			=> '100%',
		'link_followers' 	=> 'yes'
	), $atts ) );
	
	global $tlb;
	$options = $tlb->_options;
	
	$widget = array('id' => $id, 'username' => $username ,'total' => $total, 'show_followers' => $show_followers, 'width' => $width, 'link_followers' => $link_followers, 'options' => $options);
	
	return Twitter_Like_Box_Widget::get_tlb_widget($widget);
}