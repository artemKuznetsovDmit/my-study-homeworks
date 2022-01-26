<?php
abstract class Text
{
    public $storage;

    abstract function displayTextById();

    abstract function displayTextByUrl();
}