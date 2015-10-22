<?php
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . "/.."));
spl_autoload_extensions(".php");
spl_autoload_register('spl_autoload');

error_reporting( E_ERROR );

