<?php

class Validate
{
    protected $data;
    protected $condition;
    protected $errors = [];
    private $dbReference;
    var $dbConnect;

    function __construct()
    {
    }

    function __destruct()
    {
    }

    function validate($data, $condition)
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();

        $this->data = $data;
        $this->condition = $condition;

        $errors = $this->errors;

        foreach ($this->condition as $k => $v) {
            $name = $k;
            $validate_type = explode('|', $v);
            foreach ($validate_type as $k_type => $v_type) {
                switch ($v_type) {
                    case 'required':
                        if (isset($this->data[$name])) {
                            if (!is_null($this->required($name, $this->data[$name]))) {
                                $errors[] = $this->required($name, $this->data[$name]);
                            }
                        } else {
                            $errors[] = "required " . $name;
                        }
                        break;
                    case 'numeric':
                        if (isset($this->data[$name])) {
                            if (!is_null($this->numeric($name, $this->data[$name]))) {
                                $errors[] = $this->numeric($name, $this->data[$name]);
                            }
                        } else {
                            $errors[] = $name . " must be number";
                        }
                        break;
                    case 'nullable':
                        if (isset($this->data[$name])) {                          
                            $this->data[$name] = !empty($this->data[$name]) ? $this->data[$name] : 'NULL';
                        } 
                        break;
                    default:
                        $errors = [];
                }
            }
        }

        if (!empty($errors)) {
            $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            die();
        } else {
            return $this->data;
        }
    }

    function required($name, $value = NULL)
    {
        if (empty($value)) {
            return "required " . $name;
        }
    }

    function numeric($name, $value = NULL)
    {
        if (!is_numeric($value)) {
            return $name . " must be number";
        }
    }
}
