<?php

declare( strict_types=1 );

namespace exo\heating;

class HeatingManagerImpl {
	private HeatingController $heatingController;

	public function __construct() {
		$this->heatingController = new SocketHeatingController();
	}

	public function manageHeating( string $t, string $threshold, bool $active ): void {
		$dt = floatval( $t );
		$dThreshold = floatval( $threshold );
		if ( $dt < $dThreshold && $active ) {
			$this->sendMessage( "on" );
		} elseif ( $dt > $dThreshold && $active ) {
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
