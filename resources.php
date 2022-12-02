<?php

include('systemConfig.php');
include('systemEditor.php');

class resources
{
    private $dbReference;
    var $dbConnect;
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
            $resultSet = array();
            $flg = 0;
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
                HOMON_TANT_CD4,         
                SETSAKI_NAME FROM T_KOJI WHERE SITAMI_YMD="' . $YMD . '" 
                AND HOMON_TANT_CD4="' . $LOGIN_ID . '"
                AND SYUYAKU_JYUCYU_ID IS NULL AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
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
                        $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                        $data['LOGIN_ID'] = $LOGIN_ID;
                        $resultSet[] = $data;
                    }
                }

                $sql = 'SELECT KOJIHOMONJIKAN,
                HOMON_SBT,
                KOJI_ST,
                JYUCYU_ID,
                KOJI_JININ,
                KOJI_JIKAN,
                KOJI_ITEM,
                SETSAKI_ADDRESS,
                KOJI_YMD,
                HOMON_TANT_CD1,
                HOMON_TANT_CD2,
                HOMON_TANT_CD3,
                SETSAKI_NAME FROM T_KOJI WHERE KOJI_YMD="' . $YMD . '"
                AND (HOMON_TANT_CD1="' . $LOGIN_ID . '" OR HOMON_TANT_CD2="' . $LOGIN_ID . '" OR HOMON_TANT_CD3="' . $LOGIN_ID . '")
                AND SYUYAKU_JYUCYU_ID IS NULL AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
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
                        $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                        $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                        $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                        $data['LOGIN_ID'] = $LOGIN_ID;
                        $resultSet[] = $data;
                    }
                }

                if (!empty($resultSet)) {
                    $flg = 1;
                }
            }

            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(200, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $flg = 0;
            $count_jyucyu = null;
            $count_syuyaku_jyucyu = null;

            if (isset($_GET['YMD']) && isset($_GET['JYUCYU_ID']) && isset($_GET['SETSAKI_ADDRESS'])) {
                $YMD = $_GET['YMD'];
                $SETSAKI_ADDRESS = $_GET['SETSAKI_ADDRESS'];
                $JYUCYU_ID = $_GET['JYUCYU_ID'];

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
                    $flg = 1;
                }
            }

            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode((int)$count_syuyaku_jyucyu, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(200, json_encode($count_syuyaku_jyucyu = 0, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $flg = 0;
            $resultSet = array();
            $domain = $this->domain;
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
                        if ($this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['FILEPATH_ID'] = $row['FILEPATH_ID'];
                                $data['FILEPATH'] = $domain . $row['FILEPATH'];
                                $data['SINGLE_SUMMARIZE'] = 1;
                                $resultSet[] = $data;
                            }
                        }
                    }

                    if ($HOMON_SBT == "02") {
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
                        if ($this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $KOJIIRAISYO_FILEPATH = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['KOJIIRAISYO_FILEPATH'] = $KOJIIRAISYO_FILEPATH;
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['FILEPATH_ID'] = $row['FILEPATH_ID'];
                                $data['FILEPATH'] = $domain . $row['FILEPATH'];
                                $data['SINGLE_SUMMARIZE'] = 1;
                                $resultSet[] = $data;
                            }
                        }
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
                        if ($this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['DOMAIN'] = $domain;
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . '/' . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['FILEPATH_ID'] = $row['FILEPATH_ID'];
                                $data['FILEPATH'] = $domain . '/' . $row['FILEPATH'];
                                $data['SINGLE_SUMMARIZE'] = 2;
                                $resultSet[] = $data;
                            }
                        }
                    }

                    if ($HOMON_SBT == "02") {
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
                        if ($this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['DOMAIN'] = $domain;
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['FILEPATH_ID'] = $row['FILEPATH_ID'];
                                $data['FILEPATH'] = $domain . $row['FILEPATH'];
                                $data['SINGLE_SUMMARIZE'] = 2;
                                $resultSet[] = $data;
                            }
                        }
                    }
                }

                if (!empty($resultSet)) {
                    $flg = 1;
                }
            }

            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(200, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $flg = 0;
            $resultSet = array();
            $domain = $this->domain;
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

                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $data = array();
                            $data['FILEPATH'] = $domain . $row['FILEPATH'];
                            $data['ID'] = $row['ID'];
                            $data['FILEPATH_ID'] = $row['FILEPATH_ID'];
                            $data['SINGLE_SUMMARIZE'] = 1;
                            $resultSet[] = $data;
                        }
                    }
                }

                if ($SINGLE_SUMMARIZE == 2) {
                    $sql = 'SELECT T_KOJI_FILEPATH.FILEPATH,
                    T_KOJI_FILEPATH.FILEPATH_ID,
                    T_KOJI.JYUCYU_ID, 
                    T_KOJI.SYUYAKU_JYUCYU_ID
                    FROM T_KOJI_FILEPATH LEFT JOIN T_KOJI ON T_KOJI_FILEPATH.ID=T_KOJI.JYUCYU_ID
                    WHERE SYUYAKU_JYUCYU_ID= "' . $JYUCYU_ID . '"
                    AND FILE_KBN_CD="05"';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $data = array();
                            $data['FILEPATH'] = $domain . $row['FILEPATH'];
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                            $data['FILEPATH_ID'] = $row['FILEPATH_ID'];
                            $data['SINGLE_SUMMARIZE'] = 2;
                            $resultSet[] = $data;
                        }
                    }
                }
            }

            if (!empty($resultSet)) {
                $flg = 1;
            }

            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $flg = 0;
            $resultSet = array();
            $domain = $this->domain;
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
                    if ($this->result->num_rows > 0) {
                        // output data of each row                    
                        while ($row = $this->result->fetch_assoc()) {
                            $data = array();
                            $data['FILEPATH'] = $domain . $row['FILEPATH'];
                            $data['FILEPATH_ID'] = $row['FILEPATH_ID'];
                            $data['ID'] = $row['ID'];
                            $data['KOJI_ST'] = $KOJI_ST;
                            $resultSet[] = $data;
                        }
                    }
                }
            }

            if (!empty($resultSet)) {
                $flg = 1;
            }

            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    /* ==================================================================== 写真提出-登録 */
    function uploadFileImg($file)
    {
        if (!empty($file)) {
            $path = 'img/';
            $dataUploadFile = array();
            $target_file = $path . basename($file["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // $uploadOk = 1;
            // Check file size                
            if ($file["size"] > 500000) {
                // echo "Sorry, your file is too large.";
                $dataUploadFile['ERROR'][] = "Sorry, your file is too large.";
            }
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
            ) {
                // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $dataUploadFile['ERROR'][] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
            // Check if $uploadOk is set to 0 by an error
            move_uploaded_file($file["tmp_name"], $target_file);
        }
        $dataUploadFile['FILEPATH'][] = $target_file;
        return $dataUploadFile;
    }

    function uploadFilePdf($file)
    {
        if (!empty($file)) {
            $path = 'pdf/';
            $target_file = $path . basename($file["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $uploadOk = 1;
            // Check file size                
            if ($file["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            if (
                $imageFileType != "pdf"
            ) {
                echo "Sorry, only PDF files are allowed.";
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
                $img_path = [];
                $img_path = $this->uploadFileImg($_FILES['FILE_IMAGE']);

                if (!empty($img_path['ERROR'])) {
                    $this->dbReference->sendResponse(400, json_encode($img_path['ERROR'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                    die;
                }

                $JYUCYU_ID = $_POST['JYUCYU_ID'];
                $FILE_NAME = isset($_POST['FILE_NAME']) ? $_POST['FILE_NAME'] : 'NULL';
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
                if (!empty($img_path['FILEPATH'][0])) {
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
                    "' . $img_path['FILEPATH'][0] . '",
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

                $domain =  $this->domain;
                $data = array();
                $data['IMG'] = $domain . $img_path['FILEPATH'][0];
                $data['JYUCYU_ID'] = $JYUCYU_ID;

                $this->dbReference->sendResponse(200, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $flg = 0;
            $resultSet = array();
            $list_jisya_cd = [];
            if (isset($_GET['JYUCYU_ID']) && isset($_GET['KOJI_ST'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $KOJI_ST = $_GET['KOJI_ST'];

                if (in_array($KOJI_ST, ["01", "02"])) {
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
                    T_KOJI.KOJI_ST,
                    T_KOJI.HOJIN_FLG,
                    M_GYOSYA.KOJIGYOSYA_NAME FROM T_KOJI 
                    LEFT JOIN M_GYOSYA ON T_KOJI.KOJIGYOSYA_CD=M_GYOSYA.KOJIGYOSYA_CD 
                    WHERE JYUCYU_ID= "' . $JYUCYU_ID . '"              
                    AND HOJIN_FLG= 0 
                    AND T_KOJI.DEL_FLG= 0';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row                    
                        while ($row = $this->result->fetch_assoc()) {
                            $data = array();
                            $data['STATUS'] = $row['JYUCYU_ID'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_NAME1'] = $row['HOMON_TANT_NAME1'];
                            $data['HOMON_TANT_NAME2'] = $row['HOMON_TANT_NAME2'];
                            $data['HOMON_TANT_NAME3'] = $row['HOMON_TANT_NAME3'];
                            $data['HOMON_TANT_NAME4'] = $row['HOMON_TANT_NAME4'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['KOJIGYOSYA_NAME'] = $row['KOJIGYOSYA_NAME'];
                            $data['STATUS'] = 'NOT_REPORTED';
                            $resultSet['KOJI_DATA'][] = $data;
                        }
                    }

                    $resultSet['TABLE_DATA'][] = [];
                }

                if ($KOJI_ST == "03") {
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
                    T_KOJI.KOJI_ST,
                    T_KOJI.HOJIN_FLG,
                    T_KOJI.KOJIGYOSYA_CD 
                    FROM T_KOJI                    
                    LEFT JOIN M_GYOSYA ON T_KOJI.KOJIGYOSYA_CD=M_GYOSYA.KOJIGYOSYA_CD 
                    WHERE T_KOJI.JYUCYU_ID= "' . $JYUCYU_ID . '"                
                    AND T_KOJI.HOJIN_FLG= 0 
                    AND T_KOJI.DEL_FLG= 0';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row                    
                        while ($row = $this->result->fetch_assoc()) {
                            $data = array();
                            $data['STATUS'] = $row['JYUCYU_ID'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_NAME1'] = $row['HOMON_TANT_NAME1'];
                            $data['HOMON_TANT_NAME2'] = $row['HOMON_TANT_NAME2'];
                            $data['HOMON_TANT_NAME3'] = $row['HOMON_TANT_NAME3'];
                            $data['HOMON_TANT_NAME4'] = $row['HOMON_TANT_NAME4'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['KOJIGYOSYA_NAME'] = $row['KOJIGYOSYA_NAME'];                           
                            $data['STATUS'] = 'REPORTED';                           

                            $resultSet['KOJI_DATA'][] = $data;
                        }
                    }

                    $sql = 'SELECT T_KOJI.JYUCYU_ID,                   
                    T_KOJIMSAI.TUIKA_SYOHIN_NAME,
                    T_KOJIMSAI.TUIKA_JISYA_CD,
                    T_KOJIMSAI.SURYO,
                    T_KOJIMSAI.HANBAI_TANKA,
                    T_KOJIMSAI.KINGAK FROM T_KOJI 
                    LEFT JOIN T_KOJIMSAI ON T_KOJI.JYUCYU_ID=T_KOJIMSAI.JYUCYU_ID                    
                    WHERE T_KOJI.JYUCYU_ID= "' . $JYUCYU_ID . '"                
                    AND T_KOJI.HOJIN_FLG= 0 
                    AND T_KOJI.DEL_FLG= 0 
                    AND KOJIJITUIKA_FLG= 1';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row                    
                        while ($row = $this->result->fetch_assoc()) {
                            $data = array();
                            $data['STATUS'] = $row['JYUCYU_ID'];                           
                            $data['TUIKA_SYOHIN_NAME'] = $row['TUIKA_SYOHIN_NAME'];
                            $data['TUIKA_JISYA_CD'] = substr($row['TUIKA_JISYA_CD'] , -4);
                            $data['SURYO'] = $row['SURYO'];
                            $data['HANBAI_TANKA'] = $row['HANBAI_TANKA'];
                            $data['KINGAK'] = $row['KINGAK'];                           
                            $resultSet['TABLE_DATA'][] = $data;
                        }
                    }                                    

                    $sql = 'SELECT * FROM M_KOJI_KAKAKU WHERE DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);
                    if ($this->result->num_rows > 0) {
                        // output data of each row                    
                        while ($row = $this->result->fetch_assoc()) {
                            $resultSet['KOJI_KAKAKU'][] = $row;
                        }
                    }
                }
            }

            if (!empty($resultSet)) {
                $flg = 1;
            }

            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    /* ==================================================================== 法人完了書 */
    function getCorporateCompletionForm()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $flg = 0;
            $resultSet = array();

            if (isset($_GET['KBN_BIKO'])) {
                $KBN_BIKO = $_GET['KBN_BIKO'];

                $sql = 'SELECT YOBIKOMOKU1,
                YOBIKOMOKU2,
                YOBIKOMOKU3,
                YOBIKOMOKU4,
                YOBIKOMOKU5 
                FROM T_KOJI 
                LEFT JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBN_CD 
                WHERE HOJIN_FLG= 1 
                AND KBN_CD= "12" 
                AND KBN_BIKO="' . $KBN_BIKO . '" 
                AND M_KBN.DEL_FLG=0';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
            }

            if (!empty($resultSet)) {
                $flg = 1;
            }

            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
                if ($kojiSt == 1 || $kojiSt == 2) {
                    if ($_GET['SINGLE_SUMMARIZE'] == 1) {
                        //Get HOJIN_FLG
                        $sqlGetHojinFlg = 'SELECT HOJIN_FLG 
                            FROM T_KOJI 
                            WHERE JYUCYU_ID="' . $jyucyuId . '" 
                        ';
                        $this->result = $this->dbConnect->query($sqlGetHojinFlg);
                        if ($this->result->num_rows > 0) {
                            while ($row = $this->result->fetch_assoc()) {
                                $resultSet['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            }
                        }

                        $sqlNotReported = 'SELECT MAKER_CD, HINBAN 
                        FROM T_KOJIMSAI 
                        WHERE JYUCYU_ID="' . $jyucyuId . '" 
                            AND KOJIJITUIKA_FLG<>"0" 
                            AND DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sqlNotReported);
                        if ($this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $resultSet['constructionNotReportSINGLE'][] = $row;
                            }
                        }
                    }
                    if ($_GET['SINGLE_SUMMARIZE'] == 2) {
                        if (isset($_GET['SYUYAKU_JYUCYU_ID'])) {
                            $sqlGetSyuyakuKoji = 'SELECT JYUCYU_ID 
                            FROM T_KOJI 
                            WHERE SYUYAKU_JYUCYU_ID="' . $_GET['SYUYAKU_JYUCYU_ID'] . '" 
                                AND DEL_FLG=0';
                            $this->result = $this->dbConnect->query($sqlGetSyuyakuKoji);

                            $listJyucyuIdKoji = array();
                            if ($this->result->num_rows > 0) {
                                // output data of each row
                                while ($row = $this->result->fetch_assoc()) {
                                    $listJyucyuIdKoji[] = $row;
                                }
                            }

                            foreach ($listJyucyuIdKoji as $value) {
                                //Get HOJIN_FLG
                                $sqlGetHojinFlg = 'SELECT HOJIN_FLG 
                                    FROM T_KOJI 
                                    WHERE JYUCYU_ID="' . $value['JYUCYU_ID'] . '" 
                                ';
                                $this->result = $this->dbConnect->query($sqlGetHojinFlg);
                                if ($this->result->num_rows > 0) {
                                    while ($row = $this->result->fetch_assoc()) {
                                        $resultSet['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                    }
                                }

                                $sqlNotReportedSummarize = 'SELECT MAKER_CD, HINBAN 
                                    FROM T_KOJIMSAI 
                                    WHERE JYUCYU_ID="' . $value['JYUCYU_ID'] . '" 
                                        AND KOJIJITUIKA_FLG<>"0" 
                                        AND DEL_FLG=0';
                                $this->result = $this->dbConnect->query($sqlNotReportedSummarize);
                                if ($this->result->num_rows > 0) {
                                    // output data of each row
                                    while ($row = $this->result->fetch_assoc()) {
                                        $resultSet['constructionNotReportSUMMARIZE'][] = $row;
                                    }
                                }
                            }
                        } else {
                            $this->dbReference->sendResponse(404, '{"error_message": SYUYAKU_JYUCYU_ID required }');
                            die;
                        }
                    }
                } elseif ($kojiSt == 3) {
                    if ($_GET['SINGLE_SUMMARIZE'] == 1) {
                        //Get HOJIN_FLG
                        $sqlGetHojinFlg = 'SELECT HOJIN_FLG 
                            FROM T_KOJI 
                            WHERE JYUCYU_ID="' . $jyucyuId . '" 
                        ';
                        $this->result = $this->dbConnect->query($sqlGetHojinFlg);
                        if ($this->result->num_rows > 0) {
                            while ($row = $this->result->fetch_assoc()) {
                                $resultSet['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            }
                        }

                        $sqlReported = 'SELECT MAKER_CD, HINBAN, KISETU_MAKER_CD, KISETU_HINBAN, BEF_SEKO_PHOTO_FILEPATH, AFT_SEKO_PHOTO_FILEPATH, OTHER_PHOTO_FOLDERPATH
                            FROM T_KOJIMSAI 
                            WHERE JYUCYU_ID="' . $jyucyuId . '" 
                            AND KOJIJITUIKA_FLG<>"0" 
                            AND DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sqlReported);

                        if ($this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $resultSet['constructionReportSINGLE'][] = $row;
                            }
                        }
                    }
                    if ($_GET['SINGLE_SUMMARIZE'] == 2) {
                        if (isset($_GET['SYUYAKU_JYUCYU_ID'])) {
                            $sqlGetSyuyakuKoji = 'SELECT JYUCYU_ID 
                            FROM T_KOJI 
                            WHERE SYUYAKU_JYUCYU_ID="' . $_GET['SYUYAKU_JYUCYU_ID'] . '" 
                                AND DEL_FLG=0';
                            $this->result = $this->dbConnect->query($sqlGetSyuyakuKoji);

                            $listJyucyuIdKoji = array();
                            if ($this->result->num_rows > 0) {
                                // output data of each row
                                while ($row = $this->result->fetch_assoc()) {
                                    $listJyucyuIdKoji[] = $row;
                                }
                            }


                            foreach ($listJyucyuIdKoji as $value) {
                                //Get HOJIN_FLG
                                $sqlGetHojinFlg = 'SELECT HOJIN_FLG 
                                    FROM T_KOJI 
                                    WHERE JYUCYU_ID="' . $value['JYUCYU_ID'] . '" 
                                ';
                                $this->result = $this->dbConnect->query($sqlGetHojinFlg);
                                if ($this->result->num_rows > 0) {
                                    while ($row = $this->result->fetch_assoc()) {
                                        $resultSet['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                    }
                                }

                                $sqlNotReportedSummarize = 'SELECT MAKER_CD, HINBAN, KISETU_MAKER_CD, KISETU_HINBAN, BEF_SEKO_PHOTO_FILEPATH, AFT_SEKO_PHOTO_FILEPATH, OTHER_PHOTO_FOLDERPATH
                                    FROM T_KOJIMSAI 
                                    WHERE JYUCYU_ID="' . $value['JYUCYU_ID'] . '" 
                                        AND KOJIJITUIKA_FLG<>"0" 
                                        AND DEL_FLG=0';
                                $this->result = $this->dbConnect->query($sqlNotReportedSummarize);
                                if ($this->result->num_rows > 0) {
                                    // output data of each row
                                    while ($row = $this->result->fetch_assoc()) {
                                        $resultSet['constructionReportSUMMARIZE'][] = $row;
                                    }
                                }
                            }
                        } else {
                            $this->dbReference->sendResponse(404, '{"error_message": SYUYAKU_JYUCYU_ID required }');
                            die;
                        }
                    }
                } else {
                    $this->dbReference->sendResponse(400, json_encode(['Error_message' => "KOJI_ST_value: 1 || 2 || 3"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $json_string  = file_get_contents('php://input');
            $json_request = json_decode($json_string, true);
            $json_request = (array)$json_request;
            if ($json_request['SINGLE_SUMMARIZE'] == 1 && isset($json_request['JYUCYU_ID'])) {
                $JYUCYU_ID = $json_request['JYUCYU_ID'];
                $BIKO = isset($json_request['BIKO']) ? '"' . $json_request['BIKO'] . '"' : 'NULL';
                $KENSETU_KEITAI = isset($json_request['KENSETU_KEITAI']) ? '"' . $json_request['BIKO'] . '"' : 'NULL';
                $BEF_SEKO_PHOTO_FILEPATH = isset($json_request['BEF_SEKO_PHOTO_FILEPATH']) ? '"' . $json_request['BEF_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $AFT_SEKO_PHOTO_FILEPATH = isset($json_request['AFT_SEKO_PHOTO_FILEPATH']) ? '"' . $json_request['AFT_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $OTHER_PHOTO_FOLDERPATH = isset($json_request['OTHER_PHOTO_FOLDERPATH']) ? '"' . $json_request['OTHER_PHOTO_FOLDERPATH'] . '"' : 'NULL';

                $sqlUpdateKOJI = 'UPDATE T_KOJI 
                    SET KOJI_RENKEI_YMD = "' . date('Y-m-d H:i:s') . '",
                        KOJI_KEKKA = "01",
                        BIKO = ' . $BIKO . ',
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $JYUCYU_ID . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $JYUCYU_ID . '"';
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

                //Store Sign file
                $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                    FROM T_KOJI_FILEPATH';
                $rs_max = $this->dbConnect->query($query_max);
                $num = 0;
                if ($rs_max->num_rows > 0) {
                    // output data of each row
                    while ($row = $rs_max->fetch_assoc()) {
                        $num = (int)$row['FILEPATH_ID_MAX'] + 1;
                    }
                }

                $FILEPATH_ID = sprintf('%010d', $num);
                if (isset($_FILES['SIGN_FILE'])) {
                    $img_path = $this->uploadFilePdf($_FILES['SIGN_FILE']);
                } else {
                    $img_path = 0;
                }

                $sqlInsert = 'INSERT INTO T_KOJI_FILEPATH 
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
                    "' . $JYUCYU_ID . '",
                    "' . $img_path . '",
                    "08",
                    "KOJ1120F",
                    "' . $JYUCYU_ID . '",
                    "' . date('Y-m-d H:i:s') . '",
                    "KOJ1120F",
                    "' . $JYUCYU_ID . '",
                    "' . date('Y-m-d H:i:s') . '"
                )';
                $this->result = $this->dbConnect->query($sqlInsert);
            }
            if (
                $json_request['SINGLE_SUMMARIZE'] == 2 && isset($json_request['JYUCYU_ID'])
                && isset($json_request['SYUYAKU_JYUCYU_ID'])
            ) {
                $JYUCYU_ID = $json_request['JYUCYU_ID'];
                $SYUYAKU_JYUCYU_ID = $json_request['SYUYAKU_JYUCYU_ID'];
                $BIKO = isset($json_request['BIKO']) ? '"' . $json_request['BIKO'] . '"' : 'NULL';
                $KENSETU_KEITAI = isset($json_request['KENSETU_KEITAI']) ? '"' . $json_request['BIKO'] . '"' : 'NULL';
                $BEF_SEKO_PHOTO_FILEPATH = isset($json_request['BEF_SEKO_PHOTO_FILEPATH']) ? '"' . $json_request['BEF_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $AFT_SEKO_PHOTO_FILEPATH = isset($json_request['AFT_SEKO_PHOTO_FILEPATH']) ? '"' . $json_request['AFT_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $OTHER_PHOTO_FOLDERPATH = isset($json_request['OTHER_PHOTO_FOLDERPATH']) ? '"' . $json_request['OTHER_PHOTO_FOLDERPATH'] . '"' : 'NULL';

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
            }
            if (!empty($json_request['NEW_DETAIL'])) {
                foreach ($json_request['NEW_DETAIL'] as $value) {
                    $query_max = 'SELECT max(JYUCYUMSAI_ID) as JYUCYUMSAI_ID_MAX
                        FROM T_KOJIMSAI';
                    $rs_max = $this->dbConnect->query($query_max);
                    $num = 0;
                    if ($rs_max->num_rows > 0) {
                        // output data of each row
                        while ($row = $rs_max->fetch_assoc()) {
                            $num = (int)$row['JYUCYUMSAI_ID_MAX'] + 1;
                        }
                    }

                    $JYUCYUMSAI_ID = sprintf('%010d', $num);

                    $sqlInsertKOJIMSAI = 'INSERT INTO T_KOJIMSAI 
                    (
                        JYUCYU_ID,
                        JYUCYUMSAI_ID,
                        JYUCYUMSAI_ID_KIKAN,
                        HINBAN,
                        MAKER_CD,
                        CTGORY_CD,
                        SURYO,
                        HANBAI_TANKA,
                        KINGAK,
                        KISETU_HINBAN,
                        KISETU_MAKER_CD,
                        KENSETU_KEITAI,
                        BEF_SEKO_PHOTO_FILEPATH,
                        AFT_SEKO_PHOTO_FILEPATH,
                        OTHER_PHOTO_FOLDERPATH,
                        TUIKA_JISYA_CD,
                        TUIKA_SYOHIN_NAME,
                        KOJIJITUIKA_FLG,
                        ADD_PGID,
                        ADD_TANTCD,
                        ADD_YMD,
                        UPD_PGID,
                        UPD_TANTCD,
                        UPD_YMD,             
                    )
                    VALUES (
                        "' . $JYUCYU_ID . '",
                        "' . $JYUCYUMSAI_ID . '",
                        "",
                        "",
                        "",
                        "",
                        "' . $value['SURYO'] . '",
                        "' . $value['HANBAI_TANKA'] . '",
                        "' . $value['KINGAK'] . '",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "KOJ-' . $value['TUIKA_JISYA_CD'] . '",
                        "' . $value['TUIKA_SYOHIN_NAME'] . '",
                        "1",
                        "KOJ1120F",
                        "' . $JYUCYU_ID . '",
                        "' . date('Y-m-d H:i:s') . '",
                        "KOJ1120F",
                        "' . $JYUCYU_ID . '",
                        "' . date('Y-m-d H:i:s') . '"
                    )';

                    $this->result = $this->dbConnect->query($sqlInsertKOJIMSAI);
                }
            }

            $sqlInsertKOJICHECK = 'INSERT INTO T_KOJI_CHECK 
                (
                    JYUCYU_ID,
                    CHECK_FLG1,
                    CHECK_FLG2,
                    CHECK_FLG3,
                    CHECK_FLG4,
                    CHECK_FLG5,
                    CHECK_FLG6,
                    CHECK_FLG7,
                    DEL_FLG,
                    ADD_PGID,
                    ADD_TANTCD,
                    ADD_YMD,
                    UPD_PGID,
                    UPD_TANTCD,
                    UPD_YMD 
                )
                VALUES (
                "' . $JYUCYU_ID . '",
                "' . $json_request['CHECK_FLG'][0]['CHECK_FLG1'] . '",
                "' . $json_request['CHECK_FLG'][0]['CHECK_FLG2'] . '",
                "' . $json_request['CHECK_FLG'][0]['CHECK_FLG3'] . '",
                "' . $json_request['CHECK_FLG'][0]['CHECK_FLG4'] . '",
                "' . $json_request['CHECK_FLG'][0]['CHECK_FLG5'] . '",
                "' . $json_request['CHECK_FLG'][0]['CHECK_FLG6'] . '",
                "' . $json_request['CHECK_FLG'][0]['CHECK_FLG7'] . '",
                "0",
                "KOJ1120F",
                "' . $JYUCYU_ID . '",
                "' . date('Y-m-d H:i:s') . '",
                "KOJ1120F",
                "' . $JYUCYU_ID . '",
                "' . date('Y-m-d H:i:s') . '"
                )';

            $this->result = $this->dbConnect->query($sqlInsertKOJICHECK);
            $this->dbReference->sendResponse(200, "Success");
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
                $KENSETU_KEITAI = isset($_POST['KENSETU_KEITAI']) ? '"' . $_POST['KENSETU_KEITAI'] . '"' : 'NULL';
                $BEF_SEKO_PHOTO_FILEPATH = isset($_POST['BEF_SEKO_PHOTO_FILEPATH']) ? '"' . $_POST['BEF_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $AFT_SEKO_PHOTO_FILEPATH = isset($_POST['AFT_SEKO_PHOTO_FILEPATH']) ? '"' . $_POST['AFT_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $OTHER_PHOTO_FOLDERPATH = isset($_POST['OTHER_PHOTO_FOLDERPATH']) ? '"' . $_POST['OTHER_PHOTO_FOLDERPATH'] . '"' : 'NULL';

                $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                    FROM T_KOJI_FILEPATH';
                $rs_max = $this->dbConnect->query($query_max);
                $num = 0;
                if ($rs_max->num_rows > 0) {
                    // output data of each row
                    while ($row = $rs_max->fetch_assoc()) {
                        $num = (int)$row['FILEPATH_ID_MAX'] + 1;
                    }
                }

                $FILEPATH_ID = sprintf('%010d', $num);

                $sqlInsert = 'INSERT INTO T_KOJI_FILEPATH 
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
                    "' . $JYUCYU_ID . '",
                    "' . $_POST['FILE'] . '",
                    "09",
                    "KOJ1120F",
                    "' . $JYUCYU_ID . '",
                    "' . date('Y-m-d H:i:s') . '",
                    "KOJ1120F",
                    "' . $JYUCYU_ID . '",
                    "' . date('Y-m-d H:i:s') . '"
                )';
                $this->result = $this->dbConnect->query($sqlInsert);

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
                $sql = 'SELECT CANCEL_RIYU, MTMORI_YMD
                        FROM T_KOJI 
                        WHERE JYUCYU_ID="' . $JYUCYU_ID . '" AND DEL_FLG=0';
                $this->result = $this->dbConnect->query($sql);

                $resultSet = array();
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet[] = $row;
                    }
                }
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } elseif (
                isset($_POST['JYUCYU_ID'])
            ) {
                $JYUCYU_ID = $_POST['JYUCYU_ID'];
                $CANCEL_RIYU = isset($_POST['CANCEL_RIYU']) ? '"' . $_POST['CANCEL_RIYU'] . '"' : 'NULL';
                $MTMORI_YMD = isset($_POST['MTMORI_YMD']) ? '"' . $_POST['MTMORI_YMD'] . '"' : 'NULL';

                $sqlInsert = 'INSERT INTO T_KOJI 
                (
                    JYUCYU_ID,
                    CANCEL_RIYU,
                    MTMORI_YMD                     
                )
                VALUES (
                    "' . $JYUCYU_ID . '",
                    ' . $CANCEL_RIYU . ',
                    ' . $MTMORI_YMD . ',
                )';
                $this->result = $this->dbConnect->query($sqlInsert);

                $this->dbReference->sendResponse(200, "Insert Success");
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
                isset($_GET['KOJI_ST'])
            ) {
                if ($_GET['KOJI_ST'] == "01" || $_GET['KOJI_ST'] == "02") {
                    $this->dbReference->sendResponse(200, json_encode(['Text' => "「工事報告が未報告の場合は、写真提出が不可となります。」"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                    die;
                }
                if ($_GET['KOJI_ST'] == "03") {
                    $sql = 'SELECT FILEPATH 
                    FROM T_KOJI_FILEPATH 
                    WHERE FILE_KBN_CD="10" AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);

                    $domain =  $this->domain;

                    $resultSet = array();
                    if ($this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $resultSet[]['FILEPATH'] = $domain . $row['FILEPATH'];
                        }
                    }

                    $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
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
                isset($_POST['JYUCYU_ID']) &&
                ($_POST['SHITAMI_MENU'] == 1)
            ) {
                $sqlUpdateKOJI = 'UPDATE T_KOJI 
                    SET KOJI_RENKEI_YMD = "' . date('Y-m-d H:i:s') . '",
                        KOJI_KEKKA = "工事OK",
                        SITAMI_REPORT = "01",
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $_POST['JYUCYU_ID'] . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $_POST['JYUCYU_ID'] . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJI);


                if (isset($_FILES['FILE_IMAGE'])) {
                    $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                        FROM T_KOJI_FILEPATH';
                    $rs_max = $this->dbConnect->query($query_max);
                    $num = 0;
                    if ($rs_max->num_rows > 0) {
                        // output data of each row
                        while ($row = $rs_max->fetch_assoc()) {
                            $num = (int)$row['FILEPATH_ID_MAX'] + 1;
                        }
                    }

                    $FILEPATH_ID = sprintf('%010d', $num);

                    $img_path = [];
                    $img_path = $this->uploadFileImg($_FILES['FILE_IMAGE']);

                    if (!empty($img_path['ERROR'])) {
                        $this->dbReference->sendResponse(400, json_encode($img_path['ERROR'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                        die;
                    }

                    $sqlInsert = 'INSERT INTO T_KOJI_FILEPATH 
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
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . $img_path['FILEPATH'][0] . '",
                        "10",
                        "KOJ1120F",
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . date('Y-m-d H:i:s') . '",
                        "KOJ1120F",
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . date('Y-m-d H:i:s') . '"
                    )';
                    $this->result = $this->dbConnect->query($sqlInsert);
                }
                $dataSuccess = array();
                $domain =  $this->domain;
                $dataSuccess['IMG'] = $domain . $img_path['FILEPATH'][0];
                $dataSuccess['ID_KOJI_FILE_PATH'] = $FILEPATH_ID;
                $dataSuccess['JYUCYU_ID_KOJI_UPDATE'] = $_POST['JYUCYU_ID'];

                $this->dbReference->sendResponse(200, json_encode($dataSuccess, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
                isset($_POST['JYUCYU_ID']) &&
                ($_POST['SHITAMI_MENU'] == 3)
            ) {
                $sqlUpdateKOJI = 'UPDATE T_KOJI 
                    SET KOJI_RENKEI_YMD = "' . date('Y-m-d H:i:s') . '",
                        KOJI_KEKKA = "工事NG",
                        SITAMI_REPORT = "03",
                        CANCEL_RIYU = "' . $_POST['CANCEL_RIYU'] . '", 
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $_POST['JYUCYU_ID'] . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $_POST['JYUCYU_ID'] . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJI);

                if (isset($_FILES['FILE_IMAGE'])) {
                    $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                        FROM T_KOJI_FILEPATH';
                    $rs_max = $this->dbConnect->query($query_max);
                    $num = 0;
                    if ($rs_max->num_rows > 0) {
                        // output data of each row
                        while ($row = $rs_max->fetch_assoc()) {
                            $num = (int)$row['FILEPATH_ID_MAX'] + 1;
                        }
                    }

                    $FILEPATH_ID = sprintf('%010d', $num);

                    $img_path = [];
                    $img_path = $this->uploadFileImg($_FILES['FILE_IMAGE']);

                    if (!empty($img_path['ERROR'])) {
                        $this->dbReference->sendResponse(400, json_encode($img_path['ERROR'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                        die;
                    }
                    $sqlInsert = 'INSERT INTO T_KOJI_FILEPATH 
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
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . $img_path['FILEPATH'][0] . '",
                        "10",
                        "KOJ1120F",
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . date('Y-m-d H:i:s') . '",
                        "KOJ1120F",
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . date('Y-m-d H:i:s') . '"
                    )';
                    $this->result = $this->dbConnect->query($sqlInsert);
                }

                $dataSuccess = array();
                $domain =  $this->domain;
                $dataSuccess['IMG'] = $domain . $img_path['FILEPATH'][0];
                $dataSuccess['ID_KOJI_FILE_PATH'] = $FILEPATH_ID;
                $dataSuccess['JYUCYU_ID_KOJI_UPDATE'] = $_POST['JYUCYU_ID'];

                $this->dbReference->sendResponse(200, json_encode($dataSuccess, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
                isset($_POST['JYUCYU_ID']) &&
                ($_POST['SHITAMI_MENU'] == 2)
            ) {
                $sqlUpdateKOJI = 'UPDATE T_KOJI 
                    SET KOJI_RENKEI_YMD = "' . date('Y-m-d H:i:s') . '",
                        SITAMI_REPORT = "02",
                        MTMORI_YMD = "' . $_POST['MTMORI_YMD'] . '", 
                        CANCEL_RIYU = "' . $_POST['CANCEL_RIYU'] . '", 
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $_POST['JYUCYU_ID'] . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $_POST['JYUCYU_ID'] . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJI);

                if (isset($_FILES['FILE_IMAGE'])) {
                    $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                        FROM T_KOJI_FILEPATH';
                    $rs_max = $this->dbConnect->query($query_max);
                    $num = 0;
                    if ($rs_max->num_rows > 0) {
                        // output data of each row
                        while ($row = $rs_max->fetch_assoc()) {
                            $num = (int)$row['FILEPATH_ID_MAX'] + 1;
                        }
                    }

                    $FILEPATH_ID = sprintf('%010d', $num);

                    $img_path = [];
                    $img_path = $this->uploadFileImg($_FILES['FILE_IMAGE']);
                    if (!empty($img_path['ERROR'])) {
                        $this->dbReference->sendResponse(400, json_encode($img_path['ERROR'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                        die;
                    }

                    $sqlInsert = 'INSERT INTO T_KOJI_FILEPATH 
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
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . $img_path['FILEPATH'][0] . '",
                        "10",
                        "KOJ1120F",
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . date('Y-m-d H:i:s') . '",
                        "KOJ1120F",
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . date('Y-m-d H:i:s') . '"
                    )';
                    $this->result = $this->dbConnect->query($sqlInsert);
                }

                $dataSuccess = array();
                $domain =  $this->domain;
                $dataSuccess['IMG'] = $domain . $img_path['FILEPATH'][0];
                $dataSuccess['ID_KOJI_FILE_PATH'] = $FILEPATH_ID;
                $dataSuccess['JYUCYU_ID_KOJI_UPDATE'] = $_POST['JYUCYU_ID'];

                $this->dbReference->sendResponse(200, json_encode($dataSuccess, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
                isset($_POST['JYUCYU_ID']) &&
                ($_POST['SHITAMI_MENU'] == 4)
            ) {
                $sqlUpdateKOJI = 'UPDATE T_KOJI 
                    SET KOJI_RENKEI_YMD = "' . date('Y-m-d H:i:s') . '",
                        SITAMI_REPORT = "04",
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $_POST['JYUCYU_ID'] . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $_POST['JYUCYU_ID'] . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJI);

                if (isset($_FILES['FILE_IMAGE'])) {
                    $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                        FROM T_KOJI_FILEPATH';
                    $rs_max = $this->dbConnect->query($query_max);
                    $num = 0;
                    if ($rs_max->num_rows > 0) {
                        // output data of each row
                        while ($row = $rs_max->fetch_assoc()) {
                            $num = (int)$row['FILEPATH_ID_MAX'] + 1;
                        }
                    }

                    $FILEPATH_ID = sprintf('%010d', $num);

                    $img_path = [];
                    $img_path = $this->uploadFileImg($_FILES['FILE_IMAGE']);
                    if (!empty($img_path['ERROR'])) {
                        $this->dbReference->sendResponse(400, json_encode($img_path['ERROR'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                        die;
                    }

                    $sqlInsert = 'INSERT INTO T_KOJI_FILEPATH 
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
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . $img_path['FILEPATH'][0] . '",
                        "10",
                        "KOJ1120F",
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . date('Y-m-d H:i:s') . '",
                        "KOJ1120F",
                        "' . $_POST['JYUCYU_ID'] . '",
                        "' . date('Y-m-d H:i:s') . '"
                    )';
                    $this->result = $this->dbConnect->query($sqlInsert);
                }

                $dataSuccess = array();
                $domain =  $this->domain;
                $dataSuccess['IMG'] = $domain . $img_path['FILEPATH'][0];
                $dataSuccess['ID_KOJI_FILE_PATH'] = $FILEPATH_ID;
                $dataSuccess['JYUCYU_ID_KOJI_UPDATE'] = $_POST['JYUCYU_ID'];

                $this->dbReference->sendResponse(200, json_encode($dataSuccess, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
                            AND T_EIGYO_ANKEN.DEL_FLG=0 
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
                        $data['TAN_EIG_ID'] = $row['TAN_EIG_ID'];
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
                     AND T_TBETUCALENDAR.DEL_FLG=0 
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
                    AND T_KOJI.DEL_FLG=0 
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
                        AND T_KOJI.DEL_FLG=0 
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
                    AND T_EIGYO_ANKEN.DEL_FLG=0 
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
                    T_TBETUCALENDAR.MEMO_CD,                    
                    M_KBN.KBNMSAI_CD, 
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.YOBIKOMOKU1,                   
                    M_TANT.TANT_CD,
                    M_TANT.TANT_NAME         
                    FROM T_TBETUCALENDAR 
                    LEFT JOIN M_KBN ON T_TBETUCALENDAR.TAG_KBN=M_KBN.KBN_CD AND T_TBETUCALENDAR.MEMO_CD=M_KBN.KBNMSAI_CD
                    LEFT JOIN M_TANT ON T_TBETUCALENDAR.JYOKEN_CD=M_TANT.TANT_CD AND T_TBETUCALENDAR.JYOKEN_SYBET_FLG=0 
                    WHERE T_TBETUCALENDAR.YMD >= "' . $start_date . '" 
                    AND T_TBETUCALENDAR.YMD <= "' . $end_date . '"     
                    AND T_TBETUCALENDAR.DEL_FLG=0                                     
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
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['TANT_NAME'] = $row['TANT_NAME'];
                            $data['TANT_CD'] = $row['TANT_CD'];
                            $data['TAN_CAL_ID'] = $row['TAN_CAL_ID'];
                            $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                            $data['MEMO_CD'] = $row['MEMO_CD'];
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
                    AND T_KOJI.DEL_FLG=0 
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
                    AND T_KOJI.DEL_FLG=0 
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
                    AND T_KOJI.DEL_FLG=0 
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
                        AND T_KOJI.DEL_FLG=0 
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
                    AND T_EIGYO_ANKEN.DEL_FLG=0 
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
                    AND T_TBETUCALENDAR.DEL_FLG=0 
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
                    AND T_KOJI.DEL_FLG=0 
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
                    AND T_KOJI.DEL_FLG=0 
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
            $flg = 0;
            $resultSet = array();
            $domain = $this->domain;

            if (isset($_GET['JYUCYU_ID']) && isset($_GET['HOMON_SBT'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $HOMON_SBT = $_GET['HOMON_SBT'];

                $sql_get_list_file = 'SELECT FILEPATH_ID, 
                        ID, 
                        FILEPATH, 
                        FILE_KBN_CD 
                        FROM T_KOJI_FILEPATH
                        WHERE ID="' . $JYUCYU_ID . '"
                        AND (T_KOJI_FILEPATH.FILE_KBN_CD="03" OR T_KOJI_FILEPATH.FILE_KBN_CD="04" OR T_KOJI_FILEPATH.FILE_KBN_CD="05")
                        AND DEL_FLG=0';
                $arr_list_file = array();
                $result_list_file = $this->dbConnect->query($sql_get_list_file);
                if ($result_list_file->num_rows > 0) {
                    while ($row_list_file = $result_list_file->fetch_assoc()) {
                        $data_list_file = array();
                        $data_list_file['FILEPATH_ID'] = $row_list_file['FILEPATH_ID'];
                        $data_list_file['ID'] = $row_list_file['ID'];
                        $data_list_file['FILEPATH'] = $domain . $row_list_file['FILEPATH'];
                        $data_list_file['FILE_KBN_CD'] = $row_list_file['FILE_KBN_CD'];
                        $arr_list_file[] = $data_list_file;
                    }
                }

                switch ($HOMON_SBT) {
                    case "01":
                        $sql = 'SELECT JYUCYU_ID, 
                        T_KOJI.SITAMI_JININ, 
                        T_KOJI.SITAMIHOMONJIKAN, 
                        T_KOJI.SITAMIHOMONJIKAN_END, 
                        T_KOJI.SITAMI_JIKAN, 
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
                        M_KBN.KBNMSAI_NAME                        
                        FROM T_KOJI 
                        LEFT JOIN M_KBN ON T_KOJI.TAG_KBN = M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="01" 
                        WHERE T_KOJI.JYUCYU_ID="' . $JYUCYU_ID . '"
                        AND M_KBN.KBN_CD="05"                         
                        AND T_KOJI.DEL_FLG=0';
                        // echo $sql; die;
                        $this->result = $this->dbConnect->query($sql);
                        if ($this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['JININ'] = $row['SITAMI_JININ'];
                                $data['HOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['HOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['HOMON_TANT_NAME1'] = $row['HOMON_TANT_NAME1'];
                                $data['HOMON_TANT_NAME2'] = $row['HOMON_TANT_NAME2'];
                                $data['HOMON_TANT_NAME3'] = $row['HOMON_TANT_NAME3'];
                                $data['HOMON_TANT_NAME4'] = $row['HOMON_TANT_NAME4'];
                                $data['ADD_TANTNM'] = $row['ADD_TANTNM'];
                                $data['ADD_YMD'] = $row['ADD_YMD'];
                                $data['UPD_TANTNM'] = $row['UPD_TANTNM'];
                                $data['UPD_YMD'] = $row['UPD_YMD'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['FILEPATH'] = $arr_list_file;
                                $resultSet[] = $data;
                            }
                        }
                        break;
                    case "02":
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
                        M_KBN.KBNMSAI_NAME                  
                        FROM T_KOJI 
                        LEFT JOIN M_KBN ON T_KOJI.TAG_KBN = M_KBN.KBN_CD AND M_KBN.KBNMSAI_CD="01"                       
                        WHERE T_KOJI.JYUCYU_ID="' . $JYUCYU_ID . '"
                        AND M_KBN.KBN_CD="05"                         
                        AND T_KOJI.DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sql);
                        if ($this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['JININ'] = $row['KOJI_JININ'];
                                $data['HOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['HOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['JIKAN'] = $row['KOJI_JIKAN'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['HOMON_TANT_NAME1'] = $row['HOMON_TANT_NAME1'];
                                $data['HOMON_TANT_NAME2'] = $row['HOMON_TANT_NAME2'];
                                $data['HOMON_TANT_NAME3'] = $row['HOMON_TANT_NAME3'];
                                $data['HOMON_TANT_NAME4'] = $row['HOMON_TANT_NAME4'];
                                $data['ADD_TANTNM'] = $row['ADD_TANTNM'];
                                $data['ADD_YMD'] = $row['ADD_YMD'];
                                $data['UPD_TANTNM'] = $row['UPD_TANTNM'];
                                $data['UPD_YMD'] = $row['UPD_YMD'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['FILEPATH'] = $arr_list_file;
                                $resultSet[] = $data;
                            }
                        }
                        break;
                    default:
                        $flg = 0;
                        break;
                }

                if (!empty($resultSet)) {
                    $flg = 1;
                }
            }
            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $resultSet = array();
            $flg = 0;

            if (isset($_GET['JYUCYU_ID']) && isset($_GET['HOMON_SBT'])) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $HOMON_SBT = $_GET['HOMON_SBT'];

                switch ($HOMON_SBT) {
                    case '01':
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
                        ALL_DAY_FLG,
                        MEMO  FROM T_KOJI 
                        WHERE JYUCYU_ID="' . $JYUCYU_ID . '" 
                        AND DEL_FLG= 0';
                        $this->result = $this->dbConnect->query($sql);
                        if ($this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['HOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['HOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['JININ'] = $row['SITAMI_JININ'];
                                $data['JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['UPD_TANTNM'] = $row['UPD_TANTNM'];
                                $data['UPD_YMD'] = $row['UPD_YMD'];
                                $data['SITAMI_KANSAN_POINT'] = ceil($row['SITAMI_KANSAN_POINT']);
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['MEMO'] = $row['MEMO'];
                                $resultSet['DATA'][] = $data;
                            }
                        }
                        break;
                    case '02':
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
                        ALL_DAY_FLG,
                        MEMO  FROM T_KOJI 
                        WHERE JYUCYU_ID="' . $JYUCYU_ID . '" 
                        AND DEL_FLG= 0';
                        $this->result = $this->dbConnect->query($sql);
                        if ($this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['HOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['HOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['JININ'] = $row['KOJI_JININ'];
                                $data['JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['UPD_TANTNM'] = $row['UPD_TANTNM'];
                                $data['UPD_YMD'] = $row['UPD_YMD'];
                                $data['KOJI_KANSAN_POINT'] = ceil($row['KOJI_KANSAN_POINT']);
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['MEMO'] = $row['MEMO'];
                                $resultSet['DATA'][] = $data;
                            }
                        }
                        break;
                    default:
                        $flg = 0;
                        break;
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
            }

            if (!empty($resultSet['DATA'])) {
                $flg = 1;
            }

            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $flg = 0;
            if (isset($_POST['JYUCYU_ID'])) {
                $JYUCYU_ID = $_POST['JYUCYU_ID'];
                $TAG_KBN = $_POST['TAG_KBN'];
                $KBN = isset($_POST['KBN']) ? $_POST['KBN'] : NULL;
                $HOMONJIKAN = isset($_POST['HOMONJIKAN']) ? $_POST['HOMONJIKAN'] : NULL;
                $HOMONJIKAN_END = isset($_POST['HOMONJIKAN_END']) ? $_POST['HOMONJIKAN_END'] : NULL;
                $JININ = isset($_POST['JININ']) ? $_POST['JININ'] : NULL;
                $KANSAN_POINT = isset($_POST['KANSAN_POINT']) ? $_POST['KANSAN_POINT'] : NULL;
                $ALL_DAY_FLG = isset($_POST['ALL_DAY_FLG']) ? $_POST['ALL_DAY_FLG'] : NULL;
                $MEMO = isset($_POST['MEMO']) ? $_POST['MEMO'] : NULL;
                $HOMON_SBT = $_POST['HOMON_SBT'];
                $KBNMSAI_CD = $_POST['KBNMSAI_CD'];
                $JIKAN = $_POST['JIKAN'];
                $UPD_TANTCD = isset($_POST['UPD_TANTCD']) ? $_POST['UPD_TANTCD'] : '000001';
                $SKJ_RENKEI_YMD = date("Y-m-d");
                $UPD_PGID = 'KOJ1110F';
                $UPD_YMD = date("Y-m-d H:i:s");

                if ($HOMON_SBT == "01") {
                    $sql = ' UPDATE T_KOJI
                    SET TAG_KBN="' . $TAG_KBN . '",
                    SITAMIAPO_KBN="' . $KBN . '",
                    SITAMIHOMONJIKAN="' . $HOMONJIKAN . '",
                    SITAMIHOMONJIKAN_END="' . $HOMONJIKAN_END . '",
                    SITAMI_JININ=' . $JININ . ',
                    SITAMI_KANSAN_POINT=' . $KANSAN_POINT . ',
                    ALL_DAY_FLG=' . $ALL_DAY_FLG . ',
                    SKJ_RENKEI_YMD="' . $SKJ_RENKEI_YMD . '",
                    UPD_PGID= "' . $UPD_PGID . '",
                    UPD_TANTCD="' . $UPD_TANTCD . '",
                    UPD_YMD="' . $UPD_YMD . '",
                    MEMO="' . $MEMO . '",
                    SITAMI_JIKAN="' . $JIKAN . '"                 
                    WHERE JYUCYU_ID="' . $JYUCYU_ID . '"
                    AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);
                    $this->result == true ? $flg = 1 : $flg = 0;
                }

                if ($HOMON_SBT == "02") {
                    $sql = 'UPDATE T_KOJI 
                    SET TAG_KBN="' . $TAG_KBN . '",
                    KOJIAPO_KBN="' . $KBN . '",
                    KOJIHOMONJIKAN="' . $HOMONJIKAN . '",
                    KOJIHOMONJIKAN_END="' . $HOMONJIKAN_END . '",
                    KOJI_JININ=' . $JININ . ',
                    KOJI_KANSAN_POINT=' . $KANSAN_POINT . ',
                    ALL_DAY_FLG=' . $ALL_DAY_FLG . ',
                    SKJ_RENKEI_YMD="' . $SKJ_RENKEI_YMD . '",
                    UPD_PGID= "' . $UPD_PGID . '",
                    UPD_TANTCD="' . $UPD_TANTCD . '",            
                    UPD_YMD="' . $UPD_YMD . '",
                    MEMO="' . $MEMO . '" ,
                    KOJI_JIKAN="' . $JIKAN . '"       
                    WHERE JYUCYU_ID="' . $JYUCYU_ID . '"
                    AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);
                    $this->result == true ? $flg = 1 : $flg = 0;
                }
            }

            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
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
                ATTEND_NAME3 FROM T_EIGYO_ANKEN 
                WHERE TAN_EIG_ID= "' . $TAN_EIG_ID . '" 
                AND DEL_FLG= 0';
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
                isset($_POST['JYOKEN_CD']) && isset($_POST['YMD'])  && isset($_POST['JYOKEN_SYBET_FLG']) && isset($_POST['START_TIME'])
            ) {
                $YMD = $_POST['YMD'];
                $JYOKEN_CD = $_POST['JYOKEN_CD'];
                $JYOKEN_SYBET_FLG = $_POST['JYOKEN_SYBET_FLG'];

                $TAN_EIG_ID = isset($_POST['TAN_EIG_ID']) ? '"' . $_POST['TAN_EIG_ID'] . '"' : 'NULL';
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
                // $query_eigyo_anken = 'SELECT TAN_EIG_ID 
                // FROM T_EIGYO_ANKEN 
                // WHERE JYOKEN_CD=' . $JYOKEN_CD . ' 
                // AND YMD="' . $YMD . '" 
                // AND JYOKEN_SYBET_FLG=' . $JYOKEN_SYBET_FLG . ' 
                // AND START_TIME=' . $START_TIME . '
                // AND DEL_FLG=0';
                // $count_eigyo_anken = $this->dbConnect->query($query_eigyo_anken);               
                if ($TAN_EIG_ID != 'NULL') {
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
                    UPD_TANTCD="' . $UPD_TANTCD . '",
                    UPD_YMD="' . $UPD_YMD . '" 
                    WHERE TAN_EIG_ID="' . $TAN_EIG_ID . '"                
                    AND DEL_FLG=0';
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
                    DEL_FLG,                    
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
                    0,                 
                    "' . $ADD_PGID . '",
                    "' . $ADD_TANTCD . '",
                    "' . $ADD_YMD . '",
                    "' . $UPD_PGID . '",
                    "' . $UPD_TANTCD . '",
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
                                AND DEL_FLG=0';
                $this->result = $this->dbConnect->query($sql);

                //Get data M_KBN
                $sqlPulldown = 'SELECT KBN_CD, KBN_NAME, KBNMSAI_CD, KBNMSAI_NAME 
                                    FROM M_KBN 
                                    WHERE KBN_CD="06" 
                                        AND DEL_FLG=0';
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
                isset($_POST['YMD']) &&
                isset($_POST['START_TIME'])
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
                            AND DEL_FLG=0 
                            AND START_TIME=' . $START_TIME . '
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
                            AND YMD="' . $ymd . '" 
                            AND DEL_FLG=0 
                            AND START_TIME=' . $START_TIME . '
                        ';
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
                isset($_GET['HOLIDAY_YEAR']) &&
                isset($_GET['GET_MONTH']) &&
                isset($_GET['GET_YEAR'])
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
                        $resultSet[] = $row;
                        $totalHolidays = 0;
                        foreach ($row as $value) {
                            $totalHolidays = $totalHolidays + $value;
                        }
                        $resultSet['totalHolidays'] = $totalHolidays;
                    }
                } else {
                    $resultSet['0']['HOLIDAY_JAN'] = "0";
                    $resultSet['0']['HOLIDAY_FEB'] = "0";
                    $resultSet['0']['HOIDAY_MAR'] = "0";
                    $resultSet['0']['HOIDAY_APR'] = "0";
                    $resultSet['0']['HOIDAY_MAY'] = "0";
                    $resultSet['0']['HOIDAY_JUN'] = "0";
                    $resultSet['0']['HOIDAY_JUL'] = "0";
                    $resultSet['0']['HOIDAY_AUG'] = "0";
                    $resultSet['0']['HOIDAY_SEP'] = "0";
                    $resultSet['0']['HOIDAY_OCT'] = "0";
                    $resultSet['0']['HOIDAY_NOV'] = "0";
                    $resultSet['0']['HOIDAY_DEC'] = "0";
                    $resultSet['totalHolidays'] = 0;
                }

                $sqlGetHolidayMonth = 'SELECT TAN_CAL_ID 
                                FROM T_TBETUCALENDAR 
                                WHERE JYOKEN_CD="' . $tantCd . '" 
                                    AND MEMO_CD="04" 
                                    AND MONTH(`YMD`)="' . $_GET['GET_MONTH'] . '"
                                    AND DEL_FLG=0
                                ';
                $this->result = $this->dbConnect->query($sqlGetHolidayMonth);
                $resultSet['totalHolidaysPerMonth'] = $this->result->num_rows;

                $sqlGetHolidayYear = 'SELECT TAN_CAL_ID 
                                FROM T_TBETUCALENDAR 
                                WHERE JYOKEN_CD="' . $tantCd . '" 
                                    AND MEMO_CD="04" 
                                    AND YEAR(`YMD`)="' . $_GET['GET_YEAR'] . '"
                                    AND DEL_FLG=0
                                ';
                $this->result = $this->dbConnect->query($sqlGetHolidayYear);
                $resultSet['totalHolidaysPerYear'] = $this->result->num_rows;


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
            $flg = 0;
            $resultSet = array();
            if (isset($_GET['LOGIN_ID'])) {
                // 新着コメント
                $LOGIN_ID = $_GET['LOGIN_ID'];
                $sql = 'SELECT COMMENT , JYUCYU_ID
                FROM T_KOJI 
                WHERE ADD_TANTCD="' . $LOGIN_ID . '" 
                AND COMMENT IS NOT NULL 
                AND READ_FLG IS NULL';
                $this->result = $this->dbConnect->query($sql);
                if ($this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['COMMENT'][] = $row;
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
                        $data['NYUKOYOTEI_TOTAL'] = $row["COUNT(*)"];
                        $resultSet['TOTAL'][] = $data;
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
                        $data['KOJI_TOTAL'] = $row["COUNT(*)"];
                        $resultSet['TOTAL'][] = $data;
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
                        $data['SITAMI_TOTAL'] = $row["COUNT(*)"];
                        $resultSet['TOTAL'][] = $data;
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
                        $data['BUZAIHACYU_TOTAL'] = $row["COUNT(*)"];
                        $resultSet['TOTAL'][] = $data;
                    }
                }
            }

            if (!empty($resultSet)) {
                $flg = 1;
            }

            if ($flg == 1) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(200, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            if (isset($_POST['JYUCYU_ID'])) {
                $JYUCYU_ID = $_POST['JYUCYU_ID'];
                $LIST_ID = json_decode($JYUCYU_ID);
                foreach ($LIST_ID as $k => $v) {
                    $sql = 'UPDATE T_KOJI SET READ_FLG= 1 WHERE JYUCYU_ID="' . $v . '" ';
                    $this->result = $this->dbConnect->query($sql);
                }
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(508, '{"error_message": ' . $this->dbReference->getStatusCodeMeeage(508) . '}');
            }
        }
    }
    /* * * * * * * * * *
    * * * * MENU END* * * * 
    * * * * * * * * */

    function insert()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $sql = 'INSERT INTO `T_KOJI` 
            (`JYUCYU_ID`, `SITAMI_YMD`, `KOJI_YMD`, 
            `HOMON_TANT_CD1`, `HOMON_TANT_NAME1`, `HOMON_TANT_CD2`, `HOMON_TANT_NAME2`, `HOMON_TANT_CD3`, `HOMON_TANT_NAME3`, `HOMON_TANT_CD4`, `HOMON_TANT_NAME4`, 
            `SETSAKI_NAME`, `SETSAKI_ADDRESS`, `KOJI_JININ`, `SITAMI_JININ`, `HOMON_SBT`, `KOJI_ST`, 
            `KOJI_ITEM`, `SITAMI_KANSAN_POINT`, `KOJI_KANSAN_POINT`, 
            `SITAMI_JIKAN`, `KOJI_JIKAN`, `KOJI_KEKKA`, `TENPO_CD`, `HOJIN_FLG`, `MALL_CD`, `KOJIGYOSYA_CD`, `TAG_KBN`, 
            `SITAMIHOMONJIKAN`, `SITAMIHOMONJIKAN_END`, `KOJIHOMONJIKAN`, `KOJIHOMONJIKAN_END`, 
            `KOJIIRAISYO_FILEPATH`, `SITAMIIRAISYO_FILEPATH`, 
            `CANCEL_RIYU`, `SITAMIAPO_KBN`, `KOJIAPO_KBN`, `MTMORI_YMD`, `MEMO`, `COMMENT`, 
            `READ_FLG`, `ATOBARAI`, `BIKO`, `SYUYAKU_JYUCYU_ID`, `REPORT_FLG`, `SITAMI_REPORT`, `ALL_DAY_FLG`, 
            `CO_NAME`, `CO_POSTNO`, `CO_ADDRESS`, `KOJI_ITAKUHI`, `SKJ_RENKEI_YMD`, `KOJI_RENKEI_YMD`, `DEL_FLG`, 
            `ADD_PGID`, `ADD_TANTCD`, `ADD_TANTNM`, `ADD_YMD`, `UPD_PGID`, `UPD_TANTCD`, `UPD_TANTNM`, `UPD_YMD`) 
            VALUES 
            ("0301416580", "2022-12-02", "2022-12-02", 
            "00000", "AAAAA", "01010", "BBBBB", "02051", "CCCCC", "10018", "DDDDD", 
            "EEEEE", "ADDRESS_TEST", "1", "1", "01", "01", 
            "ITEM", "0.0", "0.0", 
            "2", "2", "01", "01010", "0", "02", "00000", "05", 
            "1000", "1300", "1000", "1500", 
            "pdf/test.pdf", "pdf/test.pdf", 
            NULL, "01", "01", "2022-12-02", "MEMO_ HELLO WORLD", "COMMENT_ HELLO WORLD", 
            "1", NULL, NULL, NULL, NULL, NULL, NULL, 
            "CO_NAME TEST", "6510084", "CO_ADDRESS TEST", "6000", "2022-12-02", "2022-12-02", "0", 
            "KOJ1120F", "00000", "TEST", "2022-12-02 12:58:38", "KOJ1120F", "00000", "TEST", "2022-12-02 06:58:36.000000");';
        }


        $sql = 'INSERT INTO `T_KOJI_FILEPATH` 
        (`FILEPATH_ID`, `ID`, `FILEPATH`, `FILE_KBN_CD`, `DEL_FLG`, 
        `ADD_PGID`, `ADD_TANTCD`, `ADD_YMD`, `UPD_PGID`, `UPD_TANTCD`, `UPD_YMD`) 
        VALUES 
        ("1700154648", "0301416580", "pdf/test.pdf", "03", "0", 
        "KOJ0060F", "00000", "2022-12-02 13:25:58", "KOJ0060F", "00000", "2022-12-02 07:25:57.000000")';

        $sql = 'INSERT INTO `T_KOJIMSAI` 
        (`JYUCYU_ID`, `JYUCYUMSAI_ID`, `JYUCYUMSAI_ID_KIKAN`, `HINBAN`, `MAKER_CD`, 
        `CTGORY_CD`, `SURYO`, `HANBAI_TANKA`, `KINGAK`, `KISETU_HINBAN`, `KISETU_MAKER_CD`, `KENSETU_KEITAI`, 
        `BEF_SEKO_PHOTO_FILEPATH`, `AFT_SEKO_PHOTO_FILEPATH`, `OTHER_PHOTO_FOLDERPATH`, 
        `TUIKA_JISYA_CD`, `TUIKA_SYOHIN_NAME`, `KOJIJITUIKA_FLG`, `DEL_FLG`, `RENKEI_YMD`, 
        `ADD_PGID`, `ADD_TANTCD`, `ADD_YMD`, `UPD_PGID`, `UPD_TANTCD`, `UPD_YMD`) 
        VALUES 
        ("0301416580", "0000000000", "0000000000", "KOJ-0000", "00000",
         "000", "1", "1600", "1600", NULL, NULL, NULL, 
         "img/pic.png", "img/pic.png", NULL, 
         "KOJ-00000", "TUIKA_SYOHIN_NAME", "1", "0", NULL, 
         NULL, "00000", "2022-12-02 13:28:39", NULL, "00000", "2022-12-02 07:28:38.000000");';

        $sql = 'INSERT INTO `T_TIRASI` 
        (`TANT_CD`, `YMD`, `KOJI_TIRASISU`, `RENKEI_YMD`, `DEL_FLG`, 
        `ADD_PGID`, `ADD_TANTCD`, `ADD_YMD`, `UPD_PGID`, `UPD_TANTCD`, `UPD_YMD`) 
        VALUES 
        ("00000", "2022-12-02", "999", NULL, "0", 
        "KOJ0990B", "00000", "2022-12-02 13:41:28", "KOJ0990B", "00000", "2022-12-02 07:41:28.000000");';
    }
}
