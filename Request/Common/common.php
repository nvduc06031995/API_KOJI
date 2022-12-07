<?php
include('../../System/systemConfig.php');
include('../../System/systemEditor.php');
include('../../Validate/validate.php');

class Common
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
