<?php
include('../../System/systemConfig.php');
include('../../Validate/validate.php');

class Order
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

    /* =========================== 部材発注一覧 */
    function getPartOrderList()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (isset($_GET['SYOZOKU_CD']) && $_GET['SYOZOKU_CD'] != "") {
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];

                $sql = ' SELECT T_BUZAIHACYU.BUZAI_HACYU_ID,
                T_BUZAIHACYU.HACYU_YMD,
                T_BUZAIHACYU.TANT_NAME,
                T_BUZAIHACYU.HACYU_OKFLG,
                T_BUZAIHACYUMSAI.JISYA_CD,
                T_BUZAIHACYUMSAI.SYOHIN_NAME,
                M_KBN.KBN_NAME,
                M_KBN.KBNMSAI_CD,
                M_KBN.KBNMSAI_NAME,
                T_BUZAIHACYU.SYOZOKU_CD,
                T_BUZAIHACYUMSAI.BUZAI_HACYU_ID,
                T_BUZAIHACYUMSAI.BUZAI_HACYUMSAI_ID
                FROM T_BUZAIHACYU 
                LEFT JOIN T_BUZAIHACYUMSAI ON T_BUZAIHACYU.BUZAI_HACYU_ID=T_BUZAIHACYUMSAI.BUZAI_HACYU_ID
                LEFT JOIN M_KBN ON T_BUZAIHACYU.HACYU_OKFLG = M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="08"
                WHERE T_BUZAIHACYU.SYOZOKU_CD="' . $SYOZOKU_CD . '"                
                AND T_BUZAIHACYUMSAI.BUZAI_HACYUMSAI_ID=(SELECT MIN(BUZAI_HACYUMSAI_ID) FROM T_BUZAIHACYUMSAI)         
                AND T_BUZAIHACYU.DEL_FLG=0
                ORDER BY T_BUZAIHACYU.HACYU_YMD DESC';
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
                $errors['msg'][] = 'Missing parameter SYOZOKU_CD';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function getPullDownStatus()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            $sql = ' SELECT KBNMSAI_NAME,
                KBNMSAI_CD, 
                KBN_CD
                FROM M_KBN 
                WHERE KBN_CD="08"
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

    /* =========================== 部材発注リスト */
    function getMaterialOrderingList()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if ((isset($_GET['SYOZOKU_CD']) && $_GET['SYOZOKU_CD'] != "") &&
                (isset($_GET['JISYA_CD']) && $_GET['JISYA_CD'] != "")
            ) {
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];
                $JISYA_CD = $_GET['JISYA_CD'];

                $sql = ' SELECT T_BUZAIHACYUMSAI_SAVE.MAKER_NAME,
                T_BUZAIHACYUMSAI_SAVE.BUNRUI,
                T_BUZAIHACYUMSAI_SAVE.LOT,
                T_BUZAIHACYUMSAI_SAVE.HACYU_TANKA,
                T_BUZAIHACYUMSAI_SAVE.SURYO,
                T_BUZAIHACYUMSAI_SAVE.TANI_CD,
                T_BUZAIHACYUMSAI_SAVE.KINGAK,
                T_BUZAIHACYUMSAI_SAVE.HINBAN,
                T_BUZAIHACYUMSAI_SAVE.SYOHIN_NAME,
                T_BUZAIHACYUMSAI_SAVE.JISYA_CD,
                T_BUZAIHACYUMSAI_SAVE.BUZAI_HACYUMSAI_ID,
                M_TANT.SYOZOKU_CD
                FROM T_BUZAIHACYUMSAI_SAVE 
                LEFT JOIN M_TANT ON T_BUZAIHACYUMSAI_SAVE.SAVE_TANT_CD=M_TANT.TANT_CD                
                WHERE M_TANT.SYOZOKU_CD="' . $SYOZOKU_CD . '"  
                AND T_BUZAIHACYUMSAI_SAVE.JISYA_CD="' . $JISYA_CD . '"                              
                AND T_BUZAIHACYUMSAI_SAVE.DEL_FLG=0';
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
                $errors['msg'][] = 'Missing parameter SYOZOKU_CD or JISYA_CD';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function getCheckList()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (isset($_GET['BUZAI_HACYU_ID']) && $_GET['BUZAI_HACYU_ID'] != "") {
                $BUZAI_HACYU_ID = $_GET['BUZAI_HACYU_ID'];

                $sql = ' SELECT T_BUZAIHACYUMSAI.MAKER_NAME,
                T_BUZAIHACYUMSAI.BUNRUI,
                T_BUZAIHACYUMSAI.JISYA_CD,
                T_BUZAIHACYUMSAI.SYOHIN_NAME,
                T_BUZAIHACYUMSAI.LOT,
                T_BUZAIHACYUMSAI.HACYU_TANKA,
                T_BUZAIHACYUMSAI.SURYO,
                T_BUZAIHACYUMSAI.TANI_CD,
                T_BUZAIHACYUMSAI.KINGAK,
                T_BUZAIHACYUMSAI.HINBAN,
                T_BUZAIHACYUMSAI.BUZAI_HACYU_ID,
                T_BUZAIHACYUMSAI.BUZAI_HACYUMSAI_ID            
                FROM T_BUZAIHACYU 
                LEFT JOIN T_BUZAIHACYUMSAI ON T_BUZAIHACYUMSAI.BUZAI_HACYU_ID=T_BUZAIHACYU.BUZAI_HACYU_ID                
                WHERE T_BUZAIHACYU.BUZAI_HACYU_ID="' . $BUZAI_HACYU_ID . '"                              
                AND T_BUZAIHACYU.DEL_FLG=0
                AND T_BUZAIHACYUMSAI.DEL_FLG=0';
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
                $errors['msg'][] = 'Missing parameter BUZAI_HACYU_ID';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function getQR()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            $sql = ' SELECT MAKER_NAME,
                MAKER_CD,
                BUZAI_BUNRUI,
                HINBAN,
                SYOHIN_NAME,
                BUZAI_KANRI_NO,            
                SIIRE_NAME,
                SIIRE_TANKA,
                LOT,
                TANI,
                SORYO,
                BIKO
                FROM M_BUZAI                               
                WHERE DEL_FLG=0';
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

    function postAddMaterialOrdering()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $errors_validate = [];

            $json_string  = file_get_contents('php://input');
            $json_request = json_decode($json_string, true);
            $data = (array)$json_request;

            $errors_validate = $this->validateAddMaterialOrdering($data);

            if (empty($errors_validate)) {
                $query_max_buzai_hacyu_id = 'SELECT max(BUZAI_HACYU_ID) as BUZAI_HACYU_ID_MAX
                    FROM T_BUZAIHACYU';
                $rs_max = $this->dbConnect->query($query_max_buzai_hacyu_id);
                $num = 0;
                if ($rs_max->num_rows > 0) {
                    // output data of each row
                    while ($row = $rs_max->fetch_assoc()) {
                        $num = (int)$row['BUZAI_HACYU_ID_MAX'] + 1;
                    }
                }

                $data_buzaihacyu = $data['T_BUZAIHACYU'];
                $BUZAI_HACYU_ID = sprintf('%010d', $num);
                $PRESENT_DATE = date('Y-m-d');
                $PRESENT_DATETIME = date('Y-m-d H:i:s');
                $HACYU_OKFLG = "01";
                $SYONIN_FLG = 1;
                $sql = 'INSERT INTO T_BUZAIHACYU 
                (BUZAI_HACYU_ID,HACYU_YMD,HACYU_OKFLG,
                TANT_NAME,SYOZOKU_CD,HACNG_RIYU,SYONIN_FLG,RENKEI_YMD,
                DEL_FLG,ADD_PGID,ADD_TANTCD,ADD_YMD,
                UPD_PGID,UPD_TANTCD,UPD_YMD)
                VALUES
                ("' . $BUZAI_HACYU_ID . '" , "' . $PRESENT_DATE . '" , "' . $HACYU_OKFLG . '" , 
                "' . $data_buzaihacyu['LOGIN_NAME'] . '" , "' . $data_buzaihacyu['SYOZOKU_CD'] . '" , "NULL" , ' . $SYONIN_FLG . ' , "' . $data_buzaihacyu['RENKEI_YMD'] . '",
                0,"HAND","' . $data_buzaihacyu['LOGIN_ID'] . '" , "' . $PRESENT_DATETIME . '",
                "HAND","' . $data_buzaihacyu['LOGIN_ID'] . '" , "' . $PRESENT_DATETIME . '")';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }

                $data_buzaihacyumsai = $data['T_BUZAIHACYUMSAI'];
                $lenght = count($data_buzaihacyumsai);

                for ($i = 0; $i < $lenght; $i++) {
                    $sql = 'INSERT INTO T_BUZAIHACYUMSAI 
                    (BUZAI_HACYU_ID , BUZAI_HACYUMSAI_ID , 
                    MAKER_CD , MAKER_NAME ,
                    BUNRUI , JISYA_CD , HINBAN , 
                    SYOHIN_NAME , LOT , HACYU_TANKA , 
                    SURYO , TANI_CD , KINGAK,
                    DEL_FLG,ADD_PGID,ADD_TANTCD,ADD_YMD,
                    UPD_PGID,UPD_TANTCD,UPD_YMD)
                    VALUES
                    ("' . $BUZAI_HACYU_ID . '" , ' . (1 + $i) . ', 
                    "' . $data_buzaihacyumsai[$i]['MAKER_CD'] . '" , "' . $data_buzaihacyumsai[$i]['MAKER_NAME'] . '" , 
                    "NULL" , "' . $data_buzaihacyumsai[$i]['JISYA_CD'] . '" , "' . $data_buzaihacyumsai[$i]['HINBAN'] . '" , 
                    "' . $data_buzaihacyumsai[$i]['SYOHIN_NAME'] . '", "' . $data_buzaihacyumsai[$i]['LOT'] . '" , "' . $data_buzaihacyumsai[$i]['HACYU_TANKA'] . '" , 
                    "' . $data_buzaihacyumsai[$i]['SURYO'] . '" , "' . $data_buzaihacyumsai[$i]['TANI_CD'] . '" , "' . $data_buzaihacyumsai[$i]['KINGAK'] . '",
                    0,"HAND","' . $data_buzaihacyu['LOGIN_ID'] . '" , "' . $PRESENT_DATETIME . '",
                    "HAND","' . $data_buzaihacyu['LOGIN_ID'] . '" , "' . $PRESENT_DATETIME . '")';

                    $this->result = $this->dbConnect->query($sql);

                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                    }
                }
            } else {
                $errors['msg'][] = $errors_validate;
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postEditMaterialOrdering()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $errors_validate = [];

            $json_string  = file_get_contents('php://input');
            $json_request = json_decode($json_string, true);
            $data = (array)$json_request;

            $errors_validate = $this->validateAddMaterialOrdering($data);

            if (empty($errors_validate)) {
                $query_max_buzai_hacyu_id = 'SELECT max(BUZAI_HACYU_ID) as BUZAI_HACYU_ID_MAX
                FROM T_BUZAIHACYU';
                $rs_max = $this->dbConnect->query($query_max_buzai_hacyu_id);
                $num = 0;
                if ($rs_max->num_rows > 0) {
                    // output data of each row
                    while ($row = $rs_max->fetch_assoc()) {
                        $num = (int)$row['BUZAI_HACYU_ID_MAX'] + 1;
                    }
                }

                $data_buzaihacyumsai_save = $data['T_BUZAIHACYU'];
                if (!empty($data_buzaihacyumsai_save['BUZAI_HACYUMSAI_ID'])); {
                    $list_buzai_hacyumsai_id = $data_buzaihacyumsai_save['BUZAI_HACYUMSAI_ID'];
                    foreach ($list_buzai_hacyumsai_id as $key => $value) {
                        $sql = 'UPDATE T_BUZAIHACYUMSAI_SAVE SET
                        DEL_FLG=1 WHERE SAVE_TANT_CD="' . $data_buzaihacyumsai_save['LOGIN_ID'] . '"
                        AND BUZAI_HACYUMSAI_ID=' . $value . '';
                        $this->result = $this->dbConnect->query($sql);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                        }
                    }
                }

                $BUZAI_HACYU_ID = sprintf('%010d', $num);

                $data_buzaihacyu = $data['T_BUZAIHACYU'];
                $BUZAI_HACYU_ID = sprintf('%010d', $num);
                $PRESENT_DATE = date('Y-m-d');
                $PRESENT_DATETIME = date('Y-m-d H:i:s');
                $HACYU_OKFLG = "01";
                $SYONIN_FLG = 1;
                $sql = 'INSERT INTO T_BUZAIHACYU 
                (BUZAI_HACYU_ID,HACYU_YMD,HACYU_OKFLG,
                TANT_NAME,SYOZOKU_CD,HACNG_RIYU,SYONIN_FLG,RENKEI_YMD,
                DEL_FLG,ADD_PGID,ADD_TANTCD,ADD_YMD,
                UPD_PGID,UPD_TANTCD,UPD_YMD)
                VALUES
                ("' . $BUZAI_HACYU_ID . '" , "' . $PRESENT_DATE . '" , "' . $HACYU_OKFLG . '" , 
                "' . $data_buzaihacyu['LOGIN_NAME'] . '" , "' . $data_buzaihacyu['SYOZOKU_CD'] . '" , "NULL" , ' . $SYONIN_FLG . ' , "' . $data_buzaihacyu['RENKEI_YMD'] . '",
                0,"HAND","' . $data_buzaihacyu['LOGIN_ID'] . '" , "' . $PRESENT_DATETIME . '",
                "HAND","' . $data_buzaihacyu['LOGIN_ID'] . '" , "' . $PRESENT_DATETIME . '")';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }

                $data_buzaihacyumsai = $data['T_BUZAIHACYUMSAI'];
                $lenght = count($data_buzaihacyumsai);

                for ($i = 0; $i < $lenght; $i++) {
                    $sql = 'INSERT INTO T_BUZAIHACYUMSAI 
                    (BUZAI_HACYU_ID , BUZAI_HACYUMSAI_ID , 
                    MAKER_CD , MAKER_NAME ,
                    BUNRUI , JISYA_CD , HINBAN , 
                    SYOHIN_NAME , LOT , HACYU_TANKA , 
                    SURYO , TANI_CD , KINGAK,
                    DEL_FLG,ADD_PGID,ADD_TANTCD,ADD_YMD,
                    UPD_PGID,UPD_TANTCD,UPD_YMD)
                    VALUES
                    ("' . $BUZAI_HACYU_ID . '" , ' . (1 + $i) . ', 
                    "' . $data_buzaihacyumsai[$i]['MAKER_CD'] . '" , "' . $data_buzaihacyumsai[$i]['MAKER_NAME'] . '" , 
                    "NULL" , "' . $data_buzaihacyumsai[$i]['JISYA_CD'] . '" , "' . $data_buzaihacyumsai[$i]['HINBAN'] . '" , 
                    "' . $data_buzaihacyumsai[$i]['SYOHIN_NAME'] . '", "' . $data_buzaihacyumsai[$i]['LOT'] . '" , "' . $data_buzaihacyumsai[$i]['HACYU_TANKA'] . '" , 
                    "' . $data_buzaihacyumsai[$i]['SURYO'] . '" , "' . $data_buzaihacyumsai[$i]['TANI_CD'] . '" , "' . $data_buzaihacyumsai[$i]['KINGAK'] . '",
                    0,"HAND","' . $data_buzaihacyu['LOGIN_ID'] . '" , "' . $PRESENT_DATETIME . '",
                    "HAND","' . $data_buzaihacyu['LOGIN_ID'] . '" , "' . $PRESENT_DATETIME . '")';

                    $this->result = $this->dbConnect->query($sql);

                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                    }
                }
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postClearSaved()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $errors_validate = [];

            $json_string  = file_get_contents('php://input');
            $json_request = json_decode($json_string, true);
            $data = (array)$json_request;

            $errors_validate = $this->validateAddMaterialOrdering($data);

            if (empty($errors_validate)) {
                $data_buzaihacyumsai_save = $data['T_BUZAIHACYU'];
                if (!empty($data_buzaihacyumsai_save['BUZAI_HACYUMSAI_ID'])); {
                    $list_buzai_hacyumsai_id = $data_buzaihacyumsai_save['BUZAI_HACYUMSAI_ID'];
                    foreach ($list_buzai_hacyumsai_id as $key => $value) {
                        $sql = 'UPDATE T_BUZAIHACYUMSAI_SAVE SET
                        DEL_FLG=1 WHERE SAVE_TANT_CD="' . $data_buzaihacyumsai_save['LOGIN_ID'] . '"
                        AND BUZAI_HACYUMSAI_ID=' . $value . '';
                        $this->result = $this->dbConnect->query($sql);

                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                        }
                    }
                }
            } else {
                $errors['msg'][] = $errors_validate;
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function validateAddMaterialOrdering($array)
    {
        $errors = [];
        if (isset($array['T_BUZAIHACYUMSAI']) && !empty($array['T_BUZAIHACYUMSAI'])) {
            foreach ($array['T_BUZAIHACYUMSAI'] as $key => $values) {
                if (!isset($values['MAKER_CD']) || $values['MAKER_CD'] == "") {
                    $errors[] = 'MAKER_CD' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['MAKER_NAME']) || $values['MAKER_NAME'] == "") {
                    $errors[] = 'MAKER_NAME' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['JISYA_CD']) || $values['JISYA_CD'] == "") {
                    $errors[] = 'JISYA_CD' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['HINBAN']) || $values['HINBAN'] == "") {
                    $errors[] = 'HINBAN' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['SYOHIN_NAME']) || $values['SYOHIN_NAME'] == "") {
                    $errors[] = 'SYOHIN_NAME' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['LOT']) || $values['LOT'] == "") {
                    $errors[] = 'LOT' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['HACYU_TANKA']) || $values['HACYU_TANKA'] == "") {
                    $errors[] = 'HACYU_TANKA' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['SURYO']) || $values['SURYO'] == "") {
                    $errors[] = 'SURYO' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['TANI_CD']) || $values['TANI_CD'] == "") {
                    $errors[] = 'TANI_CD' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['KINGAK']) || $values['KINGAK'] == "") {
                    $errors[] = 'KINGAK' . ' ' . '[' . $key . ']' . ' is required ';
                }
            }
        }

        if (isset($array['T_BUZAIHACYU']) && !empty($array['T_BUZAIHACYU'])) {
            if (!isset($array['T_BUZAIHACYU']['LOGIN_ID']) || $array['T_BUZAIHACYU']['LOGIN_ID'] == "") {
                $errors[] = 'LOGIN_ID is required ';
            }
            if (!isset($array['T_BUZAIHACYU']['LOGIN_NAME']) || $array['T_BUZAIHACYU']['LOGIN_NAME'] == "") {
                $errors[] = 'LOGIN_NAME is required ';
            }
            if (!isset($array['T_BUZAIHACYU']['SYOZOKU_CD']) || $array['T_BUZAIHACYU']['SYOZOKU_CD'] == "") {
                $errors[] = 'SYOZOKU_CD is required ';
            }
            if (!isset($array['T_BUZAIHACYU']['RENKEI_YMD']) || $array['T_BUZAIHACYU']['RENKEI_YMD'] == "") {
                $errors[] = 'RENKEI_YMD is required ';
            }
        }

        return $errors;
    }

    /* =========================== 部材リスト */
    function getPartList()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (isset($_GET['SYOZOKU_CD']) && $_GET['SYOZOKU_CD'] != "") {
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];

                $sql = ' SELECT T_BUZAIHACYUMSAI_SAVE.MAKER_NAME,
                T_BUZAIHACYUMSAI_SAVE.BUNRUI,
                T_BUZAIHACYUMSAI_SAVE.LOT,
                T_BUZAIHACYUMSAI_SAVE.HACYU_TANKA,
                T_BUZAIHACYUMSAI_SAVE.SURYO,
                T_BUZAIHACYUMSAI_SAVE.TANI_CD,
                T_BUZAIHACYUMSAI_SAVE.KINGAK,
                T_BUZAIHACYUMSAI_SAVE.HINBAN,
                T_BUZAIHACYUMSAI_SAVE.SYOHIN_NAME,
                T_BUZAIHACYUMSAI_SAVE.JISYA_CD,
                T_BUZAIHACYUMSAI_SAVE.BUZAI_HACYUMSAI_ID,
                M_TANT.SYOZOKU_CD
                FROM T_BUZAIHACYUMSAI_SAVE 
                LEFT JOIN M_TANT ON T_BUZAIHACYUMSAI_SAVE.SAVE_TANT_CD=M_TANT.TANT_CD                
                WHERE M_TANT.SYOZOKU_CD="' . $SYOZOKU_CD . '"                                          
                AND T_BUZAIHACYUMSAI_SAVE.DEL_FLG=0';
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
                $errors['msg'][] = 'Missing parameter SYOZOKU_CD';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function getPullDownCategory()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            $sql = ' SELECT KBNMSAI_NAME,
                KBNMSAI_CD, 
                KBN_CD
                FROM M_KBN 
                WHERE KBN_CD="14"
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

    function getPartList2()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (
                isset($_GET['SYOZOKU_CD']) && isset($_GET['BUZAI_BUNRUI']) && isset($_GET['MAKER_NAME']) &&
                isset($_GET['HINBAN']) && isset($_GET['SYOHIN_NAME']) && isset($_GET['JISYA_CD'])
            ) {
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];
                $BUZAI_BUNRUI = $_GET['BUZAI_BUNRUI'];
                $MAKER_NAME = $_GET['MAKER_NAME'];
                $HINBAN = $_GET['HINBAN'];
                $SYOHIN_NAME = $_GET['SYOHIN_NAME'];
                $JISYA_CD = $_GET['JISYA_CD'];

                $sql = ' SELECT M_BUZAI.BUZAI_BUNRUI,
                M_BUZAI.HINBAN AS HINBAN_BUZAI,
                M_BUZAI.SYOHIN_NAME AS SYOHIN_NAME_BUZAI,
                M_BUZAI.BUZAI_KANRI_NO,
                M_BUZAI.MAKER_CD AS MAKER_CD_BUZAI,
                M_BUZAI.MAKER_NAME AS MAKER_NAME_BUZAI,
                M_BUZAI.SIIRE_NAME,
                M_BUZAI.LOT,
                M_BUZAI.TANI,
                M_BUZAI.SORYO,
                M_BUZAI.BIKO,
                M_BUZAI.HAIBAN_FLG,
                T_ZAIKO.ZAIKO_ID,
                T_ZAIKO.BASYO_GYOSYA_SYBET_CD,
                T_ZAIKO.ZAIKO_SYBET_CD,
                T_ZAIKO.SOKO_CD,
                T_ZAIKO.CTGORY_NAME,
                T_ZAIKO.MAKER_CD AS MAKER_CD_ZAIKO, 
                T_ZAIKO.MAKER_NAME AS MAKER_NAME_ZAIKO,
                T_ZAIKO.JISYA_CD,
                T_ZAIKO.HINBAN AS HINBAN_ZAIKO,
                T_ZAIKO.JAN_CD,
                T_ZAIKO.SYOHIN_NAME AS SYOHIN_NAME_ZAIKO,
                T_ZAIKO.GENKA,
                T_ZAIKO.JISSU
                FROM M_BUZAI 
                LEFT JOIN T_ZAIKO ON M_BUZAI.HINBAN=T_ZAIKO.HINBAN                
                WHERE T_ZAIKO.SOKO_CD="' . $SYOZOKU_CD . '" 
                OR M_BUZAI.BUZAI_BUNRUI="' . $BUZAI_BUNRUI . '"
                OR M_BUZAI.MAKER_NAME="' . $MAKER_NAME . '"
                OR M_BUZAI.HINBAN="' . $HINBAN . '"
                OR M_BUZAI.SYOHIN_NAME="' . $SYOHIN_NAME . '"
                OR T_ZAIKO.JISYA_CD="' . $JISYA_CD . '"
                AND T_ZAIKO.DEL_FLG=0';
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
                $errors['msg'][] = 'Missing parameter SYOZOKU_CD or BUZAI_BUNRUI or MAKER_NAME or HINBAN or SYOHIN_NAME or JISYA_CD';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function getPartList3()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            $sql = ' SELECT BUZAI_BUNRUI,
                HINBAN AS HINBAN_BUZAI,
                SYOHIN_NAME AS SYOHIN_NAME_BUZAI,
                BUZAI_KANRI_NO,
                MAKER_CD AS MAKER_CD_BUZAI,
                MAKER_NAME AS MAKER_NAME_BUZAI,
                SIIRE_NAME,
                LOT,
                TANI,
                SORYO,
                BIKO,
                HAIBAN_FLG             
                FROM M_BUZAI                            
                WHERE DEL_FLG=0';
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

    /* =========================== 棚卸リスト */
    function checkInventorySaved()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (isset($_GET['LOGIN_ID']) && $_GET['LOGIN_ID'] != "") {
                $LOGIN_ID = $_GET['LOGIN_ID'];
                $sql = ' SELECT * FROM T_TANAMSAI_SAVE
                WHERE SAVE_TANT_CD="' . $LOGIN_ID . '" 
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
                $errors['msg'][] = 'Missing parameter LOGIN_ID';
            }


            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function getInventoryListWithoutSaved()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (isset($_GET['SYOZOKU_CD'])) {
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];
                $YM = date('Ym', strtotime("-1 months"));
                $PRESENT_DATE = date('Y-m-d');

                $sql = ' SELECT T_TANA.TANA_ID,
                T_TANA.TANA_YM,
                T_TANA.TANA_YMD,
                T_TANA.MONTHLY_KEIJO_YM,
                T_TANA.BASYO_GYOSYA_SYBET_CD,
                T_TANA.SOKO_CD,
                T_TANA.SYOZOKU_CD,
                T_TANAMSAI.TANAMSAI_ID,
                T_TANAMSAI.BUZAI_KANRI_NO,            
                T_TANAMSAI.HINBAN,
                T_TANAMSAI.SYOHIN_NAME,              
                T_TANAMSAI.JITUZAIKO_SU AS SENGETU_JITUZAIKO_SU, 
                M_BUZAI.BUZAI_BUNRUI,                 
                M_BUZAI.MAKER_NAME AS BUZAI_MAKER_NAME,
                M_BUZAI.SIIRE_NAME AS BUZAI_SIIRE_NAME,
                M_BUZAI.SIIRE_TANKA AS BUZAI_SIIRE_TANKA,             
                M_BUZAI.SORYO AS BUZAI_SORYO,                
                T_SYUKKOJISEKI.SURYO AS SYUKKOJISEKI_SURYO,               
                T_BUZAIHACYU.BUZAI_HACYU_ID,                     
                T_BUZAIHACYUMSAI.SURYO AS BUZAIHACYUMSAI_SURYO            
                FROM T_TANA 
                LEFT JOIN T_TANAMSAI ON T_TANA.TANA_ID=T_TANAMSAI.TANA_ID
                LEFT JOIN M_BUZAI ON T_TANAMSAI.BUZAI_KANRI_NO = M_BUZAI.BUZAI_KANRI_NO
                LEFT JOIN T_SYUKKOJISEKI ON T_TANA.SOKO_CD = T_SYUKKOJISEKI.SOKO_CD AND T_SYUKKOJISEKI.SYUKKO_DATE <= T_TANA.TANA_YMD AND T_TANAMSAI.JISYA_CD=T_SYUKKOJISEKI.JISYA_CD
                LEFT JOIN T_BUZAIHACYU ON T_TANA.SYOZOKU_CD = T_BUZAIHACYU.SYOZOKU_CD AND T_BUZAIHACYU.HACYU_YMD <= T_TANA.TANA_YMD
                LEFT JOIN T_BUZAIHACYUMSAI ON T_BUZAIHACYU.BUZAI_HACYU_ID = T_BUZAIHACYUMSAI.BUZAI_HACYU_ID AND T_BUZAIHACYUMSAI.JISYA_CD=T_TANAMSAI.JISYA_CD
                WHERE T_TANA.SYOZOKU_CD="' . $SYOZOKU_CD . '"
                AND T_TANA.TANA_YM= "' . $YM . '"         
                AND T_TANA.TANA_YMD >= "' . $PRESENT_DATE . '"                
                AND T_TANA.DEL_FLG=0';
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
                $errors['msg'][] = 'Missing parameter SYOZOKU_CD';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function getQRInventoryList()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (isset($_GET['SYOZOKU_CD'])) {
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];
                $sql = ' SELECT MAKER_NAME,
                MAKER_CD,
                BUZAI_BUNRUI,
                HINBAN,
                SYOHIN_NAME,
                BUZAI_KANRI_NO, 
                SIIRE_NAME,
                SIIRE_TANKA,
                LOT,
                TANI,
                SORYO,
                BIKO
                FROM M_BUZAI
                WHERE DEL_FLG=0';
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
                $errors['msg'][] = 'Missing parameter SYOZOKU_CD';
            }


            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function getInventoryListWithSaved()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if ((isset($_GET['SYOZOKU_CD']) && $_GET['SYOZOKU_CD'] != "") && (isset($_GET['LOGIN_ID']) && $_GET['LOGIN_ID'] != "")) {
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];
                $LOGIN_ID = $_GET['LOGIN_ID'];
                $YM = date('Ym', strtotime("-1 months"));
                $PRESENT_DATE = date('Y-m-d');

                $sql = ' SELECT T_TANAMSAI_SAVE.BUZAI_KANRI_NO, 
                T_TANAMSAI_SAVE.HINBAN, 
                T_TANAMSAI_SAVE.SYOHIN_NAME, 
                T_TANAMSAI_SAVE.SENGETU_JITUZAIKO_SU, 
                T_TANAMSAI_SAVE.JITUZAIKO_SU, 
                M_BUZAI.BUZAI_BUNRUI, 
                M_BUZAI.MAKER_NAME, 
                M_BUZAI.SIIRE_TANKA, 
                T_SYUKKOJISEKI.SURYO AS SYUKKOJISEKI_SURYO, 
                T_BUZAIHACYUMSAI.SURYO AS BUZAIHACYUMSAI_SURYO
                FROM T_TANAMSAI_SAVE 
                LEFT JOIN T_TANA ON T_TANA.TANT_CD = T_TANAMSAI_SAVE.SAVE_TANT_CD AND T_TANA.SYOZOKU_CD="' . $SYOZOKU_CD . '" AND T_TANA.TANA_YM= "' . $YM . '" AND T_TANA.DEL_FLG=0 
                LEFT JOIN T_TANAMSAI ON T_TANA.TANA_ID=T_TANAMSAI.TANA_ID
                LEFT JOIN M_BUZAI ON T_TANAMSAI_SAVE.BUZAI_KANRI_NO = M_BUZAI.BUZAI_KANRI_NO
                LEFT JOIN T_SYUKKOJISEKI ON T_TANAMSAI_SAVE.JISYA_CD = T_SYUKKOJISEKI.JISYA_CD AND T_TANA.SOKO_CD = T_SYUKKOJISEKI.SOKO_CD AND T_SYUKKOJISEKI.SYUKKO_DATE <= T_TANA.TANA_YMD
                LEFT JOIN T_BUZAIHACYU ON T_TANA.SYOZOKU_CD = T_BUZAIHACYU.SYOZOKU_CD AND T_BUZAIHACYU.HACYU_YMD <= T_TANA.TANA_YMD
                LEFT JOIN T_BUZAIHACYUMSAI ON T_TANAMSAI.JISYA_CD = T_BUZAIHACYUMSAI.JISYA_CD
                WHERE T_TANAMSAI_SAVE.SAVE_TANT_CD="' . $LOGIN_ID . '"
                AND T_TANAMSAI_SAVE.DEL_FLG=0';
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
                $errors['msg'][] = 'Missing parameter SYOZOKU_CD';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postTanamsaiSaveDelete()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $validate = new Validate();

            $validated = $validate->validate($_POST, [
                'LOGIN_ID' => 'required'
            ]);

            if ($validated) {
                $sql = 'UPDATE T_TANAMSAI_SAVE SET DEL_FLG=1 
                    WHERE SAVE_TANT_CD="' . $_POST['LOGIN_ID'] . '"';
                $this->result = $this->dbConnect->query($sql);

                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }
            }

            if (empty($errors['msg'])) {
                $resultSet = [];
                $validated['status'] = 'success';
                $resultSet[] = $validated;
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function validatePostInventoryListForCreateNotExist($array)
    {
        $errors = [];
        if (isset($array['INVENTORY_DETAIL']) && !empty($array['INVENTORY_DETAIL'])) {
            foreach ($array['INVENTORY_DETAIL'] as $key => $values) {
                if (!isset($values['BUZAI_KANRI_NO']) || $values['BUZAI_KANRI_NO'] == "") {
                    $errors[] = 'BUZAI_KANRI_NO' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['JISYA_CD']) || $values['JISYA_CD'] == "") {
                    $errors[] = 'JISYA_CD' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['HINBAN']) || $values['HINBAN'] == "") {
                    $errors[] = 'HINBAN' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['SYOHIN_NAME']) || $values['SYOHIN_NAME'] == "") {
                    $errors[] = 'SYOHIN_NAME' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['JITUZAIKO_SU']) || $values['JITUZAIKO_SU'] == "") {
                    $errors[] = 'JITUZAIKO_SU' . ' ' . '[' . $key . ']' . ' is required ';
                }
            }
        }

        if (isset($array['USER_INFO']) && !empty($array['USER_INFO'])) {
            if (isset($array['USER_INFO']['LOGIN_ID']) && $array['USER_INFO']['LOGIN_ID'] == "") {
                $errors[] = 'LOGIN_ID is required ';
            }
            if (isset($array['USER_INFO']['SYOZOKU_CD']) && $array['USER_INFO']['SYOZOKU_CD'] == "") {
                $errors[] = 'SYOZOKU_CD is required ';
            }
        }
        return $errors;
    }

    function postInventoryListWithoutSaved()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $errors_validate = [];

            $json_string  = file_get_contents('php://input');
            $json_request = json_decode($json_string, true);
            $data = (array)$json_request;

            $errors_validate = $this->validatePostInventoryListForCreateNotExist($data);

            if (empty($errors_validate)) {
                $YM = date('Ym');
                $YM_ = date('Y-m');
                $ADD_PGID = "KOJ1040B";
                $UPD_PGID = "KOJ1040B";
                $PRESENT_DATE = date('Y-m-d');
                $PRESENT_DATETIME = date('Y-m-d H:i:s');
                $LOGIN_ID = $data['USER_INFO']['LOGIN_ID'];
                $SYOZOKU_CD = $data['USER_INFO']['SYOZOKU_CD'];

                $query_max = 'SELECT max(TANA_ID) as T_TANA_ID_MAX
                    FROM T_TANA';
                $rs_max = $this->dbConnect->query($query_max);
                $num = 0;
                if ($rs_max->num_rows > 0) {
                    while ($row = $rs_max->fetch_assoc()) {
                        $num = (int)$row['T_TANA_ID_MAX'] + 1;
                    }
                }

                $T_TANA_ID_MAX = sprintf('%010d', $num);

                $sql = 'INSERT INTO T_TANA 
                    (TANA_ID,TANA_YM,TANA_YMD, 
                    MONTHLY_KEIJO_YM, BASYO_GYOSYA_SYBET_CD, 
                    SOKO_CD, SYOZOKU_CD,
                    TANT_CD,RENKEI_YMD,
                    DEL_FLG,ADD_PGID,ADD_TANTCD,ADD_YMD,
                    UPD_PGID,UPD_TANTCD,UPD_YMD)
                    VALUES
                    ("' . $T_TANA_ID_MAX . '" , "' . $YM . '" ,"' . $PRESENT_DATE . '" , 
                    "' . $YM . '" , "03" , 
                    "' . $SYOZOKU_CD . '" , "' . $SYOZOKU_CD . '",
                    "' . $LOGIN_ID . '", "' . $PRESENT_DATE . '",
                    0,"' . $ADD_PGID . '","' . $LOGIN_ID . '","' . $PRESENT_DATETIME . '",
                    "' . $UPD_PGID . '","' . $LOGIN_ID . '","' . $PRESENT_DATETIME . '")';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }

                foreach ($data['INVENTORY_DETAIL'] as $item => $value) {
                    $sql = 'INSERT INTO T_TANAMSAI 
                    (TANA_ID,TANAMSAI_ID,
                    BUZAI_KANRI_NO, JISYA_CD, 
                    HINBAN, SYOHIN_NAME, 
                    JITUZAIKO_SU,DEL_FLG,
                    ADD_PGID,ADD_TANTCD,ADD_YMD,
                    UPD_PGID,UPD_TANTCD,UPD_YMD)
                    VALUES
                    ("' . $T_TANA_ID_MAX . '" , "' . ($item + 1) . '" , 
                    "' . $value['BUZAI_KANRI_NO'] . '" , "' . $value['JISYA_CD'] . '" , 
                    "' . $value['HINBAN'] . '" , "' . $value['SYOHIN_NAME'] . '" , 
                    "' . $value['JITUZAIKO_SU'] . '",0,
                    "' . $ADD_PGID . '","' . $LOGIN_ID . '","' . $PRESENT_DATETIME . '",
                    "' . $UPD_PGID . '","' . $LOGIN_ID . '","' . $PRESENT_DATETIME . '")';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                    }
                }
            } else {
                $errors['msg'][] = $errors_validate;
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postInventoryListWithSaved()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $errors_validate = [];

            $json_string  = file_get_contents('php://input');
            $json_request = json_decode($json_string, true);
            $data = (array)$json_request;

            $errors_validate = $this->validatePostInventoryListForCreateNotExist($data);

            if (empty($errors_validate)) {
                $YM = date('Ym');
                $YM_ = date('Y-m');
                $ADD_PGID = "KOJ1040B";
                $UPD_PGID = "KOJ1040B";
                $PRESENT_DATE = date('Y-m-d');
                $PRESENT_DATETIME = date('Y-m-d H:i:s');
                $LOGIN_ID = $data['LOGIN_ID'];
                $SYOZOKU_CD = $data['SYOZOKU_CD'];

                $query_max = 'SELECT max(TANA_ID) as T_TANA_ID_MAX
                    FROM T_TANA';
                $rs_max = $this->dbConnect->query($query_max);
                $num = 0;
                if ($rs_max->num_rows > 0) {
                    while ($row = $rs_max->fetch_assoc()) {
                        $num = (int)$row['T_TANA_ID_MAX'] + 1;
                    }
                }

                $T_TANA_ID_MAX = sprintf('%010d', $num);

                $sql = 'INSERT INTO T_TANA 
                    (TANA_ID,TANA_YM,TANA_YMD, 
                    MONTHLY_KEIJO_YM, BASYO_GYOSYA_SYBET_CD, 
                    SOKO_CD, SYOZOKU_CD,
                    TANT_CD,RENKEI_YMD,
                    DEL_FLG,ADD_PGID,ADD_TANTCD,ADD_YMD,
                    UPD_PGID,UPD_TANTCD,UPD_YMD)
                    VALUES
                    ("' . $T_TANA_ID_MAX . '" , "' . $YM . '" ,"' . $PRESENT_DATE . '" , 
                    "' . $YM . '" , "03" , 
                    "' . $SYOZOKU_CD . '" , "' . $SYOZOKU_CD . '",
                    "' . $LOGIN_ID . '", "' . $PRESENT_DATE . '",
                    0,"' . $ADD_PGID . '","' . $LOGIN_ID . '","' . $PRESENT_DATETIME . '",
                    "' . $UPD_PGID . '","' . $LOGIN_ID . '","' . $PRESENT_DATETIME . '")';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }

                foreach ($data['INVENTORY_DETAIL'] as $item => $value) {
                    $sql = 'INSERT INTO T_TANAMSAI 
                    (TANA_ID,TANAMSAI_ID,BUZAI_KANRI_NO, 
                    JISYA_CD, HINBAN, 
                    SYOHIN_NAME, JITUZAIKO_SU)
                    VALUES
                    ("' . $T_TANA_ID_MAX . '" , "' . ($item + 1) . '" , 
                    "' . $value['BUZAI_KANRI_NO'] . '" , "' . $value['JISYA_CD'] . '" , 
                    "' . $value['HINBAN'] . '" , "' . $value['SYOHIN_NAME'] . '" , 
                    "' . $value['JITUZAIKO_SU'] . '",0,
                    "' . $ADD_PGID . '","' . $LOGIN_ID . '","' . $PRESENT_DATETIME . '",
                    "' . $UPD_PGID . '","' . $LOGIN_ID . '","' . $PRESENT_DATETIME . '")';
                    $this->result = $this->dbConnect->query($sql);
                }
            } else {
                $errors['msg'][] = $errors_validate;
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    /* =========================== 部材リスト-2 */
    function getInventoryListMaterialListSearch()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (
                isset($_GET['BUZAI_BUNRUI']) && isset($_GET['MAKER_NAME']) && isset($_GET['HINBAN']) &&
                isset($_GET['SYOHIN_NAME']) && isset($_GET['SYOZOKU_CD'])
            ) {               
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];
                $YM = date('Ym' , strtotime("-1 months"));
                $FIRST_DATE_OF_MONTH = date('Y-m-01');
                $LAST_DATE_OF_MONTH = date('Y-m-t');

                $data = $_GET;
                $query = [];
                foreach($data as $key => $value) {
                    if($key == "BUZAI_BUNRUI" && $value != ""){
                        $query[] = 'M_BUZAI.BUZAI_BUNRUI LIKE "%' . $value . '%"';
                    } 
                    if ($key == "MAKER_NAME" && $value != "") {
                        $query[] = 'M_BUZAI.MAKER_NAME LIKE "%' . $value . '%"';
                    } 
                    if ($key == "HINBAN" && $value != "") {
                        $query[] = 'M_BUZAI.HINBAN LIKE "%' . $value . '%"';
                    } 
                    if ($key == "SYOHIN_NAME" && $value != "") {
                        $query[] = 'M_BUZAI.SYOHIN_NAME LIKE "%' . $value . '%"';
                    }                     
                }; 
                $search_query = "";             
                $search_query = implode(' OR ', $query);                
                if ($SYOZOKU_CD != "") {
                    $sql = ' SELECT  M_BUZAI.BUZAI_BUNRUI, 
                    M_BUZAI.HINBAN AS BUZAI_HINBAN, 
                    M_BUZAI.SYOHIN_NAME AS BUZAI_SYOHIN_NAME,
                    M_BUZAI.BUZAI_KANRI_NO, 
                    M_BUZAI.MAKER_CD AS BUZAI_MAKER_CD, 
                    M_BUZAI.MAKER_NAME AS BUZAI_MAKER_NAME, 
                    M_BUZAI.SIIRE_NAME, 
                    M_BUZAI.SIIRE_TANKA, 
                    M_BUZAI.LOT,
                    M_BUZAI.TANI, 
                    M_BUZAI.SORYO, 
                    M_BUZAI.BIKO,
                    T_ZAIKO.ZAIKO_ID,
                    T_ZAIKO.BASYO_GYOSYA_SYBET_CD,
                    T_ZAIKO.ZAIKO_SYBET_CD,
                    T_ZAIKO.SOKO_CD,
                    T_ZAIKO.CTGORY_CD,
                    T_ZAIKO.CTGORY_NAME,
                    T_ZAIKO.MAKER_CD AS ZAIKO_MAKER_CD,
                    T_ZAIKO.MAKER_NAME AS ZAIKO_MAKER_NAME,
                    T_ZAIKO.JISYA_CD,
                    T_ZAIKO.HINBAN AS ZAIKO_HINBAN,
                    T_ZAIKO.JAN_CD,
                    T_ZAIKO.SYOHIN_NAME AS ZAIKO_SYOHIN_NAME,
                    T_ZAIKO.GENKA,
                    T_ZAIKO.JISSU,
                    T_ZAIKO.HIKI_ZUMI_FLG
                    FROM M_BUZAI 
                    LEFT JOIN T_ZAIKO ON M_BUZAI.BUZAI_BUNRUI=T_ZAIKO.BASYO_GYOSYA_SYBET_CD AND T_ZAIKO.SOKO_CD="' . $SYOZOKU_CD . '"
                    LEFT JOIN T_SYUKKOJISEKI ON T_SYUKKOJISEKI.SOKO_CD=T_ZAIKO.SOKO_CD 
                        AND (T_SYUKKOJISEKI.SYUKKO_DATE BETWEEN "'.$FIRST_DATE_OF_MONTH.'" AND "'.$LAST_DATE_OF_MONTH.'")
                    LEFT JOIN T_BUZAIHACYU ON T_SYUKKOJISEKI.SOKO_CD=T_ZAIKO.SOKO_CD 
                        AND (T_BUZAIHACYU.HACYU_YMD BETWEEN "'.$FIRST_DATE_OF_MONTH.'" AND "'.$LAST_DATE_OF_MONTH.'")
                    LEFT JOIN T_BUZAIHACYUMSAI ON T_BUZAIHACYU.BUZAI_HACYU_ID=T_BUZAIHACYUMSAI.BUZAI_HACYU_ID 
                    WHERE ';
                    if($search_query != ""){
                        $sql .= $search_query . ' AND ';
                    }
                    $sql .= ' M_BUZAI.DEL_FLG=0';
                    // $sql .= ' M_BUZAI.DEL_FLG=0 AND NOT EXISTS (SELECT T_TANA.TANA_ID 
                    // FROM T_TANAMSAI
                    // INNER JOIN T_TANA ON T_TANA.TANA_ID = T_TANAMSAI.TANA_ID
                    // WHERE T_TANA.SOKO_CD = "'.$SYOZOKU_CD.'"
                    // AND T_TANA.TANA_YM = "'.$YM.'" GROUP BY T_TANA.TANA_ID) 
                    // AND NOT EXISTS (SELECT T_TANA.TANA_ID 
                    // FROM T_TANAMSAI_SAVE
                    // WHERE T_TANA.SOKO_CD="'.$SYOZOKU_CD.'")';                    
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
                    $errors['msg'][] = 'SYOZOKU_CD is required';
                }
            } else {
                $errors['msg'][] = 'Missing parameter BUZAI_BUNRUI or MAKER_NAME or HINBAN or SYOHIN_NAME';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    // function getInventoryListMaterialListSelect()
    // {
    //     $this->dbReference = new systemConfig();
    //     $this->dbConnect = $this->dbReference->connectDB();
    //     if ($this->dbConnect == NULL) {
    //         $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
    //     } else {
    //         $errors = [];
    //         $resultSet = array();

    //         if (
    //             isset($_GET['BUZAI_BUNRUI']) &&
    //             isset($_GET['MAKER_NAME']) &&
    //             isset($_GET['HINBAN']) &&
    //             isset($_GET['SYOHIN_NAME']) &&
    //             isset($_GET['SYOZOKU_CD'])
    //         ) {

    //             $sql = ' SELECT M_BUZAI.BUZAI_KANRI_NO, 
    //             M_BUZAI.BUZAI_KANRI_NO, 
    //             M_BUZAI.BUZAI_KANRI_NO, 
    //             M_BUZAI.BUZAI_KANRI_NO, 
    //             M_BUZAI.BUZAI_KANRI_NO, 
    //             M_BUZAI.BUZAI_KANRI_NO, 
    //             M_BUZAI.BUZAI_KANRI_NO
    //             FROM M_BUZAI 
    //             LEFT JOIN T_SYUKKOJISEKI ON M_BUZAI.HINBAN=T_SYUKKOJISEKI.JISYA_CD 
    //             LEFT JOIN T_BUZAIHACYU ON M_BUZAI.HINBAN=T_BUZAIHACYU.HINBAN 
    //             LEFT JOIN T_BUZAIHACYUMSAI ON M_BUZAI.HINBAN=T_BUZAIHACYU.JISYA_CD 
    //             WHERE M_BUZAI.BUZAI_HACYU_ID="' . $_GET['BUZAI_BUNRUI'] . '" 
    //                 OR M_BUZAI.MAKER_NAME="' . $_GET['MAKER_NAME'] . '" 
    //                 OR M_BUZAI.HINBAN="' . $_GET['HINBAN'] . '" 
    //                 OR M_BUZAI.SYOHIN_NAME="' . $_GET['SYOHIN_NAME'] . '" 
    //             ';
    //             $this->result = $this->dbConnect->query($sql);
    //             if (!empty($this->dbConnect->error)) {
    //                 $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
    //             }

    //             if ($this->result && $this->result->num_rows > 0) {
    //                 // output data of each row
    //                 while ($row = $this->result->fetch_assoc()) {
    //                     $resultSet[] = $row;
    //                 }
    //             }
    //         } else {
    //             $errors['msg'][] = 'Missing parameter BUZAI_HACYU_ID';
    //         }

    //         if (empty($errors['msg'])) {
    //             $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    //         } else {
    //             $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    //         }
    //     }
    // }

    function postInventoryListMaterialList()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {            
            $errors = [];
            $errors_validate = [];            
            $json_string  = file_get_contents('php://input');
            $json_request = json_decode($json_string, true);
            $data = (array)$json_request;
            
            $errors_validate = $this->validatePostInventoryListMaterialList($data);
            if (empty($errors_validate)) {                 
                $LOGIN_ID = $data['LOGIN_ID']; 
                $ADD_PGID = "KOJ1040B";
                $UPD_PGID = "KOJ1040B";
                $PRESENT_DATE = date('Y-m-d');
                $PRESENT_DATETIME = date('Y-m-d H:i:s');
                
                foreach ($data['MATERIAL_LIST_DETAIL'] as $item => $value) {
                    $sql = 'INSERT INTO T_TANAMSAI_SAVE 
                    (SAVE_TANT_CD,TANAMSAI_ID,
                    BUZAI_KANRI_NO, JISYA_CD, 
                    HINBAN, SYOHIN_NAME, 
                    SENGETU_JITUZAIKO_SU, JITUZAIKO_SU,DEL_FLG,
                    ADD_PGID,ADD_TANTCD,ADD_YMD,
                    UPD_PGID,UPD_TANTCD,UPD_YMD)
                        VALUES
                    ("' . $LOGIN_ID . '" , "' . ($item + 1) . '" , "' . $value['BUZAI_KANRI_NO'] . '" , 
                    "' . $value['JISYA_CD'] . '" , "' . $value['HINBAN'] . '" , "' . $value['SYOHIN_NAME'] . '" , 
                    "0", "' . $value['JITUZAIKO_SU'] . '",0,
                    "' . $ADD_PGID . '","' . $LOGIN_ID . '","' . $PRESENT_DATETIME . '",
                    "' . $UPD_PGID . '","' . $LOGIN_ID . '","' . $PRESENT_DATETIME . '")';
                    
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                    }
                }
            } else {
                $errors['msg'][] = $errors_validate;
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function validatePostInventoryListMaterialList($array) {
        $errors = [];
        if (isset($array['MATERIAL_LIST_DETAIL']) && !empty($array['MATERIAL_LIST_DETAIL'])) {
            foreach ($array['MATERIAL_LIST_DETAIL'] as $key => $values) {
                if (!isset($values['BUZAI_KANRI_NO']) || $values['BUZAI_KANRI_NO'] == "") {
                    $errors[] = 'BUZAI_KANRI_NO' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['JISYA_CD']) || $values['JISYA_CD'] == "") {
                    $errors[] = 'JISYA_CD' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['HINBAN']) || $values['HINBAN'] == "") {
                    $errors[] = 'HINBAN' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['SYOHIN_NAME']) || $values['SYOHIN_NAME'] == "") {
                    $errors[] = 'SYOHIN_NAME' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['JITUZAIKO_SU']) || $values['JITUZAIKO_SU'] == "") {
                    $errors[] = 'JITUZAIKO_SU' . ' ' . '[' . $key . ']' . ' is required ';
                }
            }
        }
        
        if ((isset($array['LOGIN_ID']) && $array['LOGIN_ID'] == "") || !isset($array['LOGIN_ID'])) {
            $errors[] = 'LOGIN_ID is required ';
        }

        return $errors;
    }

    /* =========================== 部材発注一覧(発注承認) */
    function getPartOrderListApprove()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (isset($_GET['SYOZOKU_CD']) && $_GET['SYOZOKU_CD'] != "") {
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];

                $sql = ' SELECT T_BUZAIHACYU.BUZAI_HACYU_ID,
                T_BUZAIHACYU.HACYU_YMD,
                T_BUZAIHACYU.TANT_NAME,
                T_BUZAIHACYU.HACYU_OKFLG,
                T_BUZAIHACYUMSAI.JISYA_CD,
                T_BUZAIHACYUMSAI.SYOHIN_NAME,
                M_KBN.KBN_NAME,
                M_KBN.KBNMSAI_CD,
                M_KBN.KBNMSAI_NAME,
                T_BUZAIHACYU.SYOZOKU_CD,
                T_BUZAIHACYUMSAI.BUZAI_HACYU_ID,
                T_BUZAIHACYUMSAI.BUZAI_HACYUMSAI_ID
                FROM T_BUZAIHACYU 
                LEFT JOIN T_BUZAIHACYUMSAI ON T_BUZAIHACYU.BUZAI_HACYU_ID=T_BUZAIHACYUMSAI.BUZAI_HACYU_ID
                LEFT JOIN M_KBN ON T_BUZAIHACYU.HACYU_OKFLG = M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="08"
                WHERE T_BUZAIHACYU.SYOZOKU_CD="' . $SYOZOKU_CD . '"
                AND T_BUZAIHACYUMSAI.BUZAI_HACYUMSAI_ID=(SELECT MIN(BUZAI_HACYUMSAI_ID) FROM T_BUZAIHACYUMSAI)                
                AND T_BUZAIHACYU.DEL_FLG=0
                ORDER BY T_BUZAIHACYU.HACYU_YMD DESC';
                // echo $sql; die;
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
                $errors['msg'][] = 'Missing parameter SYOZOKU_CD';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    /* =========================== 発注承認 */
    function getPurchaseOrderApproval()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $resultSet = array();

            if (isset($_GET['BUZAI_HACYU_ID']) && $_GET['BUZAI_HACYU_ID'] != "") {
                $BUZAI_HACYU_ID = $_GET['BUZAI_HACYU_ID'];

                $sql = ' SELECT T_BUZAIHACYU.HACNG_RIYU,
                T_BUZAIHACYU.HACYU_YMD,
                T_BUZAIHACYU.HACYU_OKFLG,
                T_BUZAIHACYU.TANT_NAME,
                T_BUZAIHACYU.SYOZOKU_CD,
                T_BUZAIHACYU.SYONIN_FLG,
                T_BUZAIHACYU.RENKEI_YMD,          
                T_BUZAIHACYUMSAI.BUZAI_HACYU_ID,
                T_BUZAIHACYUMSAI.BUZAI_HACYUMSAI_ID,
                T_BUZAIHACYUMSAI.MAKER_CD,
                T_BUZAIHACYUMSAI.MAKER_NAME,
                T_BUZAIHACYUMSAI.BUNRUI,
                T_BUZAIHACYUMSAI.HINBAN,
                T_BUZAIHACYUMSAI.SYOHIN_NAME,
                T_BUZAIHACYUMSAI.LOT,
                T_BUZAIHACYUMSAI.HACYU_TANKA,
                T_BUZAIHACYUMSAI.SURYO,
                T_BUZAIHACYUMSAI.TANI_CD,
                T_BUZAIHACYUMSAI.KINGAK          
                FROM T_BUZAIHACYU 
                LEFT JOIN T_BUZAIHACYUMSAI ON T_BUZAIHACYU.BUZAI_HACYU_ID=T_BUZAIHACYUMSAI.BUZAI_HACYU_ID               
                WHERE T_BUZAIHACYU.BUZAI_HACYU_ID="' . $BUZAI_HACYU_ID . '"         
                AND T_BUZAIHACYU.DEL_FLG=0
                AND T_BUZAIHACYUMSAI.DEL_FLG=0';
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
                $errors['msg'][] = 'Missing parameter BUZAI_HACYU_ID';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postPurchaseOrderApproval()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $PRESENT_DATE = date('Y-m-d');

            $json_string  = file_get_contents('php://input');
            $json_request = json_decode($json_string, true);
            $data = (array)$json_request;

            $list_buzai_hacyu_id = $data['BUZAI_HACYU_ID'];

            if (!empty($list_buzai_hacyu_id)) {
                foreach ($list_buzai_hacyu_id as $key => $value) {
                    $sql = 'UPDATE T_BUZAIHACYU SET
                        RENKEI_YMD="' . $PRESENT_DATE . '",
                        HACYU_OKFLG="03"
                        WHERE BUZAI_HACYU_ID="' . $value . '"
                        AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);

                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                    }
                }
            } 

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postPurchaseOrderReject()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $PRESENT_DATE = date('Y-m-d');

            $json_string  = file_get_contents('php://input');
            $json_request = json_decode($json_string, true);
            $data = (array)$json_request;

            $list_buzai_hacyu_id = $data['BUZAI_HACYU_ID'];

            if (!empty($list_buzai_hacyu_id)){
                foreach ($list_buzai_hacyu_id as $key => $value) {
                    $sql = 'UPDATE T_BUZAIHACYU SET
                        RENKEI_YMD="' . $PRESENT_DATE . '",
                        HACYU_OKFLG="02"
                        WHERE BUZAI_HACYU_ID="' . $value . '"
                        AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);

                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                    }
                }
            } 

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }
}
