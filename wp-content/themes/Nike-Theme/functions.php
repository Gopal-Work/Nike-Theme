<?php
/**
 * learn functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package learn
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function learn_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on learn, use a find and replace
		* to change 'learn' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'learn', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'learn' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'learn_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'learn_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function learn_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'learn_content_width', 640 );
}
add_action( 'after_setup_theme', 'learn_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function learn_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'learn' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'learn' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'learn_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function learn_scripts() {
    // 1. Main Theme Stylesheet
    wp_enqueue_style( 'learn-style', get_stylesheet_uri(), array(), _S_VERSION );
    wp_style_add_data( 'learn-style', 'rtl', 'replace' );

    // 2. Slick Carousel CSS (REQUIRED FOR SLIDER TO WORK)
    wp_enqueue_style( 'slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1' );
    wp_enqueue_style( 'slick-theme-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array('slick-css'), '1.8.1' );

    // 3. Custom Stylesheet
    $css_path = get_stylesheet_directory() . '/css/custom.css';
    $css_ver  = file_exists( $css_path ) ? filemtime( $css_path ) : _S_VERSION;
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/css/custom.css', array('slick-css'), $css_ver );

    // 4. Navigation Script
    wp_enqueue_script( 'learn-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

    // 5. Threaded Comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // 6. Slick Carousel JS
    wp_enqueue_script( 'slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array( 'jquery' ), '1.8.1', true );

    // 7. Custom Script
    $js_path = get_stylesheet_directory() . '/js/custom.js';
    $js_ver  = file_exists( $js_path ) ? filemtime( $js_path ) : _S_VERSION;

    wp_enqueue_script( 
        'custom-script', 
        get_stylesheet_directory_uri() . '/js/custom.js', 
        array( 'jquery', 'slick-js' ), 
        $js_ver, 
        true 
    );
}
add_action( 'wp_enqueue_scripts', 'learn_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// trending category start

function render_trending_categories_shortcode( $atts ) {
    // 1. Define shortcode attributes with defaults
    $atts = shortcode_atts(
        array(
            'button_text'    => 'Shop', // Default button text
            'fallback_image' => 'https://placehold.co/400', // Default fallback image URL
        ),
        $atts,
        'trending_categories'
    );

    ob_start();

    // Get selected categories from SCF/ACF field
    // Change this:
	$selected_terms = get_field('our_trending_category');

	// To this:
	$current_page_id = get_the_ID();
	$selected_terms = get_field('our_trending_category', $current_page_id);

    $categories = array();

    if ( ! empty( $selected_terms ) ) {
        // Use selected categories
        foreach ( $selected_terms as $term ) {
            $categories[] = is_object( $term ) ? $term : get_term( $term, 'product_cat' );
        }
    } else {
        // Fallback: Fetch latest 3 WooCommerce product categories
        $categories = get_terms( array(
            'taxonomy'   => 'product_cat',
            'orderby'    => 'term_id',
            'order'      => 'DESC',
            'number'     => 3,
            'hide_empty' => false,
        ) );
    }

    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
        <div class="our_trending_category">
            <div class="container">
                <div class="title"><h2>Trending</h2></div>
                <div class="card_main">
                    <div class="inner">
                        <?php foreach ( $categories as $category ) : 
                            $cat_name  = $category->name;
                            $cat_link  = get_term_link( $category );
                            $sub_title = ! empty( $category->description ) ? $category->description : 'Sports ware Essentials';
                            
                            // Get WooCommerce Category Image
                            $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                            $image_url    = wp_get_attachment_image_url( $thumbnail_id, 'woocommerce_thumbnail' );

                            // Fallback if category has no image attached
                            if ( ! $image_url ) {
                                $image_url = $atts['fallback_image'];
                            }
                        ?>
                            <div class="card">
                                <a href="<?php echo esc_url( $cat_link ); ?>" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
									<div class="card-info">
									<!-- Category Name -->
                                    <p><?php echo esc_html( $cat_name ); ?></p>
                                    
                                    <!-- Sub Text / Description -->
                                    <h3><?php echo esc_html( $sub_title ); ?></h3>
                                    
                                    <!-- Dynamic Shortcode Attribute Text -->
                                    <span><?php echo esc_html( $atts['button_text'] ); ?></span>
									</div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php 
    endif;

    return ob_get_clean();
}
add_shortcode('trending_categories', 'render_trending_categories_shortcode');
// [trending_categories button_text="Shop Now" fallback_image="https://placehold.co/400"]

// tranding category end

// show current year

// Create [current_year] shortcode
function custom_current_year_shortcode() {
    return date('Y');
}
add_shortcode('current_year', 'custom_current_year_shortcode');

// show current year end

// category slider shortcode start

function custom_wc_child_categories_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'parent'      => '', 
            'taxonomy'    => 'product_cat',
            'placeholder' => wc_placeholder_img_src(), 
        ),
        $atts,
        'child_categories'
    );

    if ( empty( $atts['parent'] ) ) return '';

    $parent_id = is_numeric( $atts['parent'] ) ? (int) $atts['parent'] : 0;
    if ( ! $parent_id ) {
        $term = get_term_by( 'slug', $atts['parent'], $atts['taxonomy'] );
        if ( $term ) $parent_id = $term->term_id;
    }

    if ( ! $parent_id ) return '';

    $child_terms = get_terms( array(
        'taxonomy'   => $atts['taxonomy'],
        'parent'     => $parent_id,
        'hide_empty' => false,
    ) );

    if ( empty( $child_terms ) || is_wp_error( $child_terms ) ) return '';

    // Enqueue Slick Slider Assets
    // wp_enqueue_style( 'slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' );
    // wp_enqueue_script( 'slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), null, true );

    ob_start();
    ?>
    <div class="category-slider-wrapper">
        <?php foreach ( $child_terms as $child ) : 
            $category_link  = get_term_link( $child );
            $category_title = $child->name;
            $image_url      = '';

            // 1. ACF Image
            if ( function_exists( 'get_field' ) ) {
                $acf_image = get_field( 'our_sports_category_slider_image', $child );
                if ( $acf_image ) {
                    $image_url = is_array( $acf_image ) ? $acf_image['url'] : ( is_numeric( $acf_image ) ? wp_get_attachment_image_url( $acf_image, 'full' ) : $acf_image );
                }
            }

            // 2. WooCommerce Thumbnail
            if ( empty( $image_url ) ) {
                $thumbnail_id = get_term_meta( $child->term_id, 'thumbnail_id', true );
                if ( $thumbnail_id ) $image_url = wp_get_attachment_image_url( $thumbnail_id, 'full' );
            }

            // 3. Fallback
            if ( empty( $image_url ) ) $image_url = esc_url( $atts['placeholder'] );
        ?>
            <div class="catrory-slider">
                <a href="<?php echo esc_url( $category_link ); ?>">
                    <div class="category-slider-image">
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category_title ); ?>">
                    </div>
                    <div class="catrory-slider-title">
                        <span><?php echo esc_html( $category_title ); ?></span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode( 'child_categories', 'custom_wc_child_categories_shortcode' );

// category slider shortcode end


// shortcode to show hi with before text
function say_hello_function($atts){
	$att= shortcode_atts( array(
        'prev' => 'hi you name is ',
		'name' => 'senpi'
    ), $atts );

	ob_start();
	echo $att['prev'].' '.$att['name'].' have a nice day';
	return ob_get_clean(); 
}
add_shortcode('say_hello','say_hello_function');
// shortcode to show hi with before text end


// Ajax code start


/**
 * ============================================
 * STEP 1: SHORTCODE - Yeh HTML output karta hai
 * ============================================
 */
function custom_load_more_shortcode( $atts ) {

    $atts = shortcode_atts( array(
        'posts_per_page' => 3,
        'category'       => '',
        'button_text'    => 'Load More',
        'post_type'      => 'post',
    ), $atts, 'load_more_posts' );

    // Unique ID - agar same shortcode ek page pe 2 baar use ho to conflict na ho
    $unique_id = 'lm_' . wp_rand( 1000, 9999 );

    $query_args = array(
        'post_type'      => $atts['post_type'],
        'posts_per_page' => (int) $atts['posts_per_page'],
        'paged'          => 1,
    );

    if ( ! empty( $atts['category'] ) ) {
        $query_args['category_name'] = $atts['category'];
    }

    $query = new WP_Query( $query_args );

    ob_start();
    ?>
    <div class="lm-wrapper" id="<?php echo esc_attr( $unique_id ); ?>">

        <div class="lm-posts-container">
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="lm-post-item">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="lm-excerpt"><?php the_excerpt(); ?></div>
                </div>
            <?php endwhile; endif; wp_reset_postdata(); ?>
        </div>

        <?php if ( $query->max_num_pages > 1 ) : ?>
            <button
                class="lm-load-more-btn"
                data-page="1"
                data-max-pages="<?php echo esc_attr( $query->max_num_pages ); ?>"
                data-posts-per-page="<?php echo esc_attr( $atts['posts_per_page'] ); ?>"
                data-category="<?php echo esc_attr( $atts['category'] ); ?>"
                data-post-type="<?php echo esc_attr( $atts['post_type'] ); ?>"
                data-nonce="<?php echo esc_attr( wp_create_nonce( 'lm_nonce_action' ) ); ?>"
            >
                <?php echo esc_html( $atts['button_text'] ); ?>
            </button>
        <?php endif; ?>

    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'load_more_posts', 'custom_load_more_shortcode' );


/**
 * ==================================================
 * STEP 2: ENQUEUE SCRIPT - JS ko page pe load karna
 * ==================================================
 */
function custom_load_more_enqueue_scripts() {
    wp_enqueue_script(
        'custom-load-more-js',
        get_stylesheet_directory_uri() . '/js/load-more.js',
        array( 'jquery' ),
        '1.0',
        true
    );

    // JS file ko PHP se data pass karna (ajaxurl frontend pe available nahi hota)
    wp_localize_script( 'custom-load-more-js', 'lm_ajax_obj', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'custom_load_more_enqueue_scripts' );
// fir to is js file me sirf isi shortcode realte js likh payenge

/**
 * ======================================================
 * STEP 3: AJAX HANDLER - Backend jo naye posts bhejega
 * ======================================================
 */
function custom_load_more_ajax_handler() {

    // Security check - nonce verify
    check_ajax_referer( 'lm_nonce_action', 'nonce' );

    $page            = isset( $_POST['page'] ) ? (int) $_POST['page'] : 1;
    $posts_per_page  = isset( $_POST['posts_per_page'] ) ? (int) $_POST['posts_per_page'] : 3;
    $category        = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
    $post_type       = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'post';

    $next_page = $page + 1;

    $query_args = array(
        'post_type'      => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged'          => $next_page,
    );

    if ( ! empty( $category ) ) {
        $query_args['category_name'] = $category;
    }

    $query = new WP_Query( $query_args );

    ob_start();
    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
            ?>
            <div class="lm-post-item">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="lm-excerpt"><?php the_excerpt(); ?></div>
            </div>
            <?php
        endwhile;
    endif;
    wp_reset_postdata();

    $html = ob_get_clean();

    wp_send_json_success( array(
        'html'        => $html,
        'next_page'   => $next_page,
        'max_pages'   => $query->max_num_pages,
    ) );
}
// logged-in aur logged-out dono users ke liye hook
add_action( 'wp_ajax_load_more_posts_action', 'custom_load_more_ajax_handler' );
add_action( 'wp_ajax_nopriv_load_more_posts_action', 'custom_load_more_ajax_handler' );

// ajax coe end