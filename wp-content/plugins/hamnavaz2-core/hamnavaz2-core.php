<?php
/**
 * Plugin Name: Hamnavaz2 Core
 * Plugin URI: https://github.com/babakbadel/hamnavaz2
 * Description: پلتفرم اجتماعی موسیقاران - Hamnavaz2
 * Version: 1.0.0
 * Author: Babak Badel
 * Author URI: https://github.com/babakbadel
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: hamnavaz2
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'HAMNAVAZ2_VERSION', '1.0.0' );
define( 'HAMNAVAZ2_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'HAMNAVAZ2_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'HAMNAVAZ2_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Load config
require_once HAMNAVAZ2_PLUGIN_DIR . '../../config/constants.php';

// Main plugin class
class Hamnavaz2 {
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize plugin
     */
    public function init() {
        // Load dependencies
        $this->load_dependencies();

        // Register hooks
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
        add_action( 'init', [ $this, 'init_custom_post_types' ] );

        // Activation and deactivation
        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
    }

    /**
     * Load plugin dependencies
     */
    public function load_dependencies() {
        require_once HAMNAVAZ2_PLUGIN_DIR . 'includes/class-database.php';
        require_once HAMNAVAZ2_PLUGIN_DIR . 'includes/class-musician.php';
        require_once HAMNAVAZ2_PLUGIN_DIR . 'includes/class-group.php';
        require_once HAMNAVAZ2_PLUGIN_DIR . 'includes/class-rating.php';
        require_once HAMNAVAZ2_PLUGIN_DIR . 'includes/class-message.php';
        require_once HAMNAVAZ2_PLUGIN_DIR . 'includes/class-marketplace.php';
        require_once HAMNAVAZ2_PLUGIN_DIR . 'includes/helpers.php';
    }

    /**
     * Register admin menu
     */
    public function register_admin_menu() {
        add_menu_page(
            'Hamnavaz2',
            'همنواز۲',
            'manage_options',
            'hamnavaz2',
            [ $this, 'admin_dashboard' ],
            'dashicons-music',
            30
        );
    }

    /**
     * Admin dashboard page
     */
    public function admin_dashboard() {
        echo '<div class="wrap"><h1>🎵 Hamnavaz2 - پنل مدیریت</h1>';
        echo '<p>به پنل مدیریت Hamnavaz2 خوش آمدید!</p>';
        echo '</div>';
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {
        // Only on specific pages
        wp_enqueue_style( 'hamnavaz2-frontend', HAMNAVAZ2_PLUGIN_URL . 'assets/css/frontend.css', [], HAMNAVAZ2_VERSION );
        wp_enqueue_script( 'hamnavaz2-frontend', HAMNAVAZ2_PLUGIN_URL . 'assets/js/frontend.js', [ 'jquery' ], HAMNAVAZ2_VERSION, true );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts() {
        wp_enqueue_style( 'hamnavaz2-admin', HAMNAVAZ2_PLUGIN_URL . 'assets/css/admin.css', [], HAMNAVAZ2_VERSION );
        wp_enqueue_script( 'hamnavaz2-admin', HAMNAVAZ2_PLUGIN_URL . 'assets/js/admin.js', [ 'jquery' ], HAMNAVAZ2_VERSION, true );
    }

    /**
     * Initialize custom post types
     */
    public function init_custom_post_types() {
        // Musician profile
        register_post_type( 'musician', [
            'label'        => 'موزیسین',
            'public'       => true,
            'show_ui'      => true,
            'show_in_menu' => 'hamnavaz2',
            'supports'     => [ 'title', 'editor', 'thumbnail' ]
        ] );

        // Group
        register_post_type( 'hamnavaz_group', [
            'label'        => 'گروه موسیقی',
            'public'       => true,
            'show_ui'      => true,
            'show_in_menu' => 'hamnavaz2',
            'supports'     => [ 'title', 'editor', 'thumbnail' ]
        ] );

        // Marketplace item
        register_post_type( 'hamnavaz_service', [
            'label'        => 'خدمات بازار',
            'public'       => true,
            'show_ui'      => true,
            'show_in_menu' => 'hamnavaz2',
            'supports'     => [ 'title', 'editor', 'thumbnail' ]
        ] );
    }

    /**
     * Plugin activation
     */
    public static function activate() {
        // Create database tables
        $database = new Hamnavaz2_Database();
        $database->create_tables();

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public static function deactivate() {
        // Clean up if needed
        flush_rewrite_rules();
    }
}

// Initialize plugin
new Hamnavaz2();
