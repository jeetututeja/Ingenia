<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 */

get_header(); ?>
		<div id= "galaxy">
		
			<?php
			$editorial_query = new WP_Query( array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'editorial',
						'field' => 'slug',
						'terms' => 'big-story-1'
					)
				)
			) );
			// Display the custom loop
			if ( $editorial_query->have_posts() ): ?>
				<?php while ( $editorial_query->have_posts() ) : $editorial_query->the_post(); ?>
				
					<?php if ( has_post_thumbnail() ) {
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'galaxy' );
						$link = get_permalink();
						$title = get_the_title();
						$author = get_the_author();
						echo "<div class='galaxy-feature' style='background-image:url($image[0]);'>" . "<a class='feature-full' href='$link' alt='$title'></a>" . "<div class='galaxy-heading left'><h2><a class='galaxy-title' href='$link' alt='$title'>$title</a></h2><p>Por <span>$author</span></p></div></div>";
					}
					?>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php endif; ?>
			<?php
			$editorial_query = new WP_Query( array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'editorial',
						'field' => 'slug',
						'terms' => 'big-story-2'
					)
				)
			) );
			// Display the custom loop
			if ( $editorial_query->have_posts() ): ?>
				<?php while ( $editorial_query->have_posts() ) : $editorial_query->the_post(); ?>
				
					<?php if ( has_post_thumbnail() ) {
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'galaxy' );
						$link = get_permalink();
						$title = get_the_title();
						$author = get_the_author();
						echo "<div class='galaxy-feature' style='background-image:url($image[0]);'>" . "<a class='feature-full' href='$link' alt='$title'></a>" . "<div class='galaxy-heading right'><h2><a class='galaxy-title' href='$link' alt='$title'>$title</a></h2><p>Por <span>$author</span></p></div></div>";
					}
					?>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php endif; ?>
			
		</div><!-- #galaxy -->
			
		<div id="planet">
			
			<?php
			$editorial_query = new WP_Query( array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'editorial',
						'field' => 'slug',
						'terms' => 'mercury'
					)
				)
			) );
			// Display the custom loop
			if ( $editorial_query->have_posts() ): ?>
				<?php while ( $editorial_query->have_posts() ) : $editorial_query->the_post(); ?>
				
					<?php if ( has_post_thumbnail() ) {
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'galaxy' );
						$link = get_permalink();
						$title = get_the_title();
						$author = get_the_author();
						echo "<div class='planet-feature' style='background-image:url($image[0]);'>" . "<a class='feature-full' href='$link' alt='$title'></a>" . "<div class='planet-heading'><h2><a class='planet-title' href='$link' alt='$title'>$title</a></h2><p>Por <span>$author</span></p></div></div>";
					}
					?>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php endif; ?>

			<?php
			$editorial_query = new WP_Query( array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'editorial',
						'field' => 'slug',
						'terms' => 'venus'
					)
				)
			) );
			// Display the custom loop
			if ( $editorial_query->have_posts() ): ?>
				<?php while ( $editorial_query->have_posts() ) : $editorial_query->the_post(); ?>
				
					<?php if ( has_post_thumbnail() ) {
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'galaxy' );
						$link = get_permalink();
						$title = get_the_title();
						$author = get_the_author();
						echo "<div class='planet-feature' style='background-image:url($image[0]);'>" . "<a class='feature-full' href='$link' alt='$title'></a>" . "<div class='planet-heading'><h2><a class='planet-title' href='$link' alt='$title'>$title</a></h2><p>Por <span>$author</span></p></div></div>";
					}
					?>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php endif; ?>

			<?php
			$editorial_query = new WP_Query( array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'editorial',
						'field' => 'slug',
						'terms' => 'mars'
					)
				)
			) );
			// Display the custom loop
			if ( $editorial_query->have_posts() ): ?>
				<?php while ( $editorial_query->have_posts() ) : $editorial_query->the_post(); ?>
				
					<?php if ( has_post_thumbnail() ) {
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'galaxy' );
						$link = get_permalink();
						$title = get_the_title();
						$author = get_the_author();
						echo "<div class='planet-feature' style='background-image:url($image[0]);'>" . "<a class='feature-full' href='$link' alt='$title'></a>" . "<div class='planet-heading'><h2><a class='planet-title' href='$link' alt='$title'>$title</a></h2><p>Por <span>$author</span></p></div></div>";
					}
					?>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php endif; ?>
			
		</div><!-- #planet -->

		<div id="big-headline">
			<?php
			$editorial_query = new WP_Query( array(
				'post_type' => 'post',
				'posts_per_page' => 1,
				'tax_query' => array(
					array(
						'taxonomy' => 'editorial',
						'field' => 'slug',
						'terms' => 'big-headline'
					)
				)
			) );
			// Display the custom loop
			if ( $editorial_query->have_posts() ): ?>
				<?php while ( $editorial_query->have_posts() ) : $editorial_query->the_post(); 
					$link = get_permalink();
					$title = get_the_title();
					$author = get_the_author();	
					$image = get_the_post_thumbnail( $post->ID, 'galaxy', array(
						'class'	=> null,
						'title'	=> $title,
						)
					);
					$time = human_time_diff( get_the_time('U'), current_time('timestamp') );
				?>
					<h2><a href='<?php echo $link ?>' title='<?php echo $title ?>'><?php echo $title ?></h2>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php endif; ?>			
		</div><!-- #big-headline -->

		<div id="aurora">
			
			<?php
			$editorial_query = new WP_Query( array(
				'post_type' => 'post',
				'posts_per_page' => 3,
				'tax_query' => array(
					array(
						'taxonomy' => 'editorial',
						'field' => 'slug',
						'terms' => 'aurora'
					)
				)
			) );
			// Display the custom loop
			if ( $editorial_query->have_posts() ): ?>
				<?php while ( $editorial_query->have_posts() ) : $editorial_query->the_post(); 
					$link = get_permalink();
					$title = get_the_title();
					$author = get_the_author();	
					$time = human_time_diff( get_the_time('U'), current_time('timestamp') );
					$excerpt = get_the_excerpt();
					$content = get_the_content();
					$q = false;
					$i2 = false;
					// if (preg_match('/<img(.*?)>/s', $content, $image) === 1) {
					//	$i2 = "<div class='aurora-img-2'><a href='$link' title='Sigue leyendo: $title' >$image[0]</a></div>";
					// }
					if (preg_match('/<q(.*?)<\/q>/s', $content, $quote) === 1) {
						$q = "<p class='aurora-quote'><a href='$link' title='Sigue leyendo: $title' >$quote[0]</a></p>";
					}
					if ( has_post_thumbnail() ) {
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'galaxy' );
					}
					echo "<div class='aurora-feature'>" . "<div class='aurora-img' style='background-image:url($image[0]);'><a class='feature-full' href='$link' title='$title'></a>" . "<div class='aurora-title'><h2><a class='aurora-title-link' href='$link' alt='$title'>$title</a></h2></div></div>" . "<div class='aurora-meta'>" . "<p class='aurora-time-author'><span class='aurora-time'>Hace $time</span> <strong>·</strong> Por <span class='aurora-author'>$author</span></p>" . "<p class='aurora-excerpt'>$excerpt</p>" . "$q" . "$i2" . "</div></div>";
				?>
					
				<?php endwhile; wp_reset_postdata(); ?>
			<?php endif; ?>
			
		</div><!-- #aurora -->


		<div id="nebula">
			
			<?php
			$editorial_query = new WP_Query( array(
				'post_type' => 'post',
				'posts_per_page' => 3,
				'tax_query' => array(
					array(
						'taxonomy' => 'editorial',
						'field' => 'slug',
						'terms' => 'nebula'
					)
				)
			) );
			// Display the custom loop
			if ( $editorial_query->have_posts() ): ?>
				<?php while ( $editorial_query->have_posts() ) : $editorial_query->the_post(); 
					$link = get_permalink();
					$title = get_the_title();
					$author = get_the_author();	
					$image = get_the_post_thumbnail( $post->ID, 'galaxy', array(
						'class'	=> null,
						'title'	=> $title,
						)
					);
					$time = human_time_diff( get_the_time('U'), current_time('timestamp') );
				?>
					
				<div class='nebula-feature'>
					<p class='nebula-category'><?php the_category(' '); ?></p>
					<div class='the-nebula'>
						<a class='nebula-img' href='<?php echo $link ?>' title='<?php echo $title ?>'><?php echo $image ?></a>
					</div>
					<div class='nebula-heading'>
						<h2><a class='nebula-title' href='<?php echo $link ?>' alt='<?php echo $title ?>'><?php echo $title ?></a></h2>
						<p>Hace <?php echo $time ?> <strong>·</strong> Por <span><?php echo $author ?></span></p>
					</div>
				</div>
					
				<?php endwhile; wp_reset_postdata(); ?>
			<?php endif; ?>
			
		</div><!-- #nebula -->
		
		
		<div id="main">
			
		<div id="primary">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>

				<?php twentyeleven_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>