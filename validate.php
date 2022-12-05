<?php

class Validate
{
    function __construct()
    {
    }

    function __destruct()
    {
    }

    function validate($data)
    {
        var_dump($data);
        die;
    }

    function required() {
        echo 1; die;
    }
}
