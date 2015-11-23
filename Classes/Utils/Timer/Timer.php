<?php

namespace Classes\Utils\Timer;

/**
 * Description of Timer
 *
 * @author vokson
 */
class Timer {

    static private $timers; //Массив таймеров

    /**
     * Запускает таймер с именем $name
     * 
     * @param string $name Timer's name
     */

    static function start($name) {
        self::$timers[$name]['time_start'] = self::$timers[$name]['time_end'] = microtime(true);
    }

    /**
     * Останавливает таймер с именем $name
     * 
     * @param string $name Timer's name
     */
    static function stop($name) {
        self::$timers[$name]['time_end'] = microtime(true);
    }

    /**
     * Показывает время таймера $name
     * 
     * @param string $name Timer's name
     */
    static function show($name) {
        //Если таймер не остановлен, время = текущее - начало
        if (self::$timers[$name]['time_start'] == self::$timers[$name]['time_end']) {
            $time = sprintf("%01.3f", microtime(true) - self::$timers[$name]['time_start']);
        } else {
            // Если таймер остановлен, время = конец - начало
            $time = sprintf("%01.3f", self::$timers[$name]['time_end'] - self::$timers[$name]['time_start']);
        }
        return $time;
    }

    /*
     * PRINT
     */
    static function servicePrint() {
        echo "+++ TIMER +++<br/>";
        
        foreach (array_keys(self::$timers) as $name) {
            echo "$name - ".self::show($name)."sec.<br/>";
        }
        
        echo "<br/>";
    }
}
