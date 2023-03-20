<?php

class HeatingManagerImpl {
	private SocketHeatingController $socketHeatingController;

	public function __construct()
	{
		$this->socketHeatingController = new SocketHeatingController();
	}

	public function manageHeating(string $t, string $threshold, bool $active ): void {
		$dt = floatval( $t );
		$dThreshold = floatval( $threshold );
		if ( ($dt < $dThreshold) && $active ) {
			$this->socketHeatingController->sendMessage("on");
		} elseif ( ($dt > $dThreshold) && $active ) {
			$this->socketHeatingController->sendMessage("off");
		}
	}

	/**
	 * @param SocketHeatingController $socketHeatingController
	 */
	public function setSocketHeatingController(SocketHeatingController $socketHeatingController): void
	{
		$this->socketHeatingController = $socketHeatingController;
	}

}
