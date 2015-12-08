<?php

namespace command;

/**
 * Sample command class
 *
 * @author Noskov Alexey <vokson@yandex.ru>
 */
class cmdNewTransmittalCheck extends Command {

    private $finder;

    function transmitExist($transmit_in) {
        //Создаем идентификационный объект
        $idobj = new \mapper\DocumentIdentityObject;
        $idobj->setOnlyMaxRevisions(FALSE);
        $idobj->setLimit(1);

        if (!empty($transmit_in)) {
            $idobj->field('transmit_in')->eq($transmit_in);
        }
        //var_dump($idobj);
        //Если нет условий для поиска возвращает FALSE
        if (!$idobj->isVoid()) {
            //Получаем Finder
            if (!isset($this->finder)) {
                $this->finder = \mapper\PersistenceFactory::getFinder('\\domain\\Document');
            }
//            var_dump($finder);

            $collection = $this->finder->find($idobj);
//            var_dump($collection);
            return $collection->valid();
        }

        return FALSE;
    }

    function doExecute(\controller\Request $request) {

        //Максимальное время работы скрипта
        set_time_limit(\base\ApplicationRegistry::getDrawing()['get_time']);
        //Список новых трансмиталлов
        $transmit_list = array();

        //читаем корневую директорию сервера
        $path = realpath(\base\ApplicationRegistry::getDrawing()['server_path']);
        if ($server_handle = opendir($path)) {
            while (false !== ($entry = readdir($server_handle))) {

//                echo $entry . "<br/>";
                if ($entry != "." && $entry != ".." && //выбрасываем лишнее
                        substr($entry, 0, strpos($entry, '-')) == \base\ApplicationRegistry::getProject()) {

                    //Получаем номер трансмиталла, экранируем специальные символы
                    $transmit_in = filter_var(trim(substr($entry, 0, strpos($entry, ' '))), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    //проверяем есть ли уже в базе такой номер трансмитала
                    if ($this->transmitExist($transmit_in) === FALSE) {
                        $transmit_list[] = $transmit_in;
                    }
                }
            }
            closedir($server_handle);
        }

        $request->addFeedback($transmit_list);
//        var_dump($transmit_list);
        include \util\Utils::createViewName('new_transmittal_check');
    }

}
