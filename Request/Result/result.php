<?php
include('../../System/systemConfig.php');
include('../../Validate/validate.php');

class Result
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

    // function postLogin()
    // {
    //     $this->dbReference = new systemConfig();
    //     $this->dbConnect = $this->dbReference->connectDB();
    //     if ($this->dbConnect == NULL) {
    //         $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
    //     } else {
    //         $errors = [];
    //         $validate = new Validate();
    //         $resultSet = array();

    //         $validated = $validate->validate($_POST, [
    //             'LOGIN_ID' => 'required',
    //             'PASSWORD' => 'required',
    //         ]);

    //         if ($validated) {
    //             $sql = 'SELECT * FROM M_TANT WHERE TANT_CD="' . $validated['LOGIN_ID'] . '" AND PASSWORD="' . $validated['PASSWORD'] . '" AND DEL_FLG= 0';
    //             $this->result = $this->dbConnect->query($sql);

    //             if (!empty($this->dbConnect->error)) {
    //                 $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
    //             }

    //             if ($this->result->num_rows > 0) {
    //                 // output data of each row                    
    //                 while ($row = $this->result->fetch_assoc()) {
    //                     $row['STATUS'] = 'success';
    //                     $resultSet = $row;
    //                 }
    //             } else {
    //                 $errors['msg'][] = 'LOGIN_ID or PASSWORD is wrong!';
    //             }
    //         }

    //         if(empty($errors['msg'])){
    //             $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    //         } else {
    //             $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    //         }            
    //     }
    // }

    function default()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();
            if (isset($_GET['TANT_CD']) && isset($_GET['JISEKI_YMD'])) {
                $TANT_CD = $_GET['TANT_CD'];
                $JISEKI_YMD = $_GET['JISEKI_YMD'];
                
                $sql = ' SELECT ITAKUHI_MIKAKUTEI,
                ITAKUHI_KAKUTEI,
                KOJI_COUNT,
                SITAMI_COUNT,
                ADD_KOJI_COUNT,
                SALES_COUNT,
                TANT_CD,
                JISEKI_YMD
                FROM T_JISEKISYUKEI 
                WHERE TANT_CD="' . $TANT_CD . '"
                AND DATE_FORMAT(JISEKI_YMD , "%Y/%m")="'.$JISEKI_YMD.'"
                AND DEL_FLG=0';

                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

            } else {
                $errors['msg'][] = 'Missing parameter TANT_CD or JISEKI_YMD';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function getPullDownListPeople()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            $sql = ' SELECT TANT_NAME,
            TANT_CD 
            FROM M_TANT 
            WHERE TANT_KBN_CD="03"
            AND DEL_FLG=0';
            $this->result = $this->dbConnect->query($sql);
            if (!empty($this->dbConnect->error)) {
                $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
            }

            if ($this->result && $this->result->num_rows > 0) {
                // output data of each row
                while ($row = $this->result->fetch_assoc()) {
                    $resultSet[] = $row;
                }
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function getListMoth()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (isset($_GET['TANT_CD'])) {
                $TANT_CD = $_GET['TANT_CD'];
                $sql = ' SELECT DATE_FORMAT(JISEKI_YMD , "%Y/%m") AS FORMATED_DATE,
                TANT_CD 
                FROM T_JISEKISYUKEI 
                WHERE TANT_CD="' . $TANT_CD . '"
                AND DEL_FLG=0
                GROUP BY FORMATED_DATE';

                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
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
