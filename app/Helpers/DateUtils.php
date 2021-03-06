<?php

namespace App\Helpers;

use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeImmutable;
use LengthException;

class DateUtils
{

    public static function getDateAsDateTime($date)
    {
        return is_string($date) ? new DateTime($date) : $date;
    }

    public static function isWeekend($date)
    {
        $inputDate = self::getDateAsDateTime($date);
        return $inputDate->format('N') >= 6;
    }

    public static function isBefore($date1, $date2)
    {
        $inputDate1 = self::getDateAsDateTime($date1);
        $inputDate2 = self::getDateAsDateTime($date2);
        return $inputDate1 <= $inputDate2;
    }

    public static function getNextDay($date)
    {
        $inputDate = self::getDateAsDateTime($date);
        $inputDate->modify('+1 day');
        return $inputDate;
    }

    public static function sumIntervals($interval1, $interval2, $interval3 = null)
    {
        $date = new DateTime('00:00:00');
        $date->add($interval1);
        $date->add($interval2);
        if ($interval3) {
            $date->add($interval3);
        }
        return (new DateTime('00:00:00'))->diff($date);
    }

    public static function subtractIntervals($interval1, $interval2)
    {
        $date = new DateTime('00:00:00');
        $date->add($interval1);
        $date->sub($interval2);
        return (new DateTime('00:00:00'))->diff($date);
    }

    public static function getDateFromInterval($interval)
    {
        return new DateTimeImmutable($interval->format('%H:%i:%s'));
    }

    public static function getDateFromString($str)
    {
        return DateTimeImmutable::createFromFormat('Y-m-d', $str);
    }

    public static function getTimeFromString($str)
    {
        return DateTimeImmutable::createFromFormat('H:i:s', $str);
    }

    public static function getFirstDayOfMonth($date)
    {
        $time = self::getDateAsDateTime($date)->getTimestamp();
        return new DateTime(date('Y-m-1', $time));
    }

    public static function getLastDayOfMonth($date)
    {
        $time = self::getDateAsDateTime($date)->getTimestamp();
        return new DateTime(date('Y-m-t', $time));
    }

    public static function getLastDayOfLastMonth($date)
    {
        $time = self::getDateAsDateTime($date)->getTimestamp();
        return new DateTime(date('Y-m-t', strtotime('-1 months', $time)));
    }

    public static function getSecondsFromDateInterval($interval)
    {
        $d1 = new DateTimeImmutable();
        $d2 = $d1->add($interval);
        return $d2->getTimestamp() - $d1->getTimestamp();
    }

    public static function isPastWorkday($date)
    {
        return !self::isWeekend($date) && self::isBefore($date, new DateTime());
    }

    public static function getTimeStringFromSeconds($seconds)
    {
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        $s = $seconds - ($h * 3600) - ($m * 60);
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    public static function formatDateWithLocale($date, $pattern)
    {
        if (!empty($date)) {
            if (strlen($date) === 10) {
                $date .= " 00:00:00";
            }
            $time = self::getDateAsDateTime($date)->getTimestamp();
            return strftime($pattern, $time);
        }
        return "";
    }

    public static function getSecondsToTimeString($strTime)
    {
        if ($strTime != "") {
            $array = explode(":", $strTime);
            $h = intval($array[0]);
            $m = intval($array[1]);
            $hToS = $h * 3600;
            $mToS = $m * 60;
            return $hToS + $mToS + intval($array[2]);
        }
        return 0;
    }

    public static function getWorkingDays($date1, $date2)
    {
        $days = 0;

        $begin = new DateTime($date1);
        $end = (new DateTime($date2))->modify('+1 day');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            if (!self::isWeekend($dt)) {
                $days++;
            }
        }

        return $days;
    }
}
