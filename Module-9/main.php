<?php

abstract class Storage
{
    public string $id, $namefile;

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

abstract class User
{
    public string $id, $name, $role;

    abstract function getTextsToEdit();
}


class FileStorage extends Storage
{
    const BASE_PATH = 'localDB/';
    public string $id, $namefile;

    public function __construct()
    {
        // Выбираем id для обьекта. Берем первое свободное число.
        $files = array_diff(scandir('localDB'), array('..', '.'));
        if (count($files) === 0) {
            $this->id = 1;
        } else {
            $fileIdArray = [];
            foreach ($files as $file) {
                $str = strpos($file, '_');
                $file_id = substr($file, 0, $str);
                if (!array_key_exists($file_id, $fileIdArray)) {
                    $fileIdArray[] = $file_id;
                }
                $this->id = max($fileIdArray) + 1;
            }
        }
    }

    public function fileOperations($toSave)
    {
        // Выбираем имя обьекта
        $nameFile = $this->id . '_' . date("d.m.y");
        $a = 0;
        while (file_exists($nameFile)) {
            $nameFile = $this->id . '_' . date("d.m.y");
            $a++;
            $nameFile .= '_' . $a;
        }

        // Сохраняем
        $toSaveSerialized = serialize($toSave);
        file_put_contents(self::BASE_PATH . $nameFile, $toSaveSerialized);
        return $nameFile;
    }

    public function create($toSave)
    {
        $this->namefile = $this->fileOperations($toSave);
        return $this->id;
    }

    public
    function read()
    {
        $toReturnUnserialized = file_get_contents(self::BASE_PATH . $this->namefile);
        return unserialize($toReturnUnserialized, [false]);
    }

    public
    function update($toSave)
    {
        $this->namefile = $this->fileOperations($toSave);
    }

    public
    function delete()
    {
        $files = array_diff(scandir('localDB'), array('..', '.'));
        foreach ($files as $file) {
            $str = strpos($file, '_');
            $file_id = substr($file, 0, $str);
            if ($file_id == $this->id) {
                unlink(self::BASE_PATH . $file);
            }
        }
    }

    public
    function list()
    {
        $files = array_diff(scandir('localDB'), array('..', '.'));
        $filesText = [];
        foreach ($files as $file) {
            $filesText[] = file_get_contents(self::BASE_PATH . $file);
        }
        return $filesText;
    }

}


$object1 = new FileStorage();
$object1->create('abracadabra1');
$object1->create('abracadabra1_2');
var_dump($object1->read());
$object1->update('new abracadabra');
var_dump($object1->read());


$object2 = new FileStorage();
$object2->create('abracadabra2');
$object2->create('abracadabra2_2');
var_dump($object2->read());
$object2->update('new abracadabra2');
var_dump($object2->read());


$object3 = new FileStorage();
$object3->create('abracadabra3');
$object3->create('abracadabra3_2');
var_dump($object3->read());
$object3->update('new abracadabra3');
var_dump($object3->read());


$object2->delete();

