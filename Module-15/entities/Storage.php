<?php

require_once 'D:\MINE\PhpstormProjects\php-developer-base\Module-13\interfaces\EventListenerInterface.php';
require_once 'D:\MINE\PhpstormProjects\php-developer-base\Module-13\interfaces\LoggerInterface.php';

abstract class Storage implements LoggerInterface, eventListenerInterface
{
    public string $id, $nameFile;

    abstract function create($toSave);

    abstract function read();

    abstract function update($toSave);

    abstract function delete();

    abstract function list();
}