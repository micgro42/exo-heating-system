<?php

declare(strict_types=1);

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
			$this->heatingController->sendMessage( "on" );
		} elseif ( $dt > $dThreshold && $active ) {
			$this->heatingController->sendMessage( "off" );
		}
	}

	public function setHeatingController(HeatingController $heatingController ): void {
		$this->heatingController = $heatingController;
	}

}
