<?php
/**
 * Main admin class
 *
 * @author Your Inspiration Themes
 * @package YITH Maintenance Mode
 * @version 1.1.2
 */

if ( !defined( 'YITH_MAINTENANCE' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_Maintenance_Admin' ) ) {
    /**
     * YITH Custom Login Admin
     *
     * @since 1.0.0
     */
    class YITH_Maintenance_Admin {
        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $version;

        /**
         * Parameters for add_submenu_page
         *
         * @var array
         * @access public
         * @since 1.0.0
         */
        public $submenu = array();

        /**
         * Initial Options definition:
         *
         * @var array
         * @access public
         * @since 1.0.0
         */
        public $options = array();

        /**
         * Panel instance
         *
         * @var YITH_Panel
         * @since 1.0.0
         */
        public $panel;

        /**
         * Various links
         *
         * @var string
         * @access public
         * @since 1.0.0
         */
        public $banner_url = 'http://cdn.yithemes.com/plugins/yith_maintenance_mode.php?url';
        public $banner_img = 'http://cdn.yithemes.com/plugins/yith_maintenance_mode.php';
        public $doc_url    = 'http://yithemes.com/docs-plugins/yith_maintenance_mode/';

        /**
         * Constructor
         *
         * @return YITH_Maintenance_Admin
         * @since 1.0.0
         */
        public function __construct( $version ) {

            $this->version = $version;
            $this->submenu = apply_filters( 'yith_maintenance_submenu', array(
                'themes.php',
                __('YITH Maintenance Mode', 'yith-maintenance-mode'),
                __('Maintenance Mode', 'yith-maintenance-mode'),
                'administrator',
                'yith-maintenance-mode'
            ) );

            add_action( 'init', array( $this, 'load_default_options' ) );
            add_action( 'init', array( $this, 'init_panel' ) );
            add_action( 'init', array( $this, 'default_options' ) );
            add_action( 'init', array( $this, 'create_skin' ) );
            add_action( 'init', array( $this, 'load_skin' ) );
            add_action( 'update_option_yith_maintenance_skin', array( $this, 'update_option' ), 10, 2 );
            add_filter( 'plugin_action_links_' . plugin_basename( dirname(__FILE__) . '/init.php' ), array( $this, 'action_links' ) );

            return $this;
        }

        /**
         * Load options
         *
         * @return void
         * @since 1.0.0
         */
        public function load_default_options(){
            global $yith_maintenance_options;
            $this->options = apply_filters( 'yith_maintenance_options', $yith_maintenance_options );
        }


        /**
         * Default options
         *
         * Sets up the default options used on the settings page
         *
         * @access public
         * @return void
         * @since 1.0.0
         */
        public function default_options() {
            global $yith_maintenance_options;
            $this->options = apply_filters( 'yith_maintenance_options', $yith_maintenance_options );
            foreach ($this->options as $tab) {
                foreach( $tab['sections'] as $section ) {
                    foreach ( $section['fields'] as $id => $value ) {
                        if ( isset( $value['std'] ) && isset( $id ) ) {
                            add_option($id, $value['std']);
                        }
                    }
                }
            }
        }

        /**
         * Init the panel
         *
         * @return void
         * @since 1.0.0
         */
        public function init_panel() {
            $this->panel = new YITH_Panel(
                                    $this->submenu,
                                    $this->options,
                                    array(
                                        'url' => $this->banner_url,
                                        'img' => $this->banner_img
                                    ),
                                    'yith-maintenance-mode-group',
                                    'yith-maintenance-mode'
            );
        }

        /**
         * action_links function.
         *
         * @access public
         * @param mixed $links
         * @return void
         */
        public function action_links( $links ) {

            $plugin_links = array(
                '<a href="' . admin_url( $this->submenu[0] . '?page=' . $this->submenu[4] ) . '">' . __( 'Settings', 'yith-maintenance-mode' ) . '</a>',
                '<a href="' . $this->doc_url . '">' . __( 'Docs', 'yith-maintenance-mode' ) . '</a>',
            );

            return array_merge( $plugin_links, $links );
        }

        /**
         * Create a new skin
         *
         * @access public
         * @return void
         */
        public function create_skin() {
            if( isset( $_GET['yith_maintenance_new_skin'] ) ) {
                $options = yit_get_options_from_prefix('yith_maintenance_');
                array_walk_recursive( $options, array( $this, 'convert_url' ), 'in_export' );

                $skin = get_option('yith_maintenance_skin');

                $plugin_path   = YITH_MAINTENANCE_DIR . 'assets/skins/';

                file_put_contents( $plugin_path . $skin , base64_encode( serialize($options) ) );
            }
        }

        /**
         * Load skin settings
         *
         * @access public
         * @return void
         */
        public function load_skin() {
            global $yith_maintenance_options;
            $skin = get_option('yith_maintenance_skin') ? get_option('yith_maintenance_skin') : 'skin1';

            if( isset( $_GET['yith_maintenance_load_skin'] ) ) {
                $options = "";
                $template_path = defined( 'YIT' ) ? YIT_THEME_ASSETS_PATH . '/maintenance/' : get_template_directory();
                $child_path = defined( 'YIT' ) ? str_replace( get_template_directory(), get_stylesheet_directory(), YIT_THEME_ASSETS_PATH ) . '/maintenance/' : get_template_directory();

                $plugin_path   = YITH_MAINTENANCE_DIR . 'assets/skins/';

                foreach ( array( 'child_path', 'template_path', 'plugin_path' ) as $var ) {
                    if ( file_exists( ${$var} ) ) {
                        $options = unserialize( base64_decode( file_get_contents( ${$var} . $skin ) ) );
                        break;
                    }
                }

                if( ! empty( $options ) ){
                    array_walk_recursive( $options, array( $this, 'convert_url' ), 'in_import' );
                    foreach ( $yith_maintenance_options as $tab => $tab_options ) {
                        foreach ( $tab_options['sections'] as $section => $section_options ) {
                            foreach ( $section_options['fields'] as $id => $args ) {
                                if ( isset( $args['in_skin'] ) && ! $args['in_skin'] ) {
                                    unset( $options[$id] );
                                }
                            }
                        }
                    }

                    print_r($options);
                    die();

                    foreach( $options as $key => $value ) {
                        update_option($key, $value);
                    }
                }
            }
        }

        /**
         * Change the skin
         *
         * @access public
         * @return void
         */
        public function update_option( $oldvalue, $newvalue ) {
            global $yith_maintenance_options;
            if( $oldvalue != $newvalue ) {
                $options = "";
                $template_path = defined( 'YIT' ) ? YIT_THEME_ASSETS_PATH . '/maintenance/' : get_template_directory();
                $child_path = defined( 'YIT' ) ? str_replace( get_template_directory(), get_stylesheet_directory(), YIT_THEME_ASSETS_PATH ) . '/maintenance/' : get_template_directory();

                $plugin_path   = YITH_MAINTENANCE_DIR . 'assets/skins/';

                foreach ( array( 'child_path', 'template_path', 'plugin_path' ) as $var ) {
                    if ( file_exists( ${$var} . $newvalue ) ) {
                        $options = unserialize( base64_decode( file_get_contents( ${$var} . $newvalue ) ) );
                        break;
                    }
                }
                array_walk_recursive( $options, array( $this, 'convert_url' ), 'in_import' );

                foreach ( $yith_maintenance_options as $tab => $tab_options ) {
                    foreach ( $tab_options['sections'] as $section => $section_options ) {
                        foreach ( $section_options['fields'] as $id => $args ) {
                            if ( isset( $args['in_skin'] ) && ! $args['in_skin'] ) {
                                unset( $options[$id] );
                            }
                        }
                    }
                }

                foreach( $options as $key => $value ) {
                    if( $key == 'yith_maintenance_skin' ) {
                        update_option($key, $newvalue);
                    } else {
                        update_option($key, $value);
                    }
                }

                wp_redirect( $_SERVER['HTTP_REFERER'] );
                exit();
            }
        }

        public function convert_url( &$item, $key, $type ) {
            if ( ! isset( $this->importer_uploads_url ) ) {
                $upload_dir = wp_upload_dir();
                $this->importer_uploads_url = $upload_dir['baseurl'];
            }

            if ( ! isset( $this->importer_site_url ) ) {
                $this->importer_site_url = site_url();
            }

            $item = maybe_unserialize( $item );

            switch ( $type ) {

                case 'in_import' :
                    if ( is_array( $item ) ) {
                        array_walk_recursive( $item, array( $this, 'convert_url' ), $type );
                        $item = serialize($item);
                    } else {
                        $item = str_replace( '%uploadsurl%', $this->importer_uploads_url, $item );
                        $item = str_replace( '%siteurl%', $this->importer_site_url, $item );
                    }
                    break;

                case 'in_export' :
                    if ( is_array( $item ) ) {
                        array_walk_recursive( $item, array( $this, 'convert_url' ), $type );
                        $item = serialize($item);
                    } else {
                        $parsed_site_url = @parse_url( $this->importer_site_url );
                        $item = str_replace( $this->importer_uploads_url, '%uploadsurl%', $item );
                        $item = str_replace( str_replace( $parsed_site_url['scheme'] . '://' . $parsed_site_url['host'], '', $this->importer_uploads_url ), '%uploadsurl%', $item );
                        $item = str_replace( $this->importer_site_url, '%siteurl%', $item );
                    }
                    break;
            }

        }
    }
}