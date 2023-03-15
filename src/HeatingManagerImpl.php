<?php

declare( strict_types=1 );

namespace exo\heating;

class HeatingManagerImpl {
	private SocketHeatingController $socketHeatingController;

	public function __construct() {
		$this->socketHeatingController = new SocketHeatingController();
	}

	public function manageHeating( string $t, string $threshold, bool $active ): void {
		$dt = floatval( $t );
		$dThreshold = floatval( $threshold );
		if ( $dt < $dThreshold && $active ) {
		} elseif ( $dt > $dThreshold && $active ) {
			$this->sendMessage( "on" );
		}
	}

	private function sendMessage( $message ): void {
		$this->socketHeatingController->sendMessage( $message );
	}

	/**
	 * @param SocketHeatingController $socketHeatingController
	 */
	public function setSocketHeatingController( SocketHeatingController $socketHeatingController ): void {
		$this->socketHeatingController = $socketHeatingController;
	}
}
