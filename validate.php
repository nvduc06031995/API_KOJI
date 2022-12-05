<?php

class Validate
{
    protected $data;
    protected $condition;
    protected $errors = [];

    function __construct()
    {
    }

    function __destruct()
    {
    }

    function validate($data , $condition)
    {
        $this->data = $data;        
        $this->condition = $condition;

        foreach($this->condition as $k => $v) {
            switch ($v) {
                case 'required':
                    $this->required($this->data[$k]);
                  break;                
                default:
                  $errors = [];
              }           
        }
        
        if(!empty($errors)){
            return $errors;
        } else {
            return $this->data;
        }
    }

    function required($value = NULL) {
        var_dump(empty($value)); die;
        // if(empty($value)){

        // }
    }
}
