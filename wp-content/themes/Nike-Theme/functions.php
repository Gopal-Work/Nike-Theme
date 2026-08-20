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

    // bootstrap css
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3' );

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

    // bootstrap js
    wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.3.3', true );

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
        <div class="our_trending_category py-4">
            <div class="container">
                <div class="title pb-3"><h2>Trending</h2></div>
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
                            <div class="card d-flex align-items-center justify-content-center border-0">
                                <a class="w-100" href="<?php echo esc_url( $cat_link ); ?>" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
									<div class="card-info">
                                        <!-- Category Name -->
                                        <p class="text-white"><?php echo esc_html( $cat_name ); ?></p>
                                        
                                        <!-- Sub Text / Description -->
                                        <h3 class="text-white"><?php echo esc_html( $sub_title ); ?></h3>
                                        
                                        <!-- Dynamic Shortcode Attribute Text -->
                                        <span class="primary_btn px-2 py-1"><?php echo esc_html( $atts['button_text'] ); ?></span>
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

    // JS file ko PHP se data pass karna (ajax_url aur nonce ek hi jagah se)
    wp_localize_script( 'custom-load-more-js', 'lm_ajax_obj', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'lm_nonce_action' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'custom_load_more_enqueue_scripts' );


/**
 * ======================================================
 * STEP 3: AJAX HANDLER - Backend jo naye posts bhejega
 * ======================================================
 */
function custom_load_more_ajax_handler() {

    // Security check - nonce verify (JS se $_POST['nonce'] me aayega)
    check_ajax_referer( 'lm_nonce_action', 'nonce' );

    $page           = isset( $_POST['page'] ) ? (int) $_POST['page'] : 1;
    $posts_per_page = isset( $_POST['posts_per_page'] ) ? (int) $_POST['posts_per_page'] : 3;
    $category       = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
    $post_type      = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'post';

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
        'html'      => $html,
        'next_page' => $next_page,
        'max_pages' => $query->max_num_pages,
    ) );
}
add_action( 'wp_ajax_load_more_posts_action', 'custom_load_more_ajax_handler' );
add_action( 'wp_ajax_nopriv_load_more_posts_action', 'custom_load_more_ajax_handler' );

// ajax coe end


// Step 1: Add the fields to the registration form
add_action( 'woocommerce_register_form', 'add_custom_registration_fields' );

function add_custom_registration_fields() {
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="first_name" id="reg_first_name" value="<?php if ( ! empty( $_POST['first_name'] ) ) esc_attr_e( $_POST['first_name'] ); ?>" />
    </p>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="last_name" id="reg_last_name" value="<?php if ( ! empty( $_POST['last_name'] ) ) esc_attr_e( $_POST['last_name'] ); ?>" />
    </p>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_billing_birth_date"><?php _e( 'Date of Birth', 'woocommerce' ); ?></label>
        <input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="customer_dob" id="reg_billing_birth_date" value="<?php if ( ! empty( $_POST['customer_dob'] ) ) esc_attr_e( $_POST['customer_dob'] ); ?>" />
    </p>
    <?php
}

// Step 2: Validation
add_filter( 'woocommerce_registration_errors', 'validate_custom_registration_fields', 10, 3 );

function validate_custom_registration_fields( $errors, $username, $email ) {
    if ( empty( $_POST['first_name'] ) ) {
        $errors->add( 'error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
    }
    if ( empty( $_POST['last_name'] ) ) {
        $errors->add( 'error', __( '<strong>Error</strong>: Last name is required!', 'woocommerce' ) );
    }
    return $errors;
}

// Step 3: Modify Username before registration and save meta data
add_filter( 'woocommerce_new_customer_data', 'combine_first_last_name_as_username', 10, 1 );

function combine_first_last_name_as_username( $new_customer_data ) {
    if ( ! empty( $_POST['first_name'] ) && ! empty( $_POST['last_name'] ) ) {
        $first_name = sanitize_text_field( $_POST['first_name'] );
        $last_name  = sanitize_text_field( $_POST['last_name'] );
        
        // Combine first and last name
        $combined_name = $first_name . ' ' . $last_name;
        
        // Generate a clean slug/username
        $base_username = sanitize_user( strtolower( str_replace( ' ', '-', $combined_name ) ), true );
        
        // Make sure the username is unique
        $username = $base_username;
        $counter = 1;
        while ( username_exists( $username ) ) {
            $username = $base_username . '-' . $counter;
            $counter++;
        }
        
        $new_customer_data['user_login'] = $username;
        $new_customer_data['display_name'] = $combined_name;
        $new_customer_data['first_name'] = $first_name;
        $new_customer_data['last_name'] = $last_name;
    }
    return $new_customer_data;
}

// Step 4: Database save for custom meta fields & billing fields
add_action( 'woocommerce_created_customer', 'save_custom_registration_fields' );

function save_custom_registration_fields( $customer_id ) {
    if ( isset( $_POST['first_name'] ) ) {
        $first_name = sanitize_text_field( $_POST['first_name'] );
        update_user_meta( $customer_id, 'first_name', $first_name );
        // Yeh line checkout ke liye billing first name auto-fill karegi
        update_user_meta( $customer_id, 'billing_first_name', $first_name );
    }
    if ( isset( $_POST['last_name'] ) ) {
        $last_name = sanitize_text_field( $_POST['last_name'] );
        update_user_meta( $customer_id, 'last_name', $last_name );
        // Yeh line checkout ke liye billing last name auto-fill karegi
        update_user_meta( $customer_id, 'billing_last_name', $last_name );
    }
    if ( isset( $_POST['customer_dob'] ) ) {
        update_user_meta( $customer_id, 'customer_dob', sanitize_text_field( $_POST['customer_dob'] ) );
    }
}


// ajax checkhout start

// 1. Mini Cart Shortcode Register Karein
add_shortcode( 'custom_side_mini_cart', 'render_custom_side_mini_cart' );

function render_custom_side_mini_cart() {
    ob_start();
    
    if ( class_exists( 'WC_Cart' ) ) {
        $cart_count = WC()->cart->get_cart_contents_count();
        ?>
        <!-- Mini Cart Wrapper -->
        <div class="custom_side_mini_cart-wrapper">
            <a href="#" class="custom_side_mini_cart-toggle-btn">
                <span class="custom_side_mini_cart-icon">🛒 Cart</span>
                <span class="custom_side_mini_cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
            </a>

            <!-- Overlay -->
            <div class="custom_side_mini_cart-overlay"></div>

            <!-- Slide-in Sidebar Panel -->
            <div class="custom_side_mini_cart-sidebar">
                <div class="custom_side_mini_cart-header">
                    <h3>Your Cart</h3>
                    <button class="custom_side_mini_cart-close-btn">&times;</button>
                </div>
                
                <div class="custom_side_mini_cart-content widget_shopping_cart_content">
                    <?php custom_side_mini_cart_render_contents(); ?>
                </div>
            </div>
        </div>

        <style>
            .custom_side_mini_cart-wrapper { position: relative; display: inline-block; }
            .custom_side_mini_cart-toggle-btn { background: #000; color: #fff; padding: 10px 15px; text-decoration: none; display: flex; align-items: center; gap: 8px; border-radius: 4px; cursor: pointer; }
            .custom_side_mini_cart-count-badge { background: #ff4757; color: #fff; padding: 2px 6px; border-radius: 50%; font-size: 12px; }

            .custom_side_mini_cart-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); visibility: hidden; opacity: 0; transition: opacity 0.3s ease; z-index: 9998; }
            .custom_side_mini_cart-overlay.custom_side_mini_cart-active { visibility: visible; opacity: 1; }

            .custom_side_mini_cart-sidebar { position: fixed; top: 0; right: -400px; width: 380px; height: 100%; background: #fff; box-shadow: -5px 0 15px rgba(0,0,0,0.1); transition: right 0.3s ease; z-index: 9999; display: flex; flex-direction: column; }
            .custom_side_mini_cart-sidebar.custom_side_mini_cart-active { right: 0; }

            .custom_side_mini_cart-header { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
            .custom_side_mini_cart-header h3 { margin: 0; font-size: 18px; }
            .custom_side_mini_cart-close-btn { background: none; border: none; font-size: 24px; cursor: pointer; }
            .custom_side_mini_cart-content { padding: 20px; overflow-y: auto; flex-grow: 1; }

            /* Cart Items List styling */
            .custom_side_mini_cart-items-list { list-style: none; padding: 0; margin: 0; }
            .custom_side_mini_cart-item { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; border-bottom: 1px solid #f1f1f1; padding-bottom: 15px; }
            .custom_side_mini_cart-item img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
            .custom_side_mini_cart-item-details { flex-grow: 1; }
            .custom_side_mini_cart-item-details h4 { font-size: 14px; margin: 0 0 5px 0; }
            
            /* Quantity Stepper UI */
            .custom_side_mini_cart-qty-box { display: inline-flex; align-items: center; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; margin-top: 5px; }
            .custom_side_mini_cart-qty-btn { background: #f8f9fa; border: none; padding: 2px 8px; cursor: pointer; font-weight: bold; font-size: 14px; }
            .custom_side_mini_cart-qty-btn:hover { background: #e9ecef; }
            .custom_side_mini_cart-qty-input { width: 35px; text-align: center; border: none; font-size: 13px; -moz-appearance: textfield; }
            .custom_side_mini_cart-qty-input::-webkit-outer-spin-button,
            .custom_side_mini_cart-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
            .custom_side_mini_cart-footer { border-top: 1px solid #eee; padding-top: 15px; margin-top: auto; }
            .custom_side_mini_cart-total { display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 15px; }
            .custom_side_mini_cart-buttons { display: flex; gap: 10px; }
            .custom_side_mini_cart-buttons a { flex: 1; text-align: center; padding: 10px; background: #000; color: #fff; border-radius: 4px; text-decoration: none; font-size: 14px; }
            .custom_side_mini_cart-buttons a.checkout { background: #28a745; }
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toggleBtn = document.querySelector(".custom_side_mini_cart-toggle-btn");
                const sidebar = document.querySelector(".custom_side_mini_cart-sidebar");
                const overlay = document.querySelector(".custom_side_mini_cart-overlay");
                const closeBtn = document.querySelector(".custom_side_mini_cart-close-btn");

                function openCart(e) {
                    if(e) e.preventDefault();
                    sidebar.classList.add("custom_side_mini_cart-active");
                    overlay.classList.add("custom_side_mini_cart-active");
                }

                function closeCart() {
                    sidebar.classList.remove("custom_side_mini_cart-active");
                    overlay.classList.remove("custom_side_mini_cart-active");
                }

                if(toggleBtn) toggleBtn.addEventListener("click", openCart);
                if(closeBtn) closeBtn.addEventListener("click", closeCart);
                if(overlay) overlay.addEventListener("click", closeCart);

                // AJAX Quantity Update via + / - buttons
                document.body.addEventListener("click", function(e) {
                    if (e.target.classList.contains("custom_side_mini_cart-qty-btn")) {
                        e.preventDefault();
                        let btn = e.target;
                        let wrapper = btn.closest(".custom_side_mini_cart-qty-box");
                        let input = wrapper.querySelector(".custom_side_mini_cart-qty-input");
                        let cartItemKey = wrapper.getAttribute("data-key");
                        let currentVal = parseInt(input.value);
                        let newVal = currentVal;

                        if (btn.classList.contains("plus")) {
                            newVal = currentVal + 1;
                        } else if (btn.classList.contains("minus") && currentVal > 1) {
                            newVal = currentVal - 1;
                        } else if (btn.classList.contains("minus") && currentVal === 1) {
                            newVal = 0; 
                        }

                        input.value = newVal;

                        let formData = new FormData();
                        formData.append("action", "custom_side_mini_cart_update_qty");
                        formData.append("cart_item_key", cartItemKey);
                        formData.append("qty", newVal);

                        fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.text())
                        .then(html => {
                            document.querySelector(".custom_side_mini_cart-content").innerHTML = html;
                            jQuery(document.body).trigger("wc_fragment_refresh");
                        });
                    }
                });
            });
        </script>
        <?php
    }
    return ob_get_clean();
}

// 2. Render Cart Contents Function
function custom_side_mini_cart_render_contents() {
    if ( WC()->cart->is_empty() ) {
        echo '<p>Your cart is empty.</p>';
        return;
    }
    ?>
    <ul class="custom_side_mini_cart-items-list">
        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
                $thumbnail = $_product->get_image();
                $product_price = WC()->cart->get_product_price( $_product );
                ?>
                <li class="custom_side_mini_cart-item">
                    <?php echo $thumbnail; ?>
                    <div class="custom_side_mini_cart-item-details">
                        <h4><?php echo $_product->get_name(); ?></h4>
                        <div><?php echo $product_price; ?></div>
                        
                        <!-- Quantity Controller -->
                        <div class="custom_side_mini_cart-qty-box" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
                            <button type="button" class="custom_side_mini_cart-qty-btn minus">-</button>
                            <input type="text" class="custom_side_mini_cart-qty-input" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" readonly />
                            <button type="button" class="custom_side_mini_cart-qty-btn plus">+</button>
                        </div>
                    </div>
                </li>
                <?php
            }
        }
        ?>
    </ul>
    
    <div class="custom_side_mini_cart-footer">
        <div class="custom_side_mini_cart-total">
            <span>Subtotal:</span>
            <span><?php echo WC()->cart->get_cart_total(); ?></span>
        </div>
        <div class="custom_side_mini_cart-buttons">
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">View Cart</a>
            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout">Checkout</a>
        </div>
    </div>
    <?php
}

// 3. AJAX Handler to Update Quantity in Backend
add_action( 'wp_ajax_custom_side_mini_cart_update_qty', 'custom_side_mini_cart_update_qty_callback' );
add_action( 'wp_ajax_nopriv_custom_side_mini_cart_update_qty', 'custom_side_mini_cart_update_qty_callback' );

function custom_side_mini_cart_update_qty_callback() {
    if ( isset( $_POST['cart_item_key'] ) && isset( $_POST['qty'] ) ) {
        $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );
        $qty = intval( $_POST['qty'] );

        if ( $qty === 0 ) {
            WC()->cart->remove_cart_item( $cart_item_key );
        } else {
            WC()->cart->set_quantity( $cart_item_key, $qty );
        }
        WC()->cart->calculate_totals();
    }
    
    custom_side_mini_cart_render_contents();
    wp_die();
}

// 4. Live AJAX Fragment Count Update for Badge
add_filter( 'woocommerce_add_to_cart_fragments', 'custom_side_mini_cart_fragment_count' );

function custom_side_mini_cart_fragment_count( $fragments ) {
    ob_start();
    $cart_count = WC()->cart->get_cart_contents_count();
    ?>
    <span class="custom_side_mini_cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
    <?php
    $fragments['.custom_side_mini_cart-count-badge'] = ob_get_clean();
    return $fragments;
}

// ajax checkhout ended

function learn_call() {
    ob_start();

    $current_locale = get_locale();

    echo $current_locale;

    return ob_get_clean();
}

add_shortcode('learn', 'learn_call' );