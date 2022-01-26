<?php

interface LoggerInterface
{
    public function logMessage($logMessage);

    public function lastMessages($volumeMessages);
}

interface eventListenerInterface
{
    public function attachEvent($nameMethod, $callbackFunct);

    public function detouchEvent($nameMethod);
}


abstract class Storage implements LoggerInterface, eventListenerInterface
{
    public string $id, $nameFile;

    abstract function create($toSave);

    abstract function read();

    abstract function update($toSave);

    abstract function delete();

    abstract function list();
}

abstract class View
{
    public $storage;

    abstract function displayTextById();

    abstract function displayTextByUrl();
}


abstract class User implements eventListenerInterface
{
    protected string $id, $name, $role;

    abstract function getTextsToEdit();
}


class TelegraphText
{
    private string $text, $title, $author, $published, $slug;

    public function __construct($author, $slug)
    {
        $this->author = $author;
        $this->slug = 'localDB/' . $slug;
        $this->published = date('Y h:i:s A');

    }

    public function __get($name)
    {
        switch ($name) {
            case 'author':
                return $this->author;
            case 'slug':
                return $this->slug;
            case 'published':
                return $this->published;
            case 'storeText':
                return $this->storeText();
            case 'loadText':
                return $this->loadText();
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'author':
                if (strlen($value) <= 120) {
                    $this->author = $value;
                } else {
                    echo 'Authors name is too big', PHP_EOL;
                }
                break;
            case 'slug':
                if (ctype_alnum($value)) {
                    $this->slug = $value;
                } else {
                    echo 'Give another slug', PHP_EOL;
                }
                break;
            case 'published':
                if ($value >= date('Y h:i:s A')) {
                    $this->published = $value;
                } else {
                    echo 'Give another date', PHP_EOL;
                }
                break;
        }
    }

    private function storeText()
    {
        var_dump(111);
        var_dump($this->slug);
        $array = [
            'title' => $this->title,
            'text' => $this->text,
            'author' => $this->author,
            'published' => $this->published
        ];
        $arraySerialized = serialize($array);
        file_put_contents($this->slug, $arraySerialized);
    }

    private function loadText()
    {
        $arrayUnserialized = file_get_contents($this->slug);
        $array = unserialize($arrayUnserialized, ['allowed_classes' => false]);
        $this->title = $array['title'];
        $this->text = $array['text'];
        $this->author = $array['author'];
        $this->published = $array['published'];
        return $this->text;
    }

    public function editText(string $title, string $text)
    {
        $this->title = $title;
        $this->text = $text;
    }
}

$object = new TelegraphText('автор', 'slug2');
var_dump(1);
$object->editText('title1', 'text111111text 1111text111');
$object->storeText;
var_dump($object->loadText);
var_dump($object->published);
$object->published = '2022 01:27:12 PM';
$object->published = '2023 04:27:12 PM';
var_dump($object->published);
