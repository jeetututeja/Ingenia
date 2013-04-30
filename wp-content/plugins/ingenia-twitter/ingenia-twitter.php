<?php
/*
Plugin Name: Ingenia to Twitter
Description: Ingenia posts to Twitter.
Plugin URI: http://www.juandiegogonzales.com/
Author: Juan Diego Gonzales
Author URI: http://www.juandiegogonzales.com/
Version: 0.1
*/

add_action('publish_post', 'tweet_post');

function tweet_post() {
	// set global post var
	global $post; 

	// keys/tokens
	$consumerKey = '731fOaF7mZFFmCOvuhherA';
	$consumerSecret = 'HTeEP7tS9PrTHZsgNXMkX7YhrfGHOrCBCQq0QITzR0';
	$oAuthToken = '1199466020-pokzhoWEqS1yti5jh4bDsg2EVfeVFr7DK8Cv8NW';
	$oAuthSecret = 'yz5LfTRXLCxn6FVOdOQt5lk0zXyMZftylaVvxb3YFI';

	// path to twitteroauth.php
	require_once('twitteroauth.php');

	// create new instance
	$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);

	// the message
	$message = get_the_title($post->ID) . ' ' . home_url('/' . $post->ID);

	// let's send the tweet!
	$tweet->post('statuses/update', array('status' => "$message"));
}