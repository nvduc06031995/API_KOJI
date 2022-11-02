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
                YOBIKOMOKU5 FROM T_KOJI LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD WHERE HOJIN_FLG= 1 AND KBN_CD= 12 AND KBN_BIKO="'. $KBN_BIKO .'" AND M_KBN.DEL_FLG IS NULL';                
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
}
