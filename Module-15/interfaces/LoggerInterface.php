<?php

interface LoggerInterface
{
    public function logMessage($logMessage);

    public function lastMessages($volumeMessages);
}