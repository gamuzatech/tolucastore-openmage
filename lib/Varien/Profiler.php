<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Varien_Profiler
 */

class Varien_Profiler
{
    /**
     * Timers for code profiling
     *
     * @var array
     */
    private static $_timers = [];
    private static $_enabled = false;
    private static $_memory_get_usage = false;

    public static function enable()
    {
        self::$_enabled = true;
        self::$_memory_get_usage = function_exists('memory_get_usage');
    }

    public static function disable()
    {
        self::$_enabled = false;
    }

    public static function reset($timerName)
    {
        self::$_timers[$timerName] = [
            'start' => false,
            'count' => 0,
            'sum' => 0,
            'realmem' => 0,
            'emalloc' => 0,
        ];
    }

    public static function resume($timerName)
    {
        if (!self::$_enabled) {
            return;
        }

        if (empty(self::$_timers[$timerName])) {
            self::reset($timerName);
        }
        if (self::$_memory_get_usage) {
            self::$_timers[$timerName]['realmem_start'] = memory_get_usage(true);
            self::$_timers[$timerName]['emalloc_start'] = memory_get_usage();
        }
        self::$_timers[$timerName]['start'] = microtime(true);
        self::$_timers[$timerName]['count']++;
    }

    public static function start($timerName)
    {
        self::resume($timerName);
    }

    public static function pause($timerName)
    {
        if (!self::$_enabled) {
            return;
        }

        $time = microtime(true); // Get current time as quick as possible to make more accurate calculations

        if (empty(self::$_timers[$timerName])) {
            self::reset($timerName);
        }
        if (false !== self::$_timers[$timerName]['start']) {
            self::$_timers[$timerName]['sum'] += $time - self::$_timers[$timerName]['start'];
            self::$_timers[$timerName]['start'] = false;
            if (self::$_memory_get_usage) {
                self::$_timers[$timerName]['realmem'] += memory_get_usage(true) - self::$_timers[$timerName]['realmem_start'];
                self::$_timers[$timerName]['emalloc'] += memory_get_usage() - self::$_timers[$timerName]['emalloc_start'];
            }
        }
    }

    public static function stop($timerName)
    {
        self::pause($timerName);
    }

    public static function fetch($timerName, $key = 'sum')
    {
        if (empty(self::$_timers[$timerName])) {
            return false;
        } elseif (empty($key)) {
            return self::$_timers[$timerName];
        }
        switch ($key) {
            case 'sum':
                $sum = self::$_timers[$timerName]['sum'];
                if (self::$_timers[$timerName]['start'] !== false) {
                    $sum += microtime(true) - self::$_timers[$timerName]['start'];
                }
                return $sum;

            case 'count':
                return self::$_timers[$timerName]['count'];

            case 'realmem':
                if (!isset(self::$_timers[$timerName]['realmem'])) {
                    self::$_timers[$timerName]['realmem'] = -1;
                }
                return self::$_timers[$timerName]['realmem'];

            case 'emalloc':
                if (!isset(self::$_timers[$timerName]['emalloc'])) {
                    self::$_timers[$timerName]['emalloc'] = -1;
                }
                return self::$_timers[$timerName]['emalloc'];

            default:
                if (!empty(self::$_timers[$timerName][$key])) {
                    return self::$_timers[$timerName][$key];
                }
        }
        return false;
    }

    public static function getTimers()
    {
        return self::$_timers;
    }

    /**
     * Output SQl Zend_Db_Profiler
     *
     */
    public static function getSqlProfiler($res)
    {
        if (!$res) {
            return '';
        }
        $out = '';
        $profiler = $res->getProfiler();
        if ($profiler->getEnabled()) {
            $totalTime    = $profiler->getTotalElapsedSecs();
            $queryCount   = $profiler->getTotalNumQueries();
            $longestTime  = 0;
            $longestQuery = null;

            foreach ($profiler->getQueryProfiles() as $query) {
                if ($query->getElapsedSecs() > $longestTime) {
                    $longestTime  = $query->getElapsedSecs();
                    $longestQuery = $query->getQuery();
                }
            }

            $out .= 'Executed ' . $queryCount . ' queries in ' . $totalTime . ' seconds' . '<br>';
            $out .= 'Average query length: ' . $totalTime / $queryCount . ' seconds' . '<br>';
            $out .= 'Queries per second: ' . $queryCount / $totalTime . '<br>';
            $out .= 'Longest query length: ' . $longestTime . '<br>';
            $out .= 'Longest query: <br>' . $longestQuery . '<hr>';
        }
        return $out;
    }
}
