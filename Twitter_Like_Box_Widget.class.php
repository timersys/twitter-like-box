<?php
/**
*
* Widget Class  
*/

class Twitter_Like_Box_Widget extends WP_Widget
{

	var $_options;
	function __construct( ) {
	
		global $tlb;
		
		$this->wpb_prefix = $tlb->get_domain();
		
		$widget_ops = array( 'classname' => 'tlb_widget', 'description' => ' Display your Twitter followers along with a follow me button' ); // Widget Settings

        $control_ops = array( 'id_base' => 'tlb_widget' ); // Widget Control Settings

        $this->WP_Widget( 'tlb_widget', 'Twitter Like Box', $widget_ops, $control_ops ); 
        
        $this->_options = $tlb->getOptions();
        
     }
	
	
	//Function to init the widget values and call the display widget function
	function widget($args,$instance)
	{
		$title 			= apply_filters('widget_title', $instance['title']); // the widget title
		$username 		= $instance['username']; // the widget title
		$total_number 	= $instance['total_number']; // the number of followers to show
		$show_followers = $instance['show_followers']; // show followers or users i follow
		$link_followers = $instance['link_followers']; // link followers to profile
		$width 			= $instance['width']; // link followers to profile
				
		$widget = array ( 'username' => $username ,'total' => $total_number , 'show_followers' => $show_followers ,'link_followers'=> $link_followers, 'width' => $width, 'options' => $this->_options);
		
		echo $args['before_widget'];
	    
		if ( $title )
			echo $args['before_title'] . $title . $args['after_title']; 
	
		$this->display_widget($widget);
	
	    echo $args['after_widget'];
			
	}
	
	
//function that display the widget form 
	function form($instance) 
	{
		global $tlb;

 		$defaults = array( 'total_number' => 10, 'show_followers' => 'followers','link_followers'=> 'on','title' => 'My Followers', 'username' => 'chifliiiii');
 		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
 		
 		<?php if( $tlb->error != '' && defined('DOING_AJAX')):?>
 				<div class="error">
 					<p><?php echo sprintf(__('Check you <a href="%s">OAuth settings</a>, there is a problem with the connection.',$this->wpb_prefix),admin_url('options-general.php?page=twitter-like-box-reloaded'));?></p>
 				</div>
 		<?php endif;?>

 		<p>
 			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:',$this->wpb_prefix);?></label>
 			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
 		</p>
 		
 		 <p>
 			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username (without @):',$this->wpb_prefix);?></label>
 			<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>'" type="text" value="<?php echo $instance['username']; ?>" />
 		</p>
 		
 		<p>
 			<label for="<?php echo $this->get_field_id('show_thumbs'); ?>"><?php _e('Show Followers or people you follow?',$this->wpb_prefix);?></label>
 		</p>	
 		<ul>
 			<li>	
 				<input type="radio" class="radio" <?php checked( $instance['show_followers'], 'followers' ); ?> id="<?php echo $this->get_field_id('show_followers'); ?>" name="<?php echo $this->get_field_name('show_followers'); ?>"  value="followers"/> <?php _e('Followers',$this->wpb_prefix);?>
 				</li>
 			<li>
 				<input type="radio" class="radio" <?php checked( $instance['show_followers'], 'nofollowers' ); ?> id="<?php echo $this->get_field_id('show_followers'); ?>" name="<?php echo $this->get_field_name('show_followers'); ?>" value="nofollowers" /> <?php _e('People I follow',$this->wpb_prefix);?>
 			</li>	
 		</ul>
 		
 		
 		<p>
 			<label for="<?php echo $this->get_field_id('total_number'); ?>"><?php _e('How many you want to show?',$this->wpb_prefix); ?></label>
 			<input class="widefat" id="<?php echo $this->get_field_id('total_number'); ?>" name="<?php echo $this->get_field_name('total_number'); ?>" type="text" value="<?php echo $instance['total_number']; ?>" />
 		</p>
 
 		<p>
 			<label for="<?php echo $this->get_field_id('link_followers'); ?>"><?php _e('Link followers to their profiles?',$this->wpb_prefix); ?></label>
 			<input type="checkbox" class="checkbox" <?php checked( $instance['link_followers'], 'on' ); ?> id="<?php echo $this->get_field_id('link_followers'); ?>" name="<?php echo $this->get_field_name('link_followers'); ?>" value="on" /> 

 		</p>
 		<p>
 			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Widget width:',$this->wpb_prefix); ?></label>
 			<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width') ; ?>'" type="text" value="<?php echo isset($instance['width']) ? $instance['width'] : '100%'; ?>" />

 		</p>
 		<p>
 			<a href="<?php echo site_url();?>/wp-admin/options-general.php?page=twitter-like-box-reloaded" title="" target="_blank"><?php _e('More settings',$this->wpb_prefix);?></a>
 		</p>
        <?php 

	}	
	
		//function that save the widget
	function update($new_instance, $old_instance) 
	{
 			$instance['title']			 = strip_tags($new_instance['title']);
 			$instance['username'] 		= strip_tags( $new_instance['username'] )== '' ? 'chifliiiii' : strip_tags( $new_instance['username'] );
 			$instance['total_number'] 	= strip_tags($new_instance['total_number']);
 			$instance['show_followers'] = $new_instance['show_followers'];
 			$instance['link_followers'] = $new_instance['link_followers'];
 			$instance['width'] 			= $new_instance['width'];
 			
 			//Delete transient in case exist
 			$key = 'tlb_widgets_' . $instance['username'];
 			delete_transient($key);
 			return $instance;
 	}
	
	
	
	//Finally thevfunction that create the widget 
	static function get_tlb_widget($widget)
	{
		global $tlb,$you;
		
		
		$wpb_prefix = $tlb->get_domain();
		$twitter =  self::fetch_twitter_followers($widget);
		
		ob_start();
		
		if( !empty($you['error']) && '32' == $you['code']) {
			
			echo $you['error'];
		}
		else
		{
		?>
		<style type="text/css">
			 #tlb_container {
				 box-sizing: border-box;
			 }
			 #tlb_container * {
				box-sizing: content-box;
				-moz-box-sizing: content-box;
				-webkit-box-sizing: content-box;
			}
			#tlb_container {
				border:1px solid <?php echo $widget['options']['border-color'];?>;
				background:<?php echo $widget['options']['bg-color'];?>; 
				border-top-color:<?php echo $widget['options']['bordertop-color'];?>;
				padding:5px 5px 0 5px; 
				font-family:'Lucida grande',tahoma,verdana,arial,sans-serif;
				
			}
			#tlb_header {
				color:#555;
				border-bottom:1px solid #D8DFEA;
				color:#555; 
				height:60px; 
				position:relative;
			}
			#tlb_profile_img{
				position:absolute;
				top:5px;
				left:5px;
			}
			#tlb_name{
				position:absolute;
				top:5px;
				left:59px;
				font-size:14px;
				line-height:14px;
				font-weight:bold;
			}
			#tlb_name a{
				color:<?php echo $widget['options']['font-color'];?>;
			}
			#tlb_name a span{
				font-weight:normal;
				font-size:10px;
			}
			#tlb_follow{
				position:absolute;
				top:30px;
				left:59px;
			}
			#tlb_follow_total{
				padding:5px 5px 0px 5px;
				font-size:11px;
			}
			.tlb_user_item{
				line-height:1;
				padding:5px 0px 3px 5px;
				width:48px;
				float:left;
				text-align: center;
			}
			.tlb_user_item a{
				color:gray;
			}
			.tlb_user_item span{
				font-family:Arial;
				font-size:10px;
			}
			<?php echo $widget['options']['custom_css'];?>
		</style>

		<div id="tlb_container" style="width: <?php echo isset($widget['width']) ? $widget['width'] : 'auto';?>">
		<?php if(isset($twitter['error']) ) :?>
				<?php echo $twitter['error'];?>
		<?php else : ?>		
				<div id="tlb_header">
					<div id="tlb_profile_img">
						<a target="_blank" href="http://twitter.com/<?php echo $widget['username'];?>">
							<img src="<?php echo $twitter['profile_image_url'];?>" width="44" height="44" alt="<?php echo $widget['username'];?>">
						</a>
					</div>
					<div id="tlb_name">
						<a target="_blank" href="http://twitter.com/<?php echo $widget['username'];?>">
							<?php echo $widget['username'];?><span> <?php _e('on Twitter',$wpb_prefix);?></span>
						</a>
					</div>
					<div id="tlb_follow">
						<a href="https://twitter.com/<?php echo $widget['username'];?>" class="twitter-follow-button" data-show-count="false" data-width="65px" data-show-screen-name="false"><?php _e('Follow @',$wpb_prefix);?><?php echo $widget['username'];?></a>
					</div>
				</div>
			<div style="padding:0;">
				<div id="tlb_follow_total">
					<?php 
					if ( $widget['show_followers'] == 'followers')
					{
						echo $twitter['followers_count'].' '.__('people follow',$wpb_prefix).' <strong>'. $widget['username'].'</strong>';
					}
					else
					{
						echo __('You follow ',$wpb_prefix). $twitter['friends_count'].__(' users',$wpb_prefix);
					}
					?>	
				</div>
				<?php for($i=0; $i < $widget['total']; $i++)	:?>
				
					<span class="tlb_user_item">
					<?php if($widget['link_followers'] == 'on' ): ?>
						<a target="_blank" href="http://twitter.com/<?php echo $twitter['followers'][$i]['screen_name'];?>" title="<?php echo $twitter['followers'][$i]['screen_name'];?>" rel="nofollow">
					<?php endif;?>	
							<img src="<?php echo $twitter['followers'][$i]['profile_image_url'];?>" width="48" height="48" alt="<?php echo $twitter['followers'][$i]['screen_name'];?>">
							<span><?php echo substr($twitter['followers'][$i]['screen_name'], 0, 8);?></span>
					<?php if($widget['link_followers'] == 'on' ): ?>		
						</a>
					<?php endif;?>		
					</span>
				<?php endfor;?>
			<br style="clear:both">
			</div>
	
			<?php if ( $widget['options']['credits'] == 'true' ) echo '<div style="font-size:9px;text-align:right;">Widget By <a href="http://www.timersys.com/" title="Timersys">Timersys</a></div>';?>
		<?php endif;//twitter error ?>
		</div>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
		</script>
	<?php
		}
		$widget_code = ob_get_contents();
		ob_end_clean();
		return $widget_code;
	
	}
	
	/**
	* Display widget
	*/
	static function display_widget($options){
	
		echo Twitter_Like_Box_Widget::get_tlb_widget($options);
	}
	
	
	static function fetch_twitter_followers($options)
	{
		global $tlb,$you;
		
		$cache_time = $tlb->_options['cache-time'];
		$id = isset($options['id']) ? $options['id'] : 'widgets';

		$key = 'tlb_'.$id.'_' . $options['username'];
	
		// Let's see if we have a cached version
		$followers = get_transient($key);
		
		if ($followers !== false)
			return $followers;
		else
		{
			$tlb->connect();
			
			$response = $tlb->connection->get("users/lookup", array('screen_name' => $options['username']));
			
			
			if (Twitter_Like_Box_Widget::is_twitter_error($response))
			{
				// In case Twitter is down we return the last successful count
				return get_option($key);
			
			}
			else
			{ 
				
				$json = $response;
				
	
				
				@$you['name'] 			 	= $json[0]->name;
				@$you['screen_name']	 	= $json[0]->screen_name;
				@$you['followers_count'] 	= $json[0]->followers_count;
				@$you['profile_image_url']	= $json[0]->profile_image_url;
				@$you['friends_count']		= $json[0]->friends_count;
	
			
				if ( $options['show_followers'] == 'followers' )
				{
					$fans = $tlb->connection->get('followers/ids',array('screen_name' => $options['username']));
				}
				else
				{
					$fans = $tlb->connection->get('friends/ids',array('screen_name' => $options['username']));
				}
			
				
				
				if (!Twitter_Like_Box_Widget::is_twitter_error($fans))
				{
					if ($options['total'] > 90 )
					{
						$fans_ids = array_chunk($fans->ids, 90);
						$fans = array();
						foreach ( $fans_ids as $ids_a )
						{
							$fans_ids = (string)implode( ',', $ids_a );
							@$result = $tlb->connection->get('users/lookup',array('user_id' => $fans_ids ));
							
							@$fans 	= array_merge($fans , $result ); 
							
						}
					}
					else
					{
						
						$fans_ids = (string)implode( ',', array_slice($fans->ids, 0, $options['total']) );
					    @$fans = $tlb->connection->get('users/lookup',array('user_id' =>$fans_ids));
					
					}			
				}
				
			
					
				if( !Twitter_Like_Box_Widget::is_twitter_error($fans) && isset($fans[0]->screen_name) )
				{
					$followers = array();
				    for($i=0; $i < $options['total']; $i++)
				    {
				        $followers[$i]['screen_name'] 		= (string)$fans[$i]->screen_name;
				        $followers[$i]['profile_image_url'] = (string)$fans[$i]->profile_image_url;
				    }
				    
				    $you['followers'] = $followers;	
				    // Store the result in a transient, expires after 1 hour
					// Also store it as the last successful using update_option
					set_transient($key, $you, 60*60* $cache_time);
					update_option($key, $you);
				}
				
			    return $you;	
			    
			}
			
		}

	
	}
	static function is_twitter_error($response){
		global $you,$tlb;

		
		if(is_object($response) && isset($response->errors) )
		{
			$you['error'] = 'Error code: '. $response->errors[0]->code .'<br>Error message: '.$response->errors[0]->message;
			$you['code']  = $response->errors[0]->code;
			$tlb->log_error($you['error']);
						
			return true;
		}
		if(is_object($response) && isset($response->ids) && empty($response->ids))
		{
			$you['error'] = '<br>Error message: You got no users to show. check if you have followers';
			$tlb->log_error($you['error']);
			
			return true;
		}		
		
		return false;
	}	
	
	
} //end of class