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

    function postLogin()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_POST['LOGIN_ID']) && isset($_POST['PASSWORD'])) {
                $LOGIN_ID = $_POST['LOGIN_ID'];
                $PASSWORD = $_POST['PASSWORD'];
                $sql = 'SELECT * FROM M_TANT WHERE TANT_CD="' . $LOGIN_ID . '" AND PASSWORD="' . $PASSWORD . '" AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $row['STATUS'] = 'success';
                        $resultSet = $row;
                    }
                } else {
                    $this->dbReference->sendResponse(401, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(401) . '}');
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    /* * * * * * * * * *
    * * * * API仕様_工事報告 START * * * * 
    * * * * * * * * */
    /* ==================================================================== 工事一覧 */
    function getConstructionList()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['YMD']) && isset($_GET['LOGIN_ID'])) {
                $YMD = $_GET['YMD'];
                $LOGIN_ID = $_GET['LOGIN_ID'];
                $sql = 'SELECT SITAMIHOMONJIKAN,
                HOMON_SBT,
                KOJI_ST,
                JYUCYU_ID,
                SITAMI_JININ,
                SITAMI_JIKAN,
                KOJI_ITEM,
                SETSAKI_ADDRESS,
                SITAMI_YMD,            
                SETSAKI_NAME FROM T_KOJI WHERE SITAMI_YMD="' . $YMD . '" 
                AND HOMON_TANT_CD4="' . $LOGIN_ID . '"
                AND SYUYAKU_JYUCYU_ID IS NULL AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                        $data['HOMON_SBT'] = $row['HOMON_SBT'];
                        $data['KOJI_ST'] = $row['KOJI_ST'];
                        $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                        $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                        $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                        $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                        $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                        $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                        $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                        $data['LOGIN_ID'] = $LOGIN_ID;
                        $resultSet[] = $data;
                    }
                }

                $sql2 = 'SELECT KOJIHOMONJIKAN,
                HOMON_SBT,
                KOJI_ST,
                JYUCYU_ID,
                KOJI_JININ,
                KOJI_JIKAN,
                KOJI_ITEM,
                SETSAKI_ADDRESS,
                KOJI_YMD,
                SETSAKI_NAME FROM T_KOJI WHERE KOJI_YMD="' . $YMD . '"
                AND (HOMON_TANT_CD1="' . $LOGIN_ID . '" OR HOMON_TANT_CD2="' . $LOGIN_ID . '" OR HOMON_TANT_CD3="' . $LOGIN_ID . '")
                AND SYUYAKU_JYUCYU_ID IS NULL AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql2);
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                        $data['HOMON_SBT'] = $row['HOMON_SBT'];
                        $data['KOJI_ST'] = $row['KOJI_ST'];
                        $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                        $data['KOJI_JININ'] = $row['KOJI_JININ'];
                        $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                        $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                        $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                        $data['KOJI_YMD'] = $row['KOJI_YMD'];
                        $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                        $data['LOGIN_ID'] = $LOGIN_ID;
                        $resultSet[] = $data;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    /* ==================================================================== 投稿数更新 */
    function checkCount()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['YMD']) && isset($_GET['JYUCYU_ID']) && isset($_GET['SETSAKI_ADDRESS'])) {
                $YMD = $_GET['YMD'];
                $SETSAKI_ADDRESS = $_GET['SETSAKI_ADDRESS'];
                $JYUCYU_ID = $_GET['JYUCYU_ID'];

                $count_jyucyu = null;
                $count_syuyaku_jyucyu = null;

                // Check exists
                $sql = 'SELECT COUNT(*) 
                    FROM T_KOJI 
                    WHERE JYUCYU_ID="' . $JYUCYU_ID . '"
                    AND KOJI_ST="02"
                    AND DEL_FLG= 0 ';
                $this->result = $this->dbConnect->query($sql);

                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $count_jyucyu = $row["COUNT(*)"];
                    }
                }

                if ($count_jyucyu > 0) {
                    $sql = 'SELECT COUNT(*) 
                    FROM T_KOJI 
                    WHERE SETSAKI_ADDRESS="' . $SETSAKI_ADDRESS . '"
                    AND KOJI_YMD="' . $YMD . '"
                    AND KOJI_ST="02"
                    AND DEL_FLG= 0 ';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $count_syuyaku_jyucyu = $row["COUNT(*)"];
                        }
                    }
                }

                if ($count_syuyaku_jyucyu > 1) {
                    $this->dbReference->sendResponse(200, json_encode($count_syuyaku_jyucyu, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
            } else {
                $this->dbReference->sendResponse(507, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(507) . '}');
            }
        }
    }

    function postCount()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_POST['LOGIN_ID']) && isset($_POST['YMD']) && isset($_POST['JYUCYU_ID']) && isset($_POST['SETSAKI_ADDRESS'])) {
                $LOGIN_ID = $_POST['LOGIN_ID'];
                $YMD = $_POST['YMD'];
                $KOJI_TIRASISU = 1;
                $RENKEI_YMD = 'NULL';
                $UPD_PGID = "KOJ1110F";
                $sql = 'UPDATE T_TIRASI SET  YMD="' . $YMD . '", 
                    RENKEI_YMD=' . $RENKEI_YMD . ', 
                    KOJI_TIRASISU=' . $KOJI_TIRASISU . ', 
                    UPD_PGID="' . $UPD_PGID . '", 
                    UPD_TANTCD= "' . $LOGIN_ID . '", 
                    UPD_YMD=' . date('Y-m-d H:i:s') . ' 
                    WHERE TANT_CD="' . $LOGIN_ID . '" 
                    AND YMD="' . $YMD . '"';
                $this->result = $this->dbConnect->query($sql);

                $SETSAKI_ADDRESS = $_POST['SETSAKI_ADDRESS'];
                $JYUCYU_ID = $_POST['JYUCYU_ID'];
                $RENKEI_YMD = 'NULL';
                $UPD_PGID2 = "KOJ1120F";
                $sql = 'UPDATE T_KOJI SET  SYUYAKU_JYUCYU_ID="' . $JYUCYU_ID . '",                    
                    UPD_PGID="' . $UPD_PGID2 . '", 
                    UPD_TANTCD= "' . $LOGIN_ID . '", 
                    UPD_YMD="' . date('Y-m-d H:i:s') . '"
                    WHERE SETSAKI_ADDRESS="' . $SETSAKI_ADDRESS . '"
                    AND KOJI_YMD="' . $YMD . '"
                    AND DEL_FLG= 0 
                    AND KOJI_ST="02" ';
                $this->result = $this->dbConnect->query($sql);

                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(507, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(507) . '}');
            }
        }
    }

    /* ==================================================================== 依頼書 */
    function getRequestForm()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['JYUCYU_ID']) && isset($_GET['HOMON_SBT']) && isset($_GET['SINGLE_SUMMARIZE'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $HOMON_SBT = $_GET['HOMON_SBT'];
                $SINGLE_SUMMARIZE = $_GET['SINGLE_SUMMARIZE'];

                if ($SINGLE_SUMMARIZE == 1) {
                    if ($HOMON_SBT == "01") {
                        $sql = 'SELECT T_KOJI.SITAMIIRAISYO_FILEPATH,
                        T_KOJI.JYUCYU_ID,
                        T_KOJI.HOMON_SBT,
                        T_KOJI.KOJI_ST,
                        T_KOJI_FILEPATH.FILEPATH_ID,
                        T_KOJI_FILEPATH.FILEPATH 
                        FROM T_KOJI 
                        LEFT JOIN T_KOJI_FILEPATH ON T_KOJI.JYUCYU_ID=T_KOJI_FILEPATH.ID 
                        WHERE JYUCYU_ID= ' . $JYUCYU_ID . '
                        AND (T_KOJI_FILEPATH.FILE_KBN_CD="03" OR T_KOJI_FILEPATH.FILE_KBN_CD="04")                
                        AND T_KOJI.DEL_FLG=0 
                        AND T_KOJI_FILEPATH.DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sql);
                        $resultSet = array();
                        if ($this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $row['SINGLE_SUMMARIZE'] = 1;
                                $resultSet[] = $row;
                            }
                        }
                    } else if ($HOMON_SBT == "02") {
                        $sql = 'SELECT T_KOJI.KOJIIRAISYO_FILEPATH,
                        T_KOJI.JYUCYU_ID,
                        T_KOJI.HOMON_SBT,
                        T_KOJI.KOJI_ST,
                        T_KOJI_FILEPATH.FILEPATH_ID,
                        T_KOJI_FILEPATH.FILEPATH 
                        FROM T_KOJI 
                        LEFT JOIN T_KOJI_FILEPATH ON T_KOJI.JYUCYU_ID=T_KOJI_FILEPATH.ID 
                        WHERE JYUCYU_ID= ' . $JYUCYU_ID . '
                        AND (T_KOJI_FILEPATH.FILE_KBN_CD="03" OR T_KOJI_FILEPATH.FILE_KBN_CD="04")                
                        AND T_KOJI.DEL_FLG=0 
                        AND T_KOJI_FILEPATH.DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sql);
                        $resultSet = array();
                        if ($this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $row['SINGLE_SUMMARIZE'] = 1;
                                $resultSet[] = $row;
                            }
                        }
                    } else {
                        $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
                    }
                } else if ($SINGLE_SUMMARIZE == 2) {
                    if ($HOMON_SBT == "01") {
                        $sql = 'SELECT T_KOJI.SITAMIIRAISYO_FILEPATH,
                        T_KOJI_FILEPATH.FILEPATH,
                        T_KOJI.JYUCYU_ID,
                        T_KOJI.HOMON_SBT,
                        T_KOJI.KOJI_ST,
                        T_KOJI_FILEPATH.FILEPATH_ID
                        FROM T_KOJI LEFT JOIN T_KOJI_FILEPATH ON T_KOJI.JYUCYU_ID=T_KOJI_FILEPATH.ID 
                        WHERE SYUYAKU_JYUCYU_ID= "' . $JYUCYU_ID . '"
                        AND T_KOJI.HOMON_SBT="01"
                        AND T_KOJI.DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sql);
                        $resultSet = array();
                        if ($this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $row['SINGLE_SUMMARIZE'] = 2;
                                $resultSet[] = $row;
                            }
                        }
                    } else if ($HOMON_SBT == "02") {
                        $sql = 'SELECT T_KOJI.KOJIIRAISYO_FILEPATH,
                        T_KOJI_FILEPATH.FILEPATH,
                        T_KOJI.JYUCYU_ID,
                        T_KOJI.HOMON_SBT,
                        T_KOJI.KOJI_ST,
                        T_KOJI_FILEPATH.FILEPATH_ID
                        FROM T_KOJI LEFT JOIN T_KOJI_FILEPATH ON T_KOJI.JYUCYU_ID=T_KOJI_FILEPATH.ID 
                        WHERE SYUYAKU_JYUCYU_ID= "' . $JYUCYU_ID . '"
                        AND T_KOJI.HOMON_SBT="02"
                        AND T_KOJI.DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sql);
                        $resultSet = array();
                        if ($this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $row['SINGLE_SUMMARIZE'] = 2;
                                $resultSet[] = $row;
                            }
                        }
                    } else {
                        $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
                    }
                } else {
                    $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    /* ==================================================================== 写真確認 */
    function getPhotoConfirm()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['JYUCYU_ID']) && isset($_GET['SINGLE_SUMMARIZE'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $SINGLE_SUMMARIZE = $_GET['SINGLE_SUMMARIZE'];
                if ($SINGLE_SUMMARIZE == 1) {
                    $sql = ' SELECT FILEPATH,
                    ID, FILEPATH_ID
                    FROM T_KOJI_FILEPATH
                    WHERE ID="' . $JYUCYU_ID . '" 
                    AND FILE_KBN_CD="05"';
                    $this->result = $this->dbConnect->query($sql);
                    $resultSet = array();
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $row['SINGLE_SUMMARIZE'] = 1;
                            $resultSet[] = $row;
                        }
                    }
                } else if ($SINGLE_SUMMARIZE == 2) {
                    $sql = 'SELECT T_KOJI_FILEPATH.FILEPATH,
                    T_KOJI.JYUCYU_ID, 
                    T_KOJI.SYUYAKU_JYUCYU_ID
                    FROM T_KOJI_FILEPATH LEFT JOIN T_KOJI ON T_KOJI_FILEPATH.ID=T_KOJI.JYUCYU_ID
                    WHERE SYUYAKU_JYUCYU_ID= "' . $JYUCYU_ID . '"
                    AND FILE_KBN_CD="05"';
                    $this->result = $this->dbConnect->query($sql);
                    $resultSet = array();
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $row['SINGLE_SUMMARIZE'] = 2;
                            $resultSet[] = $row;
                        }
                    }
                } else {
                    $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }


    /* ==================================================================== 写真提出 */
    function getPhotoSubmission()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['JYUCYU_ID']) && isset($_GET['KOJI_ST'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $KOJI_ST = $_GET['KOJI_ST'];
                if ($KOJI_ST == "03") {
                    $sql = 'SELECT FILEPATH,
                    FILEPATH_ID, 
                    ID
                    FROM T_KOJI_FILEPATH
                    WHERE ID= "' . $JYUCYU_ID . '" 
                    AND FILE_KBN_CD="10" 
                    AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);
                    $resultSet = array();
                    if ($this->result->num_rows > 0) {
                        // output data of each row                    
                        while ($row = $this->result->fetch_assoc()) {
                            $row['KOJI_ST'] = $KOJI_ST;
                            $resultSet[] = $row;
                        }
                    }
                } else {
                    $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    /* ==================================================================== 写真提出-登録 */
    function uploadFileImg($file)
    {
        if (!empty($file)) {
            $path = 'img/';
            $target_file = $path . basename($file["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $uploadOk = 1;
            // Check file size                
            if ($file["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
            ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                move_uploaded_file($file["tmp_name"], $target_file);
            }
        }
        return $target_file;
    }

    function postPhotoSubmissionRegistration()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_POST['JYUCYU_ID'])) {
                $FILE_NAME = $_FILES['FILE_NAME'];
                $img_path = $this->uploadFileImg($FILE_NAME);

                $JYUCYU_ID = $_POST['JYUCYU_ID'];
                $FILE_NAME = isset($_POST['FILE_NAME']) ? $_POST['FILE_NAME'] : 'NULL';
                $FILEPATH = "img/";
                $FILE_KBN_CD = "10";
                $ADD_PGID = "KOJ1120F";
                $ADD_TANTCD = isset($_POST['LOGIN_ID']) ? $_POST['LOGIN_ID'] : 'NULL';
                $ADD_YMD = date('Y-m-d');
                $UPD_PGID = 'KOJ1120F';
                $UPD_TANTCD = isset($_POST['LOGIN_ID']) ? $_POST['LOGIN_ID'] : 'NULL';
                $UPD_YMD = date('Y-m-d');
                $PRESENT_DATE = date('Y-m-d');
                $query_max_filepath_id = 'SELECT max(FILEPATH_ID) as TANCALID_MAX
                    FROM T_KOJI_FILEPATH';
                $rs_max = $this->dbConnect->query($query_max_filepath_id);
                $num = 0;
                if ($rs_max->num_rows > 0) {
                    // output data of each row
                    while ($row = $rs_max->fetch_assoc()) {
                        $num = (int)$row['TANCALID_MAX'] + 1;
                    }
                }

                $FILEPATH_ID = sprintf('%010d', $num);
                if (!empty($img_path)) {                
                    $sql = 'INSERT INTO T_KOJI_FILEPATH (FILEPATH_ID,
                    ID,
                    FILEPATH,
                    FILE_KBN_CD,
                    ADD_PGID,
                    ADD_TANTCD,
                    ADD_YMD,
                    UPD_PGID,
                    UPD_TANTCD,
                    UPD_YMD
                    ) VALUES (
                    "' . $FILEPATH_ID . '",
                    "' . $JYUCYU_ID . '",
                    "' . $img_path .'",
                    "' . $FILE_KBN_CD . '",
                    "' . $ADD_PGID . '",
                    "' . $ADD_TANTCD . '",
                    "' . $ADD_YMD . '",
                    "' . $UPD_PGID . '",
                    "' . $UPD_TANTCD . '",
                    "' . $UPD_YMD . '")';
                    $this->result = $this->dbConnect->query($sql);

                    $sql = 'UPDATE T_KOJI SET KOJI_KEKKA="03",
                    SKJ_RENKEI_YMD="' . $PRESENT_DATE . '",
                    UPD_PGID="' . $UPD_PGID . '",
                    UPD_TANTCD="' . $UPD_TANTCD . '",
                    UPD_YMD="' . $UPD_YMD . '" 
                    WHERE JYUCYU_ID="' . $JYUCYU_ID . '"';

                    $this->result = $this->dbConnect->query($sql);
                }


                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(506, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(506) . '}');
            }
        }
    }

    /* ==================================================================== 承諾書 */
    function getWrittenConsent()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['JYUCYU_ID'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $sql = 'SELECT T_KOJI.JYUCYU_ID,
                T_KOJI.SETSAKI_NAME,
                T_KOJI.KOJI_YMD,
                T_KOJI.HOMON_TANT_NAME1,
                T_KOJI.HOMON_TANT_NAME2,
                T_KOJI.HOMON_TANT_NAME3,
                T_KOJI.HOMON_TANT_NAME4,
                T_KOJI.CO_NAME,
                T_KOJI.CO_POSTNO,
                T_KOJI.CO_ADDRESS,
                T_KOJI.KOJIGYOSYA_CD,
                M_GYOSYA.KOJIGYOSYA_NAME FROM T_KOJI 
                LEFT JOIN M_GYOSYA ON T_KOJI.KOJIGYOSYA_CD=M_GYOSYA.KOJIGYOSYA_CD 
                WHERE JYUCYU_ID= "' . $JYUCYU_ID . '"              
                AND HOJIN_FLG= 0 
                AND T_KOJI.DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $row['STATUS'] = 'NOT_REPORTED';
                        $resultSet[] = $row;
                    }
                }

                $sql = 'SELECT T_KOJI.JYUCYU_ID,
                T_KOJI.SETSAKI_NAME,
                T_KOJI.KOJI_YMD,
                M_GYOSYA.KOJIGYOSYA_NAME,
                T_KOJI.HOMON_TANT_NAME1,
                T_KOJI.HOMON_TANT_NAME2,
                T_KOJI.HOMON_TANT_NAME3,
                T_KOJI.HOMON_TANT_NAME4,
                T_KOJI.CO_NAME,
                T_KOJI.CO_POSTNO,
                T_KOJI.CO_ADDRESS,
                T_KOJIMSAI.TUIKA_SYOHIN_NAME,
                T_KOJIMSAI.TUIKA_JISYA_CD,
                T_KOJIMSAI.SURYO,
                T_KOJIMSAI.HANBAI_TANKA,
                T_KOJIMSAI.KINGAK FROM T_KOJI 
                LEFT JOIN T_KOJIMSAI ON T_KOJI.JYUCYU_ID=T_KOJIMSAI.JYUCYU_ID
                LEFT JOIN M_GYOSYA ON T_KOJI.KOJIGYOSYA_CD=M_GYOSYA.KOJIGYOSYA_CD 
                WHERE T_KOJI.JYUCYU_ID= "' . $JYUCYU_ID . '"                
                AND T_KOJI.HOJIN_FLG= 0 
                AND T_KOJI.DEL_FLG= 0 
                AND KOJIJITUIKA_FLG= 1';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $row['STATUS'] = 'REPORTED';
                        $resultSet[] = $row;
                    }
                }

                // $sql = 'SELECT SYOHIN_NAME,
                // KOJI_KAKAKU FROM M_KOJI_KAKAKU WHERE JISYA_CD= "KOJ' . $JISYA_CD . '" ';
                // $this->result = $this->dbConnect->query($sql);
                // $resultSet = array();
                // if ($this->result->num_rows > 0) {
                //     // output data of each row                    
                //     while ($row = $this->result->fetch_assoc()) {
                //         $resultSet[] = $row;
                //     }
                // }
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

    // 工事報告
    // Construction Report
    //----未報告の場合
    function getConstructionReport()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['JYUCYU_ID']) &&
                isset($_GET['KOJI_ST']) &&
                isset($_GET['SINGLE_SUMMARIZE'])
            ) {
                $jyucyuId = $_GET['JYUCYU_ID'];
                $kojiSt = $_GET['KOJI_ST'];

                $resultSet = array();
                if ($kojiSt == "01" || $kojiSt == "02") {
                    if ($_GET['SINGLE_SUMMARIZE'] == 1) {
                        $sqlNotReported = 'SELECT MAKER_CD, HINBAN 
                        FROM T_KOJIMSAI 
                        WHERE JYUCYU_ID="' . $jyucyuId . '" 
                            AND KOJIJITUIKA_FLG<>"0" 
                            AND DEL_FLG="0"';
                        $this->result = $this->dbConnect->query($sqlNotReported);

                        if ($this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $resultSet['constructionNotReport']['SINGLE'][] = $row;
                            }
                        }
                    }
                    if ($_GET['SINGLE_SUMMARIZE'] == 2) {
                        if (isset($_GET['SYUYAKU_JYUCYU_ID'])) {
                            $sqlGetSyuyakuKoji = 'SELECT JYUCYU_ID 
                            FROM T_KOJI 
                            WHERE SYUYAKU_JYUCYU_ID="' . $_GET['SYUYAKU_JYUCYU_ID'] . '" 
                                AND DEL_FLG="0"';
                            $this->result = $this->dbConnect->query($sqlGetSyuyakuKoji);

                            $listJyucyuIdKoji = array();
                            if ($this->result->num_rows > 0) {
                                // output data of each row
                                while ($row = $this->result->fetch_assoc()) {
                                    $listJyucyuIdKoji[] = $row;
                                }
                            }

                            foreach ($listJyucyuIdKoji as $value) {
                                $sqlNotReportedSummarize = 'SELECT MAKER_CD, HINBAN 
                                    FROM T_KOJIMSAI 
                                    WHERE JYUCYU_ID="' . $value['JYUCYU_ID'] . '" 
                                        AND KOJIJITUIKA_FLG<>"0" 
                                        AND DEL_FLG="0"';
                                $this->result = $this->dbConnect->query($sqlNotReportedSummarize);
                                if ($this->result->num_rows > 0) {
                                    // output data of each row
                                    while ($row = $this->result->fetch_assoc()) {
                                        $resultSet['constructionNotReport']['SUMMARIZE'][] = $row;
                                    }
                                }
                            }
                        } else {
                            $this->dbReference->sendResponse(404, '{"error_message": SYUYAKU_JYUCYU_ID required }');
                            die;
                        }
                    }
                } elseif ($kojiSt == "03") {
                    if ($_GET['SINGLE_SUMMARIZE'] == 1) {
                        $sqlReported = 'SELECT MAKER_CD, HINBAN, KISETU_MAKER_CD, KISETU_HINBAN, BEF_SEKO_PHOTO_FILEPATH, AFT_SEKO_PHOTO_FILEPATH, OTHER_PHOTO_FOLDERPATH
                            FROM T_KOJIMSAI 
                            WHERE JYUCYU_ID="' . $jyucyuId . '" 
                            AND KOJIJITUIKA_FLG<>"0" 
                            AND DEL_FLG="0"';
                        $this->result = $this->dbConnect->query($sqlReported);

                        if ($this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $resultSet['constructionReport']['SINGLE'][] = $row;
                            }
                        }
                    }
                    if ($_GET['SINGLE_SUMMARIZE'] == 2) {
                        if (isset($_GET['SYUYAKU_JYUCYU_ID'])) {
                            $sqlGetSyuyakuKoji = 'SELECT JYUCYU_ID 
                            FROM T_KOJI 
                            WHERE SYUYAKU_JYUCYU_ID="' . $_GET['SYUYAKU_JYUCYU_ID'] . '" 
                                AND DEL_FLG="0"';
                            $this->result = $this->dbConnect->query($sqlGetSyuyakuKoji);

                            $listJyucyuIdKoji = array();
                            if ($this->result->num_rows > 0) {
                                // output data of each row
                                while ($row = $this->result->fetch_assoc()) {
                                    $listJyucyuIdKoji[] = $row;
                                }
                            }

                            foreach ($listJyucyuIdKoji as $value) {
                                $sqlNotReportedSummarize = 'SELECT MAKER_CD, HINBAN 
                                    FROM T_KOJIMSAI 
                                    WHERE JYUCYU_ID="' . $value['JYUCYU_ID'] . '" 
                                        AND KOJIJITUIKA_FLG<>"0" 
                                        AND DEL_FLG="0"';
                                $this->result = $this->dbConnect->query($sqlNotReportedSummarize);
                                if ($this->result->num_rows > 0) {
                                    // output data of each row
                                    while ($row = $this->result->fetch_assoc()) {
                                        $resultSet['constructionReport']['SUMMARIZE'][] = $row;
                                    }
                                }
                            }
                        } else {
                            $this->dbReference->sendResponse(404, '{"error_message": SYUYAKU_JYUCYU_ID required }');
                            die;
                        }
                    }
                } else {
                    $this->dbReference->sendResponse(404, '{"error_message": KOJI_ST_value: 01 || 02 || 03 }');
                    die;
                }

                $sqlGetPullDown = 'SELECT KBN_CD, KBN_NAME, KBNMSAI_CD, KBNMSAI_NAME 
                    FROM M_KBN 
                    WHERE KBN_CD="07"';
                $this->result = $this->dbConnect->query($sqlGetPullDown);

                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['PULLDOWN'][] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // 承諾書-サイン登録
    function postLetterConsent()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['JYUCYU_ID'])
            ) {
                $query_max_tan_eig_id = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                    FROM T_KOJI_FILEPATH';
                $rs_max = $this->dbConnect->query($query_max_tan_eig_id);
                $num = 0;
                if ($rs_max->num_rows > 0) {
                    // output data of each row
                    while ($row = $rs_max->fetch_assoc()) {
                        $num = (int)$row['FILEPATH_ID_MAX'] + 1;
                    }
                }

                $FILEPATH_ID = sprintf('%010d', $num);
                $sql = 'INSERT INTO T_KOJI_FILEPATH 
                    (
                    FILEPATH_ID,
                    ID,
                    FILEPATH,
                    FILE_KBN_CD,
                    ADD_PGID,
                    ADD_TANTCD,
                    ADD_YMD,
                    UPD_PGID,
                    UPD_TANTCD,
                    UPD_YMD                  
                    )
                    VALUES (
                    "' . $FILEPATH_ID . '",
                    "' . $_GET['JYUCYU_ID'] . '",
                    NULL,
                    "08",
                    "KOJ1120F",
                    NULL,
                    "' . date('Y-m-d H:i:s') . '",
                    "KOJ1120F",
                    NULL,
                    "' . date('Y-m-d H:i:s') . '"
                    )';
                $this->result = $this->dbConnect->query($sql);
                $this->dbReference->sendResponse(200, "Success");
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // 承諾書-登録
    // ----Consent Default
    function postConsent()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['JYUCYU_ID']) &&
                isset($_POST['SYUYAKU_JYUCYU_ID'])
            ) {
                $JYUCYU_ID = $_POST['JYUCYU_ID'];
                $SYUYAKU_JYUCYU_ID = $_POST['SYUYAKU_JYUCYU_ID'];
                $BIKO = isset($_POST['BIKO']) ? '"' . $_POST['BIKO'] . '"' : 'NULL';
                $KENSETU_KEITAI = isset($_POST['KENSETU_KEITAI']) ? '"' . $_POST['BIKO'] . '"' : 'NULL';
                $BEF_SEKO_PHOTO_FILEPATH = isset($_POST['BEF_SEKO_PHOTO_FILEPATH']) ? '"' . $_POST['BEF_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $AFT_SEKO_PHOTO_FILEPATH = isset($_POST['AFT_SEKO_PHOTO_FILEPATH']) ? '"' . $_POST['AFT_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $OTHER_PHOTO_FOLDERPATH = isset($_POST['OTHER_PHOTO_FOLDERPATH']) ? '"' . $_POST['OTHER_PHOTO_FOLDERPATH'] . '"' : 'NULL';

                $sqlUpdateKOJI = 'UPDATE T_KOJI 
                    SET KOJI_RENKEI_YMD = "' . date('Y-m-d H:i:s') . '",
                        KOJI_KEKKA = "01",
                        BIKO = ' . $BIKO . ',
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $SYUYAKU_JYUCYU_ID . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE SYUYAKU_JYUCYU_ID = "' . $SYUYAKU_JYUCYU_ID . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJI);

                $sqlUpdateKOJIMSAI = 'UPDATE T_KOJIMSAI 
                    SET KENSETU_KEITAI = ' . $KENSETU_KEITAI . ',
                        BEF_SEKO_PHOTO_FILEPATH = ' . $BEF_SEKO_PHOTO_FILEPATH . ',
                        AFT_SEKO_PHOTO_FILEPATH = ' . $AFT_SEKO_PHOTO_FILEPATH . ',
                        OTHER_PHOTO_FOLDERPATH = ' . $OTHER_PHOTO_FOLDERPATH . ',
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $JYUCYU_ID . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $JYUCYU_ID . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJIMSAI);

                $this->dbReference->sendResponse(200, "Success");
            } elseif (
                isset($_POST['JYUCYU_ID'])
            ) {
                $JYUCYU_ID = $_POST['JYUCYU_ID'];

                $BIKO = isset($_POST['BIKO']) ? '"' . $_POST['BIKO'] . '"' : 'NULL';
                $KENSETU_KEITAI = isset($_POST['KENSETU_KEITAI']) ? '"' . $_POST['BIKO'] . '"' : 'NULL';
                $BEF_SEKO_PHOTO_FILEPATH = isset($_POST['BEF_SEKO_PHOTO_FILEPATH']) ? '"' . $_POST['BEF_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $AFT_SEKO_PHOTO_FILEPATH = isset($_POST['AFT_SEKO_PHOTO_FILEPATH']) ? '"' . $_POST['AFT_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $OTHER_PHOTO_FOLDERPATH = isset($_POST['OTHER_PHOTO_FOLDERPATH']) ? '"' . $_POST['OTHER_PHOTO_FOLDERPATH'] . '"' : 'NULL';
                $sqlUpdateKOJI = 'UPDATE T_KOJI 
                    SET KOJI_RENKEI_YMD = "' . date('Y-m-d H:i:s') . '",
                        KOJI_KEKKA = "01",
                        BIKO = ' . $BIKO . ',
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $JYUCYU_ID . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $JYUCYU_ID . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJI);

                $sqlUpdateKOJIMSAI = 'UPDATE T_KOJIMSAI 
                    SET KENSETU_KEITAI = ' . $KENSETU_KEITAI . ',
                        BEF_SEKO_PHOTO_FILEPATH = ' . $BEF_SEKO_PHOTO_FILEPATH . ',
                        AFT_SEKO_PHOTO_FILEPATH = ' . $AFT_SEKO_PHOTO_FILEPATH . ',
                        OTHER_PHOTO_FOLDERPATH = ' . $OTHER_PHOTO_FOLDERPATH . ',
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $JYUCYU_ID . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $JYUCYU_ID . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJIMSAI);

                $this->dbReference->sendResponse(200, "Success");
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

    // 法人完了書-登録
    function postCorporateCompletion()
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

                $BIKO = isset($_POST['BIKO']) ? '"' . $_POST['BIKO'] . '"' : 'NULL';
                $KENSETU_KEITAI = isset($_POST['KENSETU_KEITAI']) ? '"' . $_POST['BIKO'] . '"' : 'NULL';
                $BEF_SEKO_PHOTO_FILEPATH = isset($_POST['BEF_SEKO_PHOTO_FILEPATH']) ? '"' . $_POST['BEF_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $AFT_SEKO_PHOTO_FILEPATH = isset($_POST['AFT_SEKO_PHOTO_FILEPATH']) ? '"' . $_POST['AFT_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $OTHER_PHOTO_FOLDERPATH = isset($_POST['OTHER_PHOTO_FOLDERPATH']) ? '"' . $_POST['OTHER_PHOTO_FOLDERPATH'] . '"' : 'NULL';
                $sqlUpdateKOJI = 'UPDATE T_KOJI 
                    SET KOJI_RENKEI_YMD = "' . date('Y-m-d H:i:s') . '",
                        KOJI_KEKKA = "01",
                        BIKO = ' . $BIKO . ',
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $JYUCYU_ID . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $JYUCYU_ID . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJI);

                $sqlUpdateKOJIMSAI = 'UPDATE T_KOJIMSAI 
                    SET KENSETU_KEITAI = ' . $KENSETU_KEITAI . ',
                        BEF_SEKO_PHOTO_FILEPATH = ' . $BEF_SEKO_PHOTO_FILEPATH . ',
                        AFT_SEKO_PHOTO_FILEPATH = ' . $AFT_SEKO_PHOTO_FILEPATH . ',
                        OTHER_PHOTO_FOLDERPATH = ' . $OTHER_PHOTO_FOLDERPATH . ',
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $JYUCYU_ID . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $JYUCYU_ID . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJIMSAI);

                $this->dbReference->sendResponse(200, "Success");
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    // 理由記入
    function getEnterReason()
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

                //Get data T_KOJI
                $sql = 'SELECT CANCEL_RIYU 
                        FROM T_KOJI 
                        WHERE JYUCYU_ID="' . $JYUCYU_ID . '" AND DEL_FLG="0"';
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

    // 写真提出_下見
    function getPhotoSubmissionPreview()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['FILE_KBN_CD'])
            ) {
                $kojiFilePathFileKbnCd = $_GET['FILE_KBN_CD'];

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

    // 写真提出_登録
    // ----写真のみシステムに送信するボタンから遷移し、登録ボタンを押下
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

    // ----下見の結果、キャンセルの報告ボタンから遷移し、登録ボタンを押下
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

    // ----見積作成遅延報告を行うボタンから遷移し、登録ボタンを押下
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

    // ----見積書なし報告ボタンから遷移し、登録ボタンを押下
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

    /* * * * * * * * * *
    * * * * API仕様_工事報告 END * * * * 
    * * * * * * * * */

    function setTime()
    {
        $setContent = array('start_time' => '8:00:00 AM', 'end_time' => '7:00:00 PM');
        $this->dbReference->sendResponse(200, json_encode($setContent, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    /* * * * * * * * * *
    * * * * API仕様_スケジュール_1101 START * * * * 
    * * * * * * * * */

    /* ==================================================================== Default Page START */
    function getDefault()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['YMD']) && isset($_GET['KOJIGYOSYA_CD'])) {
                $YMD = $_GET['YMD'];
                $KOJIGYOSYA_CD = $_GET['KOJIGYOSYA_CD'];
                $start_date = date("Y-m-d", strtotime('monday this week', strtotime($YMD)));
                $end_date =  date("Y-m-d", strtotime('sunday this week', strtotime($YMD)));

                //Data of group
                // 【営業工事・営業下見（営業所欄）】
                $sql = 'SELECT KOJIGYOSYA_CD,
                KOJIGYOSYA_NAME
                FROM M_GYOSYA 
                WHERE KOJIGYOSYA_CD=' . $KOJIGYOSYA_CD . '';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                        $data['KOJIGYOSYA_NAME'] = $row['KOJIGYOSYA_NAME'];
                        $resultSet['OFFICE'] = $data;
                    }
                }

                $sql = 'SELECT T_EIGYO_ANKEN.START_TIME, 
                        T_EIGYO_ANKEN.TAN_EIG_ID, 
                        T_EIGYO_ANKEN.END_TIME, 
                        T_EIGYO_ANKEN.GUEST_NAME, 
                        T_EIGYO_ANKEN.ALL_DAY_FLG,
                        T_EIGYO_ANKEN.YMD,
                        M_KBN.YOBIKOMOKU1, 
                        M_KBN.KBNMSAI_NAME
                        FROM T_EIGYO_ANKEN 
                        CROSS JOIN M_KBN ON T_EIGYO_ANKEN.TAG_KBN=M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="01"                   
                        WHERE T_EIGYO_ANKEN.YMD >= "' . $start_date . '"  
                            AND T_EIGYO_ANKEN.YMD <= "' . $end_date . '" 
                            AND JYOKEN_CD="' . $KOJIGYOSYA_CD . '"
                            AND T_EIGYO_ANKEN.JYOKEN_SYBET_FLG="1" 
                            AND M_KBN.KBN_CD="10"';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $EIGYO_ANKEN_YMD = $row['YMD'];
                        $data = array();
                        $data['START_TIME'] = $row['START_TIME'];
                        $data['END_TIME'] = $row['END_TIME'];
                        $data['GUEST_NAME'] = $row['GUEST_NAME'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                        $data['YMD'] = $row['YMD'];
                        $data['TYPE'] = 1;
                        $resultSet['OFFICE'][$EIGYO_ANKEN_YMD][] = $data;
                    }
                }

                //【メモ（営業所欄）】
                $sql = 'SELECT T_TBETUCALENDAR.START_TIME, 
                T_TBETUCALENDAR.TAN_CAL_ID,
                T_TBETUCALENDAR.END_TIME, NAIYO, 
                T_TBETUCALENDAR.YMD, 
                T_TBETUCALENDAR.JYOKEN_CD, 
                T_TBETUCALENDAR.ALL_DAY_FLG, 
                M_KBN.KBNMSAI_NAME, 
                M_KBN.YOBIKOMOKU1          
                 FROM T_TBETUCALENDAR 
                 CROSS JOIN M_KBN ON T_TBETUCALENDAR.TAG_KBN=M_KBN.KBN_CD AND T_TBETUCALENDAR.JYOKEN_SYBET_FLG="1" AND M_KBN.KBNMSAI_CD="01"
                 WHERE T_TBETUCALENDAR.YMD >= "' . $start_date . '"  
                     AND T_TBETUCALENDAR.YMD <= "' . $end_date . '" 
                     AND JYOKEN_CD="' . $KOJIGYOSYA_CD . '"
                     AND M_KBN.KBN_CD="06"';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $TBETUCALENDAR_YMD = $row['YMD'];
                        $data = array();
                        $data['START_TIME'] = $row['START_TIME'];
                        $data['END_TIME'] = $row['END_TIME'];
                        $data['NAIYO'] = $row['NAIYO'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['YMD'] = $row['YMD'];
                        $data['TAN_CAL_ID'] = $row['TAN_CAL_ID'];
                        $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                        $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                        $data['TYPE'] = 2;
                        $resultSet['OFFICE'][$TBETUCALENDAR_YMD][] = $data;
                    }
                }

                $sql = 'SELECT TANT_CD,TANT_NAME FROM M_TANT WHERE SYOZOKU_CD="' . $KOJIGYOSYA_CD . '"';
                $this->result = $this->dbConnect->query($sql);
                $list_tant_cd = [];
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $list_tant_cd[] = $row;
                    }
                }

                $resultSet2 = array();
                foreach ($list_tant_cd as $k => $v) {
                    $TANT_CD = $v['TANT_CD'];
                    $TANT_NAME = $v['TANT_NAME'];

                    //【ネット下見】
                    $sql = ' SELECT T_KOJI.JYUCYU_ID,
                    T_KOJI.SITAMIHOMONJIKAN,
                    T_KOJI.SITAMIHOMONJIKAN_END,
                    T_KOJI.SETSAKI_ADDRESS,
                    T_KOJI.KOJI_ITEM,
                    T_KOJI.SETSAKI_NAME,
                    T_KOJI.SITAMIAPO_KBN,
                    T_KOJI.HOMON_SBT,
                    T_KOJI.ALL_DAY_FLG,
                    M_KBN.KBNMSAI_NAME,
                    M_TANT.TANT_CD,
                    M_TANT.TANT_NAME,
                    T_KOJI.SITAMI_YMD,
                    M_TANT.SYOZOKU_CD
                    FROM T_KOJI 
                    LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="02"
                    LEFT JOIN M_TANT ON M_TANT.TANT_CD=T_KOJI.HOMON_TANT_CD4
                    WHERE T_KOJI.SITAMI_YMD>="' . $start_date . '" 
                    AND T_KOJI.SITAMI_YMD<="' . $end_date . '" 
                    AND M_TANT.TANT_CD="' . $v['TANT_CD'] . '"
                    AND M_KBN.KBN_CD="05"
                    ORDER BY M_TANT.TANT_CD ASC,T_KOJI.SITAMI_YMD ASC';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $resultSet2[$TANT_CD]['TANT_NAME'] = $TANT_NAME;
                            $resultSet2[$TANT_CD]['TANT_CD'] = $TANT_CD;
                            $SITAMI_YMD = $row['SITAMI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                            $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['TANT_NAME'] = $row['TANT_NAME'];
                            $data['TANT_CD'] = $v['TANT_CD'];
                            $data['TYPE'] = 1;
                            $resultSet2[$TANT_CD][$SITAMI_YMD][] = $data;
                        }
                    } else {
                        $resultSet2[$TANT_CD]['TANT_CD'] = $v['TANT_CD'];
                        $resultSet2[$TANT_CD]['TANT_NAME'] = $v['TANT_NAME'];
                    }

                    //【ネット工事】
                    $sql = ' SELECT T_KOJI.JYUCYU_ID,
                    T_KOJI.KOJIHOMONJIKAN,
                    T_KOJI.KOJIHOMONJIKAN_END,
                    T_KOJI.SETSAKI_ADDRESS,
                    T_KOJI.KOJI_ITEM,
                    T_KOJI.SETSAKI_NAME,
                    T_KOJI.HOMON_SBT,
                    T_KOJI.KOJIAPO_KBN,   
                    T_KOJI.ALL_DAY_FLG,
                    M_KBN.KBNMSAI_NAME,
                    M_TANT1.TANT_CD AS TANT_CD1,
                    M_TANT1.TANT_NAME AS TANT_NAME1,
                    M_TANT2.TANT_CD AS TANT_CD2,
                    M_TANT2.TANT_NAME AS TANT_NAME2,
                    M_TANT3.TANT_CD AS TANT_CD3,
                    M_TANT3.TANT_NAME AS TANT_NAME3,
                    T_KOJI.KOJI_YMD
                    FROM T_KOJI 
                    LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="01"
                    LEFT JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                    LEFT JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                    LEFT JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                    WHERE T_KOJI.KOJI_YMD>="' . $start_date . '"  
                        AND T_KOJI.KOJI_YMD<="' . $end_date . '" 
                        AND (M_TANT1.TANT_CD="' . $v['TANT_CD'] . '" OR M_TANT2.TANT_CD="' . $v['TANT_CD'] . '" OR M_TANT3.TANT_CD="' . $v['TANT_CD'] . '" )
                        AND M_KBN.KBN_CD="05"';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $TANT_CD1 = $row['TANT_CD1'];
                            $TANT_CD2 = $row['TANT_CD2'];
                            $TANT_CD3 = $row['TANT_CD3'];
                            if (!empty($TANT_CD1) && $TANT_CD1 == $v['TANT_CD']) {
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
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['TANT_NAME'] = $row['TANT_NAME1'];
                                $data['TANT_CD'] = $TANT_CD1;
                                $data['TYPE'] = 2;
                                if (empty($resultSet2[$TANT_CD1])) {
                                    $resultSet2[$TANT_CD1]['TANT_NAME'] = $row['TANT_NAME1'];
                                    $resultSet2[$TANT_CD1]['TANT_CD'] = $TANT_CD1;
                                }
                                $resultSet2[$TANT_CD1][$KOJI_YMD][] = $data;
                            }

                            if (!empty($TANT_CD2) && $TANT_CD2 == $v['TANT_CD']) {
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
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['TANT_CD'] =  $TANT_CD2;
                                $data['TANT_NAME'] = $row['TANT_NAME2'];
                                $data['TYPE'] = 2;
                                if (empty($resultSet2[$TANT_CD2])) {
                                    $resultSet2[$TANT_CD2]['TANT_NAME'] = $row['TANT_NAME2'];
                                    $resultSet2[$TANT_CD2]['TANT_CD'] = $TANT_CD2;
                                }
                                $resultSet2[$TANT_CD2][$KOJI_YMD][] = $data;
                            }

                            if (!empty($TANT_CD3) && $TANT_CD3 == $v['TANT_CD']) {
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
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['TANT_CD'] =  $TANT_CD3;
                                $data['TANT_NAME'] = $row['TANT_NAME3'];
                                $data['TYPE'] = 2;
                                if (empty($resultSet2[$TANT_CD3])) {
                                    $resultSet2[$TANT_CD3]['TANT_NAME'] = $row['TANT_NAME3'];
                                    $resultSet2[$TANT_CD3]['TANT_CD'] = $TANT_CD3;
                                }
                                $resultSet2[$TANT_CD3][$KOJI_YMD][] = $data;
                            }
                        }
                    } else {
                        $resultSet2[$TANT_CD]['TANT_CD'] = $v['TANT_CD'];
                        $resultSet2[$TANT_CD]['TANT_NAME'] = $v['TANT_NAME'];
                    }

                    //【営業工事・営業下見（担当者欄）】
                    $sql = 'SELECT T_EIGYO_ANKEN.START_TIME, 
                    T_EIGYO_ANKEN.END_TIME, 
                    T_EIGYO_ANKEN.GUEST_NAME, 
                    T_EIGYO_ANKEN.YMD,
                    T_EIGYO_ANKEN.JYOKEN_SYBET_FLG,
                    T_EIGYO_ANKEN.TAN_EIG_ID,
                    T_EIGYO_ANKEN.ALL_DAY_FLG,
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.YOBIKOMOKU1, 
                    M_TANT.TANT_NAME, 
                    M_TANT.TANT_CD
                    FROM T_EIGYO_ANKEN 
                    CROSS JOIN M_KBN ON T_EIGYO_ANKEN.TAG_KBN=M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="01" 
                    CROSS JOIN M_TANT ON T_EIGYO_ANKEN.JYOKEN_CD=M_TANT.TANT_CD AND T_EIGYO_ANKEN.JYOKEN_SYBET_FLG="0"
                    WHERE T_EIGYO_ANKEN.YMD >= "' . $start_date . '"  
                    AND T_EIGYO_ANKEN.YMD <= "' . $end_date . '"
                    AND M_TANT.TANT_CD="' . $v['TANT_CD'] . '"';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $TANT_CD = $row['TANT_CD'];
                            $EIGYO_ANKEN_YMD = $row['YMD'];
                            $resultSet2[$TANT_CD]['TANT_NAME'] = $row['TANT_NAME'];
                            $resultSet2[$TANT_CD]['TANT_CD'] = $row['TANT_CD'];
                            $data = array();
                            $data['START_TIME'] = $row['START_TIME'];
                            $data['END_TIME'] = $row['END_TIME'];
                            $data['GUEST_NAME'] = $row['GUEST_NAME'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['TAN_EIG_ID'] = $row['TAN_EIG_ID'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['TANT_NAME'] = $row['TANT_NAME'];
                            $data['TANT_CD'] = $row['TANT_CD'];
                            $data['TYPE'] = 3;
                            $resultSet2[$TANT_CD][$EIGYO_ANKEN_YMD][] = $data;
                        }
                    } else {
                        $resultSet2[$TANT_CD]['TANT_CD'] = $v['TANT_CD'];
                        $resultSet2[$TANT_CD]['TANT_NAME'] = $v['TANT_NAME'];
                    }

                    //【メモ（営業所欄）】
                    $sql = 'SELECT T_TBETUCALENDAR.START_TIME, 
                    T_TBETUCALENDAR.END_TIME, NAIYO, 
                    T_TBETUCALENDAR.YMD,
                    T_TBETUCALENDAR.TAN_CAL_ID,
                    T_TBETUCALENDAR.JYOKEN_CD, 
                    T_TBETUCALENDAR.ALL_DAY_FLG,
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.YOBIKOMOKU1,
                    M_TANT.TANT_CD,
                    M_TANT.TANT_NAME         
                    FROM T_TBETUCALENDAR 
                    CROSS JOIN M_KBN ON T_TBETUCALENDAR.TAG_KBN=M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="01"
                    CROSS JOIN M_TANT ON T_TBETUCALENDAR.JYOKEN_CD=M_TANT.TANT_CD AND T_TBETUCALENDAR.JYOKEN_SYBET_FLG=0 
                    WHERE T_TBETUCALENDAR.YMD >= "' . $start_date . '"  
                    AND T_TBETUCALENDAR.YMD <= "' . $end_date . '"                                         
                    AND M_TANT.TANT_CD="' . $v['TANT_CD'] . '"
                    AND M_KBN.KBN_CD="06"';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $TANT_CD = $row['TANT_CD'];
                            $TBETUCALENDAR_YMD = $row['YMD'];
                            $resultSet2[$TANT_CD]['TANT_NAME'] = $row['TANT_NAME'];
                            $resultSet2[$TANT_CD]['TANT_CD'] = $row['TANT_CD'];
                            $data = array();
                            $data['START_TIME'] = $row['START_TIME'];
                            $data['END_TIME'] = $row['END_TIME'];
                            $data['NAIYO'] = $row['NAIYO'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['TANT_NAME'] = $row['TANT_NAME'];
                            $data['TANT_CD'] = $row['TANT_CD'];
                            $data['TAN_CAL_ID'] = $row['TAN_CAL_ID'];
                            $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                            $data['YMD'] = $row['YMD'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['TYPE'] = 4;
                            $resultSet2[$TANT_CD][$TBETUCALENDAR_YMD][] = $data;
                        }
                    } else {
                        $resultSet2[$TANT_CD]['TANT_CD'] = $v['TANT_CD'];
                        $resultSet2[$TANT_CD]['TANT_NAME'] = $v['TANT_NAME'];
                    }

                    //【日予実】
                    $sql = ' SELECT T_KOJI.KOJI_ITAKUHI, 
                    T_KOJI.KOJI_YMD,
                    T_KOJI.HOMON_SBT,
                    M_TANT1.DAYLY_SALES AS DAYLY_SALES1, 
                    M_TANT2.DAYLY_SALES AS DAYLY_SALES2,  
                    M_TANT3.DAYLY_SALES AS DAYLY_SALES3,  
                    M_TANT4.DAYLY_SALES AS DAYLY_SALES4,  
                    M_KBN.KBN_CD, 
                    M_KBN.KBNMSAI_CD,
                    M_TANT1.TANT_CD AS TANT_CD1,
                    M_TANT1.TANT_NAME AS TANT_NAME1,
                    M_TANT2.TANT_CD AS TANT_CD2,
                    M_TANT2.TANT_NAME AS TANT_NAME2,
                    M_TANT3.TANT_CD AS TANT_CD3,
                    M_TANT3.TANT_NAME AS TANT_NAME3, 
                    M_TANT4.TANT_CD AS TANT_CD4,
                    M_TANT4.TANT_NAME AS TANT_NAME4
                    FROM T_KOJI 
                    LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="01"
                    LEFT JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                    LEFT JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                    LEFT JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                    LEFT JOIN M_TANT as M_TANT4 ON M_TANT4.TANT_CD=T_KOJI.HOMON_TANT_CD4
                    WHERE T_KOJI.KOJI_YMD>="' . $start_date . '"  
                    AND T_KOJI.KOJI_YMD<="' . $end_date . '" 
                    AND (M_TANT1.TANT_CD="' . $v['TANT_CD'] . '" OR M_TANT2.TANT_CD="' . $v['TANT_CD'] . '" OR M_TANT3.TANT_CD="' . $v['TANT_CD'] . '" OR M_TANT4.TANT_CD="' . $v['TANT_CD'] . '" )
                    AND M_KBN.KBN_CD="16"';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $TANT_CD = $row['TANT_CD'];
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $resultSet2[$TANT_CD]['TANT_NAME'] = $row['TANT_NAME'];
                            $resultSet2[$TANT_CD]['TANT_CD'] = $row['TANT_CD'];
                            $data = array();
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['DAYLY_SALES'] = $row['DAYLY_SALES'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['TANT_NAME'] = $row['TANT_NAME'];
                            $data['TANT_CD'] = $row['TANT_CD'];
                            $data['TYPE'] = 5;
                            $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                        }
                    } else {
                        $resultSet2[$TANT_CD]['TANT_CD'] = $v['TANT_CD'];
                        $resultSet2[$TANT_CD]['TANT_NAME'] = $v['TANT_NAME'];
                    }

                    //【計予実】
                    $sql = ' SELECT T_KOJI.KOJI_ITAKUHI, 
                    T_KOJI.KOJI_YMD,
                    T_KOJI.HOMON_SBT,
                    M_TANT1.MONTHLY_SALES AS MONTHLY_SALES1, 
                    M_TANT2.MONTHLY_SALES AS MONTHLY_SALES2,  
                    M_TANT3.MONTHLY_SALES AS MONTHLY_SALES3,  
                    M_TANT4.MONTHLY_SALES AS MONTHLY_SALES4,  
                    M_KBN.KBN_CD, 
                    M_KBN.KBNMSAI_CD,
                    M_TANT1.TANT_CD AS TANT_CD1,
                    M_TANT1.TANT_NAME AS TANT_NAME1,
                    M_TANT2.TANT_CD AS TANT_CD2,
                    M_TANT2.TANT_NAME AS TANT_NAME2,
                    M_TANT3.TANT_CD AS TANT_CD3,
                    M_TANT3.TANT_NAME AS TANT_NAME3, 
                    M_TANT4.TANT_CD AS TANT_CD4,
                    M_TANT4.TANT_NAME AS TANT_NAME4
                    FROM T_KOJI 
                    LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="01" 
                    LEFT JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                    LEFT JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                    LEFT JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                    LEFT JOIN M_TANT as M_TANT4 ON M_TANT4.TANT_CD=T_KOJI.HOMON_TANT_CD4
                    WHERE T_KOJI.KOJI_YMD>="' . $start_date . '"  
                    AND T_KOJI.KOJI_YMD<="' . $end_date . '" 
                    AND (M_TANT1.TANT_CD="' . $v['TANT_CD'] . '" OR M_TANT2.TANT_CD="' . $v['TANT_CD'] . '" OR M_TANT3.TANT_CD="' . $v['TANT_CD'] . '" OR M_TANT4.TANT_CD="' . $v['TANT_CD'] . '" )
                    AND M_KBN.KBN_CD="16" ';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $TANT_CD = $row['TANT_CD'];
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $resultSet2[$TANT_CD]['TANT_NAME'] = $row['TANT_NAME'];
                            $resultSet2[$TANT_CD]['TANT_CD'] = $row['TANT_CD'];
                            $data = array();
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['DAYLY_SALES'] = $row['DAYLY_SALES'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['TANT_NAME'] = $row['TANT_NAME'];
                            $data['TANT_CD'] = $row['TANT_CD'];
                            $data['TYPE'] = 6;
                            $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                        }
                    } else {
                        $resultSet2[$TANT_CD]['TANT_CD'] = $v['TANT_CD'];
                        $resultSet2[$TANT_CD]['TANT_NAME'] = $v['TANT_NAME'];
                    }
                }

                $data_person = array();
                foreach ($resultSet2 as $key => $value) {
                    $data_person[] = $value;
                }

                $resultSet['PERSON'][] = $data_person;

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    function getDefaultPerson()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['YMD']) && isset($_GET['ID'])) {
                $YMD = $_GET['YMD'];
                $ID = $_GET['ID'];
                $start_date = date("Y-m-d", strtotime('monday this week', strtotime($YMD)));
                $end_date =  date("Y-m-d", strtotime('sunday this week', strtotime($YMD)));

                $sql = 'SELECT TANT_CD, TANT_NAME FROM M_TANT WHERE TANT_CD="' . $ID . '"';

                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data['TANT_CD'] = $row['TANT_CD'];
                        $data['TANT_NAME'] = $row['TANT_NAME'];
                        $resultSet[] = $data;
                    }
                }

                //【ネット下見】
                $sql = ' SELECT T_KOJI.JYUCYU_ID,
                    T_KOJI.SITAMIHOMONJIKAN,
                    T_KOJI.SITAMIHOMONJIKAN_END,
                    T_KOJI.SETSAKI_ADDRESS,
                    T_KOJI.KOJI_ITEM,
                    T_KOJI.SETSAKI_NAME,
                    T_KOJI.SITAMIAPO_KBN,
                    T_KOJI.ALL_DAY_FLG,
                    T_KOJI.HOMON_SBT,
                    M_KBN.KBNMSAI_NAME,
                    M_TANT.TANT_CD,
                    M_TANT.TANT_NAME,
                    T_KOJI.SITAMI_YMD,
                    M_TANT.SYOZOKU_CD
                    FROM T_KOJI 
                    LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="02"
                    LEFT JOIN M_TANT ON M_TANT.TANT_CD=T_KOJI.HOMON_TANT_CD4
                    WHERE T_KOJI.SITAMI_YMD>="' . $start_date . '" 
                    AND T_KOJI.SITAMI_YMD<="' . $end_date . '" 
                    AND M_TANT.TANT_CD=' . $ID . '
                    AND M_KBN.KBN_CD="05"  
                    ORDER BY M_TANT.TANT_CD ASC,T_KOJI.SITAMI_YMD ASC';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $SITAMI_YMD = $row['SITAMI_YMD'];
                        $data = array();
                        $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                        $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                        $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                        $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                        $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                        $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                        $data['HOMON_SBT'] = $row['HOMON_SBT'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['TYPE'] = 1;
                        $resultSet[$SITAMI_YMD][] = $data;
                    }
                }

                //【ネット工事】
                $sql = ' SELECT T_KOJI.JYUCYU_ID,
                    T_KOJI.KOJIHOMONJIKAN,
                    T_KOJI.KOJIHOMONJIKAN_END,
                    T_KOJI.SETSAKI_ADDRESS,
                    T_KOJI.KOJI_ITEM,
                    T_KOJI.SETSAKI_NAME,
                    T_KOJI.KOJIAPO_KBN,   
                    T_KOJI.ALL_DAY_FLG,
                    T_KOJI.HOMON_SBT,
                    M_KBN.KBNMSAI_NAME,
                    M_TANT1.TANT_CD AS TANT_CD1,
                    M_TANT1.TANT_NAME AS TANT_NAME1,
                    M_TANT2.TANT_CD AS TANT_CD2,
                    M_TANT2.TANT_NAME AS TANT_NAME2,
                    M_TANT3.TANT_CD AS TANT_CD3,
                    M_TANT3.TANT_NAME AS TANT_NAME3,
                    T_KOJI.KOJI_YMD
                    FROM T_KOJI 
                    LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="01"
                    LEFT JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                    LEFT JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                    LEFT JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                    WHERE T_KOJI.KOJI_YMD>="' . $start_date . '"  
                        AND T_KOJI.KOJI_YMD<="' . $end_date . '" 
                        AND (M_TANT1.TANT_CD=' . $ID . ' OR M_TANT2.TANT_CD=' . $ID . ' OR M_TANT3.TANT_CD=' . $ID . ' )
                        AND M_KBN.KBN_CD="05"';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $KOJI_YMD = $row['KOJI_YMD'];
                        $data = array();
                        $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                        $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                        $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                        $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                        $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                        $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                        $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                        $data['HOMON_SBT'] = $row['HOMON_SBT'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['TYPE'] = 2;
                        $resultSet[$KOJI_YMD][] = $data;
                    }
                }

                //【営業工事・営業下見（担当者欄）】
                $sql = 'SELECT T_EIGYO_ANKEN.START_TIME, 
                    T_EIGYO_ANKEN.END_TIME, 
                    T_EIGYO_ANKEN.GUEST_NAME, 
                    T_EIGYO_ANKEN.YMD,
                    T_EIGYO_ANKEN.JYOKEN_SYBET_FLG,
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.YOBIKOMOKU1, 
                    M_TANT.TANT_NAME, 
                    M_TANT.TANT_CD
                    FROM T_EIGYO_ANKEN 
                    CROSS JOIN M_KBN ON T_EIGYO_ANKEN.TAG_KBN=M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="01"
                    CROSS JOIN M_TANT ON T_EIGYO_ANKEN.JYOKEN_CD=M_TANT.TANT_CD
                    WHERE T_EIGYO_ANKEN.YMD >= "' . $start_date . '"  
                    AND T_EIGYO_ANKEN.YMD <= "' . $end_date . '"
                    AND T_EIGYO_ANKEN.JYOKEN_SYBET_FLG=0
                    AND M_TANT.TANT_CD=' . $ID . '
                    AND M_KBN.KBN_CD="10"';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $EIGYO_ANKEN_YMD = $row['YMD'];
                        $data = array();
                        $data['START_TIME'] = $row['START_TIME'];
                        $data['END_TIME'] = $row['END_TIME'];
                        $data['GUEST_NAME'] = $row['GUEST_NAME'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['TYPE'] = 3;
                        $resultSet[$EIGYO_ANKEN_YMD][] = $data;
                    }
                }

                //【メモ（営業所欄）】
                $sql = 'SELECT T_TBETUCALENDAR.START_TIME, 
                    T_TBETUCALENDAR.END_TIME, NAIYO, 
                    T_TBETUCALENDAR.YMD,
                    T_TBETUCALENDAR.TAN_CAL_ID,
                    T_TBETUCALENDAR.JYOKEN_CD,
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.YOBIKOMOKU1,
                    M_TANT.TANT_CD          
                    FROM T_TBETUCALENDAR 
                    CROSS JOIN M_KBN ON T_TBETUCALENDAR.TAG_KBN=M_KBN.KBN_CD
                    CROSS JOIN M_TANT ON T_TBETUCALENDAR.JYOKEN_CD=M_TANT.TANT_CD
                    WHERE T_TBETUCALENDAR.YMD >= "' . $start_date . '"  
                    AND T_TBETUCALENDAR.YMD <= "' . $end_date . '"                     
                    AND T_TBETUCALENDAR.JYOKEN_SYBET_FLG=0 
                    AND M_TANT.TANT_CD=' . $ID . '
                    AND M_KBN.KBN_CD="06"
                    AND M_KBN.KBNMSAI_CD="01" ';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $TBETUCALENDAR_YMD = $row['YMD'];
                        $data = array();
                        $data['START_TIME'] = $row['START_TIME'];
                        $data['END_TIME'] = $row['END_TIME'];
                        $data['NAIYO'] = $row['NAIYO'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['TAN_CAL_ID'] = $row['TAN_CAL_ID'];
                        $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                        $data['YMD'] = $row['YMD'];
                        $data['TYPE'] = 4;
                        $resultSet[$TBETUCALENDAR_YMD][] = $data;
                    }
                }

                //【日予実】
                $sql = ' SELECT T_KOJI.KOJI_ITAKUHI, 
                    T_KOJI.KOJI_YMD,
                    T_KOJI.HOMON_SBT,
                    M_TANT1.DAYLY_SALES AS DAYLY_SALES1, 
                    M_TANT2.DAYLY_SALES AS DAYLY_SALES2,  
                    M_TANT3.DAYLY_SALES AS DAYLY_SALES3,  
                    M_TANT4.DAYLY_SALES AS DAYLY_SALES4,  
                    M_KBN.KBN_CD, 
                    M_KBN.KBNMSAI_CD,
                    M_TANT1.TANT_CD AS TANT_CD1,
                    M_TANT1.TANT_NAME AS TANT_NAME1,
                    M_TANT2.TANT_CD AS TANT_CD2,
                    M_TANT2.TANT_NAME AS TANT_NAME2,
                    M_TANT3.TANT_CD AS TANT_CD3,
                    M_TANT3.TANT_NAME AS TANT_NAME3, 
                    M_TANT4.TANT_CD AS TANT_CD4,
                    M_TANT4.TANT_NAME AS TANT_NAME4
                    FROM T_KOJI 
                    LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD
                    LEFT JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                    LEFT JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                    LEFT JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                    LEFT JOIN M_TANT as M_TANT4 ON M_TANT4.TANT_CD=T_KOJI.HOMON_TANT_CD4
                    WHERE T_KOJI.KOJI_YMD>="' . $start_date . '"  
                    AND T_KOJI.KOJI_YMD<="' . $end_date . '" 
                    AND (M_TANT1.TANT_CD=' . $ID . ' OR M_TANT2.TANT_CD=' . $ID . ' OR M_TANT3.TANT_CD=' . $ID . ' OR M_TANT4.TANT_CD=' . $ID . ' )
                    AND M_KBN.KBN_CD="16" 
                    AND M_KBN.KBNMSAI_CD="01" ';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $KOJI_YMD = $row['KOJI_YMD'];
                        $data = array();
                        $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                        $data['DAYLY_SALES'] = $row['DAYLY_SALES'];
                        $data['KBN_CD'] = $row['KBN_CD'];
                        $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                        $data['HOMON_SBT'] = $row['HOMON_SBT'];
                        $data['TYPE'] = 5;
                        $resultSet[$KOJI_YMD][] = $data;
                    }
                }

                //【計予実】
                $sql = ' SELECT T_KOJI.KOJI_ITAKUHI, 
                    T_KOJI.KOJI_YMD,
                    T_KOJI.HOMON_SBT,
                    M_TANT1.MONTHLY_SALES AS MONTHLY_SALES1, 
                    M_TANT2.MONTHLY_SALES AS MONTHLY_SALES2,  
                    M_TANT3.MONTHLY_SALES AS MONTHLY_SALES3,  
                    M_TANT4.MONTHLY_SALES AS MONTHLY_SALES4,  
                    M_KBN.KBN_CD, 
                    M_KBN.KBNMSAI_CD,
                    M_TANT1.TANT_CD AS TANT_CD1,
                    M_TANT1.TANT_NAME AS TANT_NAME1,
                    M_TANT2.TANT_CD AS TANT_CD2,
                    M_TANT2.TANT_NAME AS TANT_NAME2,
                    M_TANT3.TANT_CD AS TANT_CD3,
                    M_TANT3.TANT_NAME AS TANT_NAME3, 
                    M_TANT4.TANT_CD AS TANT_CD4,
                    M_TANT4.TANT_NAME AS TANT_NAME4
                    FROM T_KOJI 
                    LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD
                    LEFT JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                    LEFT JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                    LEFT JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                    LEFT JOIN M_TANT as M_TANT4 ON M_TANT4.TANT_CD=T_KOJI.HOMON_TANT_CD4
                    WHERE T_KOJI.KOJI_YMD>="' . $start_date . '"  
                    AND T_KOJI.KOJI_YMD<="' . $end_date . '" 
                    AND (M_TANT1.TANT_CD=' . $ID . ' OR M_TANT2.TANT_CD=' . $ID . ' OR M_TANT3.TANT_CD=' . $ID . ' OR M_TANT4.TANT_CD=' . $ID . ' )
                    AND M_KBN.KBN_CD="16" 
                    AND M_KBN.KBNMSAI_CD="01" ';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $KOJI_YMD = $row['KOJI_YMD'];
                        $data = array();
                        $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                        $data['DAYLY_SALES'] = $row['DAYLY_SALES'];
                        $data['KBN_CD'] = $row['KBN_CD'];
                        $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                        $data['HOMON_SBT'] = $row['HOMON_SBT'];
                        $data['TYPE'] = 6;
                        $resultSet[$KOJI_YMD][] = $data;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    //【グループ週の場合、左列の担当者名】
    function getListPeople()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['KOJIGYOSYA_CD'])
            ) {
                $KOJIGYOSYA_CD = $_GET['KOJIGYOSYA_CD'];
                $sql = 'SELECT TANT_CD,TANT_NAME 
                 FROM M_TANT WHERE SYOZOKUBUSYO_CD=' . $KOJIGYOSYA_CD . '
                 AND DEL_FLG=0';
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

    //【協力店舗/営業所名プルダウン】
    function getListOffice()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $sql = ' SELECT KOJIGYOSYA_CD,KOJIGYOSYA_NAME  FROM M_GYOSYA';
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

    /* ==================================================================== Default Page END */

    /* ==================================================================== ネット工事ネット下見内容 START */
    function getNetPreviewContents()
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
                //Get data T_KOJI
                $sql = 'SELECT JYUCYU_ID, 
                T_KOJI.KOJI_JININ, 
                T_KOJI.KOJIHOMONJIKAN, 
                T_KOJI.KOJIHOMONJIKAN_END, 
                T_KOJI.KOJI_JIKAN, 
                T_KOJI.SETSAKI_ADDRESS, 
                T_KOJI.KOJI_ITEM, 
                T_KOJI.SETSAKI_NAME, 
                T_KOJI.HOMON_TANT_NAME1, 
                T_KOJI.HOMON_TANT_NAME2, 
                T_KOJI.HOMON_TANT_NAME3, 
                T_KOJI.HOMON_TANT_NAME4, 
                T_KOJI.ADD_TANTNM, 
                T_KOJI.ADD_YMD, 
                T_KOJI.UPD_TANTNM, 
                T_KOJI.UPD_YMD, 
                T_KOJI.SITAMIIRAISYO_FILEPATH, 
                T_KOJI.MEMO, 
                T_KOJI.HOMON_SBT, 
                T_KOJI.COMMENT, 
                M_KBN.KBNMSAI_NAME, 
                T_KOJI_FILEPATH.ID,
                T_KOJI_FILEPATH.FILEPATH 
                FROM T_KOJI 
                CROSS JOIN M_KBN ON T_KOJI.TAG_KBN = M_KBN.KBN_CD 
                CROSS JOIN T_KOJI_FILEPATH ON T_KOJI.JYUCYU_ID = T_KOJI_FILEPATH.ID 
                WHERE T_KOJI.JYUCYU_ID="' . $JYUCYU_ID . '"
                AND M_KBN.KBN_CD="05" 
                AND M_KBN.KBNMSAI_CD="01" 
                AND T_KOJI_FILEPATH.ID="' . $JYUCYU_ID . '" 
                AND (T_KOJI_FILEPATH.FILE_KBN_CD="03" OR T_KOJI_FILEPATH.FILE_KBN_CD="04" OR T_KOJI_FILEPATH.FILE_KBN_CD="05")
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

    /* ==================================================================== ネット工事ネット下見内容 END */

    /* ==================================================================== ネット工事ネット下見内容詳細 START*/
    function getNetConstructionNetPreviewContentsDetails()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['JYUCYU_ID'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $sql = 'SELECT TAG_KBN,
                SITAMIHOMONJIKAN,
                SITAMIHOMONJIKAN_END,
                SITAMI_JININ,
                SITAMI_JIKAN,
                SITAMIAPO_KBN,
                UPD_TANTNM,
                UPD_YMD,
                SITAMI_KANSAN_POINT,
                JYUCYU_ID,
                HOMON_SBT,
                COMMENT,
                MEMO  FROM T_KOJI WHERE JYUCYU_ID="' . $JYUCYU_ID . '" AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data['TAG_KBN'] = $row['TAG_KBN'];
                        $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                        $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                        $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                        $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                        $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                        $data['UPD_TANTNM'] = $row['UPD_TANTNM'];
                        $data['UPD_YMD'] = $row['UPD_YMD'];
                        $data['HOMON_SBT'] = $row['HOMON_SBT'];
                        $data['COMMENT'] = $row['COMMENT'];
                        $data['SITAMI_KANSAN_POINT'] = ceil($row['SITAMI_KANSAN_POINT']);
                        $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                        $data['MEMO'] = $row['MEMO'];
                        $resultSet['SITAMI'][] = $data;
                    }
                }

                $sql = ' SELECT TAG_KBN,
                KOJIHOMONJIKAN,
                KOJIHOMONJIKAN_END,
                KOJI_JININ,
                KOJI_JIKAN,
                KOJIAPO_KBN,
                UPD_TANTNM,
                UPD_YMD,
                KOJI_KANSAN_POINT,
                JYUCYU_ID,
                HOMON_SBT,
                COMMENT,
                MEMO  FROM T_KOJI WHERE JYUCYU_ID="' . $JYUCYU_ID . '" AND DEL_FLG= 0';
                $this->result2 = $this->dbConnect->query($sql);
                if ($this->result2->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result2->fetch_assoc()) {
                        $data = array();
                        $data['TAG_KBN'] = $row['TAG_KBN'];
                        $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                        $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                        $data['KOJI_JININ'] = $row['KOJI_JININ'];
                        $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                        $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                        $data['UPD_TANTNM'] = $row['UPD_TANTNM'];
                        $data['UPD_YMD'] = $row['UPD_YMD'];
                        $data['HOMON_SBT'] = $row['HOMON_SBT'];
                        $data['COMMENT'] = $row['COMMENT'];
                        $data['KOJI_KANSAN_POINT'] = ceil($row['KOJI_KANSAN_POINT']);
                        $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                        $data['MEMO'] = $row['MEMO'];
                        $resultSet['KOJI'][] = $data;
                    }
                }

                $sql = 'SELECT KBN_CD, 
                KBN_NAME , 
                KBNMSAI_CD, 
                KBNMSAI_NAME 
                FROM M_KBN 
                WHERE KBN_CD= "05" AND DEL_FLG= 0';

                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['PULLDOWN'][] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    function postNetConstructionNetPreviewContentsUpdate()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_POST['JYUCYU_ID']) && isset($_POST['TAG_KBN'])) {
                $JYUCYU_ID = $_POST['JYUCYU_ID'];
                $TAG_KBN = $_POST['TAG_KBN'];
                $KBN = isset($_POST['KBN']) ? $_POST['KBN'] : NULL;
                $JIKAN = isset($_POST['JIKAN']) ? $_POST['JIKAN'] : NULL;
                $JIKAN_END = isset($_POST['JIKAN_END']) ? $_POST['JIKAN_END'] : NULL;
                $JININ = isset($_POST['JININ']) ? $_POST['JININ'] : NULL;
                $KANSAN_POINT = isset($_POST['KANSAN_POINT']) ? $_POST['KANSAN_POINT'] : NULL;
                $ALL_DAY_FLG = isset($_POST['ALL_DAY_FLG']) ? $_POST['ALL_DAY_FLG'] : NULL;
                $SKJ_RENKEI_YMD = date("Y-m-d");
                $UPD_PGID = 'KOJ1110F';
                $UPD_TANTCD = isset($_POST['UPD_TANTCD']) ? $_POST['UPD_TANTCD'] : '000001';
                $UPD_YMD = date("Y-m-d H:i:s");
                $MEMO = isset($_POST['MEMO']) ? $_POST['MEMO'] : NULL;
                if (in_array($TAG_KBN, ["02", "04", "06"])) {
                    $sql = ' UPDATE T_KOJI
                    SET TAG_KBN="' . $TAG_KBN . '",
                    SITAMIAPO_KBN="' . $KBN . '",
                    SITAMIHOMONJIKAN="' . $JIKAN . '",
                    SITAMIHOMONJIKAN_END="' . $JIKAN_END . '",
                    SITAMI_JININ=' . $JININ . ',
                    SITAMI_KANSAN_POINT=' . $KANSAN_POINT . ',
                    ALL_DAY_FLG=' . $ALL_DAY_FLG . ',
                    SKJ_RENKEI_YMD="' . $SKJ_RENKEI_YMD . '",
                    UPD_PGID= "' . $UPD_PGID . '",
                    UPD_TANTCD="' . $UPD_TANTCD . '",
                    UPD_YMD="' . $UPD_YMD . '",
                    MEMO="' . $MEMO . '"
                    WHERE JYUCYU_ID=' . $JYUCYU_ID . '';
                    $this->result = $this->dbConnect->query($sql);
                    $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }

                if (in_array($TAG_KBN, ["01", "03", "05"])) {
                    $sql = 'UPDATE T_KOJI 
                    SET TAG_KBN="' . $TAG_KBN . '",
                    KOJIAPO_KBN="' . $KBN . '",
                    KOJIHOMONJIKAN="' . $JIKAN . '",
                    KOJIHOMONJIKAN_END="' . $JIKAN_END . '",
                    KOJI_JININ=' . $JININ . ',
                    KOJI_KANSAN_POINT=' . $KANSAN_POINT . ',
                    ALL_DAY_FLG=' . $ALL_DAY_FLG . ',
                    SKJ_RENKEI_YMD="' . $SKJ_RENKEI_YMD . '",
                    UPD_PGID= "' . $UPD_PGID . '",
                    UPD_TANTCD="' . $UPD_TANTCD . '",            
                    UPD_YMD="' . $UPD_YMD . '",
                    MEMO="' . $MEMO . '"  
                    WHERE JYUCYU_ID=' . $JYUCYU_ID . '';
                    $this->result = $this->dbConnect->query($sql);
                    $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }
    /* ==================================================================== ネット工事ネット下見内容詳細 END*/

    /* ==================================================================== 営業工事営業下見内容 START*/
    function getSalesConstructionSalesPreviewContents()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (isset($_GET['TAN_EIG_ID'])) {
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
                ATTEND_NAME3 FROM T_EIGYO_ANKEN WHERE TAN_EIG_ID= "' . $TAN_EIG_ID . '" AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['EIGYO_ANKEN'][] = $row;
                    }
                }

                $sql = ' SELECT KBN_CD,
                KBN_NAME,
                KBNMSAI_CD,
                KBNMSAI_NAME FROM M_KBN 
                WHERE KBN_CD= "10" 
                AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['PULLDOWN'][] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $sql = ' SELECT KBN_CD,
                KBN_NAME,
                KBNMSAI_CD,
                KBNMSAI_NAME FROM M_KBN 
                WHERE KBN_CD= "10" 
                AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['PULLDOWN'][] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postSalesConstructionSalesPreviewUpdate()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['JYOKEN_CD']) && isset($_POST['YMD'])  && isset($_POST['JYOKEN_SYBET_FLG'])
            ) {
                $YMD = $_POST['YMD'];
                $JYOKEN_CD = $_POST['JYOKEN_CD'];
                $JYOKEN_SYBET_FLG = $_POST['JYOKEN_SYBET_FLG'];

                $TAG_KBN = isset($_POST['TAG_KBN']) ? '"' . $_POST['TAG_KBN'] . '"' : 'NULL';
                $START_TIME = isset($_POST['START_TIME']) ? '"' . $_POST['START_TIME'] . '"' : 'NULL';
                $END_TIME = isset($_POST['END_TIME']) ? '"' . $_POST['END_TIME'] . '"' : 'NULL';
                $JININ = isset($_POST['JININ']) ? '"' . $_POST['JININ'] . '"' : 'NULL';
                $JIKAN = isset($_POST['JIKAN']) ? '"' . $_POST['JIKAN'] . '"' : 'NULL';
                $GUEST_NAME = isset($_POST['GUEST_NAME']) ? '"' . $_POST['GUEST_NAME'] . '"' : 'NULL';
                $ATTEND_NAME1 = isset($_POST['ATTEND_NAME1']) ? '"' . $_POST['ATTEND_NAME1'] . '"' : 'NULL';
                $ATTEND_NAME2 = isset($_POST['ATTEND_NAME2']) ? '"' . $_POST['ATTEND_NAME2'] . '"' : 'NULL';
                $ATTEND_NAME3 = isset($_POST['ATTEND_NAME3']) ? '"' . $_POST['ATTEND_NAME3'] . '"' : 'NULL';
                $ALL_DAY_FLG = isset($_POST['ALL_DAY_FLG']) ? '"' . $_POST['ALL_DAY_FLG'] . '"' : 'NULL';
                $RENKEI_YMD = date('Y-m-d');
                $ADD_PGID = "KOJ1110F";
                $ADD_TANTCD = isset($_POST['ADD_TANTCD']) ? '"' . $_POST['ADD_TANTCD'] . '"' : '00001';
                $ADD_YMD = date('Y-m-d H:i:s');
                $UPD_PGID = "KOJ1110F";
                $UPD_TANTCD = isset($_POST['UPD_TANTCD']) ? '"' . $_POST['UPD_TANTCD'] . '"' : '00001';
                $UPD_YMD  = date('Y-m-d H:i:s');
                $query_eigyo_anken = 'SELECT TAN_EIG_ID 
                FROM T_EIGYO_ANKEN 
                WHERE JYOKEN_CD=' . $JYOKEN_CD . ' AND YMD="' . $YMD . '" AND JYOKEN_SYBET_FLG=' . $JYOKEN_SYBET_FLG . '';
                $count_eigyo_anken = $this->dbConnect->query($query_eigyo_anken);

                if ($count_eigyo_anken->num_rows > 0) {
                    $sql = ' UPDATE T_EIGYO_ANKEN
                    SET                     
                    TAG_KBN=' . $TAG_KBN . ',
                    START_TIME=' . $START_TIME . ',
                    END_TIME=' . $END_TIME . ',
                    JININ=' . $JININ . ',
                    JIKAN=' . $JIKAN . ',
                    GUEST_NAME=' . $GUEST_NAME . ',
                    ATTEND_NAME1=' . $ATTEND_NAME1 . ',
                    ATTEND_NAME2=' . $ATTEND_NAME2 . ',
                    ATTEND_NAME3=' . $ATTEND_NAME3 . ',
                    ALL_DAY_FLG=' . $ALL_DAY_FLG . ',
                    RENKEI_YMD="' . $RENKEI_YMD . '",
                    UPD_PGID="' . $UPD_PGID . '",
                    UPD_TANTCD=' . $UPD_TANTCD . ',
                    UPD_YMD="' . $UPD_YMD . '" 
                    WHERE JYOKEN_CD="' . $JYOKEN_CD . '" AND YMD="' . $YMD . '" AND JYOKEN_SYBET_FLG= ' . $JYOKEN_SYBET_FLG . '';
                    $this->result = $this->dbConnect->query($sql);
                    $this->dbReference->sendResponse(200, json_encode('sucess', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                } else {
                    //Caculate TAN_EIG_ID                    
                    $query_max_tan_eig_id = 'SELECT max(TAN_EIG_ID) as TANCALID_MAX
                    FROM T_EIGYO_ANKEN';
                    $rs_max = $this->dbConnect->query($query_max_tan_eig_id);
                    $num = 0;
                    if ($rs_max->num_rows > 0) {
                        // output data of each row
                        while ($row = $rs_max->fetch_assoc()) {
                            $num = (int)$row['TANCALID_MAX'] + 1;
                        }
                    }

                    $TAN_EIG_ID = sprintf('%010d', $num);
                    $sql = 'INSERT INTO T_EIGYO_ANKEN 
                    (
                    TAN_EIG_ID,
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
                    ADD_PGID,
                    ADD_TANTCD,
                    ADD_YMD,
                    UPD_PGID,
                    UPD_TANTCD,
                    UPD_YMD                      
                    )
                    VALUES (
                    "' . $TAN_EIG_ID . '",
                    "' . $JYOKEN_CD . '",
                    ' . $JYOKEN_SYBET_FLG . ',
                    "' . $YMD . '",
                    ' . $TAG_KBN . ',
                    ' . $START_TIME . ',
                    ' . $END_TIME . ',
                    ' . $JININ . ',
                    ' . $JIKAN . ',
                    ' . $GUEST_NAME . ',
                    ' . $ATTEND_NAME1 . ',
                    ' . $ATTEND_NAME2 . ',
                    ' . $ATTEND_NAME3 . ',
                    ' . $ALL_DAY_FLG . ',                    
                    "' . $ADD_PGID . '",
                    ' . $ADD_TANTCD . ',
                    "' . $ADD_YMD . '",
                    "' . $UPD_PGID . '",
                    ' . $UPD_TANTCD . ',
                    "' . $UPD_YMD . '" )';
                    $this->result = $this->dbConnect->query($sql);
                    $this->dbReference->sendResponse(200, json_encode('sucess', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }
    /* ==================================================================== 営業工事営業下見内容 END*/

    /* ==================================================================== メモ更新 START */
    function getMemoRegistration()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['TAN_CAL_ID'])
            ) {
                $TAN_CAL_ID = $_GET['TAN_CAL_ID'];

                //Get data T_TBETUCALENDAR
                $sql = 'SELECT MEMO_CD, YMD, START_TIME, END_TIME, NAIYO 
                            FROM T_TBETUCALENDAR 
                            WHERE TAN_CAL_ID="' . $TAN_CAL_ID . '" 
                                AND DEL_FLG="0"';
                $this->result = $this->dbConnect->query($sql);

                //Get data M_KBN
                $sqlPulldown = 'SELECT KBN_CD, KBN_NAME, KBNMSAI_CD, KBNMSAI_NAME 
                                    FROM M_KBN 
                                    WHERE KBN_CD="06" 
                                        AND DEL_FLG="0"';
                $getPullDown = $this->dbConnect->query($sqlPulldown);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['dataTBETUCALENDAR'][] = $row;
                    }
                }
                if ($getPullDown->num_rows > 0) {
                    while ($row = $getPullDown->fetch_assoc()) {
                        $resultSet['pullDown'][] = $row;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

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

                $MEMO_CD = isset($_POST['MEMO_CD']) ? '"' . $_POST['MEMO_CD'] . '"' : 'NULL';
                $START_TIME = isset($_POST['START_TIME']) ? '"' . $_POST['START_TIME'] . '"' : 'NULL';
                $END_TIME = isset($_POST['END_TIME']) ? '"' . $_POST['END_TIME'] . '"' : 'NULL';
                $NAIYO = isset($_POST['NAIYO']) ? '"' . $_POST['NAIYO'] . '"' : 'NULL';
                $ALL_DAY_FLG = isset($_POST['ALL_DAY_FLG']) ? '"' . $_POST['ALL_DAY_FLG'] . '"' : 'NULL';

                $TAG_KBN = isset($_POST['TAG_KBN']) ? '"' . $_POST['TAG_KBN'] . '"' : 'NULL';
                $COMMENT = isset($_POST['COMMENT']) ? '"' . $_POST['COMMENT'] . '"' : 'NULL';

                $sqlDataTBetucalendar = 'SELECT *
                        FROM T_TBETUCALENDAR 
                        WHERE JYOKEN_CD="' . $jyokenCd . '" 
                            AND JYOKEN_SYBET_FLG="' . $jyokenSybetFlg . '" 
                            AND YMD="' . $ymd . '"
                            AND TAG_KBN=' . $TAG_KBN . '
                            AND MEMO_CD=' . $MEMO_CD . '
                            ';
                $getDataTBetucalendar = $this->dbConnect->query($sqlDataTBetucalendar);

                if ($getDataTBetucalendar->num_rows > 0) {
                    $sqlUpdate = 'UPDATE T_TBETUCALENDAR 
                        SET MEMO_CD=' . $MEMO_CD . ', 
                            TAG_KBN=' . $TAG_KBN . ', 
                            YMD="' . $ymd . '", 
                            START_TIME=' . $START_TIME . ', 
                            END_TIME=' . $END_TIME . ', 
                            NAIYO=' . $NAIYO . ', 
                            ALL_DAY_FLG=' . $ALL_DAY_FLG . ', 
                            RENKEI_YMD="' . date('Y-m-d') . '", 
                            UPD_PGID="KOJ1110F", 
                            UPD_TANTCD="' . $jyokenCd . '", 
                            UPD_YMD="' . date("Y-m-d H:i:s") . '" 
                        WHERE JYOKEN_CD="' . $jyokenCd . '" 
                            AND JYOKEN_SYBET_FLG="' . $jyokenSybetFlg . '" 
                            AND YMD="' . $ymd . '"';
                    $this->result = $this->dbConnect->query($sqlUpdate);
                } else {
                    $sqlDataTBetucalendar = 'SELECT max(TAN_CAL_ID) as TANCALID_MAX
                        FROM T_TBETUCALENDAR';
                    $getDataTBetucalendar = $this->dbConnect->query($sqlDataTBetucalendar);
                    $num = 0;
                    if ($getDataTBetucalendar->num_rows > 0) {
                        // output data of each row
                        while ($row = $getDataTBetucalendar->fetch_assoc()) {
                            $num = (int)$row['TANCALID_MAX'] + 1;
                        }
                    }

                    $TAN_CAL_ID = sprintf('%010d', $num);
                    $sqlInsert = 'INSERT INTO T_TBETUCALENDAR 
                                    (
                                        TAN_CAL_ID, JYOKEN_CD, JYOKEN_SYBET_FLG, YMD, TAG_KBN, START_TIME, END_TIME, 
                                        MEMO_CD, NAIYO, COMMENT, ALL_DAY_FLG, RENKEI_YMD, DEL_FLG, ADD_PGID, ADD_TANTCD, ADD_YMD, 
                                        UPD_PGID, UPD_TANTCD, UPD_YMD
                                    )
                                VALUES 
                                    (
                                        "' . $TAN_CAL_ID . '", "' . $_POST['JYOKEN_CD'] . '", "' . $_POST['JYOKEN_SYBET_FLG'] . '", 
                                        "' . $_POST['YMD'] . '", ' . $TAG_KBN . ', ' . $START_TIME . ', ' . $END_TIME . ', 
                                        ' . $MEMO_CD . ', ' . $NAIYO . ', ' . $COMMENT . ', ' . $ALL_DAY_FLG . ', "' . date('Y-m-d') . '", 0, 
                                        "KOJ1110F", "' . $jyokenCd . '", "' . date("Y-m-d H:i:s") . '", "KOJ1110F", "' . $jyokenCd . '", "' . date("Y-m-d H:i:s") . '"
                                    );';
                    $this->result = $this->dbConnect->query($sqlInsert);
                }

                $this->dbReference->sendResponse(200, json_encode('Success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": "ERROR"}');
            }
        }
    }
    function postMemoDelete()
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
                $sqlUpdate = 'UPDATE T_TBETUCALENDAR 
                        SET DEL_FLG=1, 
                            RENKEI_YMD="' . date('Y-m-d') . '" 
                        WHERE JYOKEN_CD="' . $_POST['JYOKEN_CD'] . '" 
                            AND JYOKEN_SYBET_FLG="' . $_POST['JYOKEN_SYBET_FLG'] . '" 
                            AND YMD="' . $_POST['YMD'] . '"';
                $this->result = $this->dbConnect->query($sqlUpdate);

                $this->dbReference->sendResponse(200, json_encode('Success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": "ERROR"}');
            }
        }
    }

    /* ==================================================================== メモ更新 END */

    /* ==================================================================== 休日確認  START*/
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
                            FROM T_TANTHOLIDAY 
                            WHERE TANT_CD="' . $tantCd . '" 
                                AND HOLIDAY_YEAR="' . $holidayYear . '"';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $totalHolidays = 0;
                        foreach ($row as $value) {
                            $totalHolidays = $totalHolidays + $value;
                        }
                        $resultSet['totalHolidays'] = $totalHolidays;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    /* ==================================================================== 休日確認  END*/

    /* * * * * * * * * *
    * * * * API仕様_スケジュール_1101 END * * * * 
    * * * * * * * * */

    /* * * * * * * * * *
    * * * * MENU START* * * * 
    * * * * * * * * */
    /* ==================================================================== MENU */
    function getMenu()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_GET['LOGIN_ID'])
            ) {
                // 新着コメント
                $LOGIN_ID = $_GET['LOGIN_ID'];
                $sql = 'SELECT COMMENT , JYUCYU_ID
                FROM T_KOJI 
                WHERE ADD_TANTCD="' . $LOGIN_ID . '" 
                AND COMMENT IS NOT NULL 
                AND READ_FLG IS NULL';
                $this->result = $this->dbConnect->query($sql);
                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }

                // お知らせ(入庫)
                $sql = 'SELECT M_TANT.TANT_CD,
                M_GYOSYA.KOJIGYOSYA_CD 
                FROM M_TANT 
                LEFT JOIN M_GYOSYA ON M_TANT.SYOZOKUBUSYO_CD = M_GYOSYA.KOJIGYOSYA_CD
                WHERE M_TANT.TANT_CD="' . $LOGIN_ID . '" 
                AND M_TANT.DEL_FLG= 0';
                $temp_data = '';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $temp_data = $row['KOJIGYOSYA_CD'];
                    }
                }

                $sql = 'SELECT COUNT(*)
                FROM T_NYUKOYOTEI 
                LEFT JOIN M_GYOSYA ON T_NYUKOYOTEI.SOKO_CD = M_GYOSYA.KOJIGYOSYA_CD
                WHERE T_NYUKOYOTEI.SOKO_CD="' . $temp_data . '" 
                AND T_NYUKOYOTEI.DEL_FLG= 0';

                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data[] = $row["COUNT(*)"];
                        $data[] = 'NYUKOYOTEI_TOTAL';
                        $resultSet[] = $data;
                    }
                }

                // お知らせ(完了報告) 
                $sql = 'SELECT COUNT(*)
                FROM T_KOJI 
                WHERE ADD_TANTCD="' . $LOGIN_ID . '" 
                AND KOJI_YMD="' . date('d.m.Y', strtotime("-1 days")) . '" 
                AND REPORT_FLG="02"';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data[] = $row["COUNT(*)"];
                        $data[] = 'KOJI_TOTAL';
                        $resultSet[] = $data;
                    }
                }

                // お知らせ(下見)
                $sql = 'SELECT COUNT(*)
                FROM T_KOJI 
                WHERE ADD_TANTCD="' . $LOGIN_ID . '" 
                AND SITAMI_YMD="' . date('d.m.Y', strtotime("-1 days")) . '" 
                AND REPORT_FLG="01"';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data[] = $row["COUNT(*)"];
                        $data[] = 'SITAMI_TOTAL';
                        $resultSet[] = $data;
                    }
                }

                // お知らせ(部材発注申請)    
                $sql = 'SELECT COUNT(*)
                FROM T_BUZAIHACYU 
                LEFT JOIN M_TANT ON T_BUZAIHACYU.SYOZOKU_CD = M_TANT.SYOZOKU_CD
                WHERE T_BUZAIHACYU.SYOZOKU_CD="' . $temp_data . '" 
                AND T_BUZAIHACYU.HACYU_OKFLG= "01"
                AND M_TANT.BUZAI_HACOK_FLG= 1';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data[] = $row["COUNT(*)"];
                        $data[] = 'BUZAIHACYU_TOTAL';
                        $resultSet[] = $data;
                    }
                }

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }

    function postUpdateKojiReadFlg()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            if (
                isset($_POST['JYUCYU_ID'])
            ) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $sql = 'UPDATE KOJI SET READ_FLG= 1 WHERE JYUCYU_ID="' . $JYUCYU_ID . '" ';
                $this->result = $this->dbConnect->query($sql);

                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }
    /* * * * * * * * * *
    * * * * MENU END* * * * 
    * * * * * * * * */
}
