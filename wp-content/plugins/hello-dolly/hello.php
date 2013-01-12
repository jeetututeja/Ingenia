<?php
/**
 * @package Hello_Dolly
 * @version 1.6
 */
/*
Plugin Name: Hello Dolly
Plugin URI: http://wordpress.org/extend/plugins/hello-dolly/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Matt Mullenweg
Version: 1.6
Author URI: http://ma.tt/
*/

function hello_dolly_get_lyric() {
	/** These are the lyrics to Hello Dolly */
	$lyrics = "Ingenia. Ingeeeenia.
Estás aquí, de regreso a donde perteneces.
Stay Hungry. Stay Foolish.
Design touches everything we do. 
Good design should be innovative.
Good design should make a product useful
Good design is aesthetic design.
Good design will make a product understandable.
Good design is honest.
Good design is ubnostruisve.
And good design is as little design as possible.
Like to build crazy, amazing things.
Having a great idea is just the 10% of the work.
While some may see them as the crazy ones, we see genius.
Move fast.
Ship. Ship often.
Less, but better.
Let's go up, up, up 'til the last frontier (if there's one).
Don't stop 'til you get enough.
Done is a little better than perfect.
If you've never failed, you've never tried anything new.
Laser focus.
To infinity and beyond!
Es fuego.";

	// Here we split it into lines
	$lyrics = explode( "\n", $lyrics );

	// And then randomly choose a line
	return wptexturize( $lyrics[ mt_rand( 0, count( $lyrics ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later
function hello_dolly() {
	$chosen = hello_dolly_get_lyric();
	echo "<p id='dolly'>$chosen</p>";
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'hello_dolly' );

// We need some CSS to position the paragraph
function dolly_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#dolly {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;		
		margin: 0;
		font-size: 13px;
		color: #696969;
	}
	</style>
	";
}

add_action( 'admin_head', 'dolly_css' );

?>