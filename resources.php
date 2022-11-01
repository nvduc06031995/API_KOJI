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
    echo '1';
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
                $sql = 'SELECT KOJI_KEKKA,
                SKJ_RENKEI_YMD,
                UPD_PGID,
                UPD_TANTCD,
                UPD_YMD FROM T_KOJI WHERE JYUCYU_ID= ' . $JYUCYU_ID . '';
                $this->result = $this->dbConnect->query($sql);
                $data = array();
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }

                    foreach($resultSet as $key => $value){
                        $this->changeKey = new systemEditor();
                        $oldKeyArr = [
                            'KOJI_KEKKA',
                            'SKJ_RENKEI_YMD',
                            'UPD_PGID',
                            'UPD_TANTCD',
                            'UPD_YMD',                         
                        ];
                        $newKeyArr = [
                            'kekka',
                            'renkei_ymd',
                            'update_pgid',
                            'update_tantcd',
                            'update_ymd',
                        ];
                        $resultSet[$key] = $this->changeKey->change_key($value, $oldKeyArr, $newKeyArr);
                        $resultSet[$key]['message'] = 'OKを設定';
                    }

                    array_push($data , $resultSet);
                }

                $sql2 = 'SELECT * FROM T_KOJI_FILEPATH WHERE ID= ' . $JYUCYU_ID . '';
                $this->result2 = $this->dbConnect->query($sql2);
                $resultSet2 = array();
                if ($this->result2->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result2->fetch_assoc()) {
                        $resultSet2[] = $row;
                    }
                    foreach($resultSet2 as $key => $value){
                        $this->changeKey = new systemEditor();
                        $oldKeyArr = [
                            'FILEPATH_ID',
                            'ID',
                            'FILEPATH',
                            'FILE_KBN_CD',
                            'ADD_PGID',
                            'ADD_TANTCD',
                            'ADD_YMD',
                            'UPD_PGID',
                            'UPD_TANTCD',
                            'UPD_YMD',                           
                        ];
                        $newKeyArr = [
                            'filepath_id',
                            'id',
                            'filepath',
                            'kbn_cd',
                            'add_pgid',
                            'add_tantcd',
                            'add_ymd',
                            'update_pgid',
                            'update_tantcd',
                            'update_ymd',
                        ];
                        $resultSet2[$key] = $this->changeKey->change_key($value, $oldKeyArr, $newKeyArr);
                        $resultSet2[$key]['message'] = 'OKを設定';
                    }

                    array_push($data , $resultSet2);
                }
                
                $this->dbReference->sendResponse(200, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }
}
