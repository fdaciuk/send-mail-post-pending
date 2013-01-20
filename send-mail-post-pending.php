<?php

/** Enviar e-mail para o administrador se houver posts para revisão */
function send_mail_post_pending( $post_id, $post ) {
	$post_status = get_post_status( $post );

	if( $post_status === 'pending' && ! wp_is_post_revision( $post ) ) {
		$email   = get_option( 'admin_email' );
		$subject = '[REVISAR NOVO POST] ' . get_the_title( $post_id );
		$message = 'Existe um novo post para revisão: ' . get_the_title( $post_id ) . "\n\n";
		$message .= 'Revisar o post: ' . admin_url() . 'post.php?post=' . $post_id . '&action=edit' . "\n\n";
		//$message .= $post;

		wp_mail( $email, $subject, $message );
	}
}

add_action( 'save_post', 'send_mail_post_pending', 10, 2 );