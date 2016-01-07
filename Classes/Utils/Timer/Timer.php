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

    /*
     * Вычисляет время таймера
     * 
     * @param string $name Timer's name
     * @return float Время таймера в секундах
     */
    static function getTime($name) {
        //Если таймер не остановлен, время = текущее - начало
        if (self::$timers[$name]['time_start'] == self::$timers[$name]['time_end']) {
             return microtime(true) - self::$timers[$name]['time_start'];
        } else {
            // Если таймер остановлен, время = конец - начало
            return self::$timers[$name]['time_end'] - self::$timers[$name]['time_start'];
        }
    }
    
    /*
     * Вычисляет суммарное время всех таймеров
     * 
     * @return float Время в секундах
     */
    static function getTotalTime() {
        $totalTime = 0;
        foreach (array_keys(self::$timers) as $name) {
            $totalTime += self::getTime($name);
        }
        
        return sprintf("%01.3f", $totalTime);
    }
    
    /**
     * Показывает время таймера $name
     * 
     * @param string $name Timer's name
     */
    static function show($name) {
        return sprintf("%01.3f", self::getTime($name));
    }

    /*
     * PRINT
     */
    static function servicePrint() {
        echo "+++ TIMER (" . self::getTotalTime() . ") +++<br/>";
        
        foreach (array_keys(self::$timers) as $name) {
            echo "$name - ".self::show($name)."sec.<br/>";
        }
        
        echo "<br/>";
    }
}
