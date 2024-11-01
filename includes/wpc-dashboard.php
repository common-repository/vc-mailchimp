<?php
if ( ! class_exists( 'WPcleverDashboard' ) ) {
	class WPcleverDashboard {
		function __construct() {
			add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
		}

		function add_dashboard_widget() {
			wp_add_dashboard_widget( 'wpclever_dashboard_widget', 'WPclever.net Plugins', array(
				&$this,
				'dashboard_widget_content'
			) );
		}

		function dashboard_widget_content( $post, $callback_args ) {
			$args     = (object) array( 'author' => 'wpclever', 'per_page' => '20', 'page' => '1' );
			$request  = array( 'action' => 'query_plugins', 'timeout' => 15, 'request' => serialize( $args ) );
			$url      = 'http://api.wordpress.org/plugins/info/1.0/';
			$response = wp_remote_post( $url, array( 'body' => $request ) );
			if ( ! is_wp_error( $response ) ) {
				$plugins = unserialize( $response['body'] );
				if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
					foreach ( $plugins->plugins as $pl ) {
						echo '<div class="item"><a href="https://wordpress.org/plugins/' . $pl->slug . '/"><img src="https://ps.w.org/' . $pl->slug . '/assets/icon-128x128.png"/><span class="title">' . $pl->name . '</span><br/><span class="info">Version ' . $pl->version . '</span></a></div>';
					}
				} else {
					echo 'https://wpclever.net';
				}
			} else {
				echo 'https://wpclever.net';
			}
		}
	}

	new WPcleverDashboard();
}