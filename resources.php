<?php

include('systemConfig.php');
include('systemEditor.php');
class resources
{
    private $dbReference;
    var $dbConnect;
    var $result;
    var $changeKey;

    /**
     *
     */
    function __construct()
    {
    }

    function __destruct()
    {
    }
    //工事一覧
    function getConstructionList()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['YMD'])) {
                $YMD = $_GET['YMD'];
                $sql = 'SELECT SITAMIHOMONJIKAN,
                HOMON_SBT,
                JYUCYU_ID,
                SITAMI_JININ,
                SITAMI_JIKAN,
                KOJI_ITEM,
                SETSAKI_ADDRESS,
                SETSAKI_NAME FROM T_KOJI WHERE SITAMI_YMD="' . $YMD . '" AND SYUYAKU_JYUCYU_ID IS NOT NULL AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $row['TYPE'] = 'SITAMI';
                        $resultSet[] = $row;
                    }
                }
                
                $sql2 = 'SELECT KOJIHOMONJIKAN,
                HOMON_SBT,
                JYUCYU_ID,
                KOJI_JININ,
                KOJI_JIKAN,
                KOJI_ITEM,
                SETSAKI_ADDRESS,
                SETSAKI_NAME FROM T_KOJI WHERE KOJI_YMD="' . $YMD . '" AND SYUYAKU_JYUCYU_ID IS NOT NULL AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql2);
                $resultSet2 = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $row['TYPE'] = 'KOJI';
                        $resultSet2 = $row;
                    }
                }
                $data = array();
                array_push($data , $resultSet);
                array_push($data , $resultSet2);
                
                $this->dbReference->sendResponse(200, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    function getRequestForm()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['JYUCYU_ID']) && isset($_GET['FILEPATH_ID']) && isset($_GET['FILE_KBN_CD'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $FILEPATH_ID = $_GET['FILEPATH_ID'];
                $FILE_KBN_CD = $_GET['FILE_KBN_CD'];
                $sql = 'SELECT SITAMIIRAISYO_FILEPATH,
                FILEPATH FROM T_KOJI LEFT JOIN T_KOJI_FILEPATH ON T_KOJI.JYUCYU_ID=T_KOJI_FILEPATH.ID WHERE JYUCYU_ID= ' . $JYUCYU_ID . ' AND FILEPATH_ID=' . $FILEPATH_ID . ' AND FILE_KBN_CD=' . $FILE_KBN_CD . ' AND T_KOJI.DEL_FLG IS NULL AND T_KOJI_FILEPATH.DEL_FLG IS NULL';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    function getRequestForm2()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['SYUYAKU_JYUCYU_ID']) && isset($_GET['FILEPATH_ID']) && isset($_GET['FILE_KBN_CD'])) {
                $SYUYAKU_JYUCYU_ID = $_GET['SYUYAKU_JYUCYU_ID'];
                $FILEPATH_ID = $_GET['FILEPATH_ID'];
                $FILE_KBN_CD = $_GET['FILE_KBN_CD'];
                $sql = 'SELECT KOJIIRAISYO_FILEPATH,
                FILEPATH FROM T_KOJI LEFT JOIN T_KOJI_FILEPATH ON T_KOJI.SYUYAKU_JYUCYU_ID=T_KOJI_FILEPATH.ID WHERE SYUYAKU_JYUCYU_ID= ' . $SYUYAKU_JYUCYU_ID . ' AND FILEPATH_ID=' . $FILEPATH_ID . ' AND FILE_KBN_CD=' . $FILE_KBN_CD . '  AND T_KOJI.DEL_FLG IS NULL';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    function getPhotoSubmission()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['FILEPATH_ID'])) {
                $FILEPATH_ID = $_GET['FILEPATH_ID'];
                $sql = 'SELECT FILEPATH FROM T_KOJI_FILEPATH WHERE FILEPATH_ID= ' . $FILEPATH_ID . ' AND FILE_KBN_CD=10 AND DEL_FLG IS NULL';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    function getPhotoSubmissionRegistration()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_POST['JYUCYU_ID'])) {
                $JYUCYU_ID = $_POST['JYUCYU_ID'];
                $sql = 'SELECT KOJI_KEKKA as kekka,
                SKJ_RENKEI_YMD as renkei_ymd,
                UPD_PGID as update_pgid,
                UPD_TANTCD as update_tantcd,
                UPD_YMD as update_ymd FROM T_KOJI WHERE JYUCYU_ID= ' . $JYUCYU_ID . '';
                $this->result = $this->dbConnect->query($sql);
                $data = array();
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }

                    foreach ($resultSet as $key => $value) {
                        $resultSet[$key]['message'] = 'OKを設定';
                    }

                    array_push($data, $resultSet);
                }

                $sql2 = 'SELECT FILEPATH_ID as filepath_id,
                ID as id,
                FILEPATH as filepath,
                FILE_KBN_CD as kbn_cd,
                ADD_PGID as add_pgid,
                ADD_TANTCD as add_tantcd,
                ADD_YMD as add_ymd,
                UPD_PGID as update_pgid,
                UPD_TANTCD as update_tantcd,
                UPD_YMD as update_ymd FROM T_KOJI_FILEPATH WHERE ID= ' . $JYUCYU_ID . '';
                $this->result2 = $this->dbConnect->query($sql2);
                $resultSet2 = array();
                if ($this->result2->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result2->fetch_assoc()) {
                        $resultSet2[] = $row;
                    }
                    foreach ($resultSet2 as $key => $value) {
                        $resultSet2[$key]['message'] = 'OKを設定';
                    }

                    array_push($data, $resultSet2);
                }

                $this->dbReference->sendResponse(200, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    function getWrittenConsent()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['JYUCYU_ID']) && isset($_GET['KOJIGYOSYA_CD'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $KOJIGYOSYA_CD = $_GET['KOJIGYOSYA_CD'];
                $sql = 'SELECT JYUCYU_ID,
                SETSAKI_NAME,
                KOJI_YMD,
                KOJIGYOSYA_NAME,
                HOMON_TANT_NAME1,
                HOMON_TANT_NAME2,
                HOMON_TANT_NAME3,
                HOMON_TANT_NAME4,
                CO_NAME,
                CO_POSTNO,
                CO_ADDRESS FROM T_KOJI LEFT JOIN M_GYOSYA ON T_KOJI.KOJIGYOSYA_CD=M_GYOSYA.KOJIGYOSYA_CD WHERE JYUCYU_ID= ' . $JYUCYU_ID . ' AND T_KOJI.KOJIGYOSYA_CD= ' . $KOJIGYOSYA_CD . ' AND HOJIN_FLG= 0 AND T_KOJI.DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    function getWrittenConsent2()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['JYUCYU_ID']) && isset($_GET['KOJIGYOSYA_CD'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $KOJIGYOSYA_CD = $_GET['KOJIGYOSYA_CD'];
                $sql = 'SELECT T_KOJI.JYUCYU_ID,
                SETSAKI_NAME,
                KOJI_YMD,
                KOJIGYOSYA_NAME,
                HOMON_TANT_NAME1,
                HOMON_TANT_NAME2,
                HOMON_TANT_NAME3,
                HOMON_TANT_NAME4,
                CO_NAME,
                CO_POSTNO,
                CO_ADDRESS,
                TUIKA_SYOHIN_NAME,
                TUIKA_JISYA_CD,
                SURYO,
                HANBAI_TANKA,
                KINGAK FROM T_KOJI 
                LEFT JOIN T_KOJIMSAI ON T_KOJI.JYUCYU_ID=T_KOJIMSAI.JYUCYU_ID
                LEFT JOIN M_GYOSYA ON T_KOJI.KOJIGYOSYA_CD=M_GYOSYA.KOJIGYOSYA_CD WHERE T_KOJI.JYUCYU_ID= ' . $JYUCYU_ID . ' AND T_KOJI.KOJIGYOSYA_CD= ' . $KOJIGYOSYA_CD . ' AND HOJIN_FLG= 0 AND T_KOJI.DEL_FLG= 0 AND KOJIJITUIKA_FLG= 1';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    function getWrittenConsent3()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['JISYA_CD'])) {
                $JISYA_CD = $_GET['JISYA_CD'];
                $sql = 'SELECT SYOHIN_NAME,
                KOJI_KAKAKU FROM M_KOJI_KAKAKU WHERE JISYA_CD= "KOJ' . $JISYA_CD . '" ';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    function getCorporateCompletionForm()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['KBN_BIKO'])) {
                $KBN_BIKO = $_GET['KBN_BIKO'];
                $sql = 'SELECT YOBIKOMOKU1,
                YOBIKOMOKU2,
                YOBIKOMOKU3,
                YOBIKOMOKU4,
                YOBIKOMOKU5 FROM T_KOJI LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD WHERE HOJIN_FLG= 1 AND KBN_CD= 12 AND KBN_BIKO="' . $KBN_BIKO . '" AND M_KBN.DEL_FLG IS NULL';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    function postPostCountTirasi()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            //$_POST['loginId']: required
            //$_POST['date']: required
            if (isset($_POST['loginId']) && isset($_POST['date'])) {
                $loginId = $_POST['loginId'];
                $selectionDate = $_POST['date'];
                $sql = 'SELECT YMD, RENKEI_YMD, KOJI_TIRASISU, UPD_PGID, UPD_TANTCD, UPD_YMD FROM T_TIRASI WHERE TANT_CD="' . $loginId . '" AND YMD="' . $selectionDate . '"';
                // var_dump($sql); die;
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                    foreach ($resultSet as $key => $value) {
                        //Edit Key Array
                        $this->changeKey = new systemEditor();
                        $oldKeyArr = [
                            'YMD',
                            'RENKEI_YMD',
                            'KOJI_TIRASISU',
                            'UPD_PGID',
                            'UPD_TANTCD',
                            'UPD_YMD',
                        ];
                        $newKeyArr = [
                            'login_id',
                            'execution_dt',
                            'koji_tirashisu',
                            'update_pgid',
                            'update_tantcd',
                            'update_ymd',
                        ];
                        $resultSet[$key] = $this->changeKey->change_key($value, $oldKeyArr, $newKeyArr);

                        //Add Message
                        $resultSet[$key]['message'] = 'OK';
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(507, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(507) . '}');
            }
        }
    }

    function postPostCountKoji()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            // $_POST['setsaki_address']: required
            // $_POST['koji_ymd']: required
            // $_POST['koji_st']: required
            if (
                isset($_POST['setsaki_address']) &&
                isset($_POST['koji_ymd']) &&
                isset($_POST['koji_st']) &&
                $_POST['koji_st'] == '2'
            ) {

                $setsakiAddress = $_POST['setsaki_address'];
                $kojiYmd = $_POST['koji_ymd'];
                $kojiSt = $_POST['koji_st'];

                $sql = ' SELECT SYUYAKU_JYUCYU_ID, UPD_PGID, UPD_TANTCD, UPD_YMD FROM T_KOJI WHERE SETSAKI_ADDRESS="' . $setsakiAddress . '" AND KOJI_YMD="' . $kojiYmd . '" AND KOJI_ST="' . $kojiSt . '" AND DEL_FLG="0" ';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                    foreach ($resultSet as $key => $value) {
                        //Edit Key Array
                        $this->changeKey = new systemEditor();
                        $oldKeyArr = [
                            'SYUYAKU_JYUCYU_ID',
                            'UPD_PGID',
                            'UPD_TANTCD',
                            'UPD_YMD',
                        ];
                        $newKeyArr = [
                            'syuyaku_jyucyu_id',
                            'update_pgid',
                            'update_id',
                            'update_dt',
                        ];
                        $resultSet[$key] = $this->changeKey->change_key($value, $oldKeyArr, $newKeyArr);

                        //Add Message
                        $resultSet[$key]['message'] = 'OK';
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(507, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(507) . '}');
            }
        }
    }

    //写真確認
    function getPhotoConfirm()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            // $_GET['koji_filepath_id']: required
            // $_GET['koji_filepath_file_kbn_cd']: required
            if (
                isset($_GET['koji_filepath_id'])
            ) {

                $kojiFilepathId = $_GET['koji_filepath_id'];

                $sql = ' SELECT FILEPATH 
                    FROM T_KOJI_FILEPATH 
                    WHERE FILEPATH_ID="' . $kojiFilepathId . '" 
                        AND FILE_KBN_CD="05"
                    ';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // Construction Report
    //----未報告の場合
    function getConstructionReportNotReport()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            // $_GET['kojimsai_jyucyu_id']: required
            // $_GET['kojimsai_kojijituika_flg']: required
            if (
                isset($_GET['kojimsai_jyucyu_id']) &&
                isset($_GET['kojimsai_kojijituika_flg']) &&
                $_GET['kojimsai_kojijituika_flg'] == '0'
            ) {

                $jyucyuId = $_GET['kojimsai_jyucyu_id'];
                $kojijituikaFlg = $_GET['kojimsai_kojijituika_flg'];

                $sql = 'SELECT MAKER_CD, HINBAN 
                        FROM T_KOJIMSAI 
                        WHERE JYUCYU_ID="' . $jyucyuId . '" AND KOJIJITUIKA_FLG="' . $kojijituikaFlg . '" AND DEL_FLG="0"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    //----未報告の場合で、完了書を1枚にまとめる
    function getConstructionReportNotReportSummarize()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['koji_jyucyu_id']) &&
                isset($_GET['kojimsai_jyucyu_id']) &&
                isset($_GET['kojimsai_kojijituika_flg']) &&
                $_GET['kojimsai_kojijituika_flg'] == '0'
            ) {

                $kojiJyucyuId = $_GET['koji_jyucyu_id'];
                $jyucyuId = $_GET['kojimsai_jyucyu_id'];
                $kojijituikaFlg = $_GET['kojimsai_kojijituika_flg'];

                $sql = 'SELECT MAKER_CD, HINBAN 
                        FROM T_KOJIMSAI 
                        WHERE JYUCYU_ID="' . $jyucyuId . '" AND KOJIJITUIKA_FLG="' . $kojijituikaFlg . '" AND DEL_FLG="0"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    //----報告済みの場合
    function getConstructionReportReported()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['kojimsai_jyucyu_id']) &&
                isset($_GET['kojimsai_kojijituika_flg']) &&
                $_GET['kojimsai_kojijituika_flg'] == '0'
            ) {

                $jyucyuId = $_GET['kojimsai_jyucyu_id'];
                $kojijituikaFlg = $_GET['kojimsai_kojijituika_flg'];

                $sql = 'SELECT MAKER_CD, HINBAN, KISETU_MAKER_CD, KISETU_HINBAN, BEF_SEKO_PHOTO_FILEPATH, AFT_SEKO_PHOTO_FILEPATH, OTHER_PHOTO_FOLDERPATH
                        FROM T_KOJIMSAI 
                        WHERE JYUCYU_ID="' . $jyucyuId . '" AND KOJIJITUIKA_FLG="' . $kojijituikaFlg . '" AND DEL_FLG="0"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    //----報告済みの場合で、完了書を1枚にまとめた
    function getConstructionReportReportedSummarized()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['koji_jyucyu_id']) &&
                isset($_GET['kojimsai_jyucyu_id']) &&
                isset($_GET['kojimsai_kojijituika_flg']) &&
                $_GET['kojimsai_kojijituika_flg'] == '0'
            ) {

                $jyucyuId = $_GET['kojimsai_jyucyu_id'];
                $kojijituikaFlg = $_GET['kojimsai_kojijituika_flg'];

                $sql = 'SELECT MAKER_CD, HINBAN, KISETU_MAKER_CD, KISETU_HINBAN, BEF_SEKO_PHOTO_FILEPATH, AFT_SEKO_PHOTO_FILEPATH, OTHER_PHOTO_FOLDERPATH
                        FROM T_KOJIMSAI 
                        WHERE JYUCYU_ID="' . $jyucyuId . '" AND KOJIJITUIKA_FLG="' . $kojijituikaFlg . '" AND DEL_FLG="0"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    //----建築形態プルダウン
    function getConstructionReportArchitectural()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['kbn_kbn_cd'])) {
                $kbnCd = $_GET['kbn_kbn_cd'];

                $sql = 'SELECT KBNMSAI_NAME 
                        FROM M_KBN 
                        WHERE KBN_CD="' . $kbnCd . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // Letter of Consent - Sign Registration
    function postLetterConsent()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $sql = 'SELECT FILEPATH_ID, ID, FILEPATH, FILE_KBN_CD, ADD_PGID, ADD_TANTCD, ADD_YMD, UPD_PGID, UPD_TANTCD, UPD_YMD
                    FROM T_KOJI_FILEPATH';
            $this->result = $this->dbConnect->query($sql);

            $resultSet = array();
            if ($this->result->num_rows > 0) {
                // output data of each row
                while ($row = $this->result->fetch_assoc()) {
                    $resultSet[] = $row;
                }
            }

            $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    }

    // Consent - Registration
    // ----Consent Default
    function postConsent()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['koji_jyucyu_id']) &&
                isset($_POST['kojimsai_jyucyu_id'])
            ) {
                $kojiJyucyuId = $_POST['koji_jyucyu_id'];
                $kojimsaiJyucyuId = $_POST['kojimsai_jyucyu_id'];
                $dataArr = array();

                //Get data T_KOJI
                $sql = 'SELECT  KOJI_RENKEI_YMD AS renkei_ymd, KOJI_KEKKA AS kekka, BIKO AS biko, 
                        UPD_PGID AS t_koji_update_pgid, UPD_TANTCD AS t_koji_update_id, UPD_YMD AS t_koji_update_dt
                        FROM T_KOJI 
                        WHERE JYUCYU_ID="' . $kojiJyucyuId . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                array_push($dataArr, $resultSet);

                //Get data T_KOJIMSAI
                $sql2 = 'SELECT  KENSETU_KEITAI AS kensetu_keitai, BEF_SEKO_PHOTO_FILEPATH AS bef_photo, 
                        AFT_SEKO_PHOTO_FILEPATH AS aft_photo, OTHER_PHOTO_FOLDERPATH AS other_photo, 
                        UPD_PGID AS t_kojimsai_update_pgid, UPD_TANTCD AS t_kojimsai_update_id, UPD_YMD AS t_kojimsai_update_dt
                        FROM T_KOJIMSAI 
                        WHERE JYUCYU_ID="' . $kojimsaiJyucyuId . '"';
                $this->result = $this->dbConnect->query($sql2);

                $resultSet2 = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet2[] = $row;
                    }
                }
                array_push($dataArr, $resultSet2);

                $this->dbReference->sendResponse(200, json_encode($dataArr, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // ----Consent Compiling a completed document on one sheet
    function postConsentCompiling()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['koji_syuyaku_jyucyu_id']) &&
                isset($_POST['kojimsai_jyucyu_id'])
            ) {
                $kojiSyuyakuJyucyuId = $_POST['koji_syuyaku_jyucyu_id'];
                $kojimsaiJyucyuId = $_POST['kojimsai_jyucyu_id'];
                $dataArr = array();

                //Get data T_KOJI
                $sql = 'SELECT  KOJI_RENKEI_YMD AS renkei_ymd, KOJI_KEKKA AS kekka, BIKO AS biko, 
                        UPD_PGID AS t_koji_update_pgid, UPD_TANTCD AS t_koji_update_id, UPD_YMD AS t_koji_update_dt
                        FROM T_KOJI 
                        WHERE SYUYAKU_JYUCYU_ID="' . $kojiSyuyakuJyucyuId . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                array_push($dataArr, $resultSet);

                //Get data T_KOJIMSAI
                $sql2 = 'SELECT  KENSETU_KEITAI AS kensetu_keitai, BEF_SEKO_PHOTO_FILEPATH AS bef_photo, 
                        AFT_SEKO_PHOTO_FILEPATH AS aft_photo, OTHER_PHOTO_FOLDERPATH AS other_photo, 
                        UPD_PGID AS t_kojimsai_update_pgid, UPD_TANTCD AS t_kojimsai_update_id, UPD_YMD AS t_kojimsai_update_dt
                        FROM T_KOJIMSAI 
                        WHERE JYUCYU_ID="' . $kojimsaiJyucyuId . '"';
                $this->result = $this->dbConnect->query($sql2);

                $resultSet2 = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet2[] = $row;
                    }
                }
                array_push($dataArr, $resultSet2);

                $this->dbReference->sendResponse(200, json_encode($dataArr, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // Corporate Completion Form - Registration
    function postCorporateCompletion()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['koji_jyucyu_id']) &&
                isset($_POST['kojimsai_jyucyu_id'])
            ) {
                $kojiJyucyuId = $_POST['koji_jyucyu_id'];
                $kojimsaiJyucyuId = $_POST['kojimsai_jyucyu_id'];
                $dataArr = array();

                //Get data T_KOJI
                $sql = 'SELECT  KOJI_RENKEI_YMD AS renkei_ymd, KOJI_KEKKA AS kekka, 
                        UPD_PGID AS t_koji_update_pgid, UPD_TANTCD AS t_koji_update_id, UPD_YMD AS t_koji_update_dt
                        FROM T_KOJI 
                        WHERE JYUCYU_ID="' . $kojiJyucyuId . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                array_push($dataArr, $resultSet);

                //Get data T_KOJIMSAI
                $sql2 = 'SELECT  KENSETU_KEITAI AS kensetu_keitai, BEF_SEKO_PHOTO_FILEPATH AS bef_photo, 
                        AFT_SEKO_PHOTO_FILEPATH AS aft_photo, OTHER_PHOTO_FOLDERPATH AS other_photo, 
                        UPD_PGID AS t_kojimsai_update_pgid, UPD_TANTCD AS t_kojimsai_update_id, UPD_YMD AS t_kojimsai_update_dt
                        FROM T_KOJIMSAI 
                        WHERE JYUCYU_ID="' . $kojimsaiJyucyuId . '"';
                $this->result = $this->dbConnect->query($sql2);

                $resultSet2 = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet2[] = $row;
                    }
                }
                array_push($dataArr, $resultSet2);

                $this->dbReference->sendResponse(200, json_encode($dataArr, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // Enter reason
    function getEnterReason()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['koji_jyucyu_id'])
            ) {
                $kojiJyucyuId = $_GET['koji_jyucyu_id'];

                //Get data T_KOJI
                $sql = 'SELECT CANCEL_RIYU 
                        FROM T_KOJI 
                        WHERE JYUCYU_ID="' . $kojiJyucyuId . '" AND DEL_FLG="0"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // Photo submission - preview
    function getPhotoSubmissionPreview()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['koji_filepath_file_kbn_cd'])
            ) {
                $kojiFilePathFileKbnCd = $_GET['koji_filepath_file_kbn_cd'];

                //Get data T_KOJI
                $sql = 'SELECT FILEPATH 
                        FROM T_KOJI_FILEPATH 
                        WHERE FILE_KBN_CD="' . $kojiFilePathFileKbnCd . '" AND DEL_FLG="0"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // Photo Submission_Registration
    // ----Transition from the button to send only photos to the system and press the registration button
    function postPhotoSubmissionRegistrationFromSendPhoto()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['koji_jyucyu_id'])
            ) {
                $kojiJyucyuId = $_POST['koji_jyucyu_id'];

                //Get data T_KOJI LEFT JOIN T_KOJI_FILEPATH
                $sql = 'SELECT  KOJI_RENKEI_YMD AS renkei_ymd, KOJI_KEKKA AS kekka, SITAMI_REPORT AS sitami_report, 
                        UPD_PGID AS t_koji_update_pgid, UPD_TANTCD AS t_koji_update_id, UPD_YMD AS t_koji_update_dt,  
                        FILEPATH_ID AS filepath_id, ID AS id, FILEPATH AS filepath, FILE_KBN_CD AS kbn_cd, 
                        ADD_PGID AS add_pgid, ADD_TANTCD AS add_tantcd, ADD_YMD AS add_ymd, 
                        UPD_PGID AS file_path_update_pgid, UPD_TANTCD AS file_path_update_tantcd, UPD_YMD AS file_path_update_ymd
                        FROM T_KOJI 
                        LEFT JOIN T_KOJI_FILEPATH 
                        ON T_KOJI.JYUCYU_ID = T_KOJI_FILEPATH.ID
                        WHERE T_KOJI.JYUCYU_ID="' . $kojiJyucyuId . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // ----As a result of preliminary inspection, transition from the cancel report button and press the register button
    function postPhotoSubmissionRegistrationFromCancel()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['koji_jyucyu_id'])
            ) {
                $kojiJyucyuId = $_POST['koji_jyucyu_id'];

                //Get data T_KOJI LEFT JOIN T_KOJI_FILEPATH
                $sql = 'SELECT  KOJI_RENKEI_YMD AS renkei_ymd, KOJI_KEKKA AS kekka, SITAMI_REPORT AS sitami_report, CANCEL_RIYU AS cancel_reyu, 
                        UPD_PGID AS t_koji_update_pgid, UPD_TANTCD AS t_koji_update_id, UPD_YMD AS t_koji_update_dt,  
                        FILEPATH_ID AS filepath_id, ID AS id, FILEPATH AS filepath, FILE_KBN_CD AS kbn_cd, 
                        ADD_PGID AS add_pgid, ADD_TANTCD AS add_tantcd, ADD_YMD AS add_ymd, 
                        UPD_PGID AS file_path_update_pgid, UPD_TANTCD AS file_path_update_tantcd, UPD_YMD AS file_path_update_ymd 
                        FROM T_KOJI 
                        LEFT JOIN T_KOJI_FILEPATH 
                        ON T_KOJI.JYUCYU_ID = T_KOJI_FILEPATH.ID
                        WHERE T_KOJI.JYUCYU_ID="' . $kojiJyucyuId . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // ----Transition from the button to report delayed estimate creation and press the register button
    function postPhotoSubmissionRegistrationFromReportDelayed()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['koji_jyucyu_id'])
            ) {
                $kojiJyucyuId = $_POST['koji_jyucyu_id'];

                //Get data T_KOJI LEFT JOIN T_KOJI_FILEPATH
                $sql = 'SELECT  KOJI_RENKEI_YMD AS renkei_ymd, SITAMI_REPORT AS sitami_report, 
                        MTMORI_YMD AS mitmori_ymd, CANCEL_RIYU AS cancel_reyu, 
                        UPD_PGID AS t_koji_update_pgid, UPD_TANTCD AS t_koji_update_id, UPD_YMD AS t_koji_update_dt,  
                        FILEPATH_ID AS filepath_id, ID AS id, FILEPATH AS filepath, FILE_KBN_CD AS kbn_cd, 
                        ADD_PGID AS add_pgid, ADD_TANTCD AS add_tantcd, ADD_YMD AS add_ymd, 
                        UPD_PGID AS file_path_update_pgid, UPD_TANTCD AS file_path_update_tantcd, UPD_YMD AS file_path_update_ymd 
                        FROM T_KOJI 
                        LEFT JOIN T_KOJI_FILEPATH 
                        ON T_KOJI.JYUCYU_ID = T_KOJI_FILEPATH.ID
                        WHERE T_KOJI.JYUCYU_ID="' . $kojiJyucyuId . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // ----Transition from the report without quotation button and press the registration button
    function postPhotoSubmissionRegistrationFromReportNoQuoation()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['koji_jyucyu_id'])
            ) {
                $kojiJyucyuId = $_POST['koji_jyucyu_id'];

                //Get data T_KOJI LEFT JOIN T_KOJI_FILEPATH
                $sql = 'SELECT  KOJI_RENKEI_YMD AS renkei_ymd, SITAMI_REPORT AS sitami_report, 
                        UPD_PGID AS t_koji_update_pgid, UPD_TANTCD AS t_koji_update_id, UPD_YMD AS t_koji_update_dt,  
                        FILEPATH_ID AS filepath_id, ID AS id, FILEPATH AS filepath, FILE_KBN_CD AS kbn_cd, 
                        ADD_PGID AS add_pgid, ADD_TANTCD AS add_tantcd, ADD_YMD AS add_ymd, 
                        UPD_PGID AS file_path_update_pgid, UPD_TANTCD AS file_path_update_tantcd, UPD_YMD AS file_path_update_ymd 
                        FROM T_KOJI 
                        LEFT JOIN T_KOJI_FILEPATH 
                        ON T_KOJI.JYUCYU_ID = T_KOJI_FILEPATH.ID
                        WHERE T_KOJI.JYUCYU_ID="' . $kojiJyucyuId . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }
    //ネット工事ネット下見内容詳細
    function getNetConstructionNetPreviewContentsDetails()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['JYUCYU_ID'])
            ) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $sql = ' SELECT TAG_KBN,
                SITAMIHOMONJIKAN,
                SITAMIHOMONJIKAN_END,
                KOJI_JININ,
                SITAMI_JIKAN,
                SITAMIAPO_KBN,
                UPD_TANTNM,
                UPD_YMD,
                MEMO  FROM T_KOJI WHERE JYUCYU_ID=' . $JYUCYU_ID . ' AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                $data = array();
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                    array_push($data, $resultSet);
                }

                $sql2 = ' SELECT TAG_KBN,
                KOJIHOMONJIKAN,
                KOJIHOMONJIKAN_END,
                KOJI_JININ,
                KOJI_JIKAN,
                KOJIAPO_KBN,
                UPD_TANTNM,
                UPD_YMD,
                MEMO  FROM T_KOJI WHERE JYUCYU_ID=' . $JYUCYU_ID . ' AND DEL_FLG= 0';
                $this->result2 = $this->dbConnect->query($sql2);
                $resultSet2 = array();
                if ($this->result2->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result2->fetch_assoc()) {
                        $resultSet2[] = $row;
                    }
                    array_push($data, $resultSet2);
                }

                $this->dbReference->sendResponse(200, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }
    //ネット工事ネット下見内容更新
    function postNetConstructionNetPreviewContentsUpdate()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['JYUCYU_ID'])
            ) {
                $JYUCYU_ID = $_POST['JYUCYU_ID'];
                $sql = ' SELECT TAG_KBN,
                SITAMIAPO_KBN,
                SITAMIHOMONJIKAN,
                SITAMIHOMONJIKAN_END,
                SITAMI_JININ,
                SITAMI_KANSAN_POINT,
                ALL_DAY_FLG,
                SKJ_RENKEI_YMD,
                UPD_PGID,
                UPD_TANTCD,                
                UPD_YMD,
                MEMO  FROM T_KOJI WHERE JYUCYU_ID=' . $JYUCYU_ID . '';
                $this->result = $this->dbConnect->query($sql);
                $data = array();
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                    array_push($data, $resultSet);
                }

                $sql2 = ' SELECT TAG_KBN,
                KOJIAPO_KBN,
                KOJIHOMONJIKAN,
                KOJIHOMONJIKAN_END,
                KOJI_JININ,
                KOJI_KANSAN_POINT,
                ALL_DAY_FLG,
                SKJ_RENKEI_YMD,
                UPD_PGID,
                UPD_TANTCD,               
                UPD_YMD,
                MEMO  FROM T_KOJI WHERE JYUCYU_ID=' . $JYUCYU_ID . '';
                $this->result2 = $this->dbConnect->query($sql2);
                $resultSet2 = array();
                if ($this->result2->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result2->fetch_assoc()) {
                        $resultSet2[] = $row;
                    }
                    array_push($data, $resultSet2);
                }

                $this->dbReference->sendResponse(200, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }
    //営業工事営業下見内容
    function getSalesConstructionSalesPreviewContents()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['TAN_EIG_ID'])
            ) {
                $TAN_EIG_ID = $_GET['TAN_EIG_ID'];
                $sql = ' SELECT TAG_KBN,
                YMD, 
                START_TIME,
                END_TIME,
                JININ,
                JIKAN,
                GUEST_NAME,
                ATTEND_NAME1,
                ATTEND_NAME2,
                ATTEND_NAME3 FROM T_EIGYO_ANKEN WHERE TAN_EIG_ID= ' . $TAN_EIG_ID . ' AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }
    // 営業工事営業下見内容更新
    function postSalesConstructionSalesPreviewUpdate()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['JYOKEN_CD']) && isset($_POST['YMD'])
            ) {
                $YMD = $_POST['YMD'];
                $JYOKEN_CD = $_POST['JYOKEN_CD'];
                $sql = ' SELECT TAN_EIG_ID,
                JYOKEN_CD,
                JYOKEN_SYBET_FLG,
                YMD,
                TAG_KBN,
                START_TIME,
                END_TIME,
                JININ,
                JIKAN,
                GUEST_NAME,                
                ATTEND_NAME1,
                ATTEND_NAME2,
                ATTEND_NAME3,
                ALL_DAY_FLG,
                RENKEI_YMD,
                ADD_PGID,
                ADD_TANTCD,
                ADD_YMD,
                UPD_PGID,
                UPD_TANTCD,
                UPD_YMD FROM T_EIGYO_ANKEN WHERE JYOKEN_CD=' . $JYOKEN_CD . ' AND YMD="' . $YMD . '" AND JYOKEN_SYBET_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                $data = array();
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                    array_push($data, $resultSet);
                }

                $sql2 = ' SELECT TAG_KBN,
                START_TIME,
                END_TIME,
                JININ,
                JIKAN,
                GUEST_NAME,
                ATTEND_NAME1,
                ATTEND_NAME2,
                ATTEND_NAME3,
                ALL_DAY_FLG,
                RENKEI_YMD,
                UPD_PGID,               
                UPD_TANTCD,
                UPD_YMD  FROM T_EIGYO_ANKEN WHERE JYOKEN_CD=' . $JYOKEN_CD . ' AND YMD="' . $YMD . '" AND JYOKEN_SYBET_FLG= 0';
                $this->result2 = $this->dbConnect->query($sql2);
                $resultSet2 = array();
                if ($this->result2->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result2->fetch_assoc()) {
                        $resultSet2[] = $row;
                    }
                    array_push($data, $resultSet2);
                }

                $this->dbReference->sendResponse(200, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    function setTime()
    {
        $setContent = array('start_time' => '8:00:00 AM', 'end_time' => '7:00:00 PM');
        $this->dbReference->sendResponse(200, json_encode($setContent, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    function tagPullDown()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $sql = ' SELECT KBN_NAME  FROM M_KBN WHERE KBN_CD= 05 AND DEL_FLG= 0';
            $this->result = $this->dbConnect->query($sql);

            $resultSet = array();
            if ($this->result->num_rows > 0) {
                // output data of each row
                while ($row = $this->result->fetch_assoc()) {
                    $resultSet[] = $row;
                }
            }
            $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    }

    // Net construction net preview contents
    function getNetPreviewContents()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['koji_jyucyu_id']) &&
                isset($_GET['kbn_kbn_cd']) &&
                isset($_GET['kbn_kbnmsai_cd']) &&
                isset($_GET['koji_filepath_id']) &&
                isset($_GET['koji_filepath_file_kbn_cd']) &&
                $_GET['koji_filepath_file_kbn_cd'] == '3' ||
                $_GET['koji_filepath_file_kbn_cd'] == '4' ||
                $_GET['koji_filepath_file_kbn_cd'] == '5' ||
                $_GET['kbn_kbn_cd'] == '5'
            ) {
                $kojiJyuCyuId = $_GET['koji_jyucyu_id'];
                $kbnKbnCd = $_GET['kbn_kbn_cd'];
                $kbnKbnmsaiCd = $_GET['kbn_kbnmsai_cd'];
                $kojiFilepathId = $_GET['koji_filepath_id'];
                $kojiFilePathFileKbnCd = $_GET['koji_filepath_file_kbn_cd'];

                //Get data T_KOJI
                $sql = 'SELECT JYUCYU_ID, KOJI_JININ, KOJIHOMONJIKAN, KOJIHOMONJIKAN_END, KOJI_JIKAN, SETSAKI_ADDRESS, 
                                KOJI_ITEM, SETSAKI_NAME, HOMON_TANT_NAME1, HOMON_TANT_NAME2, HOMON_TANT_NAME3, HOMON_TANT_NAME4, 
                                ADD_TANTNM, T_KOJI.ADD_YMD, T_KOJI.UPD_TANTNM, T_KOJI.UPD_YMD, SITAMIIRAISYO_FILEPATH, MEMO, COMMENT, 
                                M_KBN.KBNMSAI_NAME, T_KOJI_FILEPATH.FILEPATH 
                            FROM T_KOJI 
                            CROSS JOIN M_KBN
                            ON T_KOJI.TAG_KBN = M_KBN.KBN_CD 
                            CROSS JOIN T_KOJI_FILEPATH
                            ON T_KOJI.JYUCYU_ID = T_KOJI_FILEPATH.ID 
                            WHERE T_KOJI.JYUCYU_ID="' . $kojiJyuCyuId . '" 
                                AND M_KBN.KBN_CD="' . $kbnKbnCd . '" 
                                AND M_KBN.KBNMSAI_CD="' . $kbnKbnmsaiCd . '" 
                                AND T_KOJI_FILEPATH.ID="' . $kojiFilepathId . '" 
                                AND T_KOJI_FILEPATH.FILE_KBN_CD="' . $kojiFilePathFileKbnCd . '" 
                                AND T_KOJI.DEL_FLG="0"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // Memo registration
    function getMemoRegistration()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['tan_cal_id'])
            ) {
                $tanCalId = $_GET['tan_cal_id'];

                //Get data T_KOJI
                $sql = 'SELECT MEMO_CD, YMD, START_TIME, END_TIME, NAIYO 
                            FROM T_TBETUCALENDAR 
                            WHERE TAN_CAL_ID="' . $tanCalId . '" 
                                AND DEL_FLG="0"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // Memo update
    function postMemoUpdate()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['JYOKEN_CD']) &&
                isset($_POST['JYOKEN_SYBET_FLG']) &&
                isset($_POST['YMD'])
            ) {
                $jyokenCd = $_POST['JYOKEN_CD'];
                $jyokenSybetFlg = $_POST['JYOKEN_SYBET_FLG'];
                $ymd = $_POST['YMD'];

                //Get data T_KOJI
                $sql = 'SELECT TAN_CAL_ID, JYOKEN_CD, JYOKEN_SYBET_FLG, YMD, TAG_KBN, START_TIME, END_TIME, 
                            MEMO_CD, NAIYO, COMMENT, ALL_DAY_FLG, RENKEI_YMD, ADD_PGID, ADD_TANTCD, ADD_YMD, 
                            UPD_PGID, UPD_TANTCD, UPD_YMD 
                            FROM T_TBETUCALENDAR 
                            WHERE JYOKEN_CD="' . $jyokenCd . '" 
                                AND JYOKEN_SYBET_FLG="' . $jyokenSybetFlg . '" 
                                AND YMD="' . $ymd . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // Show holiday
    function getShowHoliday()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['TANT_CD']) &&
                isset($_GET['HOLIDAY_YEAR'])
            ) {
                $tantCd = $_GET['TANT_CD'];
                $holidayYear = $_GET['HOLIDAY_YEAR'];

                //Get data T_KOJI
                $sql = 'SELECT HOLIDAY_JAN, HOLIDAY_FEB, HOIDAY_MAR, HOIDAY_APR, HOIDAY_MAY, HOIDAY_JUN, 
                            HOIDAY_JUL, HOIDAY_AUG, HOIDAY_SEP, HOIDAY_OCT, HOIDAY_NOV, HOIDAY_DEC 
                            FROM T_TBETUCALENDAR 
                            WHERE TANT_CD="' . $tantCd . '" 
                                AND HOLIDAY_YEAR="' . $holidayYear . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // Default Page
    
    function getOnlinePreview()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['YMD'])) {
                $data_general = [];
                //【ネット下見】
                $YMD = $_GET['YMD'];
                $start_date = date("Y-m-d", strtotime('monday this week', strtotime($YMD)));
                $end_date =  date("Y-m-d", strtotime('sunday this week', strtotime($YMD)));
                $sql = ' SELECT T_KOJI.JYUCYU_ID,
                T_KOJI.SITAMIHOMONJIKAN,
                T_KOJI.SITAMIHOMONJIKAN_END,
                T_KOJI.SETSAKI_ADDRESS,
                T_KOJI.KOJI_ITEM,
                T_KOJI.SETSAKI_NAME,
                T_KOJI.SITAMIAPO_KBN,
                M_KBN.KBNMSAI_NAME,
                M_TANT.TANT_CD,
                M_TANT.TANT_NAME,
                T_KOJI.SITAMI_YMD,
                M_TANT.SYOZOKU_CD
                FROM T_KOJI 
                LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBNMSAI_CD
                LEFT JOIN M_TANT ON M_TANT.TANT_CD=T_KOJI.HOMON_TANT_CD4
                WHERE T_KOJI.SITAMI_YMD>="' . $start_date . '" 
                    AND T_KOJI.SITAMI_YMD<="' . $end_date . '" 
                    AND M_TANT.TANT_CD IS NOT NULL 
                    AND M_KBN.KBN_CD="05"
                ORDER BY M_TANT.TANT_CD ASC,T_KOJI.SITAMI_YMD ASC';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $TANT_CD = $row['TANT_CD'];
                        $SITAMI_YMD = $row['SITAMI_YMD'];
                        $resultSet[$TANT_CD]['TANT_NAME'] = $row['TANT_NAME'];
                        $resultSet[$TANT_CD]['TANT_CD'] = $TANT_CD;
                        $data = array();
                        $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                        $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                        $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                        $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                        $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                        $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['TYPE'] = 1;
                        $resultSet[$TANT_CD][$SITAMI_YMD][] = $data;
                    }
                }
                //【ネット工事】
                $sql2 = ' SELECT T_KOJI.JYUCYU_ID,
                T_KOJI.KOJIHOMONJIKAN,
                T_KOJI.KOJIHOMONJIKAN_END,
                T_KOJI.SETSAKI_ADDRESS,
                T_KOJI.KOJI_ITEM,
                T_KOJI.SETSAKI_NAME,
                T_KOJI.KOJIAPO_KBN,            
                M_KBN.KBNMSAI_NAME,
                M_TANT1.TANT_CD AS TANT_CD1,
                M_TANT1.TANT_NAME AS TANT_NAME1,
                M_TANT2.TANT_CD AS TANT_CD2,
                M_TANT2.TANT_NAME AS TANT_NAME2,
                M_TANT3.TANT_CD AS TANT_CD3,
                M_TANT3.TANT_NAME AS TANT_NAME3,
                T_KOJI.KOJI_YMD
                FROM T_KOJI 
                LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBNMSAI_CD
                LEFT JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                LEFT JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                LEFT JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                WHERE T_KOJI.KOJI_YMD>="' . $start_date . '"  
                    AND T_KOJI.KOJI_YMD<="' . $end_date . '" 
                    AND (T_KOJI.HOMON_TANT_CD1 IS NOT NULL OR T_KOJI.HOMON_TANT_CD2 IS NOT NULL OR T_KOJI.HOMON_TANT_CD3 IS NOT NULL)                     
                    AND (M_TANT1.TANT_CD IS NOT NULL OR M_TANT2.TANT_CD IS NOT NULL OR M_TANT3.TANT_CD IS NOT NULL  )
                    AND M_KBN.KBN_CD="05"
                ORDER BY TANT_CD1 ASC, TANT_CD2 ASC , TANT_CD3 ASC ,T_KOJI.SITAMI_YMD ASC';
                $this->result = $this->dbConnect->query($sql2);
                //$resultSet2 = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $TANT_CD1 = $row['TANT_CD1'];
                        $TANT_CD2 = $row['TANT_CD2'];
                        $TANT_CD3 = $row['TANT_CD3'];
                        if (!empty($TANT_CD1)) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['TYPE'] = 2;                            
                            if(empty($resultSet[$TANT_CD1])){
                                $resultSet[$TANT_CD1]['TANT_NAME'] = $row['TANT_NAME1']; 
                                $resultSet[$TANT_CD1]['TANT_CD'] = $TANT_CD1; 
                            }
                            $resultSet[$TANT_CD1][$KOJI_YMD][] = $data;
                        }

                        if (!empty($TANT_CD2)) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['TYPE'] = 2;
                            if(empty($resultSet[$TANT_CD2])){
                                $resultSet[$TANT_CD2]['TANT_NAME'] = $row['TANT_NAME2']; 
                                $resultSet[$TANT_CD2]['TANT_CD'] = $TANT_CD2; 
                            }
                            $resultSet[$TANT_CD2][$KOJI_YMD][] = $data;
                        }

                        if (!empty($TANT_CD3)) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            //$resultSet2[$TANT_CD3]['TANT_NAME'] = $row['TANT_NAME3'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['TYPE'] = 2;
                            if(empty($resultSet[$TANT_CD3])){
                                $resultSet[$TANT_CD3]['TANT_NAME'] = $row['TANT_NAME3']; 
                                $resultSet[$TANT_CD3]['TANT_CD'] = $TANT_CD3; 
                            }
                            $resultSet[$TANT_CD3][$KOJI_YMD][] = $data;
                        }
                    }
                }  
                $data_final =array();
                foreach($resultSet as $key => $value){
                    $data_final[] = $value;
                }          
                $this->dbReference->sendResponse(200, json_encode($data_final, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // function getNetConstruction()
    // {
    //     $this->dbReference = new systemConfig();
    //     $this->dbConnect = $this->dbReference->connectDB();
    //     if ($this->dbConnect == NULL) {
    //         $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
    //     } else {
    //         if ( isset($_GET['SYOZOKU_CD'])) {

    //             $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    //         } else {
    //             $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
    //         }
    //     }
    // }
    //【協力店舗/営業所名プルダウン】
    function getCollaboratingStore()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $sql = ' SELECT KOJIGYOSYA_NAME  FROM M_GYOSYA';
            $this->result = $this->dbConnect->query($sql);

            $resultSet = array();
            if ($this->result->num_rows > 0) {
                // output data of each row
                while ($row = $this->result->fetch_assoc()) {
                    $resultSet[] = $row;
                }
            }
            $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    }
    //【協力店舗/営業所名】
    function getCooperatingStore()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['TANT_CD']) && isset($_GET['KOJIGYOSYA_CD'])
            ) {
                $TANT_CD = $_GET['TANT_CD'];
                $KOJIGYOSYA_CD = $_GET['KOJIGYOSYA_CD'];
                $sql = 'SELECT KOJIGYOSYA_NAME FROM M_TANT LEFT JOIN M_GYOSYA ON M_TANT.ADD_TANTCD=M_GYOSYA.ADD_TANTCD WHERE M_TANT.TANT_CD=' . $TANT_CD . ' AND KOJIGYOSYA_CD= ' . $KOJIGYOSYA_CD . '';
                // echo $sql; die;
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }
    //【グループ週の場合、左列の担当者名】
    function getInTheCaseOfGroupWeek()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['SYOZOKU_CD'])
            ) {
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];
                $sql = 'SELECT TANT_NAME FROM M_TANT WHERE SYOZOKU_CD=' . $SYOZOKU_CD . '';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    //【営業工事・営業下見（営業所欄）】
    function getSaleConstructionPreviewSalesOffice()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['YMD']) &&
                isset($_GET['JYOKEN_CD'])
            ) {
                $eigyoAnkenYmd = $_GET['YMD'];
                $eigyoAnkenJyokenCd = $_GET['JYOKEN_CD'];
                $sql = ' SELECT START_TIME, END_TIME, GUEST_NAME, KBNMSAI_NAME, YOBIKOMOKU1
                        FROM T_EIGYO_ANKEN 
                        CROSS JOIN M_KBN ON T_EIGYO_ANKEN.TAG_KBN=M_KBN.KBNMSAI_CD 
                        WHERE T_EIGYO_ANKEN.YMD="' . $eigyoAnkenYmd . '" 
                            AND T_EIGYO_ANKEN.JYOKEN_CD="' . $eigyoAnkenJyokenCd . '" 
                            AND T_EIGYO_ANKEN.JYOKEN_SYBET_FLG="1" 
                            AND M_KBN.KBN_CD="10"
                            ';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // 【営業工事・営業下見（担当者欄）】
    function getSaleConstructionPreviewPersonInCharge()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['YMD']) &&
                isset($_GET['JYOKEN_CD'])
            ) {
                $eigyoAnkenYmd = $_GET['YMD'];
                $eigyoAnkenJyokenCd = $_GET['JYOKEN_CD'];
                $sql = ' SELECT START_TIME, END_TIME, GUEST_NAME, KBNMSAI_NAME, YOBIKOMOKU1
                        FROM T_EIGYO_ANKEN 
                        CROSS JOIN M_KBN ON T_EIGYO_ANKEN.TAG_KBN=M_KBN.KBNMSAI_CD 
                        WHERE T_EIGYO_ANKEN.YMD="' . $eigyoAnkenYmd . '" 
                            AND T_EIGYO_ANKEN.JYOKEN_CD="' . $eigyoAnkenJyokenCd . '" 
                            AND T_EIGYO_ANKEN.JYOKEN_SYBET_FLG="1" 
                            AND M_KBN.KBN_CD="10" 
                            ';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    //【メモ（担当者欄）】
    function getMemoPersonInCharge()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['YMD']) &&
                isset($_GET['JYOKEN_CD'])
            ) {
                $tbetucalendarYmd = $_GET['YMD'];
                $tbetucalendarJyokenCd = $_GET['JYOKEN_CD'];
                $sql = ' SELECT START_TIME, END_TIME, NAIYO, KBNMSAI_NAME, YOBIKOMOKU1
                        FROM T_TBETUCALENDAR 
                        CROSS JOIN M_KBN ON T_TBETUCALENDAR.TAG_KBN=M_KBN.KBNMSAI_CD 
                        WHERE T_TBETUCALENDAR.YMD="' . $tbetucalendarYmd . '" 
                            AND T_TBETUCALENDAR.JYOKEN_CD="' . $tbetucalendarJyokenCd . '" 
                            AND T_TBETUCALENDAR.JYOKEN_SYBET_FLG="0" 
                            AND M_KBN.KBN_CD="6" 
                            ';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // 【メモ（営業所欄）】
    function getMemoBusinessOffice()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['YMD']) &&
                isset($_GET['JYOKEN_CD'])
            ) {
                $tbetucalendarYmd = $_GET['YMD'];
                $tbetucalendarJyokenCd = $_GET['JYOKEN_CD'];
                $sql = ' SELECT START_TIME, END_TIME, NAIYO, KBNMSAI_NAME, YOBIKOMOKU1
                        FROM T_TBETUCALENDAR 
                        CROSS JOIN M_KBN ON T_TBETUCALENDAR.TAG_KBN=M_KBN.KBNMSAI_CD 
                        WHERE T_TBETUCALENDAR.YMD="' . $tbetucalendarYmd . '" 
                            AND T_TBETUCALENDAR.JYOKEN_CD="' . $tbetucalendarJyokenCd . '" 
                            AND T_TBETUCALENDAR.JYOKEN_SYBET_FLG="1" 
                            AND M_KBN.KBN_CD="6" 
                            ';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    //【日予実】
    function getNikkiMinoru()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['KOJI_YMD']) &&
                (
                    isset($_GET['HOMON_TANT_CD1']) ||
                    isset($_GET['HOMON_TANT_CD2']) ||
                    isset($_GET['HOMON_TANT_CD3']) ||
                    isset($_GET['HOMON_TANT_CD4'])
                ) &&
                isset($_GET['TANT_CD']) &&
                isset($_GET['KBN_CD']) &&
                isset($_GET['KBNMSAI_CD'])
            ) {
                $kojiKojiYmd = $_GET['KOJI_YMD'];
                $kojiHomonTantCd1 = $_GET['HOMON_TANT_CD1'];
                $kojiHomonTantCd2 = $_GET['HOMON_TANT_CD2'];
                $kojiHomonTantCd3 = $_GET['HOMON_TANT_CD3'];
                $kojiHomonTantCd4 = $_GET['HOMON_TANT_CD4'];
                $tantTantCd = $_GET['TANT_CD'];
                $kbnKbnCd = $_GET['KBN_CD'];
                $kbnKbnmsaiCd = $_GET['KBNMSAI_CD'];
                $sql = ' SELECT T_KOJI.KOJI_ITAKUHI, M_TANT.DAYLY_SALES, M_KBN.KBN_CD, M_KBN.KBNMSAI_CD
                        FROM T_KOJI 
                        CROSS JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD 
                        CROSS JOIN M_TANT ON M_KBN.ADD_TANTCD=M_TANT.TANT_CD 
                        WHERE T_KOJI.KOJI_YMD="' . $kojiKojiYmd . '" 
                            AND (T_KOJI.HOMON_TANT_CD1 IS NOT NULL 
                            OR T_KOJI.HOMON_TANT_CD2 IS NOT NULL 
                            OR T_KOJI.HOMON_TANT_CD3 IS NOT NULL 
                            OR T_KOJI.HOMON_TANT_CD4 IS NOT NULL) 
                            AND M_TANT.TANT_CD="' . $tantTantCd . '" 
                            AND M_KBN.KBN_CD="16" 
                            AND M_KBN.KBNMSAI_CD="1" 
                            ';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }


    //【計予実】
    function getEstimatedActual()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['KOJI_YMD']) &&
                (
                    isset($_GET['HOMON_TANT_CD1']) ||
                    isset($_GET['HOMON_TANT_CD2']) ||
                    isset($_GET['HOMON_TANT_CD3']) ||
                    isset($_GET['HOMON_TANT_CD4'])
                ) &&
                isset($_GET['TANT_CD'])
            ) {
                $kojiKojiYmd = $_GET['KOJI_YMD'];
                $tantTantCd = $_GET['TANT_CD'];
                $sql = ' SELECT T_KOJI.KOJI_ITAKUHI, M_TANT.MONTHLY_SALES, M_KBN.KBN_CD, M_KBN.KBNMSAI_CD
                        FROM T_KOJI 
                        CROSS JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD 
                        CROSS JOIN M_TANT ON M_KBN.ADD_TANTCD=M_TANT.TANT_CD 
                        WHERE T_KOJI.KOJI_YMD="' . $kojiKojiYmd . '" 
                            AND (T_KOJI.HOMON_TANT_CD1 IS NOT NULL 
                            OR T_KOJI.HOMON_TANT_CD2 IS NOT NULL 
                            OR T_KOJI.HOMON_TANT_CD3 IS NOT NULL 
                            OR T_KOJI.HOMON_TANT_CD4 IS NOT NULL) 
                            AND M_TANT.TANT_CD="' . $tantTantCd . '" 
                            AND M_KBN.KBN_CD="16" 
                            AND M_KBN.KBNMSAI_CD="1" 
                            ';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    function getNewComment()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['ADD_TANTCD'])
            ) {
                $ADD_TANTCD = $_GET['ADD_TANTCD'];
                $sql = 'SELECT COMMENT FROM T_KOJI WHERE ADD_TANTCD=' . $ADD_TANTCD . ' AND COMMENT IS NOT NULL AND READ_FLG IS NULL';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    function getNoticeInStock()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['SOKO_CD'])
            ) {
                $SOKO_CD = $_GET['SOKO_CD'];
                $sql = 'SELECT COUNT(*) FROM T_NYUKOYOTEI WHERE SOKO_CD=' . $SOKO_CD . ' AND NYUKO_YOTEI_YMD> CURRENT_DATE() AND NYUKO_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    function getNoticeCompletionReport()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['ADD_TANTCD'])
            ) {
                $ADD_TANTCD = $_GET['ADD_TANTCD'];
                $sql = 'SELECT COUNT(*) FROM T_KOJI WHERE ADD_TANTCD=' . $ADD_TANTCD . ' AND KOJI_YMD> CURRENT_DATE() AND REPORT_FLG= 02';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    function getNoticePreliminaryInspection()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['ADD_TANTCD'])
            ) {
                $ADD_TANTCD = $_GET['ADD_TANTCD'];
                $sql = 'SELECT COUNT(*) FROM T_KOJI WHERE ADD_TANTCD=' . $ADD_TANTCD . ' AND SITAMI_YMD> CURRENT_DATE() AND REPORT_FLG= 01';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    function getNoticeMaterialsOrderApplication()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['SYOZOKU_CD'])
            ) {
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];
                $sql = 'SELECT COUNT(*) FROM T_BUZAIHACYU WHERE SYOZOKU_CD=' . $SYOZOKU_CD . ' AND HACYU_OKFLG= 01';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    function getPressTheNewCommentAlreadyReadButton()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['JYUCYU_ID'])
            ) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $sql = 'SELECT READ_FLG FROM T_KOJI WHERE JYUCYU_ID=' . $JYUCYU_ID . '';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }
}
