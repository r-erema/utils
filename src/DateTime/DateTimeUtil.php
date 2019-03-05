<?php

declare(strict_types=1);

namespace Utils\DateTime;

class DateTimeUtil
{

	/**
	 * @param \DatePeriod[] $periods
	 * @return \DatePeriod[]
	 */
	public static function mergeOverlappingPeriods(array $periods): array
	{
		usort($periods, function (\DatePeriod $period1, \DatePeriod $period2) {
			return $period1->getStartDate() < $period2->getStartDate() ? -1 : 1;
		});

		$result = [];
		$periods = array_values($periods);
		while (count($periods) > 0) {
			reset($periods);
			$firstKey = key($periods);
			$period = $periods[$firstKey];
			if ($period) {
				$startDate = $period->getStartDate();
				$endDate = $period->getEndDate();
				$overlappedPeriods = array_filter($periods, function (\DatePeriod $period) use ($startDate, $endDate) {
					return $period->getStartDate() >= $startDate && $period->getStartDate() <= $endDate;
				});

				$mergedPeriod = new \DatePeriod(
					self::getPeriodWithMinStartDate($overlappedPeriods)->getStartDate(),
					$period->getDateInterval(),
					self::getPeriodWithMaxEndDate($overlappedPeriods)->getEndDate()
				);

				foreach (array_keys($overlappedPeriods) as $key) {
					unset($periods[$key]);
				}

				if (count($overlappedPeriods) > 1) {
					array_unshift($periods, $mergedPeriod);
				} else {
					$result[] = $mergedPeriod;
				}
			}
		}
		return $result;
	}

	public static function getPeriodWithMinStartDate(array $periods): \DatePeriod
	{
		usort($periods, function (\DatePeriod $period1, \DatePeriod $period2) {
			return $period1->getStartDate() < $period2->getStartDate() ? -1 : 1;
		});
		return array_shift($periods);
	}

	public static function getPeriodWithMaxEndDate(array $periods): \DatePeriod
	{
		usort($periods, function (\DatePeriod $period1, \DatePeriod $period2) {
			return $period1->getEndDate() > $period2->getEndDate() ? -1 : 1;
		});
		return array_shift($periods);
	}

}