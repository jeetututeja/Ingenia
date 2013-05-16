<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	</div><!-- #main -->

	<footer id="colophon" role="contentinfo">

			<?php
				/* A sidebar in the footer? Yep. You can can customize
				 * your footer with three columns of widgets.
				 */
				if ( ! is_404() )
					get_sidebar( 'footer' );
			?>

			<div id="site-generator">
				Edición por <b>Álvaro</b>, Juan Diego y <b>Mauricio</b>. Diseño por <a href="http://juandiegogonzales.com">Juan Diego</a>.
				<br>
				<a mailto="<?php get_bloginfo('admin_email') ?>"><?php echo get_bloginfo('admin_email') ?></a>
			</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>