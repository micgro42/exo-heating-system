<?php
declare( strict_types=1 );

namespace exo\heating\tests;

use exo\heating\HeatingManagerImpl;
use exo\heating\HomeHttpClient;
use exo\heating\ScheduleManager;
use exo\heating\SocketHeatingController;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class ScheduleManagerTest extends TestCase {
	use PHPMock;

	public static function provideTestScenarios() {
		yield 'turns on when cold' => [
			'on',
			'0022',
			'10017'
		];
		yield 'turns off when warm' => [
			'off',
			'0017',
			'10017'
		];
		yield 'does nothing when early' => [
			null,
			'0017',
			'10006'
		];
		yield 'does nothing when late' => [
			null,
			'0017',
			'10021'
		];

	}

	/**
	 * @dataProvider provideTestScenarios
	 */
	public function testScheduleManager($expectedMessage, $threshold, $timeofday): void {
		$gettimeofdayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$gettimeofdayMock->expects( $this->any() )
			->willReturn($timeofday);

		$hm = new HeatingManagerImpl();
		$hm->setSocketHeatingController( $this->getHeatingControllerMock( $expectedMessage ) );

		$sut = ScheduleManager::class;
		$sut::setHomeHttpClient( $this->getHomeHttpClientStub() );

		$sut::manage( $hm, $threshold );
	}

	private function getHeatingControllerMock( ?string $expectedMessage ): SocketHeatingController {
		$mock = $this->createMock( SocketHeatingController::class );

		if ( $expectedMessage !== null) {
			$mock->expects($this->once())
				->method('sendMessage')
				->with($expectedMessage);
		} else {
			$mock->expects($this->never())
				->method('sendMessage');
		}
		return $mock;
	}

	private function getHomeHttpClientStub(): HomeHttpClient {
		$stub = $this->createMock( HomeHttpClient::class );
		$stub->method( 'stringFromURL' )
			->willReturnCallback( fn ( $url ) => match ( $url ) {
				'http://probe.home:9999/temp' => '0020',
				'http://timer.home:9990/start' => '10007',
				'http://timer.home:9990/end' => '10020',
			} );

		return $stub;
	}
}
