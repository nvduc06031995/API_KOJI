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

    function getConstructionList()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['SITAMI_YMD']) && isset($_GET['KOJI_YMD'])) {
                $SITAMI_YMD = $_GET['SITAMI_YMD'];
                $KOJI_YMD = $_GET['KOJI_YMD'];
                $sql = 'SELECT SITAMIHOMONJIKAN,
                HOMON_SBT,
                JYUCYU_ID,
                SITAMI_JININ,
                SITAMI_JIKAN,
                KOJI_ITEM,
                SETSAKI_ADDRESS,
                SETSAKI_NAME FROM T_KOJI WHERE SITAMI_YMD="' . $SITAMI_YMD . '" AND KOJI_YMD="' . $KOJI_YMD . '" AND SYUYAKU_JYUCYU_ID IS NOT NULL AND DEL_FLG IS NULL';
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

    function getConstructionList2()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['KOJI_YMD']) && isset($_GET['user_id'])) {
                $KOJI_YMD = $_GET['KOJI_YMD'];
                $user_id = $_GET['user_id'];
                $sql = 'SELECT KOJIHOMONJIKAN,
                HOMON_SBT,
                JYUCYU_ID,
                KOJI_JININ,
                KOJI_JIKAN,
                KOJI_ITEM,
                SETSAKI_ADDRESS,
                SETSAKI_NAME FROM T_KOJI WHERE KOJI_YMD="' . $KOJI_YMD . '" AND (HOMON_TANT_CD1=' . $user_id . ' OR HOMON_TANT_CD2=' . $user_id . ' OR HOMON_TANT_CD3=' . $user_id . ') AND SYUYAKU_JYUCYU_ID IS NOT NULL AND DEL_FLG IS NULL';
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

    function getPhotoConfirm()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            // $_POST['koji_filepath_id']: required
            // $_POST['koji_filepath_file_kbn_cd']: required
            if (
                isset($_POST['koji_filepath_id']) &&
                isset($_POST['koji_filepath_file_kbn_cd']) &&
                $_POST['koji_filepath_file_kbn_cd'] == '5'
            ) {

                $kojiFilepathId = $_POST['koji_filepath_id'];
                $kojiFilepathFileKbnCd = $_POST['koji_filepath_file_kbn_cd'];

                $sql = ' SELECT FILEPATH FROM T_KOJI_FILEPATH WHERE FILEPATH_ID="' . $kojiFilepathId . '" AND FILE_KBN_CD="' . $kojiFilepathFileKbnCd . '"';
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
}
