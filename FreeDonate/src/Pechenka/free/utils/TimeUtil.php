<?php

/**
 * ██████╗ ███████╗ █████╗ ██╗  ██╗███████╗███╗  ██╗██╗  ██╗ █████╗
 * ██╔══██╗██╔════╝██╔══██╗██║  ██║██╔════╝████╗ ██║██║ ██╔╝██╔══██╗
 * ██████╔╝█████╗  ██║  ╚═╝███████║█████╗  ██╔██╗██║█████═╝ ███████║
 * ██╔═══╝ ██╔══╝  ██║  ██╗██╔══██║██╔══╝  ██║╚████║██╔═██╗ ██╔══██║
 * ██║     ███████╗╚█████╔╝██║  ██║███████╗██║ ╚███║██║ ╚██╗██║  ██║
 * ╚═╝     ╚══════╝ ╚════╝ ╚═╝  ╚═╝╚══════╝╚═╝  ╚══╝╚═╝  ╚═╝╚═╝  ╚═╝
 *
 * DEVELOPED BY 𝗣𝗘𝗖𝗛𝗘𝗡𝗞𝗔
 * VK: 𝓿𝓴.𝓬𝓸𝓶/𝓿𝓸𝓿𝓪𝓷446
 */

namespace Pechenka\free\utils;

class TimeUtil
{
    const timePattern = "/(?:([0-9]+)\s*y[a-z]*[,\s]*)?(?:([0-9]+)\s*mo[a-z]*[,\s]*)?(?:([0-9]+)\s*w[a-z]*[,\s]*)?(?:([0-9]+)\s*d[a-z]*[,\s]*)?(?:([0-9]+)\s*h[a-z]*[,\s]*)?(?:([0-9]+)\s*m[a-z]*[,\s]*)?(?:([0-9]+)\s*(?:s[a-z]*)?)?/";
    const maxYears = 100000;
    /**
     * Constants data
     */
    const YEAR = 31536000;
    const MONTH = 2592000;
    const WEEK = 604800;
    const DAY = 86400;
    const HOUR = 3600;
    const MINUTE = 60;
    private static $yearNames = ["год", "года", "лет"];
    private static $monthNames = ["месяц", "месяца", "месяцев"];
    private static $dayNames = ["день", "дня", "дней"];
    private static $hourNames = ["час", "часа", "часов"];
    private static $minuteNames = ["минута", "минуты", "минут"];
    private static $secondNames = ["секунда", "секунды", "секунд"];

    /**
     * @param $time
     * @return string
     */
    public static function toString(int $time)
    {
        if ($time <= 0) {
            return "Сейчас";
        }
        $years = 0;
        $months = 0;
        $days = 0;
        $hours = 0;
        $mins = 0;
        if ($time >= self::YEAR) {
            $years = floor($time / self::YEAR);
            $time %= self::YEAR;
        }
        if ($time >= self::MONTH) {
            $years = floor($time / self::MONTH);
            $time %= self::MONTH;
        }
        if ($time >= self::DAY) {
            $days = floor($time / self::DAY);
            $time %= self::DAY;
        }
        if ($time >= self::HOUR) {
            $hours = floor($time / self::HOUR);
            $time %= self::HOUR;
        }
        if ($time >= self::MINUTE) {
            $mins = floor($time / self::MINUTE);
            $time %= self::MINUTE;
        }
        $sec = &$time;
        $result = "";
        if ($years > 0)
            $result .= self::pluralForm($years, self::$yearNames) . " ";
        if ($months > 0)
            $result .= self::pluralForm($months, self::$monthNames) . " ";
        if ($days > 0)
            $result .= self::pluralForm($days, self::$dayNames) . " ";
        if ($hours > 0)
            $result .= self::pluralForm($hours, self::$hourNames) . " ";
        if ($mins > 0)
            $result .= self::pluralForm($mins, self::$minuteNames) . " ";
        if ($sec > 0)
            $result .= self::pluralForm($sec, self::$secondNames) . " ";
        return $result;
    }

    public static function parseDateDiff(string $string, bool $future = true)
    {
        $r = preg_match(self::timePattern, $string, $output);
        if ($r === false || $r === 0 || $output === null) {
            return -1;
        }
        array_shift($output);
        $years = (isset($output[0]) && !empty($output[0])) ? $output[0] : 0;
        $months = (isset($output[1]) && !empty($output[1])) ? $output[1] : 0;
        $weeks = (isset($output[2]) && !empty($output[2])) ? $output[2] : 0;
        $days = (isset($output[3]) && !empty($output[3])) ? $output[3] : 0;
        $hours = (isset($output[4]) && !empty($output[4])) ? $output[4] : 0;
        $minutes = (isset($output[5]) && !empty($output[5])) ? $output[5] : 0;
        $seconds = (isset($output[6]) && !empty($output[6])) ? $output[6] : 0;
        $c = ($future) ? time() : 0;
        if ($years > 0) {
            if ($years > self::maxYears) {
                $years = self::maxYears;
            }
            $c += $years * self::YEAR;
        }
        if ($months > 0) {
            $c += $months * self::MONTH;
        }
        if ($weeks > 0) {
            $c += $weeks * self::WEEK;
        }
        if ($days > 0) {
            $c += $days * self::DAY;
        }
        if ($hours > 0) {
            $c += $hours * self::HOUR;
        }
        if ($minutes > 0) {
            $c += $minutes * self::MINUTE;
        }
        if ($seconds > 0) {
            $c += $seconds;
        }
        if (!$future && ($c > (self::YEAR * 10))) {
            $c = self::YEAR * 10;
        }
        return $c;
    }

    /**
     * @param int $number - Изначальное число
     * @param array $after - варианты написания 1, 2, 5
     * @return string число с выбранным вариантом
     */
    public static function pluralForm(int $number, array $after): string
    {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $number . ' ' . $after[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }
}