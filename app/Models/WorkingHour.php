<?php

namespace App\Models;

use App\Helpers\Constants;
use App\Helpers\DateUtils;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class WorkingHour extends Model
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'work_date', 'worked_time', 'time1', 'time2', 'time3', 'time4'
    ];

    public static function loadFromUserAndDate($subscriberId, $userId, $workDate)
    {
        $registry = self::where('user_id', $userId)->where('work_date', $workDate)->first();

        if (!$registry) {
            $registry = new WorkingHour([
                'subscriber_id' => $subscriberId,
                'user_id' => $userId,
                'work_date' => $workDate,
                'worked_time' => 0
            ]);
        }

        return $registry;
    }

    public static function getWorkedInterval($workingHours)
    {
        [$t1, $t2, $t3, $t4] = self::getTimes($workingHours);

        $part1 = new DateInterval('PT0S');
        $part2 = new DateInterval('PT0S');

        if ($t1) {
            $part1 = $t1->diff(new DateTime());
        }

        if ($t2) {
            $part1 = $t1->diff($t2);
        }

        if ($t3) {
            $part2 = $t3->diff(new DateTime());
        }

        if ($t4) {
            $part2 = $t3->diff($t4);
        }

        return DateUtils::sumIntervals($part1, $part2);
    }

    public static function getLunchInterval($workingHours)
    {
        [, $t2, $t3,] = self::getTimes($workingHours);

        $lunchInterval = new DateInterval('PT0S');

        if ($t2) {
            $lunchInterval = $t2->diff(new DateTime());
        }

        if ($t3) {
            $lunchInterval = $t2->diff($t3);
        }

        return $lunchInterval;
    }

    public static function getExitTime($workingHours)
    {
        [$t1,,, $t4] = self::getTimes($workingHours);

        $workday = DateInterval::createFromDateString('8 hours');

        if (!$t1) {
            return (new DateTimeImmutable())->add($workday);
        } elseif ($t4) {
            return $t4;
        } else {
            $total = DateUtils::sumIntervals($workday, self::getLunchInterval($workingHours));
            return $t1->add($total);
        }
    }

    public static function getActiveClock($workingHours)
    {
        $nextTime = self::getNextTime($workingHours);
        if ($nextTime === 'time1' || $nextTime === 'time3') {
            return 'exitTime';
        } else if ($nextTime === 'time2' || $nextTime === 'time4') {
            return 'workedInterval';
        } else {
            return null;
        }
    }

    public static function getNextTime($workingHours)
    {
        if (!$workingHours->time1) {
            return 'time1';
        } else if (!$workingHours->time2) {
            return 'time2';
        } else if (!$workingHours->time3) {
            return 'time3';
        } else if (!$workingHours->time4) {
            return 'time4';
        } else {
            return null;
        }
    }

    public static function getMonthlyReport($userId, $date)
    {
        $registries = [];
        $startDate = DateUtils::getFirstDayOfMonth($date)->format('Y-m-d');
        $endDate = DateUtils::getLastDayOfMonth($date)->format('Y-m-d');

        $result = self::where('user_id', $userId)
            ->where('work_date', '>=', $startDate)
            ->where('work_date', '<=', $endDate)
            ->get();

        if ($result) {
            foreach ($result as $row) {
                $registries[$row['work_date']] = $row;
            }
        }

        return $registries;
    }

    // privates

    private static function getTimes($workingHours)
    {
        $times = [];
        if ($workingHours) {
            $workingHours->time1 ? array_push($times, DateUtils::getDateFromString($workingHours->time1)) : array_push($times, null);
            $workingHours->time2 ? array_push($times, DateUtils::getDateFromString($workingHours->time2)) : array_push($times, null);
            $workingHours->time3 ? array_push($times, DateUtils::getDateFromString($workingHours->time3)) : array_push($times, null);
            $workingHours->time4 ? array_push($times, DateUtils::getDateFromString($workingHours->time4)) : array_push($times, null);
        }
        return $times;
    }

    // chamados pela view

    function getBalance()
    {
        if (empty($this->time1)) {
            return '';
        }

        if ($this->worked_time == Constants::DAILY_TIME) {
            return '-';
        }

        $balance = $this->worked_time - Constants::DAILY_TIME;
        $balanceString = DateUtils::getTimeStringFromSeconds(abs($balance));
        $sign = $this->worked_time >= Constants::DAILY_TIME ? '+' : '-';
        return "{$sign}{$balanceString}";
    }

    function formatDateWithLocale($date)
    {
        return DateUtils::formatDateWithLocale($date, '%A, %d de %B de %Y');
    }
}
