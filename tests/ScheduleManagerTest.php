<?php
declare(strict_types=1);

namespace tests;

use exo\heating\ScheduleManager;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class ScheduleManagerTest extends TestCase
{

	use PHPMock;

	public function testManage(): void
	{
		$gettimeofdaymock = $this->getFunctionMock('exo\heating', 'gettimeofday');
		
	}
}
