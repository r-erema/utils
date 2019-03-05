<?php

namespace Utils\DateTime\Tests;

use PHPUnit\Framework\TestCase,
	Utils\DateTime\DateTimeUtil;

class DateTimeTest extends TestCase
{

	/**
	 * @dataProvider MergeOverlappingPeriodsProvider
	 * @param array $periods
	 * @param array $resultPeriods
	 */
	public function testMergeOverlappingPeriods(array $periods, array $resultPeriods): void
	{
		shuffle($periods);
		$mergedPeriods = DateTimeUtil::mergeOverlappingPeriods($periods);
		$this->assertEquals($resultPeriods, $mergedPeriods);
	}

	/**
	 * @throws \Exception
	 */
	public static function MergeOverlappingPeriodsProvider(): array
	{
		return [
			[
				[
					new \DatePeriod(
						new \DateTimeImmutable('2017-01-28'), new \DateInterval('P1D'), new \DateTimeImmutable('2017-04-15'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2017-02-28'), new \DateInterval('P1D'), new \DateTimeImmutable('2017-05-05'),
					),

					new \DatePeriod(
						new \DateTimeImmutable('2018-01-15'), new \DateInterval('P1D'), new \DateTimeImmutable('2018-02-06'),
					),

					new \DatePeriod(
						new \DateTimeImmutable('2015-03-03'), new \DateInterval('P1D'), new \DateTimeImmutable('2015-06-02'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2013-07-04'), new \DateInterval('P1D'), new \DateTimeImmutable('2014-08-19'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2014-08-19'), new \DateInterval('P1D'), new \DateTimeImmutable('2015-04-07'),
					),
				],
				//result
				[
					new \DatePeriod(
						new \DateTimeImmutable('2013-07-04'), new \DateInterval('P1D'), new \DateTimeImmutable('2015-06-02'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2017-01-28'), new \DateInterval('P1D'), new \DateTimeImmutable('2017-05-05'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2018-01-15'), new \DateInterval('P1D'), new \DateTimeImmutable('2018-02-06'),
					),
				]
			],

			[
				[
					new \DatePeriod(
						new \DateTimeImmutable('2011-01-17'), new \DateInterval('P1D'), new \DateTimeImmutable('2011-04-04'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2017-02-28'), new \DateInterval('P1D'), new \DateTimeImmutable('2017-05-05'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2009-08-15'), new \DateInterval('P1D'), new \DateTimeImmutable('2011-02-06'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2009-03-03'), new \DateInterval('P1D'), new \DateTimeImmutable('2009-03-03'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2009-03-03'), new \DateInterval('P1D'), new \DateTimeImmutable('2010-11-14'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2010-08-19'), new \DateInterval('P1D'), new \DateTimeImmutable('2017-04-07'),
					),
				],
				//result
				[
					new \DatePeriod(
						new \DateTimeImmutable('2009-03-03'), new \DateInterval('P1D'), new \DateTimeImmutable('2017-05-05'),
					),
				]
			],

			[
				[],
				//result
				[]
			],

			[
				[
					new \DatePeriod(
						new \DateTimeImmutable('2014-03-03'), new \DateInterval('P1D'), new \DateTimeImmutable('2015-05-05'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2016-03-03'), new \DateInterval('P1D'), new \DateTimeImmutable('2017-05-05'),
					),
				],
				//result
				[
					new \DatePeriod(
						new \DateTimeImmutable('2014-03-03'), new \DateInterval('P1D'), new \DateTimeImmutable('2015-05-05'),
					),
					new \DatePeriod(
						new \DateTimeImmutable('2016-03-03'), new \DateInterval('P1D'), new \DateTimeImmutable('2017-05-05'),
					),
				],
			]
		];
	}

}
