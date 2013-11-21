<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <header>
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<title><?php wp_title(' &mdash; ', true,'right'); ?><?php bloginfo('name'); ?></title>
<link rel="stylesheet" type="text/css" media="all" href="/wp-content/themes/Ingenia/style.css" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed">
	<div id="firebar">
		<?php show_top_must_read(); ?>
	</div>
	<header id="branding" role="banner">
		<div id="hello-user">
			<?php global $current_user;
				get_currentuserinfo();
				$current_user_username = $current_user->user_login;
				$current_user_email = $current_user->user_email;
				$current_user_firstname = $current_user->user_firstname;
				$current_user_lasname = $current_user->user_lastname;
				$current_user_displayname = $current_user->display_name;
				$current_user_userid = $current_user->ID;
				$current_user_nicename = $current_user->user_nicename;
				global $wp;
				$current_url = trailingslashit( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
			?>
			<?php echo get_avatar( $current_user_email, 200, 'http://www.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=100', '¡Hey! Aquí debes estar tú...' ); ?>
			<?php if (is_user_logged_in()) : ?>
				<p><?php fellow_user() ?><script src="<?php echo get_template_directory_uri(); ?>/js/ingenia.js"></script><br/>			
				<strong><?php if (!$current_user_firstname == "") : ?><?php echo $current_user_firstname ?><?php else : ?><?php echo $current_user_nicename ?><?php endif; ?></strong> <span class="online">●</span></p>
				<span class="tools"><strong><?php if (current_user_can('edit_posts')) : ?><a class="highlight" href="<?php echo get_bloginfo( 'url' ); ?>/wp-admin/post-new.php">Crear</a> · <?php endif; ?><a class="highlight" href="<?php echo get_bloginfo( 'url' ); ?>/wp-admin/profile.php">Perfil</a> ·</strong> <a href="<?php echo wp_logout_url($current_url) ?>">Salir</a></span>
			<?php else : ?>
				<p>Hola. Eres bienvenido,
				<strong>Humano misterioso</strong></p>
				<span class="tools"><a href="<?php echo get_bloginfo( 'url' ) ?>/wp-login.php?redirect_to=<?php echo $current_url ?>">Ingresa</a> <strong>· <a class="highlight" href="<?php echo get_bloginfo( 'url' ) ?>/wp-login.php?action=register&redirect_to=<?php echo $current_url ?>">Regístrate</a></strong></span>
			<?php endif; ?>
		</div>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><hgroup>
			<h1 id="site-title"><?php bloginfo( 'name' ); ?></h1>
			<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
		</hgroup></a>

		<?php get_search_form(); ?>

		<nav id="access" role="navigation">
			<?php /* Our navigation menu. If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assigned to the primary location is the one used. If one isn't assigned, the menu with the lowest ID is used. */ ?>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_id' => 'sections' ) ); ?>
			<?php wp_nav_menu( array( 'theme_location' => 'secondary', 'container_id' => 'companies' ) ); ?>
		</nav><!-- #access -->
		<div align="center">
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- ingenia -->
			<ins class="adsbygoogle"
			     style="display:inline-block;width:728px;height:90px"
			     data-ad-client="ca-pub-2188285947855424"
			     data-ad-slot="7321360991"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		</div>
	</header><!-- #branding -->