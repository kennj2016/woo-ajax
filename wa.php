<?php
add_action( 'admin_menu', 'wa_menu', 99);

function wa_menu() {
	add_submenu_page( 'woocommerce',
	                  __('WAF shortcode builder', 'wa'),
	                  __('WAF shortcode builder', 'wa'),
	                  'edit_posts',
	                  'wafsb', 'wafsb_func');
}

function wafsb_func(){
	include __DIR__.'/wa-admin-shortcode-builder.php';
}

add_shortcode( 'wa_waf',
               'wa_waf_func');

function wa_waf_func($args){
	ob_start();
	include __DIR__.'/wa-front-shortcode-display.php';
	$str = ob_get_clean();
	return $str;
	
}

add_action( 'wp_ajax_wa_waf_get', 'wa_waf_get' );
add_action( 'wp_ajax_nopriv_wa_waf_get', 'wa_waf_get' );

function wa_waf_get(){
	$str = '';
	ob_start();
	include __DIR__.'/wa-ajax-shortcode.php';
	$str = ob_get_clean();
	ob_clean();
	exit($str);
}

add_action('wp_enqueue_scripts', 'wa_waf_scripts');

function wa_waf_scripts(){
	wp_enqueue_style( 'wa-waf', plugins_url('/assets/css/wa-waf-shortcode.css', __FILE__));
	wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}