<?php

namespace App;


class DatabaseError 
{
    private $errorCode;

    public function __construct($errorCode)
    {
        $this->errorCode = $errorCode;
    }
}
