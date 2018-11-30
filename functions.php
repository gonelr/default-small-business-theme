<?php
/**
 * Gutenbergtheme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Business Elegant
 */

if ( ! function_exists( 'business_elegant_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function business_elegant_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on business_elegant, use a find and replace
		 * to change 'business-elegant' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'business-elegant', get_template_directory() . '/languages' );

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
		add_image_size( 'business_elegant-featured-image', 1440, 9999 );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => __( 'Primary', 'business-elegant' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add theme support for Custom Logo.
		add_theme_support( 'custom-logo', array(
			'width'       => 600,
			'height'      => 300,
			'flex-width'  => true,
			'flex-height' => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );

		add_theme_support( 'responsive-embeds' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for core editor styles
		add_theme_support( 'editor-styles' );

		// Add support for custom editor styles
		add_editor_style( 'editor.css' );

		// Add support for custom color scheme.
		add_theme_support( 'editor-color-palette', array(
			array(
				'name'  => __( 'Orange', 'business-elegant' ),
				'slug'  => 'Orange',
				'color' => '#ea8664',
			),
			array(
				'name'  => __( 'Pink', 'business-elegant' ),
				'slug'  => 'pink',
				'color' => '#fff4ee',
			),
			array(
				'name'  => __( 'Light Gray', 'business-elegant' ),
				'slug'  => 'light-gray',
				'color' => '#e5e5e5',
			),
			array(
				'name'  => __( 'Medium Gray', 'business-elegant' ),
				'slug'  => 'medium-gray',
				'color' => '#777269',
			),
			array(
				'name'  => __( 'Dark Gray', 'business-elegant' ),
				'slug'  => 'dark-gray',
				'color' => '#444444',
			),
		) );
	}
endif;
add_action( 'after_setup_theme', 'business_elegant_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function business_elegant_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'business_elegant_content_width', 710 );
}
add_action( 'after_setup_theme', 'business_elegant_content_width', 0 );

/**
 * Register Google Fonts
 */
function business_elegant_fonts_url() {
	$fonts_url = '';

	/*
	 * Translators: If there are characters in your language that are not
	 * supported by Lato, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$lora = esc_html_x( 'on', 'Lora font: on or off', 'business-elegant' );

	if ( 'off' !== $lora ) {
		$font_families = array();
		$font_families[] = 'Lora:400,400italic,700,700italic';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;

}

/**
 * Enqueue scripts and styles.
 */
function business_elegant_scripts() {

	wp_enqueue_style( 'business_elegant-style', get_stylesheet_uri() );
	
	wp_style_add_data( 'business_elegant-style', 'rtl', 'replace' );

	wp_enqueue_style( 'business_elegant-blocks-style', get_template_directory_uri() . '/css/blocks.css' );

	wp_enqueue_style( 'business_elegant-fonts', business_elegant_fonts_url() );

	wp_enqueue_script( 'business_elegant-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20151215', true );

	wp_enqueue_script( 'business_elegant-priority-navigation', get_template_directory_uri() . '/js/priority-navigation.js', array( 'jquery' ), '20151215', true );

	wp_enqueue_script( 'business_elegant-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Screenreader text
	wp_localize_script( 'business_elegant-navigation', 'smallBusinessThemeScreenReaderText', array(
		'expand'   => esc_html__( 'expand child menu', 'business-elegant' ),
		'collapse' => esc_html__( 'collapse child menu', 'business-elegant' ),
	) );

	// Icons
	wp_localize_script( 'business_elegant-navigation', 'smallBusinessThemeIcons', array(
		'dropdown' => business_elegant_get_icon_svg( 'expand_more' )
	) );

	// Menu toggle text
	wp_localize_script( 'business_elegant-navigation', 'smallBusinessThemeMenuToggleText', array(
		'menu'  => esc_html__( 'Menu', 'business-elegant' ),
		'close' => esc_html__( 'Close', 'business-elegant' ),
	) );


}
add_action( 'wp_enqueue_scripts', 'business_elegant_scripts' );

/**
 * Enqueue Gutenberg editor styles
 */
function business_elegant_editor_styles() {
	wp_enqueue_style( 'business_elegant-editor-style', get_template_directory_uri() . '/editor.css' );
}
add_action( 'enqueue_block_editor_assets', 'business_elegant_editor_styles' );

/**
 * Check whether the browser supports JavaScript
 */
function business_elegant_html_js_class() {
	echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>'. "\n";
}
add_action( 'wp_head', 'business_elegant_html_js_class', 1 );

/**
 * SVG Icons class.
 */
require get_template_directory() . '/inc/classes/svg-icons.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * SVG Icons related functions.
 */
require get_template_directory() . '/inc/icon-functions.php';

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