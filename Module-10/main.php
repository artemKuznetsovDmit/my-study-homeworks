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
    public string $id, $name, $role;

    abstract function getTextsToEdit();
}


class FileStorage extends Storage
{
    const BASE_PATH = 'localDB/';
    public string $id, $nameFile;
    public array $eventListener = [];

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

    public function create($toSave)
    {
        $this->eventListenHelper('title');

        $this->namefile = $this->fileOperations($toSave);
        return $this->id;
    }

    public function eventListenHelper($function)
    {
        if (isset($this->eventListener[$function])) {
            call_user_func($this->eventListener[$function]);
        }
    }

    public function fileOperations(string $toSave)
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

    public function read()
    {
        $this->eventListenHelper(__FUNCTION__);

        $toReturnUnserialized = file_get_contents(self::BASE_PATH . $this->namefile);
        return unserialize($toReturnUnserialized, [false]);
    }

    public function update($toSave)
    {
        $this->eventListenHelper(__FUNCTION__);
        $this->namefile = $this->fileOperations($toSave);
    }

    public function delete()
    {
        $this->eventListenHelper(__FUNCTION__);
        $files = array_diff(scandir('localDB'), array('..', '.'));
        foreach ($files as $file) {
            $str = strpos($file, '_');
            $file_id = substr($file, 0, $str);
            if ($file_id == $this->id) {
                unlink(self::BASE_PATH . $file);
            }
        }
    }

    public function list()
    {
        $this->eventListenHelper(__FUNCTION__);

        $files = array_diff(scandir('localDB'), array('..', '.'));
        $filesText = [];
        foreach ($files as $file) {
            $filesText[] = file_get_contents(self::BASE_PATH . $file);
        }
        return $filesText;
    }

    public function logMessage($logMessage)
    {
        $this->eventListenHelper(__FUNCTION__);
        $log = date('Y-m-d H:i:s') . ' ---error---> ' . $logMessage;
        error_log($log . PHP_EOL, 3, self::BASE_PATH . 'error.log');
    }

    public function lastMessages($volumeMessages)
    {
        foreach ($this->eventListener as $method) {
            $key = array_search(__FUNCTION__, $method, true);
            if ($key) {
                call_user_func($key);
            }
        }

        $log = file_get_contents(self::BASE_PATH . 'error.log');
        $logArr = explode(PHP_EOL, $log);

        // проверяем, есть ли пустые строки
        $logArr = array_diff($logArr, array(''));

        $logResult = array();
        $logArrCount = count($logArr);

        // для случаев, когда нужно выгрузить больше сообщений, чем есть в лог файле
        if ($logArrCount < $volumeMessages) {
            $volumeMessages = $logArrCount;
        }

        for ($i = $logArrCount; $i !== 0; $i--) {

            // '-1', так как
            // 1. count счиатает на одно значение больше
            // 2. нам также нужно то значение, которое занимает индекс 0 в массиве
            $logResult[] = $logArr[$i - 1];

            $volumeMessages--;
            if ($volumeMessages === 0) {
                return $logResult;
            }
        }
    }

    public function attachEvent($nameMethod, $callbackFunct)
    {
        if (is_callable($callbackFunct)) {
            $this->eventListener[$nameMethod] = $callbackFunct;
        } else {
            return false;
        }
    }


    public function detouchEvent($nameMethod)
    {
        unset($this->eventListener[$nameMethod]);
    }
}


$object1 = new FileStorage();
$object1->create('abracadabra1');
$object1->create('abracadabra1_2');
$object1->update('new abracadabra');
$object1->logMessage('9991212');
$object1->logMessage('1212333');
$object1->logMessage('8881212444');

$object1->attachEvent('list', function () {
    var_dump('I am function from eventListener');
});
$object1->list();
$object1->detouchEvent('list');
$object1->list();


$object2 = new FileStorage();
$object2->create('abracadabra2');
$object2->create('abracadabra2_2');
$object2->update('new abracadabra2');


$object3 = new FileStorage();
$object3->create('abracadabra3');
$object3->create('abracadabra3_2');
$object3->update('new abracadabra3');


$object2->delete();

//var_dump($object1->read());
//var_dump($object1->read());
//var_dump($object2->read());
//var_dump($object2->read());
//var_dump($object3->read());
//var_dump($object3->read());
//var_dump($object1->lastMessages(300));



