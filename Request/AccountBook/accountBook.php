<?php
include('../../System/systemConfig.php');
include('../../Validate/validate.php');

class AccountBook
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

    /* 出納帳 */
    function getAccountBook()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $resultSet = array();
            $errors = [];

            if (isset($_GET['TANT_CD'])) {
                $TANT_CD = $_GET['TANT_CD'];

                $sql = 'SELECT TANT_NAME, KAISYU_RUIKEI
                    FROM M_TANT 
                    WHERE TANT_CD="' . $TANT_CD . '" 
                        AND DEL_FLG=0
                ';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['TANT_NAME'] = $row['TANT_NAME'];
                        $resultSet['KAISYU_RUIKEI'] = $row['KAISYU_RUIKEI'];
                    }
                }

                // 本日時点の回収累計
                $sql = 'SELECT T_NYUKINMSAI.NYUKIN_GAK
                    FROM T_NYUKIN 
                    INNER JOIN T_NYUKINMSAI ON T_NYUKIN.NYUKIN_ID=T_NYUKINMSAI.NYUKIN_ID 
                    WHERE T_NYUKIN.TANT_CD="' . $TANT_CD . '" 
                ';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['NYUKIN_GAK'] = $row['NYUKIN_GAK'];
                    }
                }

                $resultSet['total'] = $resultSet['KAISYU_RUIKEI'] - $resultSet['NYUKIN_GAK'];
            } else {
                $errors['msg'][] = 'Missing parameter TANT_CD';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }
}