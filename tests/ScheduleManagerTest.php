<?php

use PHPUnit\Framework\TestCase;

class ScheduleManagerTest extends TestCase {
	public function testTurnsOnHeatingIfCold(): void {
		$this->createStub( \exo\heating\SocketHeatingController::class );
	}
}
