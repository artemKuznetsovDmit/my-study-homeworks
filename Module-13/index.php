<?php

require_once 'autoload.php';

$object = new FileStorage();
$object->create('title1212_165165');
var_dump($object->list());