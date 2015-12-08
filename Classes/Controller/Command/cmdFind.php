<?php

namespace command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdFind extends Command {

    function doExecute(\controller\Request $request) {
        //Запускаем таймер
        \util\Timer::start('GLOBAL');
        
        //Счетчик посещений
        \util\Counter::add();
        
        //Создаем идентификационный объект
        $idobj = new \mapper\DocumentIdentityObject;

        //Получаем переменные
        //Проверяем входящие данные на тип integer
        $project = filter_input(INPUT_POST, 'project', FILTER_VALIDATE_INT);
        if (is_int($project) && !($project < 0)) {
            $idobj->field('project')->like($project);
        }

        //Экранируем специальные символы
        $drawing = filter_input(INPUT_POST, 'drawing', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //Еси строка не пустая
        if (!empty($drawing)) {
            $idobj->field('drawing')->like($drawing);
        }

        $revision = filter_input(INPUT_POST, 'revision', FILTER_VALIDATE_INT);
        if (is_int($revision) && !($revision < 0)) {
            $idobj->field('revision')->like($revision);
        }

        $part = filter_input(INPUT_POST, 'part', FILTER_VALIDATE_INT);
        if (is_int($part) && ($part > 0)) {
            $idobj->field('part')->eq($part);
        }

        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($status)) {
            $idobj->field('drw_status')->like($status);
        }

        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($title)) {
            foreach (explode(' ', $title) as $value) {
                $idobj->field('title')->like($value);
            }
        }

        $date_in_beg = filter_input(INPUT_POST, 'date_in_beg', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($date_in_beg)) {
            //Преобразуем дату в integer
            $date_in_beg = strtotime($date_in_beg);
            if (is_int($date_in_beg)) {
                $idobj->field('date_in')->gteq($date_in_beg);
            }
        }

        $date_in_end = filter_input(INPUT_POST, 'date_in_end', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($date_in_end)) {
            //Преобразуем дату в integer
            //Прибавляем 1 день, чтобы сделать date_in_end включительно
            $date_in_end = strtotime($date_in_end . " + 1 day");
            if (is_int($date_in_end)) {
                //Строго < так как следующий день нам не нужен
                $idobj->field('date_in')->lt($date_in_end);
            }
        }

        $transmit_in = filter_input(INPUT_POST, 'transmit_in', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($transmit_in)) {
            $idobj->field('transmit_in')->like($transmit_in);
        }
        
        //Если нужны только последние ревизии
        $only_last_rev = filter_input(INPUT_POST, 'only_last_rev', FILTER_VALIDATE_BOOLEAN);
        if (is_bool($only_last_rev)) {
            $idobj->setOnlyMaxRevisions($only_last_rev);
        }
        

        //Если нет условий для поиска возвращает FALSE
        if (!$idobj->isVoid()) {
            //Получаем Finder
            $finder = \mapper\PersistenceFactory::getFinder('\\domain\\Document');
            
            //Проверяем создана ли таблица с максимальными ревизиями
            \mapper\DocumentMaxRevision::createTableIfNotExist($finder);

            $collection = $finder->find($idobj);
            
//             echo 'Collection created = '.\util\Timer::show('find').'<br/>';
//             var_dump($collection);
             
            $request->addFeedback($collection);
            include \util\Utils::createViewName('find');
        }
    }

}
