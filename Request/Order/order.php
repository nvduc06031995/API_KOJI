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

            if (isset($_GET['SYOZOKU_CD'])) {
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
                T_BUZAIHACYUMSAI.BUZAI_HACYU_ID
                FROM T_BUZAIHACYU 
                LEFT JOIN T_BUZAIHACYUMSAI ON T_BUZAIHACYU.BUZAI_HACYU_ID=T_BUZAIHACYUMSAI.BUZAI_HACYU_ID
                LEFT JOIN M_KBN ON T_BUZAIHACYU.HACYU_OKFLG = M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="08"
                WHERE T_BUZAIHACYU.SYOZOKU_CD="' . $SYOZOKU_CD . '"
                AND T_BUZAIHACYUMSAI.BUZAI_HACYU_ID=(SELECT MIN(BUZAI_HACYU_ID) FROM T_BUZAIHACYUMSAI)                
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

            if (isset($_GET['SYOZOKU_CD']) && isset($_GET['JISYA_CD'])) {
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

            if (isset($_GET['BUZAI_HACYU_ID'])) {
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
        if (isset($array['T_BUZAIHACYUMSAI'])) {
            foreach ($array['T_BUZAIHACYUMSAI'] as $key => $values) {
                if (!isset($values['MAKER_CD']) || empty($values['MAKER_CD'])) {
                    $errors[] = 'MAKER_CD' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['MAKER_NAME']) || empty($values['MAKER_NAME'])) {
                    $errors[] = 'MAKER_NAME' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['JISYA_CD']) || empty($values['JISYA_CD'])) {
                    $errors[] = 'JISYA_CD' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['HINBAN']) || empty($values['HINBAN'])) {
                    $errors[] = 'HINBAN' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['SYOHIN_NAME']) || empty($values['SYOHIN_NAME'])) {
                    $errors[] = 'SYOHIN_NAME' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['LOT']) || empty($values['LOT'])) {
                    $errors[] = 'LOT' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['HACYU_TANKA']) || empty($values['HACYU_TANKA'])) {
                    $errors[] = 'HACYU_TANKA' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['SURYO']) || empty($values['SURYO'])) {
                    $errors[] = 'SURYO' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['TANI_CD']) || empty($values['TANI_CD'])) {
                    $errors[] = 'TANI_CD' . ' ' . '[' . $key . ']' . ' is required ';
                }
                if (!isset($values['KINGAK']) || empty($values['KINGAK'])) {
                    $errors[] = 'KINGAK' . ' ' . '[' . $key . ']' . ' is required ';
                }
            }
        }

        if (isset($array['T_BUZAIHACYU'])) {
            if (!isset($array['T_BUZAIHACYU']['LOGIN_ID']) || empty($array['T_BUZAIHACYU']['LOGIN_ID'])) {
                $errors[] = 'LOGIN_ID is required ';
            }
            if (!isset($array['T_BUZAIHACYU']['LOGIN_NAME']) || empty($array['T_BUZAIHACYU']['LOGIN_NAME'])) {
                $errors[] = 'LOGIN_NAME is required ';
            }
            if (!isset($array['T_BUZAIHACYU']['SYOZOKU_CD']) || empty($array['T_BUZAIHACYU']['SYOZOKU_CD'])) {
                $errors[] = 'SYOZOKU_CD is required ';
            }
            if (!isset($array['T_BUZAIHACYU']['RENKEI_YMD']) || empty($array['T_BUZAIHACYU']['RENKEI_YMD'])) {
                $errors[] = 'RENKEI_YMD is required ';
            }
        }

        return $errors;
    }

    /* 棚卸リスト */
    function getInventoryList()
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
                $getSubMonthYear = date('Y', strtotime("-1 months")) . date('m', strtotime("-1 months"));
                $getCurrentMonthYear = date('Y') . date('m');

                $sql = ' SELECT T_TANA.SOKO_CD,
                    FROM T_TANA 
                    LEFT JOIN T_TANAMSAI ON T_TANA.TANA_ID=T_TANAMSAI.TANA_ID
                    LEFT JOIN M_BUZAI ON T_TANAMSAI.BUZAI_KANRI_NO = M_BUZAI.BUZAI_KANRI_NO
                    LEFT JOIN T_SYUKKOJISEKI ON T_TANAMSAI.HINBAN = T_SYUKKOJISEKI.JISYA_CD
                    WHERE T_TANA.SYOZOKU_CD="' . $SYOZOKU_CD . '"
                    AND T_TANA.TANA_YM >= "' . $getSubMonthYear . '"
                    AND T_TANA.TANA_YM <= "' . $getCurrentMonthYear . '"
                    AND T_TANA.DEL_FLG=0
                    AND T_TANA.SOKO_CD <= "' . $SYOZOKU_CD . '"
                    ';
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
}
