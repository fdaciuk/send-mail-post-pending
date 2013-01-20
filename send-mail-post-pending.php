<?php
/**
 * Plugin Name: Send Mail Post Pending
 * Plugin URI: http://www.tudoparawordpress.com.br/dicas-wordpress/novo-post-para-revisao/
 * Description: Send Mail Post Pending
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
            $subject = '[REVISAR NOVO POST] ' . get_the_title( $post_id );
            $message = 'Existe um novo post para revisão: ' . get_the_title( $post_id ) . "\n\n";
            $message .= 'Revisar o post: ' . admin_url() . 'post.php?post=' . $post_id . '&action=edit' . "\n\n";
            //$message .= $post;

            wp_mail( $email, $subject, $message );
        }
    }

} // Close Send_Mail_Post_Pending class.

$send_mail_post_pending = new Send_Mail_Post_Pending;
