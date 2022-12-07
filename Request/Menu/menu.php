<?php
include('../../System/systemConfig.php');
include('../../Validate/validate.php');

class Menu
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
            $errors = [];
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data['BUZAIHACYU_TOTAL'] = $row["COUNT(*)"];
                        $resultSet['TOTAL'][] = $data;
                    }
                }
            } else {
                $errors['msg'][] = 'Missing parameter LOGIN_ID ';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $validate = new Validate();
            $validated = $validate->validate($_POST, [
                'JYUCYU_ID' => 'required',
            ]);

            if ($validated) {
                $LIST_ID = json_decode($validated['JYUCYU_ID']);
                foreach ($LIST_ID as $k => $v) {
                    $sql = 'UPDATE T_KOJI SET READ_FLG= 1 WHERE JYUCYU_ID="' . $v . '" ';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
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
            for ($i = 3; $i <= 9; $i++) {
                // $sql = 'INSERT INTO `T_KOJI` 
                // (`JYUCYU_ID`, `SITAMI_YMD`, `KOJI_YMD`, 
                // `HOMON_TANT_CD1`, `HOMON_TANT_NAME1`, `HOMON_TANT_CD2`, `HOMON_TANT_NAME2`, `HOMON_TANT_CD3`, `HOMON_TANT_NAME3`, `HOMON_TANT_CD4`, `HOMON_TANT_NAME4`, 
                // `SETSAKI_NAME`, `SETSAKI_ADDRESS`, `KOJI_JININ`, `SITAMI_JININ`, `HOMON_SBT`, `KOJI_ST`, 
                // `KOJI_ITEM`, `SITAMI_KANSAN_POINT`, `KOJI_KANSAN_POINT`, 
                // `SITAMI_JIKAN`, `KOJI_JIKAN`, `KOJI_KEKKA`, `TENPO_CD`, `HOJIN_FLG`, `MALL_CD`, `KOJIGYOSYA_CD`, `TAG_KBN`, 
                // `SITAMIHOMONJIKAN`, `SITAMIHOMONJIKAN_END`, `KOJIHOMONJIKAN`, `KOJIHOMONJIKAN_END`, 
                // `KOJIIRAISYO_FILEPATH`, `SITAMIIRAISYO_FILEPATH`, 
                // `CANCEL_RIYU`, `SITAMIAPO_KBN`, `KOJIAPO_KBN`, `MTMORI_YMD`, `MEMO`, `COMMENT`, 
                // `READ_FLG`, `ATOBARAI`, `BIKO`, `SYUYAKU_JYUCYU_ID`, `REPORT_FLG`, `SITAMI_REPORT`, `ALL_DAY_FLG`, 
                // `CO_NAME`, `CO_POSTNO`, `CO_ADDRESS`, `KOJI_ITAKUHI`, `SKJ_RENKEI_YMD`, `KOJI_RENKEI_YMD`, `DEL_FLG`, 
                // `ADD_PGID`, `ADD_TANTCD`, `ADD_TANTNM`, `ADD_YMD`, `UPD_PGID`, `UPD_TANTCD`, `UPD_TANTNM`, `UPD_YMD`) 
                // VALUES 
                // ("030147348'.$i.'", "2022-12-05", "2022-12-05", 
                // "00000", "AAAAA", "01010", "BBBBB", "02051", "CCCCC", "10018", "DDDDD", 
                // "EEEEE", "ADDRESS_TEST", "1", "1", "01", "01", 
                // "ITEM", "0.0", "0.0", 
                // "2", "2", "01", "01010", "0", "02", "00000", "05", 
                // "1000", "1300", "1000", "1500", 
                // "pdf/test.pdf", "pdf/test.pdf", 
                // NULL, "01", "01", "2022-12-05", "MEMO_ HELLO WORLD", "COMMENT_ HELLO WORLD", 
                // "1", NULL, NULL, NULL, NULL, NULL, NULL, 
                // "CO_NAME TEST", "6510084", "CO_ADDRESS TEST", "6000", "2022-12-05", "2022-12-05", "0", 
                // "KOJ1120F", "00000", "TEST", "2022-12-05 12:58:38", "KOJ1120F", "00000", "TEST", "2022-12-05 06:58:36.000000");';

                // $sql = 'INSERT INTO `T_KOJI_FILEPATH` 
                // (`FILEPATH_ID`, `ID`, `FILEPATH`, `FILE_KBN_CD`, `DEL_FLG`, 
                // `ADD_PGID`, `ADD_TANTCD`, `ADD_YMD`, `UPD_PGID`, `UPD_TANTCD`, `UPD_YMD`) 
                // VALUES 
                // ("170015466'.$i.'", "0301473482", "pdf/test'.$i.'.pdf", "03", "0", 
                // "KOJ0060F", "00000", "2022-12-05 13:25:58", "KOJ0060F", "00000", "2022-12-05 07:25:57")';

                // $sql = 'INSERT INTO `T_KOJIMSAI` 
                // (`JYUCYU_ID`, `JYUCYUMSAI_ID`, `JYUCYUMSAI_ID_KIKAN`, `HINBAN`, `MAKER_CD`, 
                // `CTGORY_CD`, `SURYO`, `HANBAI_TANKA`, `KINGAK`, `KISETU_HINBAN`, `KISETU_MAKER_CD`, `KENSETU_KEITAI`, 
                // `BEF_SEKO_PHOTO_FILEPATH`, `AFT_SEKO_PHOTO_FILEPATH`, `OTHER_PHOTO_FOLDERPATH`, 
                // `TUIKA_JISYA_CD`, `TUIKA_SYOHIN_NAME`, `KOJIJITUIKA_FLG`, `DEL_FLG`, `RENKEI_YMD`, 
                // `ADD_PGID`, `ADD_TANTCD`, `ADD_YMD`, `UPD_PGID`, `UPD_TANTCD`, `UPD_YMD`) 
                // VALUES 
                // ("030147348'.$i.'", "000000001'.$i.'", "000000001'.$i.'", "KOJ-0000", "00000",
                //  "000", "1", "160'.$i.'", "160'.$i.'", NULL, NULL, NULL, 
                //  "img/pic.png", "img/pic2.png", NULL, 
                //  "KOJ-00000", "TUIKA_SYOHIN_NAME", "1", "0", NULL, 
                //  NULL, "00000", "2022-12-02 13:28:39", NULL, "00000", "2022-12-02 07:28:38");';

                // $sql = 'INSERT INTO `T_TIRASI` 
                // (`TANT_CD`, `YMD`, `KOJI_TIRASISU`, `RENKEI_YMD`, `DEL_FLG`, 
                // `ADD_PGID`, `ADD_TANTCD`, `ADD_YMD`, `UPD_PGID`, `UPD_TANTCD`, `UPD_YMD`) 
                // VALUES 
                // ("00000", "2022-12-0'.$i.'", "999", NULL, "0", 
                // "KOJ0990B", "00000", "2022-12-02 13:41:28", "KOJ0990B", "00000", "2022-12-02 07:41:28")';

                $this->result = $this->dbConnect->query($sql);
            }
        }
    }

    function update_syuyaku_jyucyu_id()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $sql = 'UPDATE T_KOJI SET SYUYAKU_JYUCYU_ID=NULL WHERE SYUYAKU_JYUCYU_ID IS NOT NULL OR SYUYAKU_JYUCYU_ID="0301447773"';
            $this->result = $this->dbConnect->query($sql);
        }
    }
}
