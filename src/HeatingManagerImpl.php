<?php

declare( strict_types=1 );

namespace exo\heating;

class HeatingManagerImpl {
	private HeatingController $heatingController;

	public function __construct() {
		$this->heatingController = new SocketHeatingController();
	}

	public function manageHeating( float $t, float $threshold ): void {
		if ( $t < $threshold ) {
			$this->sendMessage( "on" );
		} elseif ( $t > $threshold ) {
			$this->sendMessage( "off" );
		}
	}

	private function sendMessage( $message ): void {
		$this->heatingController->sendMessage( $message );
	}

	public function setHeatingController( HeatingController $heatingController ): void {
		$this->heatingController = $heatingController;
	}
}
