<?php
declare( strict_types=1 );

namespace exo\heating;

interface HeatingController {
	public function sendMessage( $message ): void;
}
