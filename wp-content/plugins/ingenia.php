<?php
/*
Plugin Name: Ingenia
Description: Site specific code changes for Ingenia.
Plugin URI: http://juandiegogonzales.com/
Author: Juan Diego Gonzales
Author URI: http://juandiegogonzales.com/
Version: 0.1
*/

remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');

function addAnalytics() {
    echo "<script type='text/javascript'>
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-30916542-1']);
    _gaq.push(['_setDomainName', 'ingeniaup.com']);
    _gaq.push(['_trackPageview']);

    (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>\n";
}

add_action('wp_head', 'addAnalytics'); // Add hook for front-end <head></head>

/*
function addBetterGraphImage() {
    if ( is_single() ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'graph' );
        echo "<meta property='og:image' content='" . $image[0] . "' />\n";
        echo "<meta property='og:image:width' content='600' />\n";
        echo "<meta property='og:image:height' content='600' />\n";
    }
}
add_action('wp_head', 'addBetterGraphImage'); // Add hook for front-end <head></head>
*/

add_filter( 'wp_mail_from_name', 'mail_from_site_name' );
function mail_from_site_name() {
    return get_bloginfo('name');
}
add_filter ("wp_mail_from", "mail_from_site_admin_email");
function mail_from_site_admin_email() {
    return get_bloginfo('admin_email');
}

/* add_action('template_redirect', 'redirect_shortlink');
function redirect_shortlink() {
    global $post;
    $url = site_url() . $_SERVER['REQUEST_URI'];
    $shortlink = site_url() . '/' . $post->ID . '/';
    if ( is_single() && $url === $shortlink ) {
        wp_redirect( get_post_permalink($post->ID) , 301 ); 
        exit;
    }
} */

add_filter('pre_get_shortlink', 'beautiful_shortlink'); 

function beautiful_shortlink() {
    global $post;
    if (is_single()) {
        $shortlink = site_url() . '/' . $post->ID;
        return $shortlink;
    }
}

add_action('template_redirect', 'redirect_simpleurl');
function redirect_simpleurl() {
    $url = $_SERVER['REQUEST_URI'];
    $has_slash = strpos($url, "/", 1);
    if ($has_slash === false) {
        $postid = substr($url, 1);
    } else {
        $postid = substr($url, 1, $has_slash-1);
    }
    if (is_404() && get_permalink($postid)) {
        wp_redirect( get_permalink($postid), 301 ); 
        exit;
    }
}

/* Disable WordPress toolbar for all users */
show_admin_bar(false);
add_action( 'admin_print_styles-profile.php', 'global_profile_hide_admin_bar' );
add_action( 'admin_print_styles-user-edit.php', 'global_profile_hide_admin_bar' );
function global_profile_hide_admin_bar() {
    echo '<style type="text/css">.show-admin-bar { display: none !important; }</style>';
}


/* Change attachments slug to post/media/filename */
function __filter_rewrite_rules( $rules ) {
    $_rules = array();
    foreach ( $rules as $rule => $rewrite )
        $_rules[ str_replace( 'attachment/', 'media/', $rule  ) ] = $rewrite; // Change slug name
    return $_rules;
}
add_filter( 'rewrite_rules_array', '__filter_rewrite_rules' );

function __filter_attachment_link( $link ) {
    return preg_replace( '#attachment/(.+)$#', 'media/$1', $link ); // Change slug name
}
add_filter( 'attachment_link', '__filter_attachment_link' );


/* Change author slug to /equipo/ */
add_action('init', 'change_author_base');
function change_author_base() {
    global $wp_rewrite;
    $author_slug = 'equipo'; // Change slug name
    $wp_rewrite->author_base = $author_slug;
}


/* Change search slug to /buscar/ */
add_action('init', 'change_search_base');
function change_search_base() {
    global $wp_rewrite;
    $search_slug = 'buscar'; // Change slug name
    $wp_rewrite->search_base = $search_slug;
}


/* Redirect /=s?{searchquery} to /buscar/{searchquery} */
function search_url_rewrite_rule() {
        if ( is_search() && !empty($_GET['s'])) {
                wp_redirect(home_url("/buscar/") . urlencode(get_query_var('s')));
                exit();
        }
}
add_action('template_redirect', 'search_url_rewrite_rule');


/* Change comments feed slug to feed/comentarios/ */
add_action('init', 'change_comment_base');
function change_comment_base() {
    global $wp_rewrite;
    $comments_slug = 'comentarios'; // Change slug name
    $wp_rewrite->comments_base = $comments_slug;
}


/* Remove Links, Tools, Theme/Plugin Editor menu from WordPress dashboard for all users including admin */
add_action( 'admin_menu', 'my_remove_menu_pages', 999 );
function my_remove_menu_pages() {
    remove_menu_page('link-manager.php');
    // remove_menu_page('tools.php');
    remove_submenu_page('themes.php', 'theme-editor.php');
    remove_submenu_page('plugins.php', 'plugin-editor.php');
    remove_submenu_page('edit.php', 'edit-tags.php');
    if ( !current_user_can( 'admin' ) ) {
        remove_menu_page('edit.php?post_type=page'); // Remove Page editor for users except admin
    }
}


/* Remove unnecesary dashboard elements from all users */
function remove_dashboard_widgets() {
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    if ( current_user_can( 'subscriber' ) ) {
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' ); // For security reasons as Subscribers can edit their comments
    }
}
add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );


/* Now registered users can edit their own comments! */
function add_theme_caps() {
    // Gets the subscriber role
    $role = get_role( 'subscriber' );
    // This only works, because it accesses the class instance.
    // would allow the author of a comment to edit it.
    $role->add_cap( 'edit_published_posts' );
}
add_action( 'admin_init', 'add_theme_caps' );


// Create the function to output the contents of our Dashboard Widget
function example_dashboard_widget_function() {
    echo '<div class="rss-widget">';
        wp_widget_rss_output(array(
        'url' => 'http://ingenia.dev/feed',
        'title' => get_bloginfo('name'),
        'items' => 5,
        'show_summary' => 1,
        'show_author' => 0,
        'show_date' => 0,
        ));
    echo '</div>';
}
// Create the function use in the action hook
function example_add_dashboard_widgets() {
    $the_title = get_bloginfo('name');
    if ( current_user_can( 'subscriber' ) ) {
        wp_add_dashboard_widget('example_dashboard_widget',$the_title , 'example_dashboard_widget_function');   
    }
    // Global the $wp_meta_boxes variable (this will allow us to alter the array)
    global $wp_meta_boxes;
    // Then we make a backup of your widget
    $my_widget = $wp_meta_boxes['dashboard']['normal']['core']['example_dashboard_widget'];
    // We then unset that part of the array
    unset($wp_meta_boxes['dashboard']['normal']['core']['example_dashboard_widget']);
    // Now we just add your widget back in
    $wp_meta_boxes['dashboard']['side']['core']['example_dashboard_widget'] = $my_widget;
}
// Hook into the 'wp_dashboard_setup' action to register our other functions
add_action('wp_dashboard_setup', 'example_add_dashboard_widgets' );

/**
 * Add custom taxonomies
 *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */

function add_custom_taxonomies_series() {
    // Add new "Locations" taxonomy to Posts
    register_taxonomy('serie', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => false,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x( 'Series', 'taxonomy general name' ),
            'singular_name' => _x( 'Serie', 'taxonomy singular name' ),
            'menu_name' => __( 'Series' ),     
            'all_items' => __( 'Todas las series' ),
            'edit_item' => __( 'Editar serie' ),
            'update_item' => __( 'Actualizar serie' ),
            'add_new_item' => __( 'Añadir nueva serie' ),
            'new_item_name' => __( 'Nombre de la nueva serie' ),
            'search_items' =>  __( 'Buscar series' ),
            'popular_items' =>  __( 'Series calientes' ),
            'separate_items_with_commas' => __( 'Esto es para una genial e ilativa serie de posts · ' ),
            'add_or_remove_items' => __( 'Añadir o quitar series' ),
            'choose_from_most_used' => __( 'Escoger de las <strong>series</strong> más usadas' )
        ),
        'manage_terms' => 'manage_categories',
        'edit_terms' => 'manage_categories',
        'delete_terms' => 'manage_categories',
        'assign_terms' => 'edit_posts',
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'serie', // This cossntrols the base slug that will display before each term
            'with_front' => true, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}
add_action( 'init', 'add_custom_taxonomies_series', 0 );

function add_custom_taxonomies_companies() {
    // Add new "Locations" taxonomy to Posts
    register_taxonomy('compania', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x( 'Compañías', 'taxonomy general name' ),
            'singular_name' => _x( 'Compañía', 'taxonomy singular name' ),
            'menu_name' => __( 'Compañías' ),     
            'all_items' => __( 'Todas las compañías' ),
            'edit_item' => __( 'Editar compañía' ),
            'update_item' => __( 'Actualizar compañía' ),
            'add_new_item' => __( 'Añadir nueva compañía' ),
            'new_item_name' => __( 'Nombre de la nueva compañía' ),
            'parent_item' => __( 'Compañía madre' ),
            'parent_item_colon' => __( 'Compañía madre:' ),
            'search_items' =>  __( 'Buscar compañías' ),
            'popular_items' =>  __( 'Compañías calientes' )
        ),
        'manage_terms' => 'manage_categories',
        'edit_terms' => 'manage_categories',
        'delete_terms' => 'manage_categories',
        'assign_terms' => 'edit_posts',
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'compania', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}
add_action( 'init', 'add_custom_taxonomies_companies', 0 );

function add_custom_taxonomies_products() {
    // Add new "Locations" taxonomy to Posts
    register_taxonomy('producto', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => false,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x( 'Productos', 'taxonomy general name' ),
            'singular_name' => _x( 'Producto', 'taxonomy singular name' ),
            'menu_name' => __( 'Productos' ),     
            'all_items' => __( 'Todos los productos' ),
            'edit_item' => __( 'Editar producto' ),
            'update_item' => __( 'Actualizar producto' ),
            'add_new_item' => __( 'Añadir nuevo producto' ),
            'new_item_name' => __( 'Nombre del nuevo producto' ),
            'popular_items' =>  __( 'Productos calientes' ),
            'separate_items_with_commas' => __( 'Aquí van los productos que se inmiscuyen en la publicación ·' ),
            'add_or_remove_items' => __( 'Añadir o quitar productos' ),
            'choose_from_most_used' => __( 'Escoger de <strong>productos</strong> calientes' )
            
        ),
        'manage_terms' => 'manage_categories',
        'edit_terms' => 'manage_categories',
        'delete_terms' => 'manage_categories',
        'assign_terms' => 'edit_posts',
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'producto', // This controls the base slug that will display before each term
            'with_front' => false // Don't display the category base before "/locations/"
        ),
    ));
}
add_action( 'init', 'add_custom_taxonomies_products', 0 );

function add_custom_taxonomies_people() {
    // Add new "Locations" taxonomy to Posts
    register_taxonomy('persona', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => false,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x( 'Personas', 'taxonomy general name' ),
            'singular_name' => _x( 'Persona', 'taxonomy singular name' ),
            'menu_name' => __( 'Personas' ),     
            'all_items' => __( 'Todas las Personas' ),
            'edit_item' => __( 'Editar persona' ),
            'update_item' => __( 'Actualizar persona' ),
            'add_new_item' => __( 'Añadir nueva persona' ),
            'new_item_name' => __( 'Nombre de la nueva persona' ),
            'search_items' =>  __( 'Buscar personas' ),
            'popular_items' =>  __( 'Personas populares' ),
            'separate_items_with_commas' => __( '...Si el artículo trata de personas en especial ·' ),
            'add_or_remove_items' => __( 'Añadir o quitar personas' ),
            'choose_from_most_used' => __( 'Escoger de las <strong>personas</strong> más usadas' )
        ),
        'manage_terms' => 'manage_categories',
        'edit_terms' => 'manage_categories',
        'delete_terms' => 'manage_categories',
        'assign_terms' => 'edit_posts',
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'persona', // This controls the base slug that will display before each term
            'with_front' => false // Don't display the category base before "/locations/"
        ),
    ));
}
add_action( 'init', 'add_custom_taxonomies_people', 0 );

function add_custom_taxonomies_labels() {
    // Add new "Locations" taxonomy to Posts
    register_taxonomy('label', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x( 'Labels', 'taxonomy general name' ),
            'singular_name' => _x( 'Label', 'taxonomy singular name' ),
            'menu_name' => __( 'Labels' ),     
            'all_items' => __( 'Todas las labels' ),
            'edit_item' => __( 'Editar label' ),
            'update_item' => __( 'Actualizar label' ),
            'add_new_item' => __( 'Añadir nueva label' ),
            'new_item_name' => __( 'Nombre de la nueva label' ),
            'parent_item' => __( 'Label madre' ),
            'parent_item_colon' => __( 'Label madre:' ),
            'search_items' =>  __( 'Buscar labels' ),
            'popular_items' =>  __( 'Labels calientes' )
        ),
        'manage_terms' => 'manage_categories',
        'edit_terms' => 'manage_categories',
        'delete_terms' => 'manage_categories',
        'assign_terms' => 'edit_posts',
        'query_var' => true,
        // Control the slugs used for this taxonomy
        'rewrite' => true
    ));
    global $wp_rewrite;
    $wp_rewrite->extra_permastructs['label'] = array('%label%', EP_NONE);
}
add_action( 'init', 'add_custom_taxonomies_labels', 0 );

function add_custom_taxonomies_editorial() {
    // Add new "Locations" taxonomy to Posts
    register_taxonomy('editorial', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x( 'Editorial Picks', 'taxonomy general name' ),
            'singular_name' => _x( 'Editorial', 'taxonomy singular name' ),
            'menu_name' => __( 'Editorial' ),     
            'all_items' => __( 'Todas las selecciones' ),
            'edit_item' => __( 'Editar selección' ),
            'update_item' => __( 'Actualizar selección' ),
            'add_new_item' => __( 'Añadir nueva selección' ),
            'new_item_name' => __( 'Nombre de la nueva selección' ),
            'parent_item' => null,
            'parent_item_colon' => null,
            'search_items' =>  __( 'Buscar selecciones' ),
            'popular_items' =>  __( 'Selecciones calientes' )
        ),
        'capabilities' => array(
            'manage_terms' => 'manage_options',
            'edit_terms' => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'manage_options'
        ),
        // Control the slugs used for this taxonomy
        'public' => true,
        'show_ui' => true,
        'show_tagcloud' => false,
        'show_in_nav_menus' => false,
        'rewrite' => array(
            'slug' => 'editorial', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}
add_action( 'init', 'add_custom_taxonomies_editorial', 0 );


/* Remove WP logo, New Link, New Page and View Site menu from the Toolbar */
function remove_toolbar_new_menu() {
global $wp_admin_bar;
$wp_admin_bar->remove_menu('wp-logo');
$wp_admin_bar->remove_menu('new-link');
$wp_admin_bar->remove_menu('new-page');
$wp_admin_bar->remove_menu('view-site');
$wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'remove_toolbar_new_menu');


function replace_footer_admin() {
    $blog_title = get_bloginfo('name');
    $blog_url = get_bloginfo('url');
    echo "<a href='$blog_url'><strong>$blog_title</strong></a>";
}  
add_filter('admin_footer_text', 'replace_footer_admin');

function replace_footer_version() {
    $blog_tagline = get_bloginfo('description');
    return $blog_tagline;
}
add_filter( 'update_footer', 'replace_footer_version', '1234');


add_filter('user_contactmethods','hide_profile_fields',10,1);
function hide_profile_fields( $contactmethods ) {
    unset($contactmethods['aim']);
    unset($contactmethods['jabber']);
    unset($contactmethods['yim']);
    return $contactmethods;
}


add_action( 'init', 'new_posts_tags');
function new_posts_tags()
{
    global $wp_taxonomies;

    // The list of labels we can modify comes from
    //  http://codex.wordpress.org/Function_Reference/register_taxonomy
    //  http://core.trac.wordpress.org/browser/branches/3.0/wp-includes/taxonomy.php#L350
    $wp_taxonomies['post_tag']->labels = (object)array(
        'name' => 'Temas',
        'singular_name' => 'Tema',
        'menu_name' => 'Temas',
        'search_items' => 'Buscar temas',
        'popular_items' => 'Temas calientes',
        'all_items' => 'Todos los temas',
        'edit_item' => 'Editar tema',
        'update_item' => 'Actualizar tema',
        'add_new_item' => 'Añadir nuevo tema',
        'new_item_name' => 'Nombre del nuevo tema',
        'separate_items_with_commas' => 'Los temas o tópicos que aborda esta pieza ·',
        'add_or_remove_items' => 'Añadir o eliminar temas',
        'choose_from_most_used' => 'Escoger <strong>temas</strong> calientes',
    );

    $wp_taxonomies['post_tag']->label = 'Tema';
}


add_action( 'init', 'new_categories');
function new_categories()
{
    global $wp_taxonomies;

    // The list of labels we can modify comes from
    //  http://codex.wordpress.org/Function_Reference/register_taxonomy
    //  http://core.trac.wordpress.org/browser/branches/3.0/wp-includes/taxonomy.php#L350
    $wp_taxonomies['category']->labels = (object)array(
        'name' => 'Hubs',
        'singular_name' => 'Hub',
        'menu_name' => 'Hubs',
        'search_items' => 'Buscar hubs',
        'popular_items' => 'Hubs calientes',
        'all_items' => 'Todos los hubs',
        'edit_item' => 'Editar hub',
        'parent_item' => null,
        'parent_item_colon' => null,
        'update_item' => 'Actualizar hub',
        'add_new_item' => 'Añadir nuevo hub',
        'new_item_name' => 'Nombre del nuevo hub',
        'add_or_remove_items' => 'Añadir o eliminar hubs',
        'choose_from_most_used' => 'Escoger hubs calientes',
    );

    $wp_taxonomies['post_tag']->label = 'Hub';
}


add_action( 'admin_head', 'custom_admin_css' );
function custom_admin_css() {
    echo "<style type='text/css'>
    #poststuff .tagsdiv .howto {
    margin: 0;
    }
    #poststuff h3, .metabox-holder h3 {
    display:none;
    }
    #poststuff .handlediv, .metabox-holder .handlediv {
    display:none;
    }
    #poststuff .tagsdiv .howto {
    margin: 0 5px 0 0;
    float:left;
    }
    .tagsdiv .newtag {
    width:50%;
    }
    #categorydiv ul.category-tabs, #categorydiv ul.add-menu-item-tabs, #categorydiv ul.wp-tab-bar {
    display:none;
    }
    #category-all {
    max-height: 300px !important;
    border-style: none !important;
    border-width: 0 !important;
    border-color: transparent !important;
    background-color: transparent !important;
    padding: 0 !important;
    }
    #category-all ul {
    margin:0;
    }
    #editorialdiv ul.category-tabs, #editorialdiv ul.add-menu-item-tabs, #editorialdiv ul.wp-tab-bar {
    display:none;
    }
    #editorial-all {
    max-height: 300px !important;
    min-height: 0;
    border-style: none !important;
    border-width: 0 !important;
    border-color: transparent !important;
    background-color: transparent !important;
    padding: 0 !important;
    }
    #editorial-all ul {
    margin:0;
    }
    #editorial-all li {
    float: left;
    margin-right: 20px;
    }
    #postimagediv{
    font-weight:bold;
    }
    #postexcerpt {
    padding-top: 8px;
    }
    #category-adder{
    display:none;
}
    #editorial-adder{
    display:none;
}
    #postexcerpt p{
    display:none;
}
.tagchecklist span a {
margin: 3px 0 0 -9px;
}
.tagchecklist span {
margin: 0 10px !important;
line-height: inherit !important;
font-size:12px;
}
#edit-slug-box {
padding: 0 1px !important;
}
#facebook-fan-page-message-box-id .howto::before {
    content: 'Facebook: ';
    font-weight: bold;
}
li#wp-admin-bar-site-name a {
    font-size: 14px;
    font-weight:500;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
}
    </style>";
}

// Function that outputs the contents of the dashboard widget
function dashboard_widget_function() {
    
    $num_posts = wp_count_posts( 'post' );
    $num_pages = wp_count_posts( 'page' );
    $num_cats  = wp_count_terms('category');
    $num_tags = wp_count_terms('post_tag');


    $num = number_format_i18n( $num_posts->publish );
    $text = _n( 'Artículo', 'Artículos', intval($num_posts->publish) );
    if ( current_user_can( 'edit_posts' ) ) {
        $num = "<a style='color:teal' href='edit.php'>$num</a>";
        $text = "<a style='color:teal;' href='edit.php'>$text</a>";
    }
    echo "<div style='font-size:15px;float:left;width:45%;margin-right:5%;'><p style='margin-top:0';><span style='text-align:right;font-family:Georgia, serif;font-size:22px;'>$num </span>";
    echo "$text</p>";

    $num_comm = wp_count_comments();

    // Approved Comments
    $num = '<span class="approved-count">' . number_format_i18n($num_comm->approved) . '</span>';
    $text = _nx( 'Comentario', 'Comentarios', $num_comm->approved, 'Right Now' );
    if ( current_user_can( 'moderate_comments' ) ) {
        $num = "<a style='color:ForestGreen;' href='edit-comments.php?comment_status=approved'>$num</a>";
        $text = "<a style='color:ForestGreen;' class='approved' href='edit-comments.php?comment_status=approved'>$text</a>";
    }
    echo "<p style='padding-bottom:10px;border-bottom:1px solid #dfdfdf;'><span style='text-align:right;font-family:Georgia, serif;font-size:22px;'>$num </span>";
    echo "$text</p>";

        $num  = wp_count_terms('compania');
    $text = _n( 'Compañía', 'Compañías', $num );
    if ( current_user_can( 'manage_categories' ) ) {
        $num = "<a href='edit-tags.php?taxonomy=compania'>$num</a>";
        $text = "<a href='edit-tags.php?taxonomy=compania'>$text</a>";
    }
    echo "<p><span style='text-align:right;font-family:Georgia, serif;font-size:22px;'>$num </span>";
    echo "$text</p>";

    $num  = wp_count_terms('producto');
    $text = _n( 'Producto', 'Productos', $num );
    if ( current_user_can( 'manage_categories' ) ) {
        $num = "<a href='edit-tags.php?taxonomy=producto'>$num</a>";
        $text = "<a href='edit-tags.php?taxonomy=producto'>$text</a>";
    }
    echo "<p><span style='text-align:right;font-family:Georgia, serif;font-size:22px;'>$num </span>";
    echo "$text</p></div>";

    // Topics
    $num = number_format_i18n( $num_tags );
    $text = _n( 'Tema', 'Temas', $num_tags );
    if ( current_user_can( 'manage_categories' ) ) {
        $num = "<a style='color:FireBrick;' href='edit-tags.php'>$num</a>";
        $text = "<a style='color:FireBrick;' href='edit-tags.php'>$text</a>";
    }
    echo "<div style='font-size:15px;'><p><span style='text-align:right;font-family:Georgia, serif;font-size:22px;'>$num </span>";
    echo "$text</p>";

    $num  = wp_count_terms('serie');
    $text = _n( 'Serie', 'Series', $num );
    if ( current_user_can( 'manage_categories' ) ) {
        $num = "<a style='color:Tomato;' href='edit-tags.php?taxonomy=serie'>$num</a>";
        $text = "<a style='color:Tomato;' href='edit-tags.php?taxonomy=serie'>$text</a>";
    }
    echo "<p style='padding-bottom:10px;border-bottom:1px solid #dfdfdf;'><span style='text-align:right;font-family:Georgia, serif;font-size:22px;'>$num </span>";
    echo "$text</p>";

        // Categories
    $num = number_format_i18n( $num_cats );
    $text = _n( 'Hub', 'Hubs', $num_cats );
    if ( current_user_can( 'manage_categories' ) ) {
        $num = "<a style='color:#333; href='edit-tags.php?taxonomy=category'>$num</a>";
        $text = "<a style='color:#333; href='edit-tags.php?taxonomy=category'>$text</a>";
    }
    echo "<p><span style='text-align:right;font-family:Georgia, serif;font-size:22px;'>$num </span>";
    echo "$text</p>";

    $num  = wp_count_terms('label');
    $text = _n( 'Hello', 'Labels', $num );
    if ( current_user_can( 'manage_categories' ) ) {
        $num = "<a style='color:#333; href='edit-tags.php?taxonomy=label'>$num</a>";
        $text = "<a style='color:#333; href='edit-tags.php?taxonomy=label'>$text</a>";
    }
    echo "<p><span style='text-align:right;font-family:Georgia, serif;font-size:22px;'>$num </span>";
    echo "$text</p>";

    $num  = wp_count_terms('persona');
    $text = _n( 'Persona', 'Personas', $num );
    if ( current_user_can( 'manage_categories' ) ) {
        $num = "<a href='edit-tags.php?taxonomy=persona'>$num</a>";
        $text = "<a href='edit-tags.php?taxonomy=persona'>$text</a>";
    }
    echo "<p><span style='text-align:right;font-family:Georgia, serif;font-size:22px;'>$num </span>";
    echo "$text</p></div>";
}

// Function used in the action hook
function add_dashboard_widgets() {
    wp_add_dashboard_widget('dashboard_widget', 'Esto está sucediendo. Ahora.', 'dashboard_widget_function');

    // Globalize the metaboxes array, this holds all the widgets for wp-admin

    global $wp_meta_boxes;
    
    // Get the regular dashboard widgets array 
    // (which has our new widget already but at the end)

    $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
    
    // Backup and delete our new dashbaord widget from the end of the array

    $example_widget_backup = array('dashboard_widget' => $normal_dashboard['dashboard_widget']);
    unset($normal_dashboard['dashboard_widget']);

    // Merge the two arrays together so our widget is at the beginning

    $sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);

    // Save the sorted array back into the original metaboxes 

    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

// Register the new dashboard widget with the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );

function hello_dolly_get_lyric() {
    /** These are the lyrics to Hello Dolly */
    $lyrics = "Ingenia. Ingeeeenia.
Estás aquí, de regreso a donde perteneces.
Stay Hungry. Stay Foolish.
Design touches everything we do. 
Good design should be innovative.
Good design should make a product useful.
Good design is aesthetic design.
Good design will make a product understandable.
Good design is honest.
Good design is unobtrusive.
And good design is as little design as possible.
Like to build crazy, amazing things.
Having a great idea is just 10% of the work.
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
        font-size: 12px;
        color: #777777;
    }
    </style>
    ";
}
add_action( 'admin_head', 'dolly_css' );


function my_remove_meta_boxes() {
    remove_meta_box('commentsdiv', 'post', 'core');

    remove_meta_box('slugdiv', 'post', 'core');
    remove_meta_box('trackbacksdiv', 'post', 'core');
    remove_meta_box('postcustom', 'post', 'core');
}
add_action( 'admin_menu', 'my_remove_meta_boxes' );

// add_action('user_register', 'set_user_metaboxes');
add_action('admin_init', 'set_user_metaboxes');
function set_user_metaboxes($user_id=NULL) {

    // These are the metakeys we will need to update
    $meta_key['order'] = 'meta-box-order_post';
    $meta_key['hidden'] = 'metaboxhidden_post';

    // So this can be used without hooking into user_register
    if ( ! $user_id)
        $user_id = get_current_user_id(); 

    // Set the default order if it has not been set yet
    // if ( ! get_user_meta( $user_id, $meta_key['order'], true) ) {
        $meta_value = array(
            'side' => 'submitdiv,postimagediv,formatdiv,categorydiv,authordiv,companiadiv,labeldiv,facebook-fan-page-message-box-id,facebook-author-message-box-id',
            'normal' => 'postexcerpt,tagsdiv-post_tag,tagsdiv-serie,tagsdiv-producto,tagsdiv-persona,editorialdiv,revisionsdiv',
            'advanced' => '',
        );
        update_user_meta( $user_id, $meta_key['order'], $meta_value );
    //}
        // Set the default hiddens if it has not been set yet
    // if ( ! get_user_meta( $user_id, $meta_key['hidden'], true) ) {
        $meta_value = array('authordiv','commentstatusdiv');
        update_user_meta( $user_id, $meta_key['hidden'], $meta_value );
    //}
}

function excerpt_count_js(){
      echo '<script>jQuery(document).ready(function(){
jQuery("#postexcerpt .inside").after("<div style=\"position:relative;left:10px;color:#555;margin-bottom:10px;\">El <strong>extracto</strong> será distribuido como el resumen del post por toda la red. Tiene <input type=\"text\" value=\"0\" maxlength=\"3\" size=\"1\" id=\"excerpt_counter\" readonly=\"\" style=\"background:#f9f9f9;\"> caracteres de 160 ~ 200 recomendados.</div>");
     jQuery("#excerpt_counter").val(jQuery("#excerpt").val().length);
     jQuery("#excerpt").keyup( function() {
     jQuery("#excerpt_counter").val(jQuery("#excerpt").val().length);
   });
});</script>';
}
add_action( 'admin_head-post.php', 'excerpt_count_js');
add_action( 'admin_head-post-new.php', 'excerpt_count_js');


// TinyMCE: First line toolbar customizations
if( !function_exists('base_extended_editor_mce_buttons') ){
    function base_extended_editor_mce_buttons($buttons) {
        // The settings are returned in this array. Customize to suite your needs.
        return array(
            'fullscreen','styleselect','formatselect', 'bold', 'italic', 'bullist', 'numlist', 'link', 'unlink' , 'removeformat','wp_adv'
        );
        /* WordPress Default
        return array(
            'bold', 'italic', 'strikethrough', 'separator', 
            'bullist', 'numlist', 'blockquote', 'separator', 
            'justifyleft', 'justifycenter', 'justifyright', 'separator', 
            'link', 'unlink', 'wp_more', 'separator', 
            'spellchecker', 'fullscreen', 'wp_adv'
        ); */
    }
    add_filter("mce_buttons", "base_extended_editor_mce_buttons", 0);
}
 
// TinyMCE: Second line toolbar customizations
if( !function_exists('base_extended_editor_mce_buttons_2') ){
    function base_extended_editor_mce_buttons_2($buttons) {
        // The settings are returned in this array. Customize to suite your needs. An empty array is used here because I remove the second row of icons.
        return array('undo', 'redo','strikethrough','underline','outdent', 'indent','charmap','wp_help');
        /* WordPress Default
        return array(
            'formatselect', 'underline', 'justifyfull', 'forecolor', 'separator', 
            'pastetext', 'pasteword', 'removeformat', 'separator', 
            'media', 'charmap', 'separator', 
            'outdent', 'indent', 'separator', 
            'undo', 'redo', 'wp_help'
        ); */
    }
    add_filter("mce_buttons_2", "base_extended_editor_mce_buttons_2", 0);
}

// Customize the format dropdown items
if( !function_exists('base_custom_mce_format') ){
    function base_custom_mce_format($init) {
        // Add block format elements you want to show in dropdown
        $init['theme_advanced_blockformats'] = 'p,h2,h3,h4';
        // Add elements not included in standard tinyMCE dropdown p,h1,h2,h3,h4,h5,h6
        //$init['extended_valid_elements'] = 'code[*]';
        return $init;
    }
    add_filter('tiny_mce_before_init', 'base_custom_mce_format' );
}

// Callback function to filter the MCE settings
function my_mce_before_init_insert_formats( $init_array ) {  
    // Define the style_formats array
    $style_formats = array(  
        // Each array child is a format with it's own settings
        array(  
            'title' => 'quote',  
            'block' => 'q',  
            'classes' => 'pull pull aligncenter',
            'wrapper' => true,  
        ),
        array(  
            'title' => '→ quote',  
            'block' => 'q',  
            'classes' => 'pull alignright',
            'wrapper' => true,
            
        ),
        array(  
            'title' => '← quote',  
            'block' => 'q',  
            'classes' => 'pull alignleft',
            'wrapper' => true, 
        ),
        array(  
            'title' => 'bquote',  
            'block' => 'blockquote',  
            'classes' => 'pull aligncenter',
            'wrapper' => true,
        ),
        array(  
            'title' => '→ bquote',  
            'block' => 'blockquote',  
            'classes' => 'pull alignright',
            'wrapper' => true,
        ),
        array(  
            'title' => '← bquote',  
            'block' => 'blockquote',  
            'classes' => 'pull alignleft',
            'wrapper' => true, 
        ),
        array(  
            'title' => 'nebula',  
            'block' => 'blockquote',  
            'classes' => 'pull alignleft',
            'wrapper' => true, 
        ),
        array(  
            'title' => 'lead',  
            'block' => 'blockquote',  
            'classes' => 'pull alignleft',
            'wrapper' => true, 
        ),
        array(  
            'title' => 'words cite',  
            'block' => 'blockquote',  
            'classes' => 'pull alignleft',
            'wrapper' => true, 
        ),
        array(  
            'title' => 'statement',  
            'block' => 'blockquote',  
            'classes' => 'pull alignleft',
            'wrapper' => true, 
        ),
        array(  
            'title' => 'q&a',  
            'block' => 'blockquote',  
            'classes' => 'pull alignleft',
            'wrapper' => true, 
        ),
    );  
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );  
    
    return $init_array;  
  
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );  

// Set quality 100 for jpeg images. (Retina-ready addon)
add_filter('jpeg_quality', function($arg){return 100;});

// Disable the page analysis score from showing up in publish box and edit posts pages
// add_filter( 'wpseo_use_page_analysis', '__return_false' );

// Add Ingenia logo to the login page
function ingenia_login_logo() {
    echo '<style type="text/css">'.
             'h1 a { background-image:url('.get_bloginfo( 'template_directory' ).'/images/ingenia-login.png) !important; background-size: contain; }'.
         '</style>';
}
add_action( 'login_head', 'ingenia_login_logo' );

// Add url to login img
function ingenia_login_url() {
    return home_url( '/' );
}
add_filter( 'login_headerurl', 'ingenia_login_url' );

// Add login title to img
function ingenia_login_title() {
    $blogtitledesc = get_option( 'blogname' ) . " &mdash; " . get_option( 'blogdescription' );
    return $blogtitledesc;
}
add_filter( 'login_headertitle', 'ingenia_login_title' );