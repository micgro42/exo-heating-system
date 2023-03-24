<?php

declare( strict_types=1 );

namespace exo\heating;

/**
 * The system obtains temperature data from a remote source,
 * compares it with a given threshold and controls a remote heating
 * unit by switching it on and off. It does so only within a time
 * period configured on a remote service (or other source)
 *
 * This is purpose-built crap.
 */
class ScheduleManager {
	private static HomeHttpClient $homeHttpClient;

	/**
	 * This method is the entry point into the code. You can assume that it is
	 * called at regular interval with the appropriate parameters.
	 */
	public static function manage( HeatingManagerImpl $hM, string $threshold ): void {
		if ( !self::$homeHttpClient ) {
			self::$homeHttpClient = new CurlHomeHttpClient();
		}

		$t = self::$homeHttpClient->stringFromURL( "http://probe.home:9999/temp", 4 );

		$now = gettimeofday( true );
		if ( $now > self::startHour() && $now < self::endHour() ) {
			$hM->manageHeating( (float)$t, (float)$threshold );
		}
	}

	private static function endHour(): float {
		return (float)self::$homeHttpClient->stringFromURL( "http://timer.home:9990/end", 5 );
	}

	private static function startHour(): float {
		return (float)self::$homeHttpClient->stringFromURL( "http://timer.home:9990/start", 5 );
	}

	public static function setHomeHttpClient( HomeHttpClient $homeHttpClient ): void {
		self::$homeHttpClient = $homeHttpClient;
	}
}
