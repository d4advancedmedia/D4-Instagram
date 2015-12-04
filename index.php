<?php
/*
	Plugin Name: D4 Instagram
	Plugin URI: https://github.com/d4advancedmedia/
	GitHub Theme URI: https://github.com/d4advancedmedia/
	GitHub Branch: master
	Description: Simple Instagram Feed based off of Grayson Laramore's original code
	Version: 11Oct15
	Author: D4 Adv. Media
	License: GPL2
*/

wp_register_style( 'd4instagram', plugins_url( '/css/d4instagram.css' , __FILE__ ));
wp_enqueue_style('d4instagram' );

include ('lib/d4instagram.php');

?>