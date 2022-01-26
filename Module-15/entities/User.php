<?php

require_once '../interfaces/EventListenerInterface.php';
require_once '../interfaces/LoggerInterface.php';

abstract class User implements eventListenerInterface
{
    public string $id, $name, $role;

    abstract function getTextsToEdit();
}