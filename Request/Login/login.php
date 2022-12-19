<?php
include('../../System/systemConfig.php');
include('../../Validate/validate.php');

class Login
{
    private $dbReference;
    var $dbConnect;
    var $validate;
    var $result;
    var $changeKey;
    var $domain = "https://koji-app.starboardasiavn.com/";
    /**
     *
     */
    function __construct()
    {
    }

    function __destruct()
    {
    }

    function postLogin()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $validate = new Validate();
            $resultSet = array();

            $validated = $validate->validate($_POST, [
                'LOGIN_ID' => 'required',
                'PASSWORD' => 'required',
            ]);

            if ($validated) {
                $LOGIN_ID = "";
                $sql = 'SELECT TANT_CD FROM M_TANT WHERE TANT_CD="' . $validated['LOGIN_ID'] . '" AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    while ($row = $this->result->fetch_assoc()) {
                        $LOGIN_ID = $row['TANT_CD'];
                    }
                }

                if ($LOGIN_ID != "") {
                    $sql = 'SELECT * FROM M_TANT WHERE TANT_CD="' . $validated['LOGIN_ID'] . '" AND PASSWORD="' . $validated['PASSWORD'] . '" AND DEL_FLG= 0';
                    $this->result = $this->dbConnect->query($sql);

                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                    }

                    if ($this->result->num_rows > 0) {
                        // output data of each row                    
                        while ($row = $this->result->fetch_assoc()) {
                            $row['STATUS'] = 'success';
                            $resultSet = $row;
                        }
                    } else {
                        $errors['msg'][] = 'パスワードが正しくありません';
                    }
                } else {
                    $errors['msg'][] = '担当者コードが存在しません。正しいコードを入力してください。';
                }
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }
}
