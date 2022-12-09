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
                LEFT JOIN M_KBN ON T_BUZAIHACYU.HACYU_OKFLG = M_KBN.KBNMSAI_CD AND M_KBN.KNB_CD="08"
                WHERE T_BUZAIHACYU.SYOZOKU_CD="' . $SYOZOKU_CD . '"
                AND MIN(T_BUZAIHACYUMSAI.BUZAI_HACYU_ID)                
                AND DEL_FLG=0
                ORDER BY T_BUZAIHACYU.HACYU_YMD DESC';
                echo $sql; die;
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

            if (isset($_GET['SYOZOKU_CD'])) {
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
                M_TANT.SYOZOKU_CD
                FROM T_TANAMSAI_SAVE 
                LEFT JOIN M_TANT ON T_TANAMSAI_SAVE.SAVE_TANT_CD=M_TANT.TANT_CD
                LEFT JOIN T_BUZAIHACYUMSAI_SAVE ON T_TANAMSAI_SAVE.JISYA_CD=T_BUZAIHACYUMSAI_SAVE.JISYA_CD
                LEFT JOIN T_BUZAIHACYUMSAI ON T_TANAMSAI_SAVE.JISYA_CD=T_BUZAIHACYUMSAI.JISYA_CD
                WHERE M_TANT.SYOZOKU_CD="' . $SYOZOKU_CD . '"                              
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

    function postAddMaterialOrdering()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $validate = new Validate();        

            $validated = $validate->validate($_POST, [
                'SYOZOKU_CD' => 'required',
                'LOGIN_ID' => 'required',
                'RENKEI_YMD' => 'required',
                'MAKER_CD' => 'required',
                'MAKER_NAME' => 'required',
                'BUNRUI' => 'nullable',
                'JISYA_CD' => 'required',
                'HINBAN' => 'required',
                'SYOHIN_NAME' => 'required',
                'LOT' => 'required',
                'HACYU_TANKA' => 'required',
                'SURYO' => 'required',
                'TANI_CD' => 'required',
                'KINGAK' => 'required'
            ]);

            if ($validated) {
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

                $BUZAI_HACYU_ID = sprintf('%010d', $num);
                $HACYU_YMD = date('Y-m-d');
                $HACYU_OKFLG = "01";                
                $SYONIN_FLG = 1;

                $sql = 'INSERT INTO T_BUZAIHACYU 
                (BUZAI_HACYU_ID,HACYU_YMD,HACYU_OKFLG,
                TANT_NAME,SYOZOKU_CD,HACNG_RIYU,SYONIN_FLG,RENKEI_YMD)
                VALUES
                ("' . $BUZAI_HACYU_ID . '" , "' . $HACYU_YMD . '" , "' . $HACYU_OKFLG . '" , 
                "' . $validated['LOGIN_ID'] . '" , "' . $validated['SYOZOKU_CD'] . '" , "NULL" , ' . $SYONIN_FLG . ' , "' . $validated['RENKEI_YMD'] . '")';
                $this->result = $this->dbConnect->query($sql);

                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }                                

                $arr = [1,2];
                $lenght = count($arr);

                for($i = 1 ; $i <= $lenght ; $i++) {
                    $sql = 'INSERT INTO T_BUZAIHACYUMSAI 
                    (BUZAI_HACYU_ID , BUZAI_HACYUMSAI_ID , 
                    MAKER_CD , MAKER_NAME ,
                    BUNRUI , JISYA_CD , HINBAN , 
                    SYOHIN_NAME , LOT , HACYU_TANKA , 
                    SURYO , TANI_CD , KINGAK)
                    VALUES
                    ("' . $BUZAI_HACYU_ID . '" , '.$i.', 
                    "' . $validated['MAKER_CD'] . '" , "' . $validated['MAKER_NAME'] . '" , 
                    "' . $validated['BUNRUI'] . '" , "' . $validated['JISYA_CD'] . '" , "' . $validated['HINBAN'] . '" , 
                    "' . $validated['SYOHIN_NAME'] . '", "' . $validated['LOT'] . '" , "' . $validated['HACYU_TANKA'] . '" , 
                    "'.$validated['SURYO'].'" , ' . $validated['TANI_CD'] . ' , "' . $validated['KINGAK'] . '")';
                    $this->result = $this->dbConnect->query($sql);
                }                
                

                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }
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
            $validate = new Validate();        

            $validated = $validate->validate($_POST, [
                'SYOZOKU_CD' => 'required',
                'LOGIN_ID' => 'required',
                'RENKEI_YMD' => 'required',
                'MAKER_CD' => 'required',
                'MAKER_NAME' => 'required',
                'BUNRUI' => 'nullable',
                'JISYA_CD' => 'required',
                'HINBAN' => 'required',
                'SYOHIN_NAME' => 'required',
                'LOT' => 'required',
                'HACYU_TANKA' => 'required',
                'SURYO' => 'required',
                'TANI_CD' => 'required',
                'KINGAK' => 'required'
            ]);

            if ($validated) {
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

                $BUZAI_HACYU_ID = sprintf('%010d', $num);
                $HACYU_YMD = date('Y-m-d');
                $HACYU_OKFLG = "01";                
                $SYONIN_FLG = 1;

                $sql = 'INSERT INTO T_BUZAIHACYU 
                (BUZAI_HACYU_ID,HACYU_YMD,HACYU_OKFLG,
                TANT_NAME,SYOZOKU_CD,HACNG_RIYU,SYONIN_FLG,RENKEI_YMD)
                VALUES
                ("' . $BUZAI_HACYU_ID . '" , "' . $HACYU_YMD . '" , "' . $HACYU_OKFLG . '" , 
                "' . $validated['LOGIN_ID'] . '" , "' . $validated['SYOZOKU_CD'] . '" , "NULL" , ' . $SYONIN_FLG . ' , "' . $validated['RENKEI_YMD'] . '")';
                $this->result = $this->dbConnect->query($sql);

                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }                                

                $arr = [1,2];
                $lenght = count($arr);

                for($i = 1 ; $i <= $lenght ; $i++) {
                    $sql = 'INSERT INTO T_BUZAIHACYUMSAI 
                    (BUZAI_HACYU_ID , BUZAI_HACYUMSAI_ID , 
                    MAKER_CD , MAKER_NAME ,
                    BUNRUI , JISYA_CD , HINBAN , 
                    SYOHIN_NAME , LOT , HACYU_TANKA , 
                    SURYO , TANI_CD , KINGAK)
                    VALUES
                    ("' . $BUZAI_HACYU_ID . '" , '.$i.', 
                    "' . $validated['MAKER_CD'] . '" , "' . $validated['MAKER_NAME'] . '" , 
                    "' . $validated['BUNRUI'] . '" , "' . $validated['JISYA_CD'] . '" , "' . $validated['HINBAN'] . '" , 
                    "' . $validated['SYOHIN_NAME'] . '", "' . $validated['LOT'] . '" , "' . $validated['HACYU_TANKA'] . '" , 
                    "'.$validated['SURYO'].'" , ' . $validated['TANI_CD'] . ' , "' . $validated['KINGAK'] . '")';
                    $this->result = $this->dbConnect->query($sql);
                }                
                

                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    /* 棚卸リスト */
    function getInventoryListForCreate()
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
                $getCurrentDate = date('Y-m-d');

                $sql = ' SELECT T_TANAMSAI.BUZAI_KANRI_NO, T_TANAMSAI.HINBAN, T_TANAMSAI.SYOHIN_NAME, T_TANAMSAI.JITUZAIKO_SU, 
                                M_BUZAI.BUZAI_BUNRUI, M_BUZAI.MAKER_NAME, M_BUZAI.SIIRE_TANKA, 
                                T_SYUKKOJISEKI.SURYO AS SYUKKOJISEKI_SURYO, T_BUZAIHACYUMSAI.SURYO AS BUZAIHACYUMSAI_SURYO
                    FROM T_TANA 
                    LEFT JOIN T_TANAMSAI ON T_TANA.TANA_ID=T_TANAMSAI.TANA_ID
                    LEFT JOIN M_BUZAI ON T_TANAMSAI.BUZAI_KANRI_NO = M_BUZAI.BUZAI_KANRI_NO
                    LEFT JOIN T_SYUKKOJISEKI ON T_TANAMSAI.HINBAN = T_SYUKKOJISEKI.JISYA_CD
                    LEFT JOIN T_BUZAIHACYU ON T_TANA.SYOZOKU_CD = T_BUZAIHACYU.SYOZOKU_CD
                    LEFT JOIN T_BUZAIHACYUMSAI ON T_TANAMSAI.JISYA_CD = T_BUZAIHACYUMSAI.JISYA_CD
                    WHERE T_TANA.SOKO_CD="' . $SYOZOKU_CD . '"
                        AND T_TANA.TANA_YM >= "'. $getSubMonthYear .'"
                        AND T_TANA.TANA_YM <= "'. $getCurrentMonthYear .'"
                        AND T_TANA.DEL_FLG=0
                        AND T_TANA.TANA_YMD >= "'. $getCurrentDate .'"
                        AND T_SYUKKOJISEKI.SOKO_CD = "'. $SYOZOKU_CD .'"
                        AND T_SYUKKOJISEKI.SYUKKO_DATE = T_TANA.TANA_YMD
                        AND T_BUZAIHACYU.HACYU_YMD = T_TANA.TANA_YMD
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

    function getInventoryListForEdit()
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
                $getCurrentDate = date('Y-m-d');

                $sql = ' SELECT T_TANAMSAI_SAVE.BUZAI_KANRI_NO, T_TANAMSAI_SAVE.HINBAN, T_TANAMSAI_SAVE.SYOHIN_NAME, 
                                T_TANAMSAI_SAVE.SENGETU_JITUZAIKO_SU, T_TANAMSAI_SAVE.JITUZAIKO_SU, 
                                M_BUZAI.BUZAI_BUNRUI, M_BUZAI.MAKER_NAME, M_BUZAI.SIIRE_TANKA, 
                                T_SYUKKOJISEKI.SURYO AS SYUKKOJISEKI_SURYO, T_BUZAIHACYUMSAI.SURYO AS BUZAIHACYUMSAI_SURYO
                    FROM T_TANA 
                    LEFT JOIN T_TANAMSAI ON T_TANA.TANA_ID=T_TANAMSAI.TANA_ID
                    LEFT JOIN T_TANAMSAI_SAVE ON T_TANAMSAI.TANAMSAI_ID = T_TANAMSAI_SAVE.TANAMSAI_ID
                    LEFT JOIN M_BUZAI ON T_TANAMSAI_SAVE.BUZAI_KANRI_NO = M_BUZAI.BUZAI_KANRI_NO
                    LEFT JOIN T_SYUKKOJISEKI ON T_TANAMSAI_SAVE.HINBAN = T_SYUKKOJISEKI.JISYA_CD
                    LEFT JOIN T_BUZAIHACYU ON T_TANA.SYOZOKU_CD = T_BUZAIHACYU.SYOZOKU_CD
                    LEFT JOIN T_BUZAIHACYUMSAI ON T_TANAMSAI.JISYA_CD = T_BUZAIHACYUMSAI.JISYA_CD
                    WHERE T_TANA.SOKO_CD="' . $SYOZOKU_CD . '"
                        AND T_TANA.TANA_YM >= "'. $getSubMonthYear .'"
                        AND T_TANA.TANA_YM <= "'. $getCurrentMonthYear .'"
                        AND T_TANA.DEL_FLG=0
                        AND T_TANA.TANA_YMD >= "'. $getCurrentDate .'"
                        AND T_SYUKKOJISEKI.SOKO_CD = "'. $SYOZOKU_CD .'"
                        AND T_SYUKKOJISEKI.SYUKKO_DATE = T_TANA.TANA_YMD
                        AND T_BUZAIHACYU.SYOZOKU_CD = T_TANA.TANA_YMD
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

    function deleteTanamsaiSave() 
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
                $sql = 'UPDATE T_TANAMSAI_SAVE SET DEL_FLG="1" 
                    WHERE SAVE_TANT_CD="' . $_POST['LOGIN_ID'] . '"
                ';
                $this->result = $this->dbConnect->query($sql);

                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }                                        
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('Success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postInventoryListForCreateNotExist() 
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $validate = new Validate();

            $validated = $validate->validate($_POST, [
                'LOGIN_ID' => 'required',
            ]);

            if ($validated) {
                $getCurrentMonthYear = date('Y') . date('m');
                $getCurrentDate = date('Y-m-d');
                $getCurrentYear = date('Y');

                $sqlGetInfoLogin = 'SELECT TANT_CD, SYOZOKU_CD
                                    FROM M_TANT';
                $this->result = $this->dbConnect->query($sqlGetInfoLogin);
                if ($this->result->num_rows > 0) {
                    while ($row = $this->result->fetch_assoc()) {
                        $getInfoLogin['LOGIN_ID'] = $row['TANT_CD'];
                        $getInfoLogin['SYOZOKU_CD'] = $row['SYOZOKU_CD'];
                    }
                }

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
                    (TANA_ID,TANA_YM,TANA_YMD, MONTHLY_KEIJO_YM, BASYO_GYOSYA_SYBET_CD, SOKO_CD, 
                    SYOZOKU_CD,TANT_CD,RENKEI_YMD)
                    VALUES
                    ("' . $T_TANA_ID_MAX . '" , "' . $getCurrentMonthYear . '" , "' . $getCurrentDate . '" , 
                    "' . $getCurrentYear . '" , "03" , "'. $getInfoLogin['SYOZOKU_CD'] . '" , "'. $getInfoLogin['SYOZOKU_CD'] . '", 
                    "' . $getInfoLogin['LOGIN_ID'] . '", "' . $getCurrentDate . '")';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql error : ' . $this->dbConnect->error;
                }

                $sqlGetMBuzai = 'SELECT * FROM M_BUZAI';
                $this->result = $this->dbConnect->query($sqlGetMBuzai);
                if ($this->result->num_rows > 0) {
                    while ($row = $this->result->fetch_assoc()) {
                        $getInfoMBuzai[] = $row;
                    }
                }

                foreach($getInfoMBuzai as $item => $value) {
                    $sql = 'INSERT INTO T_TANAMSAI 
                        (TANA_ID,TANAMSAI_ID,BUZAI_KANRI_NO, JISYA_CD, HINBAN, SYOHIN_NAME, JITUZAIKO_SU)
                        VALUES
                        ("' . $T_TANA_ID_MAX . '" , "' . $getCurrentMonthYear . '" , "' . $getCurrentDate . '" , 
                        "' . $getCurrentYear . '" , "03" , "'. $getInfoLogin['SYOZOKU_CD'] . '" , "'. $getInfoLogin['SYOZOKU_CD'] . '", 
                        "' . $getInfoLogin['LOGIN_ID'] . '", "' . $getCurrentDate . '")';
                    $this->result = $this->dbConnect->query($sql);
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

