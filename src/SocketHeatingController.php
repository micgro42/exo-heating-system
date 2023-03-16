<?php

declare( strict_types=1 );

namespace exo\heating;

class SocketHeatingController {

	public function sendMessage( $message ): void {
		try {
			if ( !( $s = socket_create( AF_INET, SOCK_STREAM, 0 ) ) ) {
				die( 'could not create socket' );
			}
			if ( !socket_connect( $s, 'heater.home', 9999 ) ) {
				die( 'could not connect!' );
			}
			socket_send( $s, $message, strlen( $message ), 0 );
			socket_close( $s );
		} catch ( Exception $e ) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
}
