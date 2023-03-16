<?php

declare( strict_types=1 );

namespace exo\heating\tests;

use Exception;
use exo\heating\CurlHomeHttpClient;
use exo\heating\HeatingManagerImpl;
use exo\heating\ScheduleManager;
use exo\heating\SocketHeatingController;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class ScheduleManagerTest extends TestCase {
	use PHPMock;

	public function testTurnOnHeatingIfCold(): void {
		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->any() )->willReturn( '10017' );
		$mockHeatingController = $this->createMock( SocketHeatingController::class );
		$mockHeatingController
			->expects( $this->once() )
			->method( 'sendMessage' )
			->with( 'on' );
		$stubHomeHttpClient = $this->createStub( CurlHomeHttpClient::class );
		$stubHomeHttpClient->method( 'stringFromUrl' )->willReturnCallback( fn( $url ) => match ( $url ) {
			'http://timer.home:9990/start' => '10007',
			'http://timer.home:9990/end' => '10023',
			'http://probe.home:9999/temp' => '17',
			default => throw new Exception( 'Unexpected URL: ' . $url )
		} );

		$sut = ScheduleManager::class;
		$sut::setCurlHomeHttpClient( $stubHomeHttpClient );
		$heatingManager = new HeatingManagerImpl();
		$heatingManager->setSocketHeatingController( $mockHeatingController );

		$sut::manage( $heatingManager, '22' );
	}
}
