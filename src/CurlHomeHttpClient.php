<?php

declare( strict_types=1 );

namespace exo\heating;

class CurlHomeHttpClient {

	public function stringFromURL( string $urlString, int $s ): string {
		$c = curl_init();

		curl_setopt( $c, CURLOPT_URL, $urlString );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );

		$o = curl_exec( $c );

		curl_close( $c );

		return substr( $o, 0, $s );
	}
}
