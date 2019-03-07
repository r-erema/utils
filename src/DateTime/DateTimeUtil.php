<?php

declare(strict_types=1);

namespace Utils\DateTime;

use Utils\DateTime\Exceptions\EndDateCantBeLessThenStartDateException;

class DateTimeUtil
{

	/**
	 * @param \DatePeriod[] $periods
	 * @return \DatePeriod[]
	 * @throws EndDateCantBeLessThenStartDateException
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

				if ($endDate < $startDate) {
					$startDateFormat = $startDate->format('Y-m-d H:i:s');
					$endDateFormat = $endDate !== null ? $endDate->format('Y-m-d H:i:s') : 'null';
					throw new EndDateCantBeLessThenStartDateException("Start date: {$startDateFormat}, end date: {$endDateFormat}");
				}

				$overlappedPeriods = array_filter($periods, function (\DatePeriod $period) use ($startDate, $endDate) {
					return $period->getStartDate() >= $startDate && $period->getStartDate() <= $endDate;
				});

				$periodWithMinStartDate = self::getPeriodWithMinStartDate($overlappedPeriods);
				$periodWithMaxEndDate = self::getPeriodWithMaxEndDate($overlappedPeriods);

				if ($periodWithMinStartDate && $periodWithMaxEndDate) {
					$mergedPeriod = new \DatePeriod(
						$periodWithMinStartDate->getStartDate(),
						$period->getDateInterval(),
						$periodWithMaxEndDate->getEndDate()
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
		}
		return $result;
	}

	public static function getPeriodWithMinStartDate(array $periods): ?\DatePeriod
	{
		usort($periods, function (\DatePeriod $period1, \DatePeriod $period2) {
			return $period1->getStartDate() < $period2->getStartDate() ? -1 : 1;
		});
		return array_shift($periods);
	}

	public static function getPeriodWithMaxEndDate(array $periods): ?\DatePeriod
	{
		usort($periods, function (\DatePeriod $period1, \DatePeriod $period2) {
			return $period1->getEndDate() > $period2->getEndDate() ? -1 : 1;
		});
		return array_shift($periods);
	}

}