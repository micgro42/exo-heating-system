<?php
declare( strict_types=1 );

namespace exo\heating;

interface HomeHttpClient {
	public function stringFromURL( string $urlString, int $s );
}
