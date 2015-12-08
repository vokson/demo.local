<?php

namespace Classes\Controller\Base;
/**
 * Description of Registry
 *
 * @author vokson
 */
abstract class Registry {
    abstract protected function get($key);
    abstract protected function set($key, $val);
        
}
