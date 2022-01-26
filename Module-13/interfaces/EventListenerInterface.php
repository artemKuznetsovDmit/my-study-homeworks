<?php

interface eventListenerInterface
{
    public function attachEvent($nameMethod, $callbackFunct);

    public function detouchEvent($nameMethod);
}

