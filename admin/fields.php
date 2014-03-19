<?php
	$options = get_option( $this->options_name );
	/* General Settings
	===========================================*/
	$errors =  get_option('tlb_errors');
		

	
	$this->settings['border-color'] = array(
		'title'   => __( 'Border Color' , $this->WPB_PREFIX),
		'desc'    => __( 'Widget border color' , $this->WPB_PREFIX),
		'std'     => '#AAA',
		'type'    => 'color',
		'section' => 'general'
	);

	$this->settings['bordertop-color'] = array(
		'title'   => __( 'Top Border Color' , $this->WPB_PREFIX),
		'desc'    => __( 'Color of the top border.' , $this->WPB_PREFIX),
		'std'     => '#315C99',
		'type'    => 'color',
		'section' => 'general'
	);

	$this->settings['bg-color'] = array(
		'title'   => __( 'Background Color' , $this->WPB_PREFIX),
		'desc'    => __( 'Widget background color' , $this->WPB_PREFIX),
		'std'     => 'transparent',
		'type'    => 'color',
		'section' => 'general'
	);
	
	$this->settings['font-color'] = array(
		'section' => 'general',
		'title'   => 'Font Color', // Not used for headings.
		'desc'    => 'Widget font color',
		'type'    => 'color',
		'std'	  => '#3B5998'
	);
	
	$this->settings['custom_css'] = array(
		'title'   => __( 'Custom CSS' , $this->WPB_PREFIX),
		'desc'    => __( 'Enter here your custom CSS' , $this->WPB_PREFIX),
		'type'    => 'code',
		'section' => 'general',
		'std'     => '#tlb_container {
	
}
#tlb_header {

}
#tlb_profile_img{

}
#tlb_name{

}
#tlb_name a{
	
}
#tlb_name a span{

}
#tlb_follow{

}
#tlb_follow_total{

}
.tlb_user_item{

}
.tlb_user_item a{

}
.tlb_user_item span{

}
'
	
	);

	$this->settings['credits'] = array(
		'title'   => __( 'Credits Url' , $this->WPB_PREFIX),
		'desc'    => __( 'Give us some support by enabling the small link on footer.' , $this->WPB_PREFIX),
		'std'     => 'false',
		'type'    => 'select',
		'section' => 'general',
		'choices' => array(
			'true' => __( 'Yes' , $this->WPB_PREFIX),
			'false' => __( 'No' , $this->WPB_PREFIX)
		)
	);

/**
* OAuth settings
*/

	$this->settings['consumer-key'] = array(
		'title'   => __( 'Api Key' , $this->WPB_PREFIX),
		'desc'    => '',
		'std'     => '',
		'type'    => 'text',
		'section' => 'oauth'
	);
	
	$this->settings['consumer-secret'] = array(
		'title'   => __( 'Api Secret' , $this->WPB_PREFIX),
		'desc'    => '',
		'std'     => '',
		'type'    => 'password',
		'section' => 'oauth'
	);
	$this->settings['access-token'] = array(
		'title'   => __( 'Access token' , $this->WPB_PREFIX),
		'desc'    => '',
		'std'     => '',
		'type'    => 'text',
		'section' => 'oauth'
	);
	
	$this->settings['access-token-secret'] = array(
		'title'   => __( 'Access token secret' , $this->WPB_PREFIX),
		'desc'    => '',
		'std'     => '',
		'type'    => 'password',
		'section' => 'oauth'
	);

	$this->settings['cache-time'] = array(
		'title'   => __( 'Cache Expire time' , $this->WPB_PREFIX),
		'desc'    => 'Twitter Like Box is saved in the Wordpress cache to prevent waste of resources and to load your pages faster. By default the cache expires in 6 hours.',
		'std'     => '6',
		'type'    => 'text',
		'section' => 'oauth'
	);

	$this->settings['errors'] = array(
		'title'   => __( 'Connections errors' , $this->WPB_PREFIX),
		'desc'    => '',
		'std'     => $errors,
		'type'    => 'disabled',
		'section' => 'oauth'
	);