<?php
include('../../System/systemConfig.php');
include('../../Validate/validate.php');

class Koji
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
            $errors = [];

            if ((isset($_GET['YMD']) && $_GET['YMD'] != "") && (isset($_GET['LOGIN_ID']) && $_GET['LOGIN_ID'] != "")) {
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
                SETSAKI_NAME,
                KOJIHOMONJIKAN, 
                KOJI_JININ,
                KOJI_JIKAN FROM T_KOJI WHERE SITAMI_YMD="' . $YMD . '" 
                AND HOMON_TANT_CD4="' . $LOGIN_ID . '"
                AND SYUYAKU_JYUCYU_ID IS NULL AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                        $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                        $data['KOJI_JININ'] = $row['KOJI_JININ'];
                        $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
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
                SETSAKI_NAME,
                SITAMIHOMONJIKAN,
                SITAMI_JININ,
                SITAMI_JIKAN FROM T_KOJI WHERE KOJI_YMD="' . $YMD . '"
                AND (HOMON_TANT_CD1="' . $LOGIN_ID . '" OR HOMON_TANT_CD2="' . $LOGIN_ID . '" OR HOMON_TANT_CD3="' . $LOGIN_ID . '")
                AND SYUYAKU_JYUCYU_ID IS NULL AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                        $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                        $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                        $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                        $data['LOGIN_ID'] = $LOGIN_ID;
                        $resultSet[] = $data;
                    }
                }
            } else {
                $errors['msg'][] = 'Missing parameter YMD or LOGIN_ID';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $count_jyucyu = null;
            $count_syuyaku_jyucyu = null;

            if ((isset($_GET['YMD']) && $_GET['YMD'] != "") && (isset($_GET['JYUCYU_ID']) && $_GET['JYUCYU_ID'] != "") &&
                (isset($_GET['SETSAKI_ADDRESS']) && $_GET['SETSAKI_ADDRESS'] != "")
            ) {
                $YMD = $_GET['YMD'];
                $SETSAKI_ADDRESS = $_GET['SETSAKI_ADDRESS'];
                $JYUCYU_ID = $_GET['JYUCYU_ID'];

                // Check exists
                $sql = 'SELECT COUNT(*) 
                    FROM T_KOJI 
                    WHERE JYUCYU_ID="' . $JYUCYU_ID . '"
                    AND KOJI_YMD="' . $YMD . '"                    
                    AND DEL_FLG= 0 ';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                    AND DEL_FLG= 0 ';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $count_syuyaku_jyucyu = $row["COUNT(*)"];
                        }
                    }
                }
            } else {
                $errors['msg'][] = 'Missing parameter YMD or JYUCYU_ID or SETSAKI_ADDRESS';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode((int)$count_syuyaku_jyucyu, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postUpdateSummarize()
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
                'YMD' => 'required',
                'JYUCYU_ID' => 'required',
                'SETSAKI_ADDRESS' => 'required'
            ]);

            if ($validated) {
                $UPD_PGID = "KOJ1120F";
                $UPD_YMD = date('Y-m-d H:i:s');
                $sql = 'UPDATE T_KOJI SET  SYUYAKU_JYUCYU_ID="' . $validated['JYUCYU_ID'] . '",                    
                    UPD_PGID="' . $UPD_PGID . '", 
                    UPD_TANTCD= "' . $validated['LOGIN_ID'] . '", 
                    UPD_YMD="' . $UPD_YMD . '"
                    WHERE SETSAKI_ADDRESS="' . $validated['SETSAKI_ADDRESS'] . '"
                    AND KOJI_YMD="' . $validated['YMD'] . '"
                    AND DEL_FLG= 0 ';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postTirasiUpdate()
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
                'YMD' => 'required',
                'KOJI_TIRASISU' => 'required',
            ]);

            if ($validated) {
                $RENKEI_YMD = date('Y-m-d');
                $UPD_PGID = "KOJ1110F";
                $UPD_YMD = date('Y-m-d H:i:s');

                $sql = 'UPDATE T_TIRASI SET  YMD="' . $validated['YMD'] . '", 
                    RENKEI_YMD="' . $RENKEI_YMD . '", 
                    KOJI_TIRASISU=' . $validated['KOJI_TIRASISU'] . ', 
                    UPD_PGID="' . $UPD_PGID . '", 
                    UPD_TANTCD= "' . $validated['LOGIN_ID'] . '", 
                    UPD_YMD="' . $UPD_YMD . '"
                    WHERE TANT_CD="' . $validated['LOGIN_ID'] . '" 
                    AND YMD="' . $validated['YMD'] . '"';

                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = array();
            $domain = $this->domain;

            if ((isset($_GET['JYUCYU_ID']) && $_GET['JYUCYU_ID'] != "") &&
                (isset($_GET['HOMON_SBT']) && $_GET['HOMON_SBT'] != "") &&
                (isset($_GET['SINGLE_SUMMARIZE']) && $_GET['SINGLE_SUMMARIZE'] != "")
            ) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $HOMON_SBT = $_GET['HOMON_SBT'];
                $SINGLE_SUMMARIZE = $_GET['SINGLE_SUMMARIZE'];

                if ($SINGLE_SUMMARIZE == "01") {
                    if ($HOMON_SBT == "01") {
                        $sql = 'SELECT T_KOJI.SITAMIIRAISYO_FILEPATH,
                        T_KOJI.JYUCYU_ID,
                        T_KOJI.HOMON_SBT,
                        T_KOJI.KOJI_ST                       
                        FROM T_KOJI 
                        WHERE JYUCYU_ID= "' . $JYUCYU_ID . '"             
                        AND T_KOJI.DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sql);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];                          
                                $data['SINGLE_SUMMARIZE'] = 1;
                                $resultSet[] = $data;
                            }
                        }
                    }

                    if ($HOMON_SBT == "02") {
                        $sql = 'SELECT T_KOJI.KOJIIRAISYO_FILEPATH,
                        T_KOJI.JYUCYU_ID,
                        T_KOJI.HOMON_SBT,
                        T_KOJI.KOJI_ST                     
                        FROM T_KOJI                 
                        WHERE JYUCYU_ID= "' . $JYUCYU_ID . '"                               
                        AND T_KOJI.DEL_FLG=0 ';
                        $this->result = $this->dbConnect->query($sql);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $KOJIIRAISYO_FILEPATH = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['KOJIIRAISYO_FILEPATH'] = $KOJIIRAISYO_FILEPATH;
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];                               
                                $data['SINGLE_SUMMARIZE'] = 1;
                                $resultSet[] = $data;
                            }
                        }
                    }
                } else if ($SINGLE_SUMMARIZE == "02") {
                    if ($HOMON_SBT == "01") {
                        $sql = 'SELECT T_KOJI.SITAMIIRAISYO_FILEPATH,
                        T_KOJI_FILEPATH.FILEPATH,
                        T_KOJI.JYUCYU_ID,
                        T_KOJI.HOMON_SBT
                        WHERE SYUYAKU_JYUCYU_ID= "' . $JYUCYU_ID . '"                      
                        AND T_KOJI.HOMON_SBT="01"
                        AND T_KOJI.DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sql);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['DOMAIN'] = $domain;
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
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

                    if ($HOMON_SBT == "02") {
                        $sql = 'SELECT T_KOJI.KOJIIRAISYO_FILEPATH,
                        T_KOJI_FILEPATH.FILEPATH,
                        T_KOJI.JYUCYU_ID,
                        T_KOJI.HOMON_SBT
                        WHERE SYUYAKU_JYUCYU_ID= "' . $JYUCYU_ID . '"                      
                        AND T_KOJI.HOMON_SBT="02"
                        AND T_KOJI.DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sql);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
                            // output data of each row                    
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['DOMAIN'] = $domain;
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];                             
                                $data['SINGLE_SUMMARIZE'] = 2;
                                $resultSet[] = $data;
                            }
                        }
                    }
                }
            } else {
                $errors['msg'][] = 'Missing parameter JYUCYU_ID or HOMON_SBT or SINGLE_SUMMARIZE';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = array();
            $domain = $this->domain;
            if ((isset($_GET['JYUCYU_ID']) && $_GET['JYUCYU_ID'] != "") &&
                (isset($_GET['SINGLE_SUMMARIZE']) && $_GET['SINGLE_SUMMARIZE'] != "")
            ) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $SINGLE_SUMMARIZE = $_GET['SINGLE_SUMMARIZE'];
                if ($SINGLE_SUMMARIZE == 1) {
                    $sql = ' SELECT FILEPATH,
                    ID, FILEPATH_ID
                    FROM T_KOJI_FILEPATH
                    WHERE ID="' . $JYUCYU_ID . '" 
                    AND FILE_KBN_CD="05"';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }

                    if ($this->result && $this->result->num_rows > 0) {
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
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
            } else {
                $errors['msg'][] = 'Missing parameter JYUCYU_ID or SINGLE_SUMMARIZE';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = array();
            $domain = $this->domain;
            if ((isset($_GET['JYUCYU_ID']) && $_GET['JYUCYU_ID'] != "") &&
                (isset($_GET['KOJI_ST']) && $_GET['KOJI_ST'] != "")
            ) {
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
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
            } else {
                $errors['msg'][] = 'Missing parameter JYUCYU_ID or KOJI_ST';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    /* ==================================================================== 写真提出-登録 */
    function uploadFileImg($file)
    {
        if (!empty($file)) {
            $path = '../../img/';
            $dataUploadFile = array();
            $target_file = $path . basename($file["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $path_return = 'img/' . basename($file["name"]);
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

        $dataUploadFile['FILEPATH'][] = $path_return;
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
            $errors = [];
            $resultSet = array();
            $validate = new Validate();

            $validated = $validate->validate($_POST, [
                'LOGIN_ID' => 'required',
                'JYUCYU_ID' => 'required',
            ]);

            if ($validated) {
                $FILE_NAME = $_FILES['FILE_NAME'];
                $img_path = [];
                $img_path = $this->uploadFileImg($FILE_NAME);

                if (!empty($img_path['ERROR'])) {
                    $this->dbReference->sendResponse(400, json_encode($img_path['ERROR'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                    die;
                }

                $JYUCYU_ID = $validated['JYUCYU_ID'];
                $FILE_KBN_CD = "10";
                $ADD_PGID = "KOJ1120F";
                $ADD_TANTCD = $validated['LOGIN_ID'];
                $ADD_YMD = date('Y-m-d');
                $UPD_PGID = 'KOJ1120F';
                $UPD_TANTCD = $validated['LOGIN_ID'];
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }

                    $sql = 'UPDATE T_KOJI SET KOJI_KEKKA="03",
                    SKJ_RENKEI_YMD="' . $PRESENT_DATE . '",
                    UPD_PGID="' . $UPD_PGID . '",
                    UPD_TANTCD="' . $UPD_TANTCD . '",
                    UPD_YMD="' . $UPD_YMD . '" 
                    WHERE JYUCYU_ID="' . $JYUCYU_ID . '"';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }

                $domain =  $this->domain;
                $resultSet['IMG'] = $domain . $img_path['FILEPATH'][0];
                $resultSet['JYUCYU_ID'] = $JYUCYU_ID;
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = array();
            $list_jisya_cd = [];
            if ((isset($_GET['JYUCYU_ID']) && $_GET['JYUCYU_ID'] != "") &&
                (isset($_GET['KOJI_ST']) && $_GET['KOJI_ST'] != "")
            ) {
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
                        // output data of each row                    
                        while ($row = $this->result->fetch_assoc()) {
                            $data = array();
                            $data['STATUS'] = $row['JYUCYU_ID'];
                            $data['TUIKA_SYOHIN_NAME'] = $row['TUIKA_SYOHIN_NAME'];
                            $data['TUIKA_JISYA_CD'] = substr($row['TUIKA_JISYA_CD'], -4);
                            $data['SURYO'] = $row['SURYO'];
                            $data['HANBAI_TANKA'] = $row['HANBAI_TANKA'];
                            $data['KINGAK'] = $row['KINGAK'];
                            $resultSet['TABLE_DATA'][] = $data;
                        }
                    }

                    $sql = 'SELECT * FROM M_KOJI_KAKAKU WHERE DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
                        // output data of each row                    
                        while ($row = $this->result->fetch_assoc()) {
                            $resultSet['KOJI_KAKAKU'][] = $row;
                        }
                    }
                }
            } else {
                $errors['msg'][] = 'Missing parameter JYUCYU_ID or KOJI_ST';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = array();
            $domain = $this->domain;
            if ((isset($_GET['TENPO_CD']) && $_GET['TENPO_CD'] != "") &&
                (isset($_GET['JYUCYU_ID']) && $_GET['JYUCYU_ID'] != "")
            ) {
                $TENPO_CD = $_GET['TENPO_CD'];
                $JYUCYU_ID = $_GET['JYUCYU_ID'];

                $sql = 'SELECT YOBIKOMOKU1,
                YOBIKOMOKU2,
                YOBIKOMOKU3,
                YOBIKOMOKU4,
                YOBIKOMOKU5 
                FROM M_KBN
                WHERE KBN_CD= "12" 
                AND KBN_BIKO="' . $TENPO_CD . '" 
                AND DEL_FLG=0';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['KBN'][] = $row;
                    }
                }

                $sql = 'SELECT FILEPATH,
                FILEPATH_ID,
                FILE_KBN_CD,
                ID FROM T_KOJI_FILEPATH
                WHERE ID="' . $JYUCYU_ID . '" 
                AND DEL_FLG=0';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row                    
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data['FILEPATH'] = $domain . $row['FILEPATH'];
                        $data['FILEPATH_ID'] = $row['FILEPATH_ID'];
                        $data['FILE_KBN_CD'] = $row['FILE_KBN_CD'];
                        $resultSet['FILE'][] = $data;
                    }
                }
            } else {
                $errors['msg'][] = 'Missing parameter TENPO_CD or JYUCYU_ID';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
                        $sqlGetHojinFlg = 'SELECT HOJIN_FLG, TENPO_CD 
                            FROM T_KOJI 
                            WHERE JYUCYU_ID="' . $jyucyuId . '" 
                        ';
                        $this->result = $this->dbConnect->query($sqlGetHojinFlg);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }

                        if ($this->result && $this->result->num_rows > 0) {
                            while ($row = $this->result->fetch_assoc()) {
                                $resultSet['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $resultSet['TENPO_CD'] = $row['TENPO_CD'];
                            }
                        }

                        $sqlNotReported = 'SELECT MAKER_CD, HINBAN 
                        FROM T_KOJIMSAI 
                        WHERE JYUCYU_ID="' . $jyucyuId . '" 
                            AND KOJIJITUIKA_FLG<>"0" 
                            AND DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sqlNotReported);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }

                        if ($this->result && $this->result->num_rows > 0) {
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
                            if (!empty($this->dbConnect->error)) {
                                $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                            }

                            $listJyucyuIdKoji = array();
                            if ($this->result && $this->result->num_rows > 0) {
                                // output data of each row
                                while ($row = $this->result->fetch_assoc()) {
                                    $listJyucyuIdKoji[] = $row;
                                }
                            }

                            foreach ($listJyucyuIdKoji as $value) {
                                //Get HOJIN_FLG
                                $sqlGetHojinFlg = 'SELECT HOJIN_FLG, TENPO_CD 
                                    FROM T_KOJI 
                                    WHERE JYUCYU_ID="' . $value['JYUCYU_ID'] . '" 
                                ';
                                $this->result = $this->dbConnect->query($sqlGetHojinFlg);
                                if (!empty($this->dbConnect->error)) {
                                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                                }

                                if ($this->result && $this->result->num_rows > 0) {
                                    while ($row = $this->result->fetch_assoc()) {
                                        $resultSet['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                        $resultSet['TENPO_CD'] = $row['TENPO_CD'];
                                    }
                                }

                                $sqlNotReportedSummarize = 'SELECT MAKER_CD, HINBAN 
                                    FROM T_KOJIMSAI 
                                    WHERE JYUCYU_ID="' . $value['JYUCYU_ID'] . '" 
                                        AND KOJIJITUIKA_FLG<>"0" 
                                        AND DEL_FLG=0';
                                $this->result = $this->dbConnect->query($sqlNotReportedSummarize);
                                if (!empty($this->dbConnect->error)) {
                                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                                }

                                if ($this->result && $this->result->num_rows > 0) {
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
                        $sqlGetHojinFlg = 'SELECT HOJIN_FLG, TENPO_CD 
                            FROM T_KOJI 
                            WHERE JYUCYU_ID="' . $jyucyuId . '" 
                        ';
                        $this->result = $this->dbConnect->query($sqlGetHojinFlg);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }

                        if ($this->result && $this->result->num_rows > 0) {
                            while ($row = $this->result->fetch_assoc()) {
                                $resultSet['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $resultSet['TENPO_CD'] = $row['TENPO_CD'];
                            }
                        }

                        $sqlReported = 'SELECT MAKER_CD, HINBAN, KISETU_MAKER_CD, KISETU_HINBAN, BEF_SEKO_PHOTO_FILEPATH, AFT_SEKO_PHOTO_FILEPATH, OTHER_PHOTO_FOLDERPATH
                            FROM T_KOJIMSAI 
                            WHERE JYUCYU_ID="' . $jyucyuId . '" 
                            AND KOJIJITUIKA_FLG<>"0" 
                            AND DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sqlReported);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }

                        if ($this->result && $this->result->num_rows > 0) {
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
                            if (!empty($this->dbConnect->error)) {
                                $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                            }

                            $listJyucyuIdKoji = array();
                            if ($this->result && $this->result->num_rows > 0) {
                                // output data of each row
                                while ($row = $this->result->fetch_assoc()) {
                                    $listJyucyuIdKoji[] = $row;
                                }
                            }

                            foreach ($listJyucyuIdKoji as $value) {
                                //Get HOJIN_FLG
                                $sqlGetHojinFlg = 'SELECT HOJIN_FLG, TENPO_CD 
                                    FROM T_KOJI 
                                    WHERE JYUCYU_ID="' . $value['JYUCYU_ID'] . '" 
                                ';
                                $this->result = $this->dbConnect->query($sqlGetHojinFlg);
                                if (!empty($this->dbConnect->error)) {
                                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                                }

                                if ($this->result && $this->result->num_rows > 0) {
                                    while ($row = $this->result->fetch_assoc()) {
                                        $resultSet['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                        $resultSet['TENPO_CD'] = $row['TENPO_CD'];
                                    }
                                }

                                $sqlNotReportedSummarize = 'SELECT MAKER_CD, HINBAN, KISETU_MAKER_CD, KISETU_HINBAN, BEF_SEKO_PHOTO_FILEPATH, AFT_SEKO_PHOTO_FILEPATH, OTHER_PHOTO_FOLDERPATH
                                    FROM T_KOJIMSAI 
                                    WHERE JYUCYU_ID="' . $value['JYUCYU_ID'] . '" 
                                        AND KOJIJITUIKA_FLG<>"0" 
                                        AND DEL_FLG=0';
                                $this->result = $this->dbConnect->query($sqlNotReportedSummarize);
                                if (!empty($this->dbConnect->error)) {
                                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                                }

                                if ($this->result && $this->result->num_rows > 0) {
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

                $sqlGetPullDown = 'SELECT KBN_CD, KBN_NAME, KBNMSAI_CD, KBNMSAI_NAME, KBN_BIKO 
                    FROM M_KBN 
                    WHERE KBN_CD="07"';
                $this->result = $this->dbConnect->query($sqlGetPullDown);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['PULLDOWN'][] = $row;
                    }
                }

                if (empty($errors['msg'])) {
                    $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                } else {
                    $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                //Store Sign file
                $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                    FROM T_KOJI_FILEPATH';
                $rs_max = $this->dbConnect->query($query_max);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
            }
            if (!empty($json_request['NEW_DETAIL'])) {
                foreach ($json_request['NEW_DETAIL'] as $value) {
                    $query_max = 'SELECT max(JYUCYUMSAI_ID) as JYUCYUMSAI_ID_MAX
                        FROM T_KOJIMSAI';
                    $rs_max = $this->dbConnect->query($query_max);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }

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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
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
            if (!empty($this->dbConnect->error)) {
                $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('Store success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                $resultSet = array();
                if ($this->result && $this->result->num_rows > 0) {
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
                if ($this->result && $this->result->num_rows > 0) {
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

                $KENSETU_KEITAI = isset($_POST['KENSETU_KEITAI']) ? '"' . $_POST['KENSETU_KEITAI'] . '"' : 'NULL';
                $BEF_SEKO_PHOTO_FILEPATH = isset($_POST['BEF_SEKO_PHOTO_FILEPATH']) ? '"' . $_POST['BEF_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $AFT_SEKO_PHOTO_FILEPATH = isset($_POST['AFT_SEKO_PHOTO_FILEPATH']) ? '"' . $_POST['AFT_SEKO_PHOTO_FILEPATH'] . '"' : 'NULL';
                $OTHER_PHOTO_FOLDERPATH = isset($_POST['OTHER_PHOTO_FOLDERPATH']) ? '"' . $_POST['OTHER_PHOTO_FOLDERPATH'] . '"' : 'NULL';

                $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                    FROM T_KOJI_FILEPATH';
                $rs_max = $this->dbConnect->query($query_max);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

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
                    "' . $JYUCYU_ID . '",
                    "' . $img_path . '",
                    "09",
                    "KOJ1120F",
                    "' . $JYUCYU_ID . '",
                    "' . date('Y-m-d H:i:s') . '",
                    "KOJ1120F",
                    "' . $JYUCYU_ID . '",
                    "' . date('Y-m-d H:i:s') . '"
                )';
                $this->result = $this->dbConnect->query($sqlInsert);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                $sqlUpdateKOJI = 'UPDATE T_KOJI 
                    SET KOJI_RENKEI_YMD = "' . date('Y-m-d H:i:s') . '",
                        KOJI_KEKKA = "工事OK",
                        UPD_PGID = "KOJ1120F",
                        UPD_TANTCD = "' . $JYUCYU_ID . '",
                        UPD_YMD = "' . date('Y-m-d H:i:s') . '"
                    WHERE JYUCYU_ID = "' . $JYUCYU_ID . '"
                    ';
                $this->result = $this->dbConnect->query($sqlUpdateKOJI);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if (empty($errors['msg'])) {
                    $this->dbReference->sendResponse(200, json_encode('Store success', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                } else {
                    $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                $resultSet = array();
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if (empty($errors['msg'])) {
                    $this->dbReference->sendResponse(200, json_encode("Store Success", JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                } else {
                    $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
            } else {
                $this->dbReference->sendResponse(400, json_encode("Error", JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }

                    $domain =  $this->domain;

                    $resultSet = array();
                    if ($this->result && $this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $resultSet[]['FILEPATH'] = $domain . $row['FILEPATH'];
                        }
                    }

                    if (empty($errors['msg'])) {
                        $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                    } else {
                        $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                    }
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }


                if (isset($_FILES['FILE_IMAGE'])) {
                    $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                        FROM T_KOJI_FILEPATH';
                    $rs_max = $this->dbConnect->query($query_max);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }
                $dataSuccess = array();
                $domain =  $this->domain;
                $dataSuccess['IMG'] = $domain . $img_path['FILEPATH'][0];
                $dataSuccess['ID_KOJI_FILE_PATH'] = $FILEPATH_ID;
                $dataSuccess['JYUCYU_ID_KOJI_UPDATE'] = $_POST['JYUCYU_ID'];

                if (empty($errors['msg'])) {
                    $this->dbReference->sendResponse(200, json_encode($dataSuccess, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                } else {
                    $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if (isset($_FILES['FILE_IMAGE'])) {
                    $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                        FROM T_KOJI_FILEPATH';
                    $rs_max = $this->dbConnect->query($query_max);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }

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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }

                $dataSuccess = array();
                $domain =  $this->domain;
                $dataSuccess['IMG'] = $domain . $img_path['FILEPATH'][0];
                $dataSuccess['ID_KOJI_FILE_PATH'] = $FILEPATH_ID;
                $dataSuccess['JYUCYU_ID_KOJI_UPDATE'] = $_POST['JYUCYU_ID'];

                if (empty($errors['msg'])) {
                    $this->dbReference->sendResponse(200, json_encode($dataSuccess, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                } else {
                    $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if (isset($_FILES['FILE_IMAGE'])) {
                    $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                        FROM T_KOJI_FILEPATH';
                    $rs_max = $this->dbConnect->query($query_max);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }

                $dataSuccess = array();
                $domain =  $this->domain;
                $dataSuccess['IMG'] = $domain . $img_path['FILEPATH'][0];
                $dataSuccess['ID_KOJI_FILE_PATH'] = $FILEPATH_ID;
                $dataSuccess['JYUCYU_ID_KOJI_UPDATE'] = $_POST['JYUCYU_ID'];

                if (empty($errors['msg'])) {
                    $this->dbReference->sendResponse(200, json_encode($dataSuccess, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                } else {
                    $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if (isset($_FILES['FILE_IMAGE'])) {
                    $query_max = 'SELECT max(FILEPATH_ID) as FILEPATH_ID_MAX
                        FROM T_KOJI_FILEPATH';
                    $rs_max = $this->dbConnect->query($query_max);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }

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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }

                $dataSuccess = array();
                $domain =  $this->domain;
                $dataSuccess['IMG'] = $domain . $img_path['FILEPATH'][0];
                $dataSuccess['ID_KOJI_FILE_PATH'] = $FILEPATH_ID;
                $dataSuccess['JYUCYU_ID_KOJI_UPDATE'] = $_POST['JYUCYU_ID'];

                if (empty($errors['msg'])) {
                    $this->dbReference->sendResponse(200, json_encode($dataSuccess, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                } else {
                    $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                }
            } else {
                $this->dbReference->sendResponse(400, json_encode([], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    /* * * * * * * * * *
    * * * * API仕様_工事報告 END * * * * 
    * * * * * * * * */
}
