<?php
/**
 * Plugin Name: Send Mail Post Pending
 * Plugin URI: http://www.tudoparawordpress.com.br/dicas-wordpress/novo-post-para-revisao/
 * Description: Send e-mail to the administrator for review if there are posts
 * Author: fdaciuk, claudiosanches
 * Author URI: http://da2k.com.br/
 * Version: 1.0
 * License: GPLv2 or later
 * Text Domain: smpp
 * Domain Path: /languages/
 */

class Send_Mail_Post_Pending {

    /**
     * Class construct.
     */
    public function __construct() {

        // Load textdomain.
        add_action( 'plugins_loaded', array( &$this, 'languages' ), 0 );

        // Add menu options.
        add_action( 'admin_menu', array( &$this, 'add_menu' ) );

        // Send mail.
        add_action( 'save_post', array( &$this, 'send_mail' ), 10, 2 );

    }

    /**
     * Load translations.
     */
    public function languages() {
        load_plugin_textdomain( 'smpp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Add Menu Options.
     */
    public function add_menu() {
        add_options_page( 'Send Mail Post Pending', 'SMPP', 'manage_options', 'smpp_options', array( &$this, 'page_settings' ) );
    }

    /**
     * Page Settings
     */
    public function page_settings() {
        include_once 'smpp-settings.php';
        // $settings_api = WeDevs_Settings_API::getInstance();

        // echo '<div class="wrap">';
        //     settings_errors();
        //     $settings_api->show_navigation();
        //     $settings_api->show_forms();
        // echo '</div>';
    }


    /**
     * Send an email to the administrator if there are posts for review
     *
     * @param  int    $post_id Current post id.
     * @param  object $post    Current post object.
     *
     * @return                 Send an email via wp_mail.
     */
    public function send_mail( $post_id, $post ) {
        $post_status = get_post_status( $post );

        if ( 'pending' === $post_status && ! wp_is_post_revision( $post ) ) {
            $email   = get_option( 'admin_email' );
            $subject = __( '[REVIEWING NEW POST] ', 'smpp' ) . get_the_title( $post_id );
            $message = __( 'There is a new post for review: ', 'smpp' ) . get_the_title( $post_id ) . "\n\n";
            $message .= __( 'Review the post: ' , 'smpp' ) . admin_url() . 'post.php?post=' . $post_id . '&action=edit' . "\n\n";

            wp_mail( $email, $subject, $message );
        }
    }

} // Close Send_Mail_Post_Pending class.

$send_mail_post_pending = new Send_Mail_Post_Pending;