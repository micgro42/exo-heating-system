<?php
declare( strict_types=1 );

namespace exo\heating\tests;

use exo\heating\HeatingController;
use exo\heating\HeatingManagerImpl;
use exo\heating\HomeHttpClient;
use exo\heating\ScheduleManager;
use Generator;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class ScheduleManagerTest extends TestCase {
	use PHPMock;

	public static function provideTestData(): Generator {
		yield 'turn on heating if cold' => [
			'10011',
			'0007',
			'on'
		];

		yield 'turn off heating if cold' => [
			'10011',
			'0025',
			'off'
		];

		yield 'do not change heating if temp match' => [
			'10011',
			'0022',
			null
		];

		yield 'do not change heating if early' => [
			'10005',
			'0007',
			null
		];

		yield 'do not change heating if late' => [
			'10023',
			'0007',
			null
		];
	}

	/**
	 * @dataProvider provideTestData
	 */
	public function testTurnOnHeatingIfCold( $currentTime, $actualTemp, $expectedMessage ): void {
		$this->stubGetTimeOfDay( $currentTime );
		$homeHttpClientstub = $this->getHomeHttpClientStub( '10007', '10020', $actualTemp );
		$heatingController = $this->getHeatingControllerMock( $expectedMessage );

		$heatingManager = new HeatingManagerImpl();
		$heatingManager->setHeatingController( $heatingController );
		ScheduleManager::setHomeHttpClient( $homeHttpClientstub );

		ScheduleManager::manage( $heatingManager, '0022' );
	}

	private function stubGetTimeOfDay( $returnedTime ): void {
		$gettimeofdayStub = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$gettimeofdayStub->expects( $this->any() )->willReturn( $returnedTime );
	}

	private function getHeatingControllerMock( ?string $expectedMessage ): HeatingController {
		$heatingControllerMock = $this->createMock( HeatingController::class );
		if ( $expectedMessage ) {
			$heatingControllerMock
				->expects( $this->once() )
				->method( 'sendMessage' )
				->with( $expectedMessage );
		} else {
			$heatingControllerMock
				->expects( $this->never() )
				->method( 'sendMessage' );
		}

		return $heatingControllerMock;
	}

	private function getHomeHttpClientStub( $start, $end, $actualTemp ): HomeHttpClient {
		$homeHttpClientStub = $this->createStub( HomeHttpClient::class );
		$homeHttpClientStub->method( 'stringFromURL' )
			->willReturnCallback( fn( $url ) => match ( $url ) {
				'http://probe.home:9999/temp' => $actualTemp,
				'http://timer.home:9990/start' => $start,
				'http://timer.home:9990/end' => $end
			} );
		return $homeHttpClientStub;
	}
}
