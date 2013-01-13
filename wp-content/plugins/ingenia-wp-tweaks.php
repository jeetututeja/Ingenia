<?php
/*
Plugin Name: WordPress for Ingenia
Description: Site specific code changes for Ingenia. Do not show WordPress toolbar, change some untranslated slugs, etc.
Plugin URI: http://www.ingeniaup.com/
Author: Juan Diego Gonzales
Author URI: http://www.juandiegogonzales.com/
Version: 0.1
*/

/* Disable WordPress toolbar for all users */
show_admin_bar(false);

/* Change attachments slug to post/media/filename */
function __filter_rewrite_rules( $rules )
{
    $_rules = array();
    foreach ( $rules as $rule => $rewrite )
        $_rules[ str_replace( 'attachment/', 'media/', $rule  ) ] = $rewrite; // Change slug name
    return $_rules;
}
add_filter( 'rewrite_rules_array', '__filter_rewrite_rules' );

function __filter_attachment_link( $link )
{
    return preg_replace( '#attachment/(.+)$#', 'media/$1', $link ); // Change slug name
}
add_filter( 'attachment_link', '__filter_attachment_link' );

/* Change author slug to /autor/ */
add_action('init', 'change_author_base');
function change_author_base() {
    global $wp_rewrite;
    $author_slug = 'autor'; // Change slug name
    $wp_rewrite->author_base = $author_slug;
}

/* Change search slug to /buscar/ */
add_action('init', 'change_search_base');
function change_search_base() {
    global $wp_rewrite;
    $search_slug = 'buscar'; // Change slug name
    $wp_rewrite->search_base = $search_slug;
}

/* Redirect =s?{searchquery} to /buscar/ */
function search_url_rewrite_rule() {
        if ( is_search() && !empty($_GET['s'])) {
                wp_redirect(home_url("/buscar/") . urlencode(get_query_var('s')));
                exit();
        }
}
add_action('template_redirect', 'search_url_rewrite_rule');

/* Change comments feed slug to /comentarios/ */
add_action('init', 'change_comment_base');
function change_comment_base() {
    global $wp_rewrite;
    $comments_slug = 'comentarios'; // Change slug name
    $wp_rewrite->comments_base = $comments_slug;
}

?>