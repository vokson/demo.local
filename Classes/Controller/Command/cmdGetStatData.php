<?php

namespace command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdGetStatData extends Command {

    function doExecute(\controller\Request $request) {
        //Устанавливаем временную зону
        date_default_timezone_set('Europe/Moscow');

        //Экранируем специальные символы
        $period = filter_input(INPUT_POST, 'period', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //Если строка не пустая
        if (empty($period) || $period === FALSE) {
            $period = 'day';
        }
        

        //Получаем сдвиг 
        $offset = filter_input(INPUT_POST, 'offset', FILTER_VALIDATE_INT);
        if (!is_int($offset)) {
            $offset = 0;
        }

        //Вычисляем метку времени начала дня со сдвигом
        switch ($period) {
            case'day':
                $beg = strtotime('now 00:00:00') + 24 * 60 * 60 * $offset;
                $end = $beg + 24 * 60 * 60 ;
                break;
            case 'month':
                $beg = strtotime(sprintf("%+d", $offset).' month', strtotime('first day of this month'));
                $end = strtotime('+1 month', $beg);
                break;
        }

//        echo "DAY_BEG = $beg <br/>";
//        echo "DAY_END = $end <br/>";
        //Создаем объект для идентификации
        $idobj = new \mapper\CounterIdentityObject;
        $idobj->field('time')->gteq($beg);
        $idobj->field('time')->lt($end);


        //Получаем Finder
        $finder = \mapper\PersistenceFactory::getFinder('\\domain\\CounterObject');
        $day_collection = $finder->find($idobj);
//        var_dump($day_collection);

        $arr_by_uin = array();
        $arr_by_time = array();
        foreach ($day_collection as $obj) {
            //Добавляем 1 в массив запросов по пользователям
            if (!isset($arr_by_uin[$obj->getUin()])) {
                $arr_by_uin[$obj->getUin()] = 1;
            } else {
                $arr_by_uin[$obj->getUin()] ++;
            }

            //Определяем час
            switch ($period) {
            case 'day':
                $time = date("Y-m-d H", $obj->getTime()) . ':00';
                break;
            case 'month':
                $time = date("Y-m-d", $obj->getTime());
                break;
        }
            
            //Добавляем 1 в массив запросов по часам
            if (!isset($arr_by_time[$time])) {
                $arr_by_time[$time] = 1;
            } else {
                $arr_by_time[$time] ++;
            }
        }

        $request->addFeedback(array($arr_by_uin, $arr_by_time));
//        var_dump($arr_by_uin);
//        var_dump($arr_by_time);

        include \util\Utils::createViewName('getStatData');
    }

}
