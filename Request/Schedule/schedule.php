<?php
include('../../System/systemConfig.php');
include('../../Validate/validate.php');

class Schedule
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
            $errors = [];
            $resultSet = array();

            if ((isset($_GET['YMD']) && $_GET['YMD'] != "") &&
                (isset($_GET['KOJIGYOSYA_CD']) && $_GET['KOJIGYOSYA_CD'] != "")
            ) {
                $domain = $this->domain;
                $YMD = $_GET['YMD'];
                $KOJIGYOSYA_CD = $_GET['KOJIGYOSYA_CD'];
                $YM = date('Y-m', strtotime($YMD));
                $start_date = date("Y-m-d", strtotime('monday this week', strtotime($YMD)));
                $end_date =  date("Y-m-d", strtotime('sunday this week', strtotime($YMD)));

                //Data of group
                // 【営業工事・営業下見（営業所欄）】
                $sql = 'SELECT KOJIGYOSYA_CD,
                KOJIGYOSYA_NAME
                FROM M_GYOSYA 
                WHERE KOJIGYOSYA_CD="' . $KOJIGYOSYA_CD . '"';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $data = array();
                        $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                        $data['KOJIGYOSYA_NAME'] = $row['KOJIGYOSYA_NAME'];
                        $resultSet['OFFICE'] = $data;
                    }
                }

                $sql = 'SELECT T_EIGYO_ANKEN.TAN_EIG_ID,
                        T_EIGYO_ANKEN.JYOKEN_CD,  
                        T_EIGYO_ANKEN.JYOKEN_SYBET_FLG, 
                        T_EIGYO_ANKEN.YMD,
                        T_EIGYO_ANKEN.START_TIME, 
                        T_EIGYO_ANKEN.END_TIME, 
                        T_EIGYO_ANKEN.TAG_KBN, 
                        T_EIGYO_ANKEN.JININ,
                        T_EIGYO_ANKEN.JIKAN, 
                        T_EIGYO_ANKEN.GUEST_NAME,
                        T_EIGYO_ANKEN.ATTEND_NAME1,
                        T_EIGYO_ANKEN.ATTEND_NAME2,
                        T_EIGYO_ANKEN.ATTEND_NAME3,
                        T_EIGYO_ANKEN.ALL_DAY_FLG,
                        T_EIGYO_ANKEN.RENKEI_YMD,
                        M_KBN.KBN_CD,
                        M_KBN.KBN_NAME,
                        M_KBN.KBN_BIKO,
                        M_KBN.KBNMSAI_CD,
                        M_KBN.KBNMSAI_NAME, 
                        M_KBN.KBNMSAI_BIKO,
                        M_KBN.YOBIKOMOKU1, 
                        M_KBN.YOBIKOMOKU2,
                        M_KBN.YOBIKOMOKU3, 
                        M_KBN.YOBIKOMOKU4,
                        M_KBN.YOBIKOMOKU5
                        FROM T_EIGYO_ANKEN 
                        CROSS JOIN M_KBN ON T_EIGYO_ANKEN.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="10"                   
                        WHERE DATE_FORMAT(T_EIGYO_ANKEN.YMD , "%Y-%m") = "' . $YM . '"
                            AND JYOKEN_CD="' . $KOJIGYOSYA_CD . '"
                            AND T_EIGYO_ANKEN.JYOKEN_SYBET_FLG="1" 
                            AND T_EIGYO_ANKEN.DEL_FLG=0 ORDER BY START_TIME ASC';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $EIGYO_ANKEN_YMD = $row['YMD'];
                        $data = array();
                        $data['TAN_EIG_ID'] = $row['TAN_EIG_ID'];
                        $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                        $data['JYOKEN_SYBET_FLG'] = $row['JYOKEN_SYBET_FLG'];
                        $data['YMD'] = $row['YMD'];
                        $data['TAG_KBN'] = $row['TAG_KBN'];
                        $data['START_TIME'] = $row['START_TIME'];
                        $data['END_TIME'] = $row['END_TIME'];
                        $data['JININ'] = $row['JININ'];
                        $data['JIKAN'] = $row['JIKAN'];
                        $data['GUEST_NAME'] = $row['GUEST_NAME'];
                        $data['ATTEND_NAME1'] = $row['ATTEND_NAME1'];
                        $data['ATTEND_NAME2'] = $row['ATTEND_NAME2'];
                        $data['ATTEND_NAME3'] = $row['ATTEND_NAME3'];
                        $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                        $data['RENKEI_YMD'] = $row['RENKEI_YMD'];
                        $data['KBN_CD'] = $row['KBN_CD'];
                        $data['KBN_NAME'] = $row['KBN_NAME'];
                        $data['KBN_BIKO'] = $row['KBN_BIKO'];
                        $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                        $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                        $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                        $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                        $data['TYPE'] = 1;
                        $resultSet['OFFICE'][$EIGYO_ANKEN_YMD][] = $data;
                    }
                }

                //【メモ（営業所欄）】
                $sql = 'SELECT T_TBETUCALENDAR.TAN_CAL_ID,
                T_TBETUCALENDAR.JYOKEN_CD, 
                T_TBETUCALENDAR.JYOKEN_SYBET_FLG, 
                T_TBETUCALENDAR.YMD, 
                T_TBETUCALENDAR.TAG_KBN, 
                T_TBETUCALENDAR.START_TIME, 
                T_TBETUCALENDAR.END_TIME,
                T_TBETUCALENDAR.MEMO_CD,
                T_TBETUCALENDAR.NAIYO, 
                T_TBETUCALENDAR.COMMENT, 
                T_TBETUCALENDAR.ALL_DAY_FLG, 
                T_TBETUCALENDAR.RENKEI_YMD,                
                M_KBN.KBN_CD,
                M_KBN.KBN_NAME,
                M_KBN.KBN_BIKO,
                M_KBN.KBNMSAI_CD,
                M_KBN.KBNMSAI_NAME, 
                M_KBN.KBNMSAI_BIKO,
                M_KBN.YOBIKOMOKU1, 
                M_KBN.YOBIKOMOKU2,
                M_KBN.YOBIKOMOKU3, 
                M_KBN.YOBIKOMOKU4,
                M_KBN.YOBIKOMOKU5        
                 FROM T_TBETUCALENDAR 
                 CROSS JOIN M_KBN ON T_TBETUCALENDAR.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="06"
                 WHERE DATE_FORMAT(T_TBETUCALENDAR.YMD , "%Y-%m") = "' . $YM . '"                     
                     AND T_TBETUCALENDAR.DEL_FLG=0 
                     AND JYOKEN_CD="' . $KOJIGYOSYA_CD . '"
                     AND T_TBETUCALENDAR.JYOKEN_SYBET_FLG=1 
                     ORDER BY START_TIME ASC';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $TBETUCALENDAR_YMD = $row['YMD'];
                        $data = array();
                        $data['TAN_CAL_ID'] = $row['TAN_CAL_ID'];
                        $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                        $data['JYOKEN_SYBET_FLG'] = $row['JYOKEN_SYBET_FLG'];
                        $data['YMD'] = $row['YMD'];
                        $data['TAG_KBN'] = $row['TAG_KBN'];
                        $data['START_TIME'] = $row['START_TIME'];
                        $data['END_TIME'] = $row['END_TIME'];
                        $data['MEMO_CD'] = $row['MEMO_CD'];
                        $data['NAIYO'] = $row['NAIYO'];
                        $data['COMMENT'] = $row['COMMENT'];
                        $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                        $data['RENKEI_YMD'] = $row['RENKEI_YMD'];
                        $data['KBN_CD'] = $row['KBN_CD'];
                        $data['KBN_NAME'] = $row['KBN_NAME'];
                        $data['KBN_BIKO'] = $row['KBN_BIKO'];
                        $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                        $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                        $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                        $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                        $data['TYPE'] = 2;
                        $resultSet['OFFICE'][$TBETUCALENDAR_YMD][] = $data;
                    }
                }

                $sql = 'SELECT TANT_CD,TANT_NAME FROM M_TANT WHERE SYOZOKU_CD="' . $KOJIGYOSYA_CD . '"';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                $list_tant_cd = [];
                if ($this->result && $this->result->num_rows > 0) {
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
                    T_KOJI.SITAMI_YMD,
                    T_KOJI.KOJI_YMD,
                    T_KOJI.HOMON_TANT_CD1,
                    T_KOJI.HOMON_TANT_CD2,
                    T_KOJI.HOMON_TANT_CD3,
                    T_KOJI.HOMON_TANT_CD4,
                    T_KOJI.SETSAKI_NAME,
                    T_KOJI.SETSAKI_ADDRESS,
                    T_KOJI.KOJI_JININ,
                    T_KOJI.SITAMI_JININ,
                    T_KOJI.HOMON_SBT,
                    T_KOJI.KOJI_ST,
                    T_KOJI.KOJI_ITEM,
                    T_KOJI.SITAMI_KANSAN_POINT,
                    T_KOJI.KOJI_KANSAN_POINT,
                    T_KOJI.SITAMI_JIKAN,
                    T_KOJI.KOJI_JIKAN,
                    T_KOJI.KOJI_KEKKA,
                    T_KOJI.TENPO_CD,
                    T_KOJI.HOJIN_FLG,
                    T_KOJI.MALL_CD,
                    T_KOJI.KOJIGYOSYA_CD,
                    T_KOJI.TAG_KBN,
                    T_KOJI.SITAMIHOMONJIKAN,
                    T_KOJI.SITAMIHOMONJIKAN_END,
                    T_KOJI.KOJIHOMONJIKAN,
                    T_KOJI.KOJIHOMONJIKAN_END,
                    T_KOJI.KOJIIRAISYO_FILEPATH,
                    T_KOJI.SITAMIIRAISYO_FILEPATH,
                    T_KOJI.CANCEL_RIYU,
                    T_KOJI.SITAMIAPO_KBN,
                    T_KOJI.KOJIAPO_KBN,
                    T_KOJI.MTMORI_YMD,
                    T_KOJI.MEMO,
                    T_KOJI.COMMENT,
                    T_KOJI.READ_FLG,
                    T_KOJI.ATOBARAI,
                    T_KOJI.BIKO,
                    T_KOJI.SYUYAKU_JYUCYU_ID,
                    T_KOJI.REPORT_FLG,
                    T_KOJI.SITAMI_REPORT,
                    T_KOJI.ALL_DAY_FLG,
                    T_KOJI.CO_NAME,
                    T_KOJI.CO_POSTNO,
                    T_KOJI.CO_ADDRESS,
                    T_KOJI.KOJI_ITAKUHI,
                    T_KOJI.SKJ_RENKEI_YMD,
                    T_KOJI.KOJI_RENKEI_YMD,
                    M_KBN.KBN_CD,
                    M_KBN.KBN_NAME,
                    M_KBN.KBN_BIKO,
                    M_KBN.KBNMSAI_CD,
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.KBNMSAI_BIKO,
                    M_KBN.YOBIKOMOKU1, 
                    M_KBN.YOBIKOMOKU2,
                    M_KBN.YOBIKOMOKU3, 
                    M_KBN.YOBIKOMOKU4,
                    M_KBN.YOBIKOMOKU5,
                    M_TANT.TANT_CD,
                    M_TANT.TANT_NAME,                    
                    M_TANT.SYOZOKU_CD
                    FROM T_KOJI 
                    CROSS JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="05"
                    CROSS JOIN M_TANT ON M_TANT.TANT_CD=T_KOJI.HOMON_TANT_CD4
                    WHERE DATE_FORMAT(T_KOJI.SITAMI_YMD , "%Y-%m") = "' . $YM . '" 
                    AND M_TANT.TANT_CD="' . $TANT_CD . '"
                    AND T_KOJI.DEL_FLG=0 
                    ORDER BY SITAMIHOMONJIKAN ASC';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $resultSet2[$TANT_CD]['TANT_NAME'] = $TANT_NAME;
                            $resultSet2[$TANT_CD]['TANT_CD'] = $TANT_CD;
                            $SITAMI_YMD = $row['SITAMI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                            $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                            $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                            $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_JININ'] = $row['KOJI_JININ'];
                            $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                            $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                            $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                            $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                            $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                            $data['TENPO_CD'] = $row['TENPO_CD'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['MALL_CD'] = $row['MALL_CD'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                            $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                            $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                            $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                            $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                            $data['MEMO'] = $row['MEMO'];
                            $data['COMMENT'] = $row['COMMENT'];
                            $data['READ_FLG'] = $row['READ_FLG'];
                            $data['ATOBARAI'] = $row['ATOBARAI'];
                            $data['BIKO'] = $row['BIKO'];
                            $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                            $data['REPORT_FLG'] = $row['REPORT_FLG'];
                            $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                            $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['TANT_NAME'] = $row['TANT_NAME'];
                            $data['TANT_CD'] = $TANT_CD;
                            $data['SYOZOKU_CD'] = $row['SYOZOKU_CD'];
                            $data['TYPE'] = 1;
                            $resultSet2[$TANT_CD][$SITAMI_YMD][] = $data;
                        }
                    } else {
                        $resultSet2[$TANT_CD]['TANT_CD'] = $TANT_CD;
                        $resultSet2[$TANT_CD]['TANT_NAME'] = $v['TANT_NAME'];
                    }

                    //【ネット工事】
                    $sql = ' SELECT T_KOJI.JYUCYU_ID,
                    T_KOJI.SITAMI_YMD,
                    T_KOJI.KOJI_YMD,
                    T_KOJI.HOMON_TANT_CD1,
                    T_KOJI.HOMON_TANT_CD2,
                    T_KOJI.HOMON_TANT_CD3,
                    T_KOJI.HOMON_TANT_CD4,
                    T_KOJI.SETSAKI_NAME,
                    T_KOJI.SETSAKI_ADDRESS,
                    T_KOJI.KOJI_JININ,
                    T_KOJI.SITAMI_JININ,
                    T_KOJI.HOMON_SBT,
                    T_KOJI.KOJI_ST,
                    T_KOJI.KOJI_ITEM,
                    T_KOJI.SITAMI_KANSAN_POINT,
                    T_KOJI.KOJI_KANSAN_POINT,
                    T_KOJI.SITAMI_JIKAN,
                    T_KOJI.KOJI_JIKAN,
                    T_KOJI.KOJI_KEKKA,
                    T_KOJI.TENPO_CD,
                    T_KOJI.HOJIN_FLG,
                    T_KOJI.MALL_CD,
                    T_KOJI.KOJIGYOSYA_CD,
                    T_KOJI.TAG_KBN,
                    T_KOJI.SITAMIHOMONJIKAN,
                    T_KOJI.SITAMIHOMONJIKAN_END,
                    T_KOJI.KOJIHOMONJIKAN,
                    T_KOJI.KOJIHOMONJIKAN_END,
                    T_KOJI.KOJIIRAISYO_FILEPATH,
                    T_KOJI.SITAMIIRAISYO_FILEPATH,
                    T_KOJI.CANCEL_RIYU,
                    T_KOJI.SITAMIAPO_KBN,
                    T_KOJI.KOJIAPO_KBN,
                    T_KOJI.MTMORI_YMD,
                    T_KOJI.MEMO,
                    T_KOJI.COMMENT,
                    T_KOJI.READ_FLG,
                    T_KOJI.ATOBARAI,
                    T_KOJI.BIKO,
                    T_KOJI.SYUYAKU_JYUCYU_ID,
                    T_KOJI.REPORT_FLG,
                    T_KOJI.SITAMI_REPORT,
                    T_KOJI.ALL_DAY_FLG,
                    T_KOJI.CO_NAME,
                    T_KOJI.CO_POSTNO,
                    T_KOJI.CO_ADDRESS,
                    T_KOJI.KOJI_ITAKUHI,
                    T_KOJI.SKJ_RENKEI_YMD,
                    T_KOJI.KOJI_RENKEI_YMD,
                    M_KBN.KBN_CD,
                    M_KBN.KBN_NAME,
                    M_KBN.KBN_BIKO,
                    M_KBN.KBNMSAI_CD,
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.KBNMSAI_BIKO,
                    M_KBN.YOBIKOMOKU1, 
                    M_KBN.YOBIKOMOKU2,
                    M_KBN.YOBIKOMOKU3, 
                    M_KBN.YOBIKOMOKU4,
                    M_KBN.YOBIKOMOKU5,
                    M_TANT1.TANT_CD AS TANT_CD1,
                    M_TANT1.TANT_NAME AS TANT_NAME1,
                    M_TANT2.TANT_CD AS TANT_CD2,
                    M_TANT2.TANT_NAME AS TANT_NAME2,
                    M_TANT3.TANT_CD AS TANT_CD3,
                    M_TANT3.TANT_NAME AS TANT_NAME3,
                    T_KOJI.KOJI_YMD
                    FROM T_KOJI 
                    CROSS JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="05"
                    CROSS JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                    CROSS JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                    CROSS JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                    WHERE DATE_FORMAT(T_KOJI.KOJI_YMD , "%Y-%m") = "' . $YM . '" 
                        AND T_KOJI.DEL_FLG=0                         
                        AND (M_TANT1.TANT_CD="' . $TANT_CD . '" OR M_TANT2.TANT_CD="' . $TANT_CD . '" OR M_TANT3.TANT_CD="' . $TANT_CD . '" )
                        ORDER BY KOJIHOMONJIKAN ASC';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $TANT_CD1 = $row['TANT_CD1'];
                            $TANT_CD2 = $row['TANT_CD2'];
                            $TANT_CD3 = $row['TANT_CD3'];
                            if (!empty($TANT_CD1) && $TANT_CD1 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['TANT_NAME'] = $row['TANT_NAME1'];
                                $data['TANT_CD'] = $TANT_CD1;
                                $data['TYPE'] = 2;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }

                            if (!empty($TANT_CD2) && $TANT_CD2 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['TANT_CD'] =  $TANT_CD2;
                                $data['TANT_NAME'] = $row['TANT_NAME2'];
                                $data['TYPE'] = 2;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }

                            if (!empty($TANT_CD3) && $TANT_CD3 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['TANT_CD'] =  $TANT_CD3;
                                $data['TANT_NAME'] = $row['TANT_NAME3'];
                                $data['TYPE'] = 2;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }
                        }
                    }

                    //【営業工事・営業下見（担当者欄）】
                    $sql = 'SELECT T_EIGYO_ANKEN.TAN_EIG_ID,
                    T_EIGYO_ANKEN.JYOKEN_CD,  
                    T_EIGYO_ANKEN.JYOKEN_SYBET_FLG, 
                    T_EIGYO_ANKEN.YMD,
                    T_EIGYO_ANKEN.START_TIME, 
                    T_EIGYO_ANKEN.END_TIME, 
                    T_EIGYO_ANKEN.TAG_KBN, 
                    T_EIGYO_ANKEN.JININ,
                    T_EIGYO_ANKEN.JIKAN, 
                    T_EIGYO_ANKEN.GUEST_NAME,
                    T_EIGYO_ANKEN.ATTEND_NAME1,
                    T_EIGYO_ANKEN.ATTEND_NAME2,
                    T_EIGYO_ANKEN.ATTEND_NAME3,
                    T_EIGYO_ANKEN.ALL_DAY_FLG,
                    T_EIGYO_ANKEN.RENKEI_YMD,
                    M_KBN.KBN_CD,
                    M_KBN.KBN_NAME,
                    M_KBN.KBN_BIKO,
                    M_KBN.KBNMSAI_CD,
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.KBNMSAI_BIKO,
                    M_KBN.YOBIKOMOKU1, 
                    M_KBN.YOBIKOMOKU2,
                    M_KBN.YOBIKOMOKU3, 
                    M_KBN.YOBIKOMOKU4,
                    M_KBN.YOBIKOMOKU5,
                    M_TANT.TANT_NAME, 
                    M_TANT.TANT_CD
                    FROM T_EIGYO_ANKEN 
                    CROSS JOIN M_KBN ON T_EIGYO_ANKEN.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="10"
                    CROSS JOIN M_TANT ON T_EIGYO_ANKEN.JYOKEN_CD=M_TANT.TANT_CD AND T_EIGYO_ANKEN.JYOKEN_SYBET_FLG="0"
                    WHERE DATE_FORMAT(T_EIGYO_ANKEN.YMD , "%Y-%m") = "' . $YM . '"
                    AND T_EIGYO_ANKEN.DEL_FLG=0                    
                    AND M_TANT.TANT_CD="' . $TANT_CD . '"
                    ORDER BY START_TIME ASC';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $TANT_CD = $row['TANT_CD'];
                            $EIGYO_ANKEN_YMD = $row['YMD'];
                            $data = array();
                            $data['TAN_EIG_ID'] = $row['TAN_EIG_ID'];
                            $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                            $data['JYOKEN_SYBET_FLG'] = $row['JYOKEN_SYBET_FLG'];
                            $data['YMD'] = $row['YMD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['START_TIME'] = $row['START_TIME'];
                            $data['END_TIME'] = $row['END_TIME'];
                            $data['JININ'] = $row['JININ'];
                            $data['JIKAN'] = $row['JIKAN'];
                            $data['GUEST_NAME'] = $row['GUEST_NAME'];
                            $data['ATTEND_NAME1'] = $row['ATTEND_NAME1'];
                            $data['ATTEND_NAME2'] = $row['ATTEND_NAME2'];
                            $data['ATTEND_NAME3'] = $row['ATTEND_NAME3'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['RENKEI_YMD'] = $row['RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['TANT_NAME'] = $row['TANT_NAME'];
                            $data['TANT_CD'] = $row['TANT_CD'];
                            $data['TYPE'] = 3;
                            $resultSet2[$TANT_CD][$EIGYO_ANKEN_YMD][] = $data;
                        }
                    }

                    //【メモ（営業所欄）】
                    $sql = 'SELECT T_TBETUCALENDAR.TAN_CAL_ID,
                    T_TBETUCALENDAR.JYOKEN_CD, 
                    T_TBETUCALENDAR.JYOKEN_SYBET_FLG, 
                    T_TBETUCALENDAR.YMD, 
                    T_TBETUCALENDAR.TAG_KBN, 
                    T_TBETUCALENDAR.START_TIME, 
                    T_TBETUCALENDAR.END_TIME,
                    T_TBETUCALENDAR.MEMO_CD,
                    T_TBETUCALENDAR.NAIYO, 
                    T_TBETUCALENDAR.COMMENT, 
                    T_TBETUCALENDAR.ALL_DAY_FLG, 
                    T_TBETUCALENDAR.RENKEI_YMD,
                    M_KBN.KBN_CD,
                    M_KBN.KBN_NAME,
                    M_KBN.KBN_BIKO,
                    M_KBN.KBNMSAI_CD,
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.KBNMSAI_BIKO,
                    M_KBN.YOBIKOMOKU1, 
                    M_KBN.YOBIKOMOKU2,
                    M_KBN.YOBIKOMOKU3, 
                    M_KBN.YOBIKOMOKU4,
                    M_KBN.YOBIKOMOKU5,                                      
                    M_TANT.TANT_CD,
                    M_TANT.TANT_NAME         
                    FROM T_TBETUCALENDAR 
                    CROSS JOIN M_KBN ON T_TBETUCALENDAR.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="06"
                    CROSS JOIN M_TANT ON T_TBETUCALENDAR.JYOKEN_CD=M_TANT.TANT_CD AND T_TBETUCALENDAR.JYOKEN_SYBET_FLG=0 
                    WHERE DATE_FORMAT(T_TBETUCALENDAR.YMD , "%Y-%m") = "' . $YM . '"                  
                    AND T_TBETUCALENDAR.DEL_FLG=0                                     
                    AND M_TANT.TANT_CD="' . $TANT_CD . '"
                    ORDER BY START_TIME ASC';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $TANT_CD = $row['TANT_CD'];
                            $TBETUCALENDAR_YMD = $row['YMD'];
                            $data = array();
                            $data['TAN_CAL_ID'] = $row['TAN_CAL_ID'];
                            $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                            $data['JYOKEN_SYBET_FLG'] = $row['JYOKEN_SYBET_FLG'];
                            $data['YMD'] = $row['YMD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['START_TIME'] = $row['START_TIME'];
                            $data['END_TIME'] = $row['END_TIME'];
                            $data['MEMO_CD'] = $row['MEMO_CD'];
                            $data['NAIYO'] = $row['NAIYO'];
                            $data['COMMENT'] = $row['COMMENT'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['RENKEI_YMD'] = $row['RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['TANT_NAME'] = $row['TANT_NAME'];
                            $data['TANT_CD'] = $row['TANT_CD'];
                            $data['TYPE'] = 4;
                            $resultSet2[$TANT_CD][$TBETUCALENDAR_YMD][] = $data;
                        }
                    }

                    //【日予実】
                    $sql = ' SELECT T_KOJI.JYUCYU_ID,
                    T_KOJI.SITAMI_YMD,
                    T_KOJI.KOJI_YMD,
                    T_KOJI.HOMON_TANT_CD1,
                    T_KOJI.HOMON_TANT_CD2,
                    T_KOJI.HOMON_TANT_CD3,
                    T_KOJI.HOMON_TANT_CD4,
                    T_KOJI.SETSAKI_NAME,
                    T_KOJI.SETSAKI_ADDRESS,
                    T_KOJI.KOJI_JININ,
                    T_KOJI.SITAMI_JININ,
                    T_KOJI.HOMON_SBT,
                    T_KOJI.KOJI_ST,
                    T_KOJI.KOJI_ITEM,
                    T_KOJI.SITAMI_KANSAN_POINT,
                    T_KOJI.KOJI_KANSAN_POINT,
                    T_KOJI.SITAMI_JIKAN,
                    T_KOJI.KOJI_JIKAN,
                    T_KOJI.KOJI_KEKKA,
                    T_KOJI.TENPO_CD,
                    T_KOJI.HOJIN_FLG,
                    T_KOJI.MALL_CD,
                    T_KOJI.KOJIGYOSYA_CD,
                    T_KOJI.TAG_KBN,
                    T_KOJI.SITAMIHOMONJIKAN,
                    T_KOJI.SITAMIHOMONJIKAN_END,
                    T_KOJI.KOJIHOMONJIKAN,
                    T_KOJI.KOJIHOMONJIKAN_END,
                    T_KOJI.KOJIIRAISYO_FILEPATH,
                    T_KOJI.SITAMIIRAISYO_FILEPATH,
                    T_KOJI.CANCEL_RIYU,
                    T_KOJI.SITAMIAPO_KBN,
                    T_KOJI.KOJIAPO_KBN,
                    T_KOJI.MTMORI_YMD,
                    T_KOJI.MEMO,
                    T_KOJI.COMMENT,
                    T_KOJI.READ_FLG,
                    T_KOJI.ATOBARAI,
                    T_KOJI.BIKO,
                    T_KOJI.SYUYAKU_JYUCYU_ID,
                    T_KOJI.REPORT_FLG,
                    T_KOJI.SITAMI_REPORT,
                    T_KOJI.ALL_DAY_FLG,
                    T_KOJI.CO_NAME,
                    T_KOJI.CO_POSTNO,
                    T_KOJI.CO_ADDRESS,
                    T_KOJI.KOJI_ITAKUHI,
                    T_KOJI.SKJ_RENKEI_YMD,
                    T_KOJI.KOJI_RENKEI_YMD,
                    M_KBN.KBN_CD,
                    M_KBN.KBN_NAME,
                    M_KBN.KBN_BIKO,
                    M_KBN.KBNMSAI_CD,
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.KBNMSAI_BIKO,
                    M_KBN.YOBIKOMOKU1, 
                    M_KBN.YOBIKOMOKU2,
                    M_KBN.YOBIKOMOKU3, 
                    M_KBN.YOBIKOMOKU4,
                    M_KBN.YOBIKOMOKU5,
                    M_TANT1.DAYLY_SALES AS DAYLY_SALES1, 
                    M_TANT2.DAYLY_SALES AS DAYLY_SALES2,  
                    M_TANT3.DAYLY_SALES AS DAYLY_SALES3,  
                    M_TANT4.DAYLY_SALES AS DAYLY_SALES4,  
                    M_TANT1.TANT_CD AS TANT_CD1,
                    M_TANT1.TANT_NAME AS TANT_NAME1,
                    M_TANT2.TANT_CD AS TANT_CD2,
                    M_TANT2.TANT_NAME AS TANT_NAME2,
                    M_TANT3.TANT_CD AS TANT_CD3,
                    M_TANT3.TANT_NAME AS TANT_NAME3, 
                    M_TANT4.TANT_CD AS TANT_CD4,
                    M_TANT4.TANT_NAME AS TANT_NAME4
                    FROM T_KOJI 
                    CROSS JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="16" AND M_KBN.KBNMSAI_CD="01"  
                    CROSS JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                    CROSS JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                    CROSS JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                    CROSS JOIN M_TANT as M_TANT4 ON M_TANT4.TANT_CD=T_KOJI.HOMON_TANT_CD4
                    WHERE DATE_FORMAT(T_KOJI.KOJI_YMD , "%Y-%m") = "' . $YM . '"
                    AND T_KOJI.DEL_FLG=0                    
                    AND (M_TANT1.TANT_CD="' . $TANT_CD . '" OR M_TANT2.TANT_CD="' . $TANT_CD . '" OR M_TANT3.TANT_CD="' . $TANT_CD . '" OR M_TANT4.TANT_CD="' . $TANT_CD . '" )                                      
                    ORDER BY KOJIHOMONJIKAN ASC';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $TANT_CD1 = $row['TANT_CD1'];
                            $TANT_CD2 = $row['TANT_CD2'];
                            $TANT_CD3 = $row['TANT_CD3'];
                            $TANT_CD4 = $row['TANT_CD4'];

                            if (!empty($TANT_CD1) && $TANT_CD1 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['DAYLY_SALES'] = $row['DAYLY_SALES1'];
                                $data['TANT_NAME'] = $row['TANT_NAME1'];
                                $data['TANT_CD'] = $TANT_CD1;
                                $data['TYPE'] = 5;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }

                            if (!empty($TANT_CD2) && $TANT_CD2 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['DAYLY_SALES'] = $row['DAYLY_SALES2'];
                                $data['TANT_NAME'] = $row['TANT_NAME2'];
                                $data['TANT_CD1'] = $row['TANT_CD2'];
                                $data['TYPE'] = 5;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }

                            if (!empty($TANT_CD3) && $TANT_CD3 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['DAYLY_SALES'] = $row['DAYLY_SALES3'];
                                $data['TANT_NAME'] = $row['TANT_NAME3'];
                                $data['TANT_CD1'] = $row['TANT_CD3'];
                                $data['TYPE'] = 5;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }

                            if (!empty($TANT_CD4) && $TANT_CD4 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['DAYLY_SALES'] = $row['DAYLY_SALES4'];
                                $data['TANT_NAME'] = $row['TANT_NAME4'];
                                $data['TANT_CD1'] = $row['TANT_CD4'];
                                $data['TYPE'] = 5;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }
                        }
                    }

                    //【計予実】
                    $sql = ' SELECT T_KOJI.JYUCYU_ID,
                    T_KOJI.SITAMI_YMD,
                    T_KOJI.KOJI_YMD,
                    T_KOJI.HOMON_TANT_CD1,
                    T_KOJI.HOMON_TANT_CD2,
                    T_KOJI.HOMON_TANT_CD3,
                    T_KOJI.HOMON_TANT_CD4,
                    T_KOJI.SETSAKI_NAME,
                    T_KOJI.SETSAKI_ADDRESS,
                    T_KOJI.KOJI_JININ,
                    T_KOJI.SITAMI_JININ,
                    T_KOJI.HOMON_SBT,
                    T_KOJI.KOJI_ST,
                    T_KOJI.KOJI_ITEM,
                    T_KOJI.SITAMI_KANSAN_POINT,
                    T_KOJI.KOJI_KANSAN_POINT,
                    T_KOJI.SITAMI_JIKAN,
                    T_KOJI.KOJI_JIKAN,
                    T_KOJI.KOJI_KEKKA,
                    T_KOJI.TENPO_CD,
                    T_KOJI.HOJIN_FLG,
                    T_KOJI.MALL_CD,
                    T_KOJI.KOJIGYOSYA_CD,
                    T_KOJI.TAG_KBN,
                    T_KOJI.SITAMIHOMONJIKAN,
                    T_KOJI.SITAMIHOMONJIKAN_END,
                    T_KOJI.KOJIHOMONJIKAN,
                    T_KOJI.KOJIHOMONJIKAN_END,
                    T_KOJI.KOJIIRAISYO_FILEPATH,
                    T_KOJI.SITAMIIRAISYO_FILEPATH,
                    T_KOJI.CANCEL_RIYU,
                    T_KOJI.SITAMIAPO_KBN,
                    T_KOJI.KOJIAPO_KBN,
                    T_KOJI.MTMORI_YMD,
                    T_KOJI.MEMO,
                    T_KOJI.COMMENT,
                    T_KOJI.READ_FLG,
                    T_KOJI.ATOBARAI,
                    T_KOJI.BIKO,
                    T_KOJI.SYUYAKU_JYUCYU_ID,
                    T_KOJI.REPORT_FLG,
                    T_KOJI.SITAMI_REPORT,
                    T_KOJI.ALL_DAY_FLG,
                    T_KOJI.CO_NAME,
                    T_KOJI.CO_POSTNO,
                    T_KOJI.CO_ADDRESS,
                    T_KOJI.KOJI_ITAKUHI,
                    T_KOJI.SKJ_RENKEI_YMD,
                    T_KOJI.KOJI_RENKEI_YMD,
                    M_KBN.KBN_CD,
                    M_KBN.KBN_NAME,
                    M_KBN.KBN_BIKO,
                    M_KBN.KBNMSAI_CD,
                    M_KBN.KBNMSAI_NAME, 
                    M_KBN.KBNMSAI_BIKO,
                    M_KBN.YOBIKOMOKU1, 
                    M_KBN.YOBIKOMOKU2,
                    M_KBN.YOBIKOMOKU3, 
                    M_KBN.YOBIKOMOKU4,
                    M_KBN.YOBIKOMOKU5,
                    M_TANT1.MONTHLY_SALES AS MONTHLY_SALES1, 
                    M_TANT2.MONTHLY_SALES AS MONTHLY_SALES2,  
                    M_TANT3.MONTHLY_SALES AS MONTHLY_SALES3,  
                    M_TANT4.MONTHLY_SALES AS MONTHLY_SALES4,                    
                    M_TANT1.TANT_CD AS TANT_CD1,
                    M_TANT1.TANT_NAME AS TANT_NAME1,
                    M_TANT2.TANT_CD AS TANT_CD2,
                    M_TANT2.TANT_NAME AS TANT_NAME2,
                    M_TANT3.TANT_CD AS TANT_CD3,
                    M_TANT3.TANT_NAME AS TANT_NAME3, 
                    M_TANT4.TANT_CD AS TANT_CD4,
                    M_TANT4.TANT_NAME AS TANT_NAME4
                    FROM T_KOJI 
                    CROSS JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="16" AND M_KBN.KBNMSAI_CD="01"
                    CROSS JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                    CROSS JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                    CROSS JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                    CROSS JOIN M_TANT as M_TANT4 ON M_TANT4.TANT_CD=T_KOJI.HOMON_TANT_CD4
                    WHERE DATE_FORMAT(T_KOJI.KOJI_YMD , "%Y-%m") = "' . $YM . '"
                    AND T_KOJI.DEL_FLG=0                    
                    AND (M_TANT1.TANT_CD="' . $TANT_CD . '" OR M_TANT2.TANT_CD="' . $TANT_CD . '" OR M_TANT3.TANT_CD="' . $TANT_CD . '" OR M_TANT4.TANT_CD="' . $TANT_CD . '" )                                       
                    ORDER BY KOJIHOMONJIKAN ASC';
                    // echo $sql; die;
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
                        // output data of each row
                        while ($row = $this->result->fetch_assoc()) {
                            $TANT_CD1 = $row['TANT_CD1'];
                            $TANT_CD2 = $row['TANT_CD2'];
                            $TANT_CD3 = $row['TANT_CD3'];
                            $TANT_CD4 = $row['TANT_CD4'];

                            if (!empty($TANT_CD1) && $TANT_CD1 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['MONTHLY_SALES'] = $row['MONTHLY_SALES1'];
                                $data['TANT_NAME'] = $row['TANT_NAME1'];
                                $data['TANT_CD'] = $TANT_CD1;
                                $data['TYPE'] = 6;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }

                            if (!empty($TANT_CD2) && $TANT_CD2 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['MONTHLY_SALES'] = $row['MONTHLY_SALES2'];
                                $data['TANT_NAME'] = $row['TANT_NAME2'];
                                $data['TANT_CD1'] = $TANT_CD2;
                                $data['TYPE'] = 6;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }

                            if (!empty($TANT_CD3) && $TANT_CD3 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['MONTHLY_SALES'] = $row['MONTHLY_SALES3'];
                                $data['TANT_NAME'] = $row['TANT_NAME3'];
                                $data['TANT_CD1'] = $TANT_CD3;
                                $data['TYPE'] = 6;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }

                            if (!empty($TANT_CD4) && $TANT_CD4 == $TANT_CD) {
                                $KOJI_YMD = $row['KOJI_YMD'];
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['MONTHLY_SALES'] = $row['MONTHLY_SALES4'];
                                $data['TANT_NAME'] = $row['TANT_NAME4'];
                                $data['TANT_CD1'] = $TANT_CD4;
                                $data['TYPE'] = 6;
                                $resultSet2[$TANT_CD][$KOJI_YMD][] = $data;
                            }
                        }
                    }
                }

                $data_person = array();
                foreach ($resultSet2 as $key => $value) {
                    $data_person[] = $value;
                }

                $resultSet['PERSON'][] = $data_person;
            } else {
                $errors['msg'][] =  'Missing parameter YMD or KOJIGYOSYA_CD';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = array();
            $domain = $this->domain;
            if ((isset($_GET['YMD']) && $_GET['YMD'] != "") && (isset($_GET['ID']) && $_GET['ID'] != "")) {
                $YMD = $_GET['YMD'];
                $ID = $_GET['ID'];
                $YM = date('Y-m', strtotime($YMD));
                $start_date = date("Y-m-d", strtotime('monday this week', strtotime($YMD)));
                $end_date =  date("Y-m-d", strtotime('sunday this week', strtotime($YMD)));

                $sql = 'SELECT TANT_CD, TANT_NAME FROM M_TANT WHERE TANT_CD="' . $ID . '"';

                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                T_KOJI.SITAMI_YMD,
                T_KOJI.KOJI_YMD,
                T_KOJI.HOMON_TANT_CD1,
                T_KOJI.HOMON_TANT_CD2,
                T_KOJI.HOMON_TANT_CD3,
                T_KOJI.HOMON_TANT_CD4,
                T_KOJI.SETSAKI_NAME,
                T_KOJI.SETSAKI_ADDRESS,
                T_KOJI.KOJI_JININ,
                T_KOJI.SITAMI_JININ,
                T_KOJI.HOMON_SBT,
                T_KOJI.KOJI_ST,
                T_KOJI.KOJI_ITEM,
                T_KOJI.SITAMI_KANSAN_POINT,
                T_KOJI.KOJI_KANSAN_POINT,
                T_KOJI.SITAMI_JIKAN,
                T_KOJI.KOJI_JIKAN,
                T_KOJI.KOJI_KEKKA,
                T_KOJI.TENPO_CD,
                T_KOJI.HOJIN_FLG,
                T_KOJI.MALL_CD,
                T_KOJI.KOJIGYOSYA_CD,
                T_KOJI.TAG_KBN,
                T_KOJI.SITAMIHOMONJIKAN,
                T_KOJI.SITAMIHOMONJIKAN_END,
                T_KOJI.KOJIHOMONJIKAN,
                T_KOJI.KOJIHOMONJIKAN_END,
                T_KOJI.KOJIIRAISYO_FILEPATH,
                T_KOJI.SITAMIIRAISYO_FILEPATH,
                T_KOJI.CANCEL_RIYU,
                T_KOJI.SITAMIAPO_KBN,
                T_KOJI.KOJIAPO_KBN,
                T_KOJI.MTMORI_YMD,
                T_KOJI.MEMO,
                T_KOJI.COMMENT,
                T_KOJI.READ_FLG,
                T_KOJI.ATOBARAI,
                T_KOJI.BIKO,
                T_KOJI.SYUYAKU_JYUCYU_ID,
                T_KOJI.REPORT_FLG,
                T_KOJI.SITAMI_REPORT,
                T_KOJI.ALL_DAY_FLG,
                T_KOJI.CO_NAME,
                T_KOJI.CO_POSTNO,
                T_KOJI.CO_ADDRESS,
                T_KOJI.KOJI_ITAKUHI,
                T_KOJI.SKJ_RENKEI_YMD,
                T_KOJI.KOJI_RENKEI_YMD,
                M_KBN.KBN_CD,
                M_KBN.KBN_NAME,
                M_KBN.KBN_BIKO,
                M_KBN.KBNMSAI_CD,
                M_KBN.KBNMSAI_NAME, 
                M_KBN.KBNMSAI_BIKO,
                M_KBN.YOBIKOMOKU1, 
                M_KBN.YOBIKOMOKU2,
                M_KBN.YOBIKOMOKU3, 
                M_KBN.YOBIKOMOKU4,
                M_KBN.YOBIKOMOKU5,
                M_TANT.TANT_CD,
                M_TANT.TANT_NAME,
                T_KOJI.SITAMI_YMD,
                M_TANT.SYOZOKU_CD
                FROM T_KOJI 
                CROSS JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="05"
                CROSS JOIN M_TANT ON M_TANT.TANT_CD=T_KOJI.HOMON_TANT_CD4
                WHERE DATE_FORMAT(T_KOJI.SITAMI_YMD , "%Y-%m")="' . $YM . '" 
                AND T_KOJI.DEL_FLG=0                   
                AND M_TANT.TANT_CD="' . $ID . '"';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $SITAMI_YMD = $row['SITAMI_YMD'];
                        $data = array();
                        $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                        $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                        $data['KOJI_YMD'] = $row['KOJI_YMD'];
                        $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                        $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                        $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                        $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                        $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                        $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                        $data['KOJI_JININ'] = $row['KOJI_JININ'];
                        $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                        $data['HOMON_SBT'] = $row['HOMON_SBT'];
                        $data['KOJI_ST'] = $row['KOJI_ST'];
                        $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                        $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                        $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                        $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                        $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                        $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                        $data['TENPO_CD'] = $row['TENPO_CD'];
                        $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                        $data['MALL_CD'] = $row['MALL_CD'];
                        $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                        $data['TAG_KBN'] = $row['TAG_KBN'];
                        $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                        $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                        $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                        $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                        $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                        $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                        $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                        $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                        $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                        $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                        $data['MEMO'] = $row['MEMO'];
                        $data['COMMENT'] = $row['COMMENT'];
                        $data['READ_FLG'] = $row['READ_FLG'];
                        $data['ATOBARAI'] = $row['ATOBARAI'];
                        $data['BIKO'] = $row['BIKO'];
                        $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                        $data['REPORT_FLG'] = $row['REPORT_FLG'];
                        $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                        $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                        $data['CO_NAME'] = $row['CO_NAME'];
                        $data['CO_POSTNO'] = $row['CO_POSTNO'];
                        $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                        $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                        $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                        $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                        $data['KBN_CD'] = $row['KBN_CD'];
                        $data['KBN_NAME'] = $row['KBN_NAME'];
                        $data['KBN_BIKO'] = $row['KBN_BIKO'];
                        $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                        $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                        $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                        $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                        $data['TYPE'] = 1;
                        $resultSet[$SITAMI_YMD][] = $data;
                    }
                }

                //【ネット工事】
                $sql = ' SELECT T_KOJI.JYUCYU_ID,
                T_KOJI.SITAMI_YMD,
                T_KOJI.KOJI_YMD,
                T_KOJI.HOMON_TANT_CD1,
                T_KOJI.HOMON_TANT_CD2,
                T_KOJI.HOMON_TANT_CD3,
                T_KOJI.HOMON_TANT_CD4,
                T_KOJI.SETSAKI_NAME,
                T_KOJI.SETSAKI_ADDRESS,
                T_KOJI.KOJI_JININ,
                T_KOJI.SITAMI_JININ,
                T_KOJI.HOMON_SBT,
                T_KOJI.KOJI_ST,
                T_KOJI.KOJI_ITEM,
                T_KOJI.SITAMI_KANSAN_POINT,
                T_KOJI.KOJI_KANSAN_POINT,
                T_KOJI.SITAMI_JIKAN,
                T_KOJI.KOJI_JIKAN,
                T_KOJI.KOJI_KEKKA,
                T_KOJI.TENPO_CD,
                T_KOJI.HOJIN_FLG,
                T_KOJI.MALL_CD,
                T_KOJI.KOJIGYOSYA_CD,
                T_KOJI.TAG_KBN,
                T_KOJI.SITAMIHOMONJIKAN,
                T_KOJI.SITAMIHOMONJIKAN_END,
                T_KOJI.KOJIHOMONJIKAN,
                T_KOJI.KOJIHOMONJIKAN_END,
                T_KOJI.KOJIIRAISYO_FILEPATH,
                T_KOJI.SITAMIIRAISYO_FILEPATH,
                T_KOJI.CANCEL_RIYU,
                T_KOJI.SITAMIAPO_KBN,
                T_KOJI.KOJIAPO_KBN,
                T_KOJI.MTMORI_YMD,
                T_KOJI.MEMO,
                T_KOJI.COMMENT,
                T_KOJI.READ_FLG,
                T_KOJI.ATOBARAI,
                T_KOJI.BIKO,
                T_KOJI.SYUYAKU_JYUCYU_ID,
                T_KOJI.REPORT_FLG,
                T_KOJI.SITAMI_REPORT,
                T_KOJI.ALL_DAY_FLG,
                T_KOJI.CO_NAME,
                T_KOJI.CO_POSTNO,
                T_KOJI.CO_ADDRESS,
                T_KOJI.KOJI_ITAKUHI,
                T_KOJI.SKJ_RENKEI_YMD,
                T_KOJI.KOJI_RENKEI_YMD,
                M_KBN.KBN_CD,
                M_KBN.KBN_NAME,
                M_KBN.KBN_BIKO,
                M_KBN.KBNMSAI_CD,
                M_KBN.KBNMSAI_NAME, 
                M_KBN.KBNMSAI_BIKO,
                M_KBN.YOBIKOMOKU1, 
                M_KBN.YOBIKOMOKU2,
                M_KBN.YOBIKOMOKU3, 
                M_KBN.YOBIKOMOKU4,
                M_KBN.YOBIKOMOKU5,
                M_TANT1.TANT_CD AS TANT_CD1,
                M_TANT1.TANT_NAME AS TANT_NAME1,
                M_TANT2.TANT_CD AS TANT_CD2,
                M_TANT2.TANT_NAME AS TANT_NAME2,
                M_TANT3.TANT_CD AS TANT_CD3,
                M_TANT3.TANT_NAME AS TANT_NAME3,
                T_KOJI.KOJI_YMD
                FROM T_KOJI 
                CROSS JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="05"
                CROSS JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                CROSS JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                CROSS JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                WHERE DATE_FORMAT(T_KOJI.KOJI_YMD , "%Y-%m")="' . $YM . '" 
                    AND T_KOJI.DEL_FLG=0                          
                    AND (M_TANT1.TANT_CD="' . $ID . '" OR M_TANT2.TANT_CD="' . $ID . '" OR M_TANT3.TANT_CD="' . $ID . '" )';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $KOJI_YMD = $row['KOJI_YMD'];
                        $data = array();
                        $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                        $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                        $data['KOJI_YMD'] = $row['KOJI_YMD'];
                        $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                        $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                        $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                        $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                        $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                        $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                        $data['KOJI_JININ'] = $row['KOJI_JININ'];
                        $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                        $data['HOMON_SBT'] = $row['HOMON_SBT'];
                        $data['KOJI_ST'] = $row['KOJI_ST'];
                        $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                        $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                        $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                        $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                        $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                        $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                        $data['TENPO_CD'] = $row['TENPO_CD'];
                        $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                        $data['MALL_CD'] = $row['MALL_CD'];
                        $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                        $data['TAG_KBN'] = $row['TAG_KBN'];
                        $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                        $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                        $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                        $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                        $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                        $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                        $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                        $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                        $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                        $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                        $data['MEMO'] = $row['MEMO'];
                        $data['COMMENT'] = $row['COMMENT'];
                        $data['READ_FLG'] = $row['READ_FLG'];
                        $data['ATOBARAI'] = $row['ATOBARAI'];
                        $data['BIKO'] = $row['BIKO'];
                        $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                        $data['REPORT_FLG'] = $row['REPORT_FLG'];
                        $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                        $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                        $data['CO_NAME'] = $row['CO_NAME'];
                        $data['CO_POSTNO'] = $row['CO_POSTNO'];
                        $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                        $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                        $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                        $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                        $data['KBN_CD'] = $row['KBN_CD'];
                        $data['KBN_NAME'] = $row['KBN_NAME'];
                        $data['KBN_BIKO'] = $row['KBN_BIKO'];
                        $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                        $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                        $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                        $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                        $data['TYPE'] = 2;
                        $resultSet[$KOJI_YMD][] = $data;
                    }
                }

                //【営業工事・営業下見（担当者欄）】
                $sql = 'SELECT  T_EIGYO_ANKEN.TAN_EIG_ID,
                T_EIGYO_ANKEN.JYOKEN_CD,  
                T_EIGYO_ANKEN.JYOKEN_SYBET_FLG, 
                T_EIGYO_ANKEN.YMD,
                T_EIGYO_ANKEN.START_TIME, 
                T_EIGYO_ANKEN.END_TIME, 
                T_EIGYO_ANKEN.TAG_KBN, 
                T_EIGYO_ANKEN.JININ,
                T_EIGYO_ANKEN.JIKAN, 
                T_EIGYO_ANKEN.GUEST_NAME,
                T_EIGYO_ANKEN.ATTEND_NAME1,
                T_EIGYO_ANKEN.ATTEND_NAME2,
                T_EIGYO_ANKEN.ATTEND_NAME3,
                T_EIGYO_ANKEN.ALL_DAY_FLG,
                T_EIGYO_ANKEN.RENKEI_YMD,
                M_KBN.KBN_CD,
                M_KBN.KBN_NAME,
                M_KBN.KBN_BIKO,
                M_KBN.KBNMSAI_CD,
                M_KBN.KBNMSAI_NAME, 
                M_KBN.KBNMSAI_BIKO,
                M_KBN.YOBIKOMOKU1, 
                M_KBN.YOBIKOMOKU2,
                M_KBN.YOBIKOMOKU3, 
                M_KBN.YOBIKOMOKU4,
                M_KBN.YOBIKOMOKU5,
                M_TANT.TANT_NAME, 
                M_TANT.TANT_CD
                FROM T_EIGYO_ANKEN 
                CROSS JOIN M_KBN ON T_EIGYO_ANKEN.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="10"
                CROSS JOIN M_TANT ON T_EIGYO_ANKEN.JYOKEN_CD=M_TANT.TANT_CD
                WHERE DATE_FORMAT(T_EIGYO_ANKEN.YMD , "%Y-%m")= "' . $YM . '" 
                AND T_EIGYO_ANKEN.DEL_FLG=0                  
                AND T_EIGYO_ANKEN.JYOKEN_SYBET_FLG=0
                AND M_TANT.TANT_CD="' . $ID . '"';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $EIGYO_ANKEN_YMD = $row['YMD'];
                        $data = array();
                        $data['TAN_EIG_ID'] = $row['TAN_EIG_ID'];
                        $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                        $data['JYOKEN_SYBET_FLG'] = $row['JYOKEN_SYBET_FLG'];
                        $data['YMD'] = $row['YMD'];
                        $data['TAG_KBN'] = $row['TAG_KBN'];
                        $data['START_TIME'] = $row['START_TIME'];
                        $data['END_TIME'] = $row['END_TIME'];
                        $data['JININ'] = $row['JININ'];
                        $data['JIKAN'] = $row['JIKAN'];
                        $data['GUEST_NAME'] = $row['GUEST_NAME'];
                        $data['ATTEND_NAME1'] = $row['ATTEND_NAME1'];
                        $data['ATTEND_NAME2'] = $row['ATTEND_NAME2'];
                        $data['ATTEND_NAME3'] = $row['ATTEND_NAME3'];
                        $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                        $data['RENKEI_YMD'] = $row['RENKEI_YMD'];
                        $data['KBN_CD'] = $row['KBN_CD'];
                        $data['KBN_NAME'] = $row['KBN_NAME'];
                        $data['KBN_BIKO'] = $row['KBN_BIKO'];
                        $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                        $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                        $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                        $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                        $data['TYPE'] = 3;
                        $resultSet[$EIGYO_ANKEN_YMD][] = $data;
                    }
                }

                //【メモ（営業所欄）】
                $sql = 'SELECT  T_TBETUCALENDAR.TAN_CAL_ID,
                T_TBETUCALENDAR.JYOKEN_CD, 
                T_TBETUCALENDAR.JYOKEN_SYBET_FLG, 
                T_TBETUCALENDAR.YMD, 
                T_TBETUCALENDAR.TAG_KBN, 
                T_TBETUCALENDAR.START_TIME, 
                T_TBETUCALENDAR.END_TIME,
                T_TBETUCALENDAR.MEMO_CD,
                T_TBETUCALENDAR.NAIYO, 
                T_TBETUCALENDAR.COMMENT, 
                T_TBETUCALENDAR.ALL_DAY_FLG, 
                T_TBETUCALENDAR.RENKEI_YMD, 
                M_KBN.KBN_CD,
                M_KBN.KBN_NAME,
                M_KBN.KBN_BIKO,
                M_KBN.KBNMSAI_CD,
                M_KBN.KBNMSAI_NAME, 
                M_KBN.KBNMSAI_BIKO,
                M_KBN.YOBIKOMOKU1, 
                M_KBN.YOBIKOMOKU2,
                M_KBN.YOBIKOMOKU3, 
                M_KBN.YOBIKOMOKU4,
                M_KBN.YOBIKOMOKU5,
                M_TANT.TANT_CD          
                FROM T_TBETUCALENDAR 
                CROSS JOIN M_KBN ON T_TBETUCALENDAR.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="10"
                CROSS JOIN M_TANT ON T_TBETUCALENDAR.JYOKEN_CD=M_TANT.TANT_CD
                WHERE DATE_FORMAT(T_TBETUCALENDAR.YMD , "%Y-%m")= "' . $YM . '"
                AND T_TBETUCALENDAR.DEL_FLG=0                                      
                AND T_TBETUCALENDAR.JYOKEN_SYBET_FLG=0 
                AND M_TANT.TANT_CD="' . $ID . '"';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $TBETUCALENDAR_YMD = $row['YMD'];
                        $data = array();
                        $data['TAN_CAL_ID'] = $row['TAN_CAL_ID'];
                        $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                        $data['JYOKEN_SYBET_FLG'] = $row['JYOKEN_SYBET_FLG'];
                        $data['YMD'] = $row['YMD'];
                        $data['TAG_KBN'] = $row['TAG_KBN'];
                        $data['START_TIME'] = $row['START_TIME'];
                        $data['END_TIME'] = $row['END_TIME'];
                        $data['MEMO_CD'] = $row['MEMO_CD'];
                        $data['NAIYO'] = $row['NAIYO'];
                        $data['COMMENT'] = $row['COMMENT'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                        $data['RENKEI_YMD'] = $row['RENKEI_YMD'];
                        $data['KBN_CD'] = $row['KBN_CD'];
                        $data['KBN_NAME'] = $row['KBN_NAME'];
                        $data['KBN_BIKO'] = $row['KBN_BIKO'];
                        $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                        $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                        $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                        $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                        $data['TYPE'] = 4;
                        $resultSet[$TBETUCALENDAR_YMD][] = $data;
                    }
                }

                //【日予実】
                $sql = ' SELECT T_KOJI.JYUCYU_ID,
                T_KOJI.SITAMI_YMD,
                T_KOJI.KOJI_YMD,
                T_KOJI.HOMON_TANT_CD1,
                T_KOJI.HOMON_TANT_CD2,
                T_KOJI.HOMON_TANT_CD3,
                T_KOJI.HOMON_TANT_CD4,
                T_KOJI.SETSAKI_NAME,
                T_KOJI.SETSAKI_ADDRESS,
                T_KOJI.KOJI_JININ,
                T_KOJI.SITAMI_JININ,
                T_KOJI.HOMON_SBT,
                T_KOJI.KOJI_ST,
                T_KOJI.KOJI_ITEM,
                T_KOJI.SITAMI_KANSAN_POINT,
                T_KOJI.KOJI_KANSAN_POINT,
                T_KOJI.SITAMI_JIKAN,
                T_KOJI.KOJI_JIKAN,
                T_KOJI.KOJI_KEKKA,
                T_KOJI.TENPO_CD,
                T_KOJI.HOJIN_FLG,
                T_KOJI.MALL_CD,
                T_KOJI.KOJIGYOSYA_CD,
                T_KOJI.TAG_KBN,
                T_KOJI.SITAMIHOMONJIKAN,
                T_KOJI.SITAMIHOMONJIKAN_END,
                T_KOJI.KOJIHOMONJIKAN,
                T_KOJI.KOJIHOMONJIKAN_END,
                T_KOJI.KOJIIRAISYO_FILEPATH,
                T_KOJI.SITAMIIRAISYO_FILEPATH,
                T_KOJI.CANCEL_RIYU,
                T_KOJI.SITAMIAPO_KBN,
                T_KOJI.KOJIAPO_KBN,
                T_KOJI.MTMORI_YMD,
                T_KOJI.MEMO,
                T_KOJI.COMMENT,
                T_KOJI.READ_FLG,
                T_KOJI.ATOBARAI,
                T_KOJI.BIKO,
                T_KOJI.SYUYAKU_JYUCYU_ID,
                T_KOJI.REPORT_FLG,
                T_KOJI.SITAMI_REPORT,
                T_KOJI.ALL_DAY_FLG,
                T_KOJI.CO_NAME,
                T_KOJI.CO_POSTNO,
                T_KOJI.CO_ADDRESS,
                T_KOJI.KOJI_ITAKUHI,
                T_KOJI.SKJ_RENKEI_YMD,
                T_KOJI.KOJI_RENKEI_YMD,
                M_KBN.KBN_CD,
                M_KBN.KBN_NAME,
                M_KBN.KBN_BIKO,
                M_KBN.KBNMSAI_CD,
                M_KBN.KBNMSAI_NAME, 
                M_KBN.KBNMSAI_BIKO,
                M_KBN.YOBIKOMOKU1, 
                M_KBN.YOBIKOMOKU2,
                M_KBN.YOBIKOMOKU3, 
                M_KBN.YOBIKOMOKU4,
                M_KBN.YOBIKOMOKU5,
                M_TANT1.DAYLY_SALES AS DAYLY_SALES1, 
                M_TANT2.DAYLY_SALES AS DAYLY_SALES2,  
                M_TANT3.DAYLY_SALES AS DAYLY_SALES3,  
                M_TANT4.DAYLY_SALES AS DAYLY_SALES4,                      
                M_TANT1.TANT_CD AS TANT_CD1,
                M_TANT1.TANT_NAME AS TANT_NAME1,
                M_TANT2.TANT_CD AS TANT_CD2,
                M_TANT2.TANT_NAME AS TANT_NAME2,
                M_TANT3.TANT_CD AS TANT_CD3,
                M_TANT3.TANT_NAME AS TANT_NAME3, 
                M_TANT4.TANT_CD AS TANT_CD4,
                M_TANT4.TANT_NAME AS TANT_NAME4
                FROM T_KOJI 
                CROSS JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBNMSAI_CD="01" AND M_KBN.KBN_CD="16"
                CROSS JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                CROSS JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                CROSS JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                CROSS JOIN M_TANT as M_TANT4 ON M_TANT4.TANT_CD=T_KOJI.HOMON_TANT_CD4
                WHERE DATE_FORMAT(T_KOJI.KOJI_YMD , "%Y-%m")= "' . $YM . '"
                AND T_KOJI.DEL_FLG=0                                               
                AND (M_TANT1.TANT_CD="' . $ID . '" OR M_TANT2.TANT_CD="' . $ID . '" OR M_TANT3.TANT_CD="' . $ID . '" OR M_TANT4.TANT_CD="' . $ID . '" )';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        if (!empty($row['TANT_CD1']) && $row['TANT_CD1'] == $ID) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                            $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                            $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                            $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_JININ'] = $row['KOJI_JININ'];
                            $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                            $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                            $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                            $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                            $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                            $data['TENPO_CD'] = $row['TENPO_CD'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['MALL_CD'] = $row['MALL_CD'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                            $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                            $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                            $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                            $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                            $data['MEMO'] = $row['MEMO'];
                            $data['COMMENT'] = $row['COMMENT'];
                            $data['READ_FLG'] = $row['READ_FLG'];
                            $data['ATOBARAI'] = $row['ATOBARAI'];
                            $data['BIKO'] = $row['BIKO'];
                            $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                            $data['REPORT_FLG'] = $row['REPORT_FLG'];
                            $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                            $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['DAYLY_SALES'] = $row['DAYLY_SALES1'];
                            $data['TYPE'] = 5;
                            $resultSet[$KOJI_YMD][] = $data;
                        }

                        if (!empty($row['TANT_CD2']) && $row['TANT_CD2'] == $ID) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                            $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                            $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                            $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_JININ'] = $row['KOJI_JININ'];
                            $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                            $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                            $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                            $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                            $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                            $data['TENPO_CD'] = $row['TENPO_CD'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['MALL_CD'] = $row['MALL_CD'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                            $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                            $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                            $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                            $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                            $data['MEMO'] = $row['MEMO'];
                            $data['COMMENT'] = $row['COMMENT'];
                            $data['READ_FLG'] = $row['READ_FLG'];
                            $data['ATOBARAI'] = $row['ATOBARAI'];
                            $data['BIKO'] = $row['BIKO'];
                            $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                            $data['REPORT_FLG'] = $row['REPORT_FLG'];
                            $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                            $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['DAYLY_SALES'] = $row['DAYLY_SALES2'];
                            $data['TYPE'] = 5;
                            $resultSet[$KOJI_YMD][] = $data;
                        }

                        if (!empty($row['TANT_CD3']) && $row['TANT_CD3'] == $ID) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                            $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                            $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                            $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_JININ'] = $row['KOJI_JININ'];
                            $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                            $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                            $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                            $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                            $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                            $data['TENPO_CD'] = $row['TENPO_CD'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['MALL_CD'] = $row['MALL_CD'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                            $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                            $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                            $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                            $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                            $data['MEMO'] = $row['MEMO'];
                            $data['COMMENT'] = $row['COMMENT'];
                            $data['READ_FLG'] = $row['READ_FLG'];
                            $data['ATOBARAI'] = $row['ATOBARAI'];
                            $data['BIKO'] = $row['BIKO'];
                            $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                            $data['REPORT_FLG'] = $row['REPORT_FLG'];
                            $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                            $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['DAYLY_SALES'] = $row['DAYLY_SALES3'];
                            $data['TYPE'] = 5;
                            $resultSet[$KOJI_YMD][] = $data;
                        }

                        if (!empty($row['TANT_CD4']) && $row['TANT_CD4'] == $ID) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                            $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                            $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                            $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_JININ'] = $row['KOJI_JININ'];
                            $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                            $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                            $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                            $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                            $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                            $data['TENPO_CD'] = $row['TENPO_CD'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['MALL_CD'] = $row['MALL_CD'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                            $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                            $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                            $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                            $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                            $data['MEMO'] = $row['MEMO'];
                            $data['COMMENT'] = $row['COMMENT'];
                            $data['READ_FLG'] = $row['READ_FLG'];
                            $data['ATOBARAI'] = $row['ATOBARAI'];
                            $data['BIKO'] = $row['BIKO'];
                            $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                            $data['REPORT_FLG'] = $row['REPORT_FLG'];
                            $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                            $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['DAYLY_SALES'] = $row['DAYLY_SALES4'];
                            $data['TYPE'] = 5;
                            $resultSet[$KOJI_YMD][] = $data;
                        }
                    }
                }

                //【計予実】
                $sql = ' SELECT T_KOJI.JYUCYU_ID,
                T_KOJI.SITAMI_YMD,
                T_KOJI.KOJI_YMD,
                T_KOJI.HOMON_TANT_CD1,
                T_KOJI.HOMON_TANT_CD2,
                T_KOJI.HOMON_TANT_CD3,
                T_KOJI.HOMON_TANT_CD4,
                T_KOJI.SETSAKI_NAME,
                T_KOJI.SETSAKI_ADDRESS,
                T_KOJI.KOJI_JININ,
                T_KOJI.SITAMI_JININ,
                T_KOJI.HOMON_SBT,
                T_KOJI.KOJI_ST,
                T_KOJI.KOJI_ITEM,
                T_KOJI.SITAMI_KANSAN_POINT,
                T_KOJI.KOJI_KANSAN_POINT,
                T_KOJI.SITAMI_JIKAN,
                T_KOJI.KOJI_JIKAN,
                T_KOJI.KOJI_KEKKA,
                T_KOJI.TENPO_CD,
                T_KOJI.HOJIN_FLG,
                T_KOJI.MALL_CD,
                T_KOJI.KOJIGYOSYA_CD,
                T_KOJI.TAG_KBN,
                T_KOJI.SITAMIHOMONJIKAN,
                T_KOJI.SITAMIHOMONJIKAN_END,
                T_KOJI.KOJIHOMONJIKAN,
                T_KOJI.KOJIHOMONJIKAN_END,
                T_KOJI.KOJIIRAISYO_FILEPATH,
                T_KOJI.SITAMIIRAISYO_FILEPATH,
                T_KOJI.CANCEL_RIYU,
                T_KOJI.SITAMIAPO_KBN,
                T_KOJI.KOJIAPO_KBN,
                T_KOJI.MTMORI_YMD,
                T_KOJI.MEMO,
                T_KOJI.COMMENT,
                T_KOJI.READ_FLG,
                T_KOJI.ATOBARAI,
                T_KOJI.BIKO,
                T_KOJI.SYUYAKU_JYUCYU_ID,
                T_KOJI.REPORT_FLG,
                T_KOJI.SITAMI_REPORT,
                T_KOJI.ALL_DAY_FLG,
                T_KOJI.CO_NAME,
                T_KOJI.CO_POSTNO,
                T_KOJI.CO_ADDRESS,
                T_KOJI.KOJI_ITAKUHI,
                T_KOJI.SKJ_RENKEI_YMD,
                T_KOJI.KOJI_RENKEI_YMD,
                M_KBN.KBN_CD,
                M_KBN.KBN_NAME,
                M_KBN.KBN_BIKO,
                M_KBN.KBNMSAI_CD,
                M_KBN.KBNMSAI_NAME, 
                M_KBN.KBNMSAI_BIKO,
                M_KBN.YOBIKOMOKU1, 
                M_KBN.YOBIKOMOKU2,
                M_KBN.YOBIKOMOKU3, 
                M_KBN.YOBIKOMOKU4,
                M_KBN.YOBIKOMOKU5,
                M_TANT1.MONTHLY_SALES AS MONTHLY_SALES1, 
                M_TANT2.MONTHLY_SALES AS MONTHLY_SALES2,  
                M_TANT3.MONTHLY_SALES AS MONTHLY_SALES3,  
                M_TANT4.MONTHLY_SALES AS MONTHLY_SALES4,                      
                M_TANT1.TANT_CD AS TANT_CD1,
                M_TANT1.TANT_NAME AS TANT_NAME1,
                M_TANT2.TANT_CD AS TANT_CD2,
                M_TANT2.TANT_NAME AS TANT_NAME2,
                M_TANT3.TANT_CD AS TANT_CD3,
                M_TANT3.TANT_NAME AS TANT_NAME3, 
                M_TANT4.TANT_CD AS TANT_CD4,
                M_TANT4.TANT_NAME AS TANT_NAME4
                FROM T_KOJI 
                CROSS JOIN M_KBN ON T_KOJI.TAG_KBN=M_KBN.KBNMSAI_CD AND M_KBN.KBNMSAI_CD="01" AND M_KBN.KBN_CD="16"  
                CROSS JOIN M_TANT as M_TANT1 ON M_TANT1.TANT_CD=T_KOJI.HOMON_TANT_CD1
                CROSS JOIN M_TANT as M_TANT2 ON M_TANT2.TANT_CD=T_KOJI.HOMON_TANT_CD2
                CROSS JOIN M_TANT as M_TANT3 ON M_TANT3.TANT_CD=T_KOJI.HOMON_TANT_CD3
                CROSS JOIN M_TANT as M_TANT4 ON M_TANT4.TANT_CD=T_KOJI.HOMON_TANT_CD4
                WHERE DATE_FORMAT(T_KOJI.KOJI_YMD , "%Y-%m")= "' . $YM . '" 
                AND T_KOJI.DEL_FLG=0                                                   
                AND (M_TANT1.TANT_CD="' . $ID . '" OR M_TANT2.TANT_CD="' . $ID . '" OR M_TANT3.TANT_CD="' . $ID . '" OR M_TANT4.TANT_CD="' . $ID . '" )';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        if (!empty($row['TANT_CD1']) && $row['TANT_CD1'] == $ID) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                            $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                            $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                            $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_JININ'] = $row['KOJI_JININ'];
                            $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                            $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                            $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                            $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                            $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                            $data['TENPO_CD'] = $row['TENPO_CD'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['MALL_CD'] = $row['MALL_CD'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                            $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                            $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                            $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                            $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                            $data['MEMO'] = $row['MEMO'];
                            $data['COMMENT'] = $row['COMMENT'];
                            $data['READ_FLG'] = $row['READ_FLG'];
                            $data['ATOBARAI'] = $row['ATOBARAI'];
                            $data['BIKO'] = $row['BIKO'];
                            $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                            $data['REPORT_FLG'] = $row['REPORT_FLG'];
                            $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                            $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['MONTHLY_SALES'] = $row['MONTHLY_SALES1'];
                            $data['TYPE'] = 6;
                            $resultSet[$KOJI_YMD][] = $data;
                        }

                        if (!empty($row['TANT_CD2']) && $row['TANT_CD2'] == $ID) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                            $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                            $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                            $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_JININ'] = $row['KOJI_JININ'];
                            $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                            $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                            $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                            $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                            $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                            $data['TENPO_CD'] = $row['TENPO_CD'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['MALL_CD'] = $row['MALL_CD'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                            $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                            $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                            $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                            $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                            $data['MEMO'] = $row['MEMO'];
                            $data['COMMENT'] = $row['COMMENT'];
                            $data['READ_FLG'] = $row['READ_FLG'];
                            $data['ATOBARAI'] = $row['ATOBARAI'];
                            $data['BIKO'] = $row['BIKO'];
                            $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                            $data['REPORT_FLG'] = $row['REPORT_FLG'];
                            $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                            $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['MONTHLY_SALES'] = $row['MONTHLY_SALES2'];
                            $data['TYPE'] = 6;
                            $resultSet[$KOJI_YMD][] = $data;
                        }

                        if (!empty($row['TANT_CD3']) && $row['TANT_CD3'] == $ID) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                            $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                            $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                            $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_JININ'] = $row['KOJI_JININ'];
                            $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                            $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                            $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                            $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                            $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                            $data['TENPO_CD'] = $row['TENPO_CD'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['MALL_CD'] = $row['MALL_CD'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                            $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                            $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                            $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                            $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                            $data['MEMO'] = $row['MEMO'];
                            $data['COMMENT'] = $row['COMMENT'];
                            $data['READ_FLG'] = $row['READ_FLG'];
                            $data['ATOBARAI'] = $row['ATOBARAI'];
                            $data['BIKO'] = $row['BIKO'];
                            $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                            $data['REPORT_FLG'] = $row['REPORT_FLG'];
                            $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                            $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['MONTHLY_SALES'] = $row['MONTHLY_SALES3'];
                            $data['TYPE'] = 6;
                            $resultSet[$KOJI_YMD][] = $data;
                        }

                        if (!empty($row['TANT_CD4']) && $row['TANT_CD4'] == $ID) {
                            $KOJI_YMD = $row['KOJI_YMD'];
                            $data = array();
                            $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                            $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                            $data['KOJI_YMD'] = $row['KOJI_YMD'];
                            $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                            $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                            $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                            $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                            $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                            $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                            $data['KOJI_JININ'] = $row['KOJI_JININ'];
                            $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                            $data['HOMON_SBT'] = $row['HOMON_SBT'];
                            $data['KOJI_ST'] = $row['KOJI_ST'];
                            $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                            $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                            $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                            $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                            $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                            $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                            $data['TENPO_CD'] = $row['TENPO_CD'];
                            $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                            $data['MALL_CD'] = $row['MALL_CD'];
                            $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                            $data['TAG_KBN'] = $row['TAG_KBN'];
                            $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                            $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                            $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                            $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                            $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                            $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                            $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                            $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                            $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                            $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                            $data['MEMO'] = $row['MEMO'];
                            $data['COMMENT'] = $row['COMMENT'];
                            $data['READ_FLG'] = $row['READ_FLG'];
                            $data['ATOBARAI'] = $row['ATOBARAI'];
                            $data['BIKO'] = $row['BIKO'];
                            $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                            $data['REPORT_FLG'] = $row['REPORT_FLG'];
                            $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                            $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                            $data['CO_NAME'] = $row['CO_NAME'];
                            $data['CO_POSTNO'] = $row['CO_POSTNO'];
                            $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                            $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                            $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                            $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                            $data['KBN_CD'] = $row['KBN_CD'];
                            $data['KBN_NAME'] = $row['KBN_NAME'];
                            $data['KBN_BIKO'] = $row['KBN_BIKO'];
                            $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                            $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                            $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                            $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                            $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                            $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                            $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                            $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                            $data['MONTHLY_SALES'] = $row['MONTHLY_SALES4'];
                            $data['TYPE'] = 6;
                            $resultSet[$KOJI_YMD][] = $data;
                        }
                    }
                }
            } else {
                $errors['msg'][] = 'Missing parameter YMD or ID ';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = array();
            if (isset($_GET['KOJIGYOSYA_CD'])) {
                $KOJIGYOSYA_CD = $_GET['KOJIGYOSYA_CD'];
                $sql = 'SELECT TANT_CD,TANT_NAME 
                 FROM M_TANT WHERE SYOZOKUBUSYO_CD=' . $KOJIGYOSYA_CD . '
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
            } else {
                $errors['msg'][] = 'Missing parameter KOJIGYOSYA_CD ';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = array();

            if ((isset($_GET['TANT_KBN_CD']) && $_GET['TANT_KBN_CD'] != "") &&
                (isset($_GET['SYOZOKU_CD']) && $_GET['SYOZOKU_CD'] != "")
            ) {
                $TANT_KBN_CD = $_GET['TANT_KBN_CD'];
                $SYOZOKU_CD = $_GET['SYOZOKU_CD'];

                if (in_array($TANT_KBN_CD, ["01", "03"])) {
                    $sql = ' SELECT KOJIGYOSYA_CD,KOJIGYOSYA_NAME 
                    FROM M_GYOSYA WHERE DEL_FLG=0';
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
                }

                if (in_array($TANT_KBN_CD, ["02", "04"])) {
                    $sql = ' SELECT KOJIGYOSYA_CD,KOJIGYOSYA_NAME                    
                    FROM M_GYOSYA 
                    WHERE KOJIGYOSYA_CD="' . $SYOZOKU_CD . '" AND DEL_FLG=0';
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
                }
            } else {
                $errors['msg'][] = 'Missing parameter TANT_KBN_CD or SYOZOKU_CD';
            }



            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
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
            $errors = [];
            $resultSet = array();
            $domain = $this->domain;

            if ((isset($_GET['JYUCYU_ID']) && $_GET['JYUCYU_ID'] != "") &&
                (isset($_GET['HOMON_SBT']) && $_GET['HOMON_SBT'] != "")
            ) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($result_list_file && $result_list_file->num_rows > 0) {
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
                        $sql = 'SELECT T_KOJI.JYUCYU_ID,
                        T_KOJI.SITAMI_YMD,
                        T_KOJI.KOJI_YMD,
                        T_KOJI.HOMON_TANT_CD1,
                        T_KOJI.HOMON_TANT_CD2,
                        T_KOJI.HOMON_TANT_CD3,
                        T_KOJI.HOMON_TANT_CD4,
                        T_KOJI.SETSAKI_NAME,
                        T_KOJI.SETSAKI_ADDRESS,
                        T_KOJI.KOJI_JININ,
                        T_KOJI.SITAMI_JININ,
                        T_KOJI.HOMON_SBT,
                        T_KOJI.KOJI_ST,
                        T_KOJI.KOJI_ITEM,
                        T_KOJI.SITAMI_KANSAN_POINT,
                        T_KOJI.KOJI_KANSAN_POINT,
                        T_KOJI.SITAMI_JIKAN,
                        T_KOJI.KOJI_JIKAN,
                        T_KOJI.KOJI_KEKKA,
                        T_KOJI.TENPO_CD,
                        T_KOJI.HOJIN_FLG,
                        T_KOJI.MALL_CD,
                        T_KOJI.KOJIGYOSYA_CD,
                        T_KOJI.TAG_KBN,
                        T_KOJI.SITAMIHOMONJIKAN,
                        T_KOJI.SITAMIHOMONJIKAN_END,
                        T_KOJI.KOJIHOMONJIKAN,
                        T_KOJI.KOJIHOMONJIKAN_END,
                        T_KOJI.KOJIIRAISYO_FILEPATH,
                        T_KOJI.SITAMIIRAISYO_FILEPATH,
                        T_KOJI.CANCEL_RIYU,
                        T_KOJI.SITAMIAPO_KBN,
                        T_KOJI.KOJIAPO_KBN,
                        T_KOJI.MTMORI_YMD,
                        T_KOJI.MEMO,
                        T_KOJI.COMMENT,
                        T_KOJI.READ_FLG,
                        T_KOJI.ATOBARAI,
                        T_KOJI.BIKO,
                        T_KOJI.SYUYAKU_JYUCYU_ID,
                        T_KOJI.REPORT_FLG,
                        T_KOJI.SITAMI_REPORT,
                        T_KOJI.ALL_DAY_FLG,
                        T_KOJI.CO_NAME,
                        T_KOJI.CO_POSTNO,
                        T_KOJI.CO_ADDRESS,
                        T_KOJI.KOJI_ITAKUHI,
                        T_KOJI.SKJ_RENKEI_YMD,
                        T_KOJI.KOJI_RENKEI_YMD,
                        T_KOJI.ADD_TANTNM, 
                        T_KOJI.ADD_YMD, 
                        T_KOJI.UPD_TANTNM, 
                        T_KOJI.UPD_YMD,                                             
                        M_KBN.KBN_CD,
                        M_KBN.KBN_NAME,
                        M_KBN.KBN_BIKO,
                        M_KBN.KBNMSAI_CD,
                        M_KBN.KBNMSAI_NAME, 
                        M_KBN.KBNMSAI_BIKO,
                        M_KBN.YOBIKOMOKU1, 
                        M_KBN.YOBIKOMOKU2,
                        M_KBN.YOBIKOMOKU3, 
                        M_KBN.YOBIKOMOKU4,
                        M_KBN.YOBIKOMOKU5              
                        FROM T_KOJI 
                        LEFT JOIN M_KBN ON T_KOJI.TAG_KBN = M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="05"
                        WHERE T_KOJI.JYUCYU_ID="' . $JYUCYU_ID . '"        
                        AND T_KOJI.DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sql);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['HOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['HOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['ADD_TANTNM'] = $row['ADD_TANTNM'];
                                $data['ADD_YMD'] = $row['ADD_YMD'];
                                $data['UPD_TANTNM'] = $row['UPD_TANTNM'];
                                $data['UPD_YMD'] = $row['UPD_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['FILEPATH'] = $arr_list_file;
                                $resultSet[] = $data;
                            }
                        }
                        break;
                    case "02":
                        $sql = 'SELECT T_KOJI.JYUCYU_ID,
                        T_KOJI.SITAMI_YMD,
                        T_KOJI.KOJI_YMD,
                        T_KOJI.HOMON_TANT_CD1,
                        T_KOJI.HOMON_TANT_CD2,
                        T_KOJI.HOMON_TANT_CD3,
                        T_KOJI.HOMON_TANT_CD4,
                        T_KOJI.SETSAKI_NAME,
                        T_KOJI.SETSAKI_ADDRESS,
                        T_KOJI.KOJI_JININ,
                        T_KOJI.SITAMI_JININ,
                        T_KOJI.HOMON_SBT,
                        T_KOJI.KOJI_ST,
                        T_KOJI.KOJI_ITEM,
                        T_KOJI.SITAMI_KANSAN_POINT,
                        T_KOJI.KOJI_KANSAN_POINT,
                        T_KOJI.SITAMI_JIKAN,
                        T_KOJI.KOJI_JIKAN,
                        T_KOJI.KOJI_KEKKA,
                        T_KOJI.TENPO_CD,
                        T_KOJI.HOJIN_FLG,
                        T_KOJI.MALL_CD,
                        T_KOJI.KOJIGYOSYA_CD,
                        T_KOJI.TAG_KBN,
                        T_KOJI.SITAMIHOMONJIKAN,
                        T_KOJI.SITAMIHOMONJIKAN_END,
                        T_KOJI.KOJIHOMONJIKAN,
                        T_KOJI.KOJIHOMONJIKAN_END,
                        T_KOJI.KOJIIRAISYO_FILEPATH,
                        T_KOJI.SITAMIIRAISYO_FILEPATH,
                        T_KOJI.CANCEL_RIYU,
                        T_KOJI.SITAMIAPO_KBN,
                        T_KOJI.KOJIAPO_KBN,
                        T_KOJI.MTMORI_YMD,
                        T_KOJI.MEMO,
                        T_KOJI.COMMENT,
                        T_KOJI.READ_FLG,
                        T_KOJI.ATOBARAI,
                        T_KOJI.BIKO,
                        T_KOJI.SYUYAKU_JYUCYU_ID,
                        T_KOJI.REPORT_FLG,
                        T_KOJI.SITAMI_REPORT,
                        T_KOJI.ALL_DAY_FLG,
                        T_KOJI.CO_NAME,
                        T_KOJI.CO_POSTNO,
                        T_KOJI.CO_ADDRESS,
                        T_KOJI.KOJI_ITAKUHI,
                        T_KOJI.SKJ_RENKEI_YMD,
                        T_KOJI.KOJI_RENKEI_YMD,
                        T_KOJI.ADD_TANTNM, 
                        T_KOJI.ADD_YMD, 
                        T_KOJI.UPD_TANTNM, 
                        T_KOJI.UPD_YMD,                                             
                        M_KBN.KBN_CD,
                        M_KBN.KBN_NAME,
                        M_KBN.KBN_BIKO,
                        M_KBN.KBNMSAI_CD,
                        M_KBN.KBNMSAI_NAME, 
                        M_KBN.KBNMSAI_BIKO,
                        M_KBN.YOBIKOMOKU1, 
                        M_KBN.YOBIKOMOKU2,
                        M_KBN.YOBIKOMOKU3, 
                        M_KBN.YOBIKOMOKU4,
                        M_KBN.YOBIKOMOKU5              
                        FROM T_KOJI 
                        LEFT JOIN M_KBN ON T_KOJI.TAG_KBN = M_KBN.KBNMSAI_CD AND M_KBN.KBN_CD="05"                       
                        WHERE T_KOJI.JYUCYU_ID="' . $JYUCYU_ID . '"
                        AND M_KBN.KBN_CD="05"                         
                        AND T_KOJI.DEL_FLG=0';
                        $this->result = $this->dbConnect->query($sql);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['HOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['HOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['ADD_TANTNM'] = $row['ADD_TANTNM'];
                                $data['ADD_YMD'] = $row['ADD_YMD'];
                                $data['UPD_TANTNM'] = $row['UPD_TANTNM'];
                                $data['UPD_YMD'] = $row['UPD_YMD'];
                                $data['KBN_CD'] = $row['KBN_CD'];
                                $data['KBN_NAME'] = $row['KBN_NAME'];
                                $data['KBN_BIKO'] = $row['KBN_BIKO'];
                                $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                                $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                                $data['KBNMSAI_BIKO'] = $row['KBNMSAI_BIKO'];
                                $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                                $data['YOBIKOMOKU2'] = $row['YOBIKOMOKU2'];
                                $data['YOBIKOMOKU3'] = $row['YOBIKOMOKU3'];
                                $data['YOBIKOMOKU4'] = $row['YOBIKOMOKU4'];
                                $data['YOBIKOMOKU5'] = $row['YOBIKOMOKU5'];
                                $data['FILEPATH'] = $arr_list_file;
                                $resultSet[] = $data;
                            }
                        }
                        break;
                    default:
                        break;
                }
            } else {
                $errors['msg'][] = 'Missing parameter JYUCYU_ID or HOMON_SBT ';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = array();
            $domain = $this->domain;

            if ((isset($_GET['JYUCYU_ID']) && $_GET['JYUCYU_ID'] != "") &&
                (isset($_GET['HOMON_SBT']) && $_GET['HOMON_SBT'] != "")
            ) {
                $JYUCYU_ID = $_GET['JYUCYU_ID'];
                $HOMON_SBT = $_GET['HOMON_SBT'];

                switch ($HOMON_SBT) {
                    case '01':
                        $sql = 'SELECT JYUCYU_ID,
                        SITAMI_YMD,
                        KOJI_YMD,
                        HOMON_TANT_CD1,
                        HOMON_TANT_CD2,
                        HOMON_TANT_CD3,
                        HOMON_TANT_CD4,
                        SETSAKI_NAME,
                        SETSAKI_ADDRESS,
                        KOJI_JININ,
                        SITAMI_JININ,
                        HOMON_SBT,
                        KOJI_ST,
                        KOJI_ITEM,
                        SITAMI_KANSAN_POINT,
                        KOJI_KANSAN_POINT,
                        SITAMI_JIKAN,
                        KOJI_JIKAN,
                        KOJI_KEKKA,
                        TENPO_CD,
                        HOJIN_FLG,
                        MALL_CD,
                        KOJIGYOSYA_CD,
                        TAG_KBN,
                        SITAMIHOMONJIKAN,
                        SITAMIHOMONJIKAN_END,
                        KOJIHOMONJIKAN,
                        KOJIHOMONJIKAN_END,
                        KOJIIRAISYO_FILEPATH,
                        SITAMIIRAISYO_FILEPATH,
                        CANCEL_RIYU,
                        SITAMIAPO_KBN,
                        KOJIAPO_KBN,
                        MTMORI_YMD,
                        MEMO,
                        COMMENT,
                        READ_FLG,
                        ATOBARAI,
                        BIKO,
                        SYUYAKU_JYUCYU_ID,
                        REPORT_FLG,
                        SITAMI_REPORT,
                        ALL_DAY_FLG,
                        CO_NAME,
                        CO_POSTNO,
                        CO_ADDRESS,
                        KOJI_ITAKUHI,
                        SKJ_RENKEI_YMD,
                        KOJI_RENKEI_YMD,
                        UPD_TANTNM,
                        UPD_TANTCD,
                        UPD_YMD FROM T_KOJI 
                        WHERE JYUCYU_ID="' . $JYUCYU_ID . '" 
                        AND DEL_FLG= 0';
                        $this->result = $this->dbConnect->query($sql);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['KOJI_JININ'] = $row['KOJI_JININ'];
                                $data['JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['KOJI_JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['HOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['HOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['KOJIHOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['KOJIHOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['UPD_TANTNM'] = $row['UPD_TANTNM'];
                                $data['UPD_TANTCD'] = $row['UPD_TANTCD'];
                                $data['UPD_YMD'] = $row['UPD_YMD'];
                                $resultSet['DATA'][] = $data;
                            }
                        }
                        break;
                    case '02':
                        $sql = ' SELECT JYUCYU_ID,
                        SITAMI_YMD,
                        KOJI_YMD,
                        HOMON_TANT_CD1,
                        HOMON_TANT_CD2,
                        HOMON_TANT_CD3,
                        HOMON_TANT_CD4,
                        SETSAKI_NAME,
                        SETSAKI_ADDRESS,
                        KOJI_JININ,
                        SITAMI_JININ,
                        HOMON_SBT,
                        KOJI_ST,
                        KOJI_ITEM,
                        SITAMI_KANSAN_POINT,
                        KOJI_KANSAN_POINT,
                        SITAMI_JIKAN,
                        KOJI_JIKAN,
                        KOJI_KEKKA,
                        TENPO_CD,
                        HOJIN_FLG,
                        MALL_CD,
                        KOJIGYOSYA_CD,
                        TAG_KBN,
                        SITAMIHOMONJIKAN,
                        SITAMIHOMONJIKAN_END,
                        KOJIHOMONJIKAN,
                        KOJIHOMONJIKAN_END,
                        KOJIIRAISYO_FILEPATH,
                        SITAMIIRAISYO_FILEPATH,
                        CANCEL_RIYU,
                        SITAMIAPO_KBN,
                        KOJIAPO_KBN,
                        MTMORI_YMD,
                        MEMO,
                        COMMENT,
                        READ_FLG,
                        ATOBARAI,
                        BIKO,
                        SYUYAKU_JYUCYU_ID,
                        REPORT_FLG,
                        SITAMI_REPORT,
                        ALL_DAY_FLG,
                        CO_NAME,
                        CO_POSTNO,
                        CO_ADDRESS,
                        KOJI_ITAKUHI,
                        SKJ_RENKEI_YMD,
                        KOJI_RENKEI_YMD,
                        UPD_TANTNM,
                        UPD_TANTCD,
                        UPD_YMD  FROM T_KOJI 
                        WHERE JYUCYU_ID="' . $JYUCYU_ID . '" 
                        AND DEL_FLG= 0';
                        $this->result = $this->dbConnect->query($sql);
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['SITAMI_YMD'] = $row['SITAMI_YMD'];
                                $data['KOJI_YMD'] = $row['KOJI_YMD'];
                                $data['HOMON_TANT_CD1'] = $row['HOMON_TANT_CD1'];
                                $data['HOMON_TANT_CD2'] = $row['HOMON_TANT_CD2'];
                                $data['HOMON_TANT_CD3'] = $row['HOMON_TANT_CD3'];
                                $data['HOMON_TANT_CD4'] = $row['HOMON_TANT_CD4'];
                                $data['SETSAKI_NAME'] = $row['SETSAKI_NAME'];
                                $data['SETSAKI_ADDRESS'] = $row['SETSAKI_ADDRESS'];
                                $data['JININ'] = $row['KOJI_JININ'];
                                $data['SITAMI_JININ'] = $row['SITAMI_JININ'];
                                $data['HOMON_SBT'] = $row['HOMON_SBT'];
                                $data['KOJI_ST'] = $row['KOJI_ST'];
                                $data['KOJI_ITEM'] = $row['KOJI_ITEM'];
                                $data['SITAMI_KANSAN_POINT'] = $row['SITAMI_KANSAN_POINT'];
                                $data['KOJI_KANSAN_POINT'] = $row['KOJI_KANSAN_POINT'];
                                $data['SITAMI_JIKAN'] = $row['SITAMI_JIKAN'];
                                $data['JIKAN'] = $row['KOJI_JIKAN'];
                                $data['KOJI_KEKKA'] = $row['KOJI_KEKKA'];
                                $data['TENPO_CD'] = $row['TENPO_CD'];
                                $data['HOJIN_FLG'] = $row['HOJIN_FLG'];
                                $data['MALL_CD'] = $row['MALL_CD'];
                                $data['KOJIGYOSYA_CD'] = $row['KOJIGYOSYA_CD'];
                                $data['TAG_KBN'] = $row['TAG_KBN'];
                                $data['SITAMIHOMONJIKAN'] = $row['SITAMIHOMONJIKAN'];
                                $data['SITAMIHOMONJIKAN_END'] = $row['SITAMIHOMONJIKAN_END'];
                                $data['HOMONJIKAN'] = $row['KOJIHOMONJIKAN'];
                                $data['HOMONJIKAN_END'] = $row['KOJIHOMONJIKAN_END'];
                                $data['KOJIIRAISYO_FILEPATH'] = $domain . $row['KOJIIRAISYO_FILEPATH'];
                                $data['SITAMIIRAISYO_FILEPATH'] = $domain . $row['SITAMIIRAISYO_FILEPATH'];
                                $data['CANCEL_RIYU'] = $row['CANCEL_RIYU'];
                                $data['SITAMIAPO_KBN'] = $row['SITAMIAPO_KBN'];
                                $data['KOJIAPO_KBN'] = $row['KOJIAPO_KBN'];
                                $data['MTMORI_YMD'] = $row['MTMORI_YMD'];
                                $data['MEMO'] = $row['MEMO'];
                                $data['COMMENT'] = $row['COMMENT'];
                                $data['READ_FLG'] = $row['READ_FLG'];
                                $data['ATOBARAI'] = $row['ATOBARAI'];
                                $data['BIKO'] = $row['BIKO'];
                                $data['SYUYAKU_JYUCYU_ID'] = $row['SYUYAKU_JYUCYU_ID'];
                                $data['REPORT_FLG'] = $row['REPORT_FLG'];
                                $data['SITAMI_REPORT'] = $row['SITAMI_REPORT'];
                                $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                                $data['CO_NAME'] = $row['CO_NAME'];
                                $data['CO_POSTNO'] = $row['CO_POSTNO'];
                                $data['CO_ADDRESS'] = $row['CO_ADDRESS'];
                                $data['KOJI_ITAKUHI'] = $row['KOJI_ITAKUHI'];
                                $data['SKJ_RENKEI_YMD'] = $row['SKJ_RENKEI_YMD'];
                                $data['KOJI_RENKEI_YMD'] = $row['KOJI_RENKEI_YMD'];
                                $data['UPD_TANTNM'] = $row['UPD_TANTNM'];
                                $data['UPD_TANTCD'] = $row['UPD_TANTCD'];
                                $data['UPD_YMD'] = $row['UPD_YMD'];
                                $resultSet['DATA'][] = $data;
                            }
                        }
                        break;
                    default:
                        break;
                }

                $sql = 'SELECT KBN_CD, 
                KBN_NAME , 
                KBNMSAI_CD, 
                KBNMSAI_NAME 
                FROM M_KBN 
                WHERE KBN_CD= "05" AND DEL_FLG= 0';

                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['PULLDOWN'][] = $row;
                    }
                }
            } else {
                $errors['msg'][] = 'Missing parameter JYUCYU_ID or HOMON_SBT ';
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $validate = new Validate();

            $validated = $validate->validate($_POST, [
                'JYUCYU_ID' => 'required',
                'KBN' => 'required',
                'HOMONJIKAN' => 'required',
                'HOMONJIKAN_END' => 'required',
                'JININ' => 'required',
                'KANSAN_POINT' => 'required',
                'MEMO' => 'required',
                'HOMON_SBT' => 'required',
                'JIKAN' => 'required',
                'KBNMSAI_CD' => 'required',
                'LOGIN_ID' => 'required',
                'ALL_DAY_FLG' => 'nullable',
            ]);

            if ($validated) {
                $JYUCYU_ID = '"' . $validated['JYUCYU_ID'] . '"';
                $KBN = '"' . $validated['KBN'] . '"';
                $HOMONJIKAN = '"' . $validated['HOMONJIKAN'] . '"';
                $HOMONJIKAN_END = '"' . $validated['HOMONJIKAN_END'] . '"';
                $JININ = '"' . $validated['JININ'] . '"';
                $KANSAN_POINT = '"' . $validated['KANSAN_POINT'] . '"';
                $MEMO = '"' . $validated['MEMO'] . '"';
                $HOMON_SBT = '"' . $validated['HOMON_SBT'] . '"';
                $JIKAN = '' . $validated['JIKAN'] . '';
                $TAG_KBN = '"' . $validated['KBNMSAI_CD'] . '"';
                $LOGIN_ID = '"' . $validated['LOGIN_ID'] . '"';
                $ALL_DAY_FLG = !is_null($validated['ALL_DAY_FLG']) ? 1 : 0;
                $UPD_PGID = '"KOJ1110F"';
                $PRESENT_DATE = '"' . date('Y-m-d') . '"';
                $PRESENT_DATETIME = '"' . date('Y-m-d H:i:s') . '"';

                if ($validated['HOMON_SBT'] == "01") {
                    $sql = ' UPDATE T_KOJI
                    SET TAG_KBN=' . $TAG_KBN . ',
                    SITAMIAPO_KBN=' . $KBN . ',
                    SITAMIHOMONJIKAN=' . $HOMONJIKAN . ',
                    SITAMIHOMONJIKAN_END=' . $HOMONJIKAN_END . ',
                    SITAMI_JININ=' . $JININ . ',
                    SITAMI_KANSAN_POINT=' . $KANSAN_POINT . ',
                    ALL_DAY_FLG=' . $ALL_DAY_FLG . ',
                    SKJ_RENKEI_YMD=' . $PRESENT_DATE . ',
                    SITAMI_JIKAN=' . $JIKAN . ',                 
                    MEMO=' . $MEMO . ',
                    READ_FLG=(SELECT IF(EXISTS(SELECT KOJI_2.MEMO FROM (SELECT * FROM T_KOJI) AS KOJI_2 WHERE KOJI_2.JYUCYU_ID=' . $JYUCYU_ID . ' AND KOJI_2.MEMO=' . $MEMO . '),1,0) AS result),
                    UPD_PGID= ' . $UPD_PGID . ',
                    UPD_TANTCD=' . $LOGIN_ID . ',
                    UPD_YMD=' . $PRESENT_DATETIME . '
                    WHERE JYUCYU_ID=' . $JYUCYU_ID . '
                    AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }

                if ($validated['HOMON_SBT'] == "02") {
                    $sql = 'UPDATE T_KOJI 
                    SET TAG_KBN=' . $TAG_KBN . ',
                    KOJIAPO_KBN=' . $KBN . ',
                    KOJIHOMONJIKAN=' . $HOMONJIKAN . ',
                    KOJIHOMONJIKAN_END=' . $HOMONJIKAN_END . ',
                    KOJI_JININ=' . $JININ . ',
                    KOJI_JIKAN=' . $JIKAN . ' ,      
                    KOJI_KANSAN_POINT=' . $KANSAN_POINT . ',
                    ALL_DAY_FLG=' . $ALL_DAY_FLG . ',
                    SKJ_RENKEI_YMD=' . $PRESENT_DATE . ',
                    MEMO=' . $MEMO . ' ,
                    READ_FLG=(SELECT IF(EXISTS(SELECT KOJI_2.MEMO FROM (SELECT * FROM T_KOJI) AS KOJI_2 WHERE KOJI_2.JYUCYU_ID=' . $JYUCYU_ID . ' AND KOJI_2.MEMO=' . $MEMO . '),1,0) AS result),
                    UPD_PGID= ' . $UPD_PGID . ',
                    UPD_TANTCD=' . $LOGIN_ID . ',            
                    UPD_YMD=' . $PRESENT_DATETIME . '
                    WHERE JYUCYU_ID=' . $JYUCYU_ID . '
                    AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }
            }

            if (empty($errors['msg'])) {
                $resultSet = [];
                $validated['status'] = 'success';
                $resultSet[] = $validated;
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $resultSet = array();
            $errors = [];

            if (isset($_GET['TAN_EIG_ID']) && $_GET['TAN_EIG_ID'] != "") {
                $TAN_EIG_ID = $_GET['TAN_EIG_ID'];
                $sql = ' SELECT TAN_EIG_ID,
                JYOKEN_CD,  
                JYOKEN_SYBET_FLG, 
                YMD,
                START_TIME, 
                END_TIME, 
                TAG_KBN, 
                JININ,
                JIKAN, 
                GUEST_NAME,
                ATTEND_NAME1,
                ATTEND_NAME2,
                ATTEND_NAME3,
                ALL_DAY_FLG,
                RENKEI_YMD
                FROM T_EIGYO_ANKEN 
                WHERE TAN_EIG_ID= "' . $TAN_EIG_ID . '" 
                AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['PULLDOWN'][] = $row;
                    }
                }
            } else {
                $sql = ' SELECT KBN_CD,
                KBN_NAME,
                KBNMSAI_CD,
                KBNMSAI_NAME FROM M_KBN 
                WHERE KBN_CD= "10" 
                AND DEL_FLG= 0';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['PULLDOWN'][] = $row;
                    }
                }
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = [];
            $validate = new Validate();
            $validated = $validate->validate($_POST, [
                'JYOKEN_CD' => 'required',
                'YMD' => 'required',
                'KBNMSAI_CD' => 'required',
                'START_TIME' => 'required',
                'END_TIME' => 'required',
                'JININ' => 'required',
                'JIKAN' => 'required',
                'JYOKEN_SYBET_FLG' => 'required',
                'GUEST_NAME' => 'nullable',
                'ATTEND_NAME1' => 'nullable',
                'ATTEND_NAME2' => 'nullable',
                'ATTEND_NAME3' => 'nullable',
                'ALL_DAY_FLG' => 'nullable',
                'TAN_EIG_ID' => 'nullable',
                'LOGIN_ID' => 'required'
            ]);

            if ($validated) {
                $TAN_EIG_ID_TEMP = "";
                $TAN_EIG_ID = "";
                $LOGIN_ID = '"' . $validated['LOGIN_ID'] . '"';
                $JYOKEN_CD = '"' . $validated['JYOKEN_CD'] . '"';
                $YMD = '"' . $validated['YMD'] . '"';
                $JYOKEN_SYBET_FLG = $validated['JYOKEN_SYBET_FLG'];
                $TAG_KBN = '"' . $validated['KBNMSAI_CD'] . '"';
                $START_TIME = '"' . $validated['START_TIME'] . '"';
                $END_TIME = '"' . $validated['END_TIME'] . '"';
                $JININ = '"' . $validated['JININ'] . '"';
                $JIKAN = '' . $validated['JIKAN'] . '';
                $GUEST_NAME = !is_null($validated['GUEST_NAME']) ? '"' . $validated['GUEST_NAME'] . '"' : 'NULL';
                $ATTEND_NAME1 = !is_null($validated['ATTEND_NAME1']) ? '"' . $validated['ATTEND_NAME1'] . '"' : 'NULL';
                $ATTEND_NAME2 = !is_null($validated['ATTEND_NAME2']) ? '"' . $validated['ATTEND_NAME2'] . '"' : 'NULL';
                $ATTEND_NAME3 = !is_null($validated['ATTEND_NAME3']) ? '"' . $validated['ATTEND_NAME3'] . '"' : 'NULL';
                $ALL_DAY_FLG = !is_null($validated['ALL_DAY_FLG']) ? 1 : 0;
                $ADD_PGID = '"KOJ1110F"';
                $UPD_PGID = '"KOJ1110F"';
                $PRESENT_DATE = '"' . date('Y-m-d') . '"';
                $PRESENT_DATETIME = '"' . date('Y-m-d H:i:s') . '"';

                if (isset($validated['TAN_EIG_ID']) && !is_null($validated['TAN_EIG_ID'])) {
                    $TAN_EIG_ID_TEMP = $validated['TAN_EIG_ID'];
                    $TAN_EIG_ID = '"' . $validated['TAN_EIG_ID'] . '"';
                    $sql = ' UPDATE T_EIGYO_ANKEN SET                     
                        TAG_KBN=' . $TAG_KBN . ',
                        START_TIME=' .  $START_TIME . ',
                        END_TIME=' .  $END_TIME . ',
                        JININ=' .  $JININ . ',
                        JIKAN=' .  $JIKAN . ',
                        GUEST_NAME=' .  $GUEST_NAME . ',
                        ATTEND_NAME1=' .  $ATTEND_NAME1 . ',
                        ATTEND_NAME2=' .  $ATTEND_NAME2 . ',
                        ATTEND_NAME3=' .  $ATTEND_NAME3 . ',
                        ALL_DAY_FLG=' .  $ALL_DAY_FLG . ',
                        RENKEI_YMD=' . $PRESENT_DATE . ',
                        UPD_PGID=' . $UPD_PGID . ',
                        UPD_TANTCD=' . $LOGIN_ID . ',
                        UPD_YMD=' . $PRESENT_DATETIME . ' 
                        WHERE TAN_EIG_ID=' . $TAN_EIG_ID . '                
                        AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
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

                    $TAN_EIG_ID_TEMP = sprintf('%010d', $num);
                    $TAN_EIG_ID = '"' . sprintf('%010d', $num) . '"';
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
                        ' . $TAN_EIG_ID . ',
                        ' . $JYOKEN_CD . ',
                        ' . $JYOKEN_SYBET_FLG . ',
                        ' . $YMD . ',
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
                        ' . $ADD_PGID . ',
                        ' . $LOGIN_ID . ',
                        ' . $PRESENT_DATETIME . ',
                        ' . $UPD_PGID . ',
                        ' . $LOGIN_ID . ',
                        ' . $PRESENT_DATETIME . ' )';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }
            }

            if (empty($errors['msg'])) {
                $validated['TAN_EIG_ID'] = $TAN_EIG_ID_TEMP;
                $validated['status'] = 'success';
                $resultSet[] = $validated;
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        }
    }

    function postSalesConstructionSalesPreviewDelete()
    {
        $this->dbReference = new systemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == NULL) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $errors = [];
            $PRESENT_DATE = date('Y-m-d');
            $validate = new Validate();
            $validated = $validate->validate($_POST, [
                'TAN_EIG_ID' => 'required',
            ]);
            if ($validated) {
                $sql = 'UPDATE T_EIGYO_ANKEN 
                        SET DEL_FLG=1, 
                        RENKEI_YMD="' . $PRESENT_DATE . '" 
                        WHERE TAN_EIG_ID="' . $validated['TAN_EIG_ID'] . '"';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
            }

            if (empty($errors['msg'])) {
                $resultSet = [];
                $validated['status'] = 'success';
                $resultSet[] = $validated;

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(200, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $resultSet = array();

            if (isset($_GET['TAN_CAL_ID']) && $_GET['TAN_CAL_ID'] != "") {
                $TAN_CAL_ID = $_GET['TAN_CAL_ID'];

                $sql = 'SELECT TAN_CAL_ID,
                JYOKEN_CD, 
                JYOKEN_SYBET_FLG, 
                YMD, 
                TAG_KBN, 
                START_TIME, 
                END_TIME,
                MEMO_CD,
                NAIYO, 
                COMMENT, 
                ALL_DAY_FLG, 
                RENKEI_YMD 
                FROM T_TBETUCALENDAR 
                WHERE TAN_CAL_ID="' . $TAN_CAL_ID . '" 
                AND DEL_FLG=0';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    while ($row = $this->result->fetch_assoc()) {
                        $resultSet['dataTBETUCALENDAR'][] = $row;
                    }
                }

                $sqlPulldown = 'SELECT KBN_CD, KBN_NAME, 
                KBNMSAI_CD, KBNMSAI_NAME 
                FROM M_KBN 
                WHERE KBN_CD="06" 
                AND DEL_FLG=0';
                $getPullDown = $this->dbConnect->query($sqlPulldown);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if ($getPullDown->num_rows > 0) {
                    while ($row = $getPullDown->fetch_assoc()) {
                        $resultSet['pullDown'][] = $row;
                    }
                }
            } else {
                $sqlPulldown = 'SELECT KBN_CD, KBN_NAME, 
                KBNMSAI_CD, KBNMSAI_NAME 
                FROM M_KBN 
                WHERE KBN_CD="06" 
                AND DEL_FLG=0';
                $getPullDown = $this->dbConnect->query($sqlPulldown);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }

                if ($getPullDown->num_rows > 0) {
                    while ($row = $getPullDown->fetch_assoc()) {
                        $resultSet['pullDown'][] = $row;
                    }
                }
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $validate = new Validate();
            $validated = $validate->validate($_POST, [
                'JYOKEN_CD' => 'required',
                'JYOKEN_SYBET_FLG' => 'required',
                'YMD' => 'required',
                'START_TIME' => 'required',
                'END_TIME' => 'required',
                'MEMO_CD' => 'required',
                'KBNMSAI_CD' => 'required',
                'LOGIN_ID' => 'required',
                'NAIYO' => 'nullable',
                'ALL_DAY_FLG' => 'nullable',
                'COMMENT' => 'nullable',
                'TAN_CAL_ID' => 'nullable',
            ]);

            if ($validated) {
                $TANT_CAL_ID_TEMP = "";
                $TANT_CAL_ID = "";
                $JYOKEN_CD = '"' . $validated['JYOKEN_CD'] . '"';
                $JYOKEN_SYBET_FLG = $validated['JYOKEN_SYBET_FLG'];
                $YMD = '"' . $validated['YMD'] . '"';
                $TAG_KBN = '"' . $validated['KBNMSAI_CD'] . '"';
                $START_TIME = '"' . $validated['START_TIME'] . '"';
                $END_TIME = '"' . $validated['END_TIME'] . '"';
                $MEMO_CD = '"' . $validated['MEMO_CD'] . '"';
                $LOGIN_ID = '"' . $validated['LOGIN_ID'] . '"';
                $NAIYO = !is_null($validated['NAIYO']) ? '"' . $validated['NAIYO'] . '"' : 'NULL';
                $COMMENT = !is_null($validated['COMMENT']) ? '"' . $validated['COMMENT'] . '"' : 'NULL';
                $ALL_DAY_FLG = !is_null($validated['ALL_DAY_FLG']) ? 1 : 0;
                $ADD_PGID = '"KOJ1110F"';
                $UPD_PGID = '"KOJ1110F"';
                $PRESENT_DATE = '"' . date('Y-m-d') . '"';
                $PRESENT_DATETIME = '"' . date('Y-m-d H:i:s') . '"';

                if (isset($validated['TANT_CAL_ID']) && !is_null($validated['TANT_CAL_ID'])) {
                    $TANT_CAL_ID_TEMP = $validated['TANT_CAL_ID'];
                    $TANT_CAL_ID = '"' . $validated['TANT_CAL_ID'] . '"';
                    $sql = 'UPDATE T_TBETUCALENDAR 
                    SET MEMO_CD=' . $MEMO_CD . ', 
                    TAG_KBN=' . $TAG_KBN . ', 
                    YMD=' . $YMD . ', 
                    START_TIME=' . $START_TIME . ', 
                    END_TIME=' . $END_TIME . ', 
                    NAIYO=' . $NAIYO . ',
                    COMMENT=' . $COMMENT . ', 
                    ALL_DAY_FLG=' . $ALL_DAY_FLG . ', 
                    RENKEI_YMD=' . $PRESENT_DATE . ', 
                    UPD_PGID=' . $UPD_PGID . ', 
                    UPD_TANTCD=' . $LOGIN_ID . ', 
                    UPD_YMD=' . $PRESENT_DATETIME . ' 
                    WHERE TAN_CAL_ID=' . $TANT_CAL_ID . '                           
                    AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
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

                    $TANT_CAL_ID_TEMP = sprintf('%010d', $num);
                    $TAN_CAL_ID = '"' . sprintf('%010d', $num) . '"';
                    $sql = 'INSERT INTO T_TBETUCALENDAR 
                    (
                        TAN_CAL_ID, JYOKEN_CD, 
                        JYOKEN_SYBET_FLG, YMD, 
                        TAG_KBN, START_TIME, END_TIME, 
                        MEMO_CD, NAIYO, COMMENT, 
                        ALL_DAY_FLG, RENKEI_YMD, 
                        DEL_FLG, ADD_PGID, ADD_TANTCD, ADD_YMD, 
                        UPD_PGID, UPD_TANTCD, UPD_YMD
                    )
                    VALUES 
                    (
                        ' . $TAN_CAL_ID . ', ' . $JYOKEN_CD . ', 
                        ' . $JYOKEN_SYBET_FLG . ', ' . $YMD . ',
                        ' . $TAG_KBN . ', ' . $START_TIME . ', ' . $END_TIME . ', 
                        ' . $MEMO_CD . ', ' . $NAIYO . ', ' . $COMMENT . ', 
                        ' . $ALL_DAY_FLG . ', ' . $PRESENT_DATE . ', 
                        0, ' . $ADD_PGID . ', ' . $LOGIN_ID . ', ' . $PRESENT_DATETIME . ', ' . $UPD_PGID . ', ' . $LOGIN_ID . ', ' . $PRESENT_DATETIME . '
                    )';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }
            }

            if (empty($errors['msg'])) {
                $resultSet = [];
                $validated['status'] = 'success';
                $validated['TAN_CAL_ID'] = $TANT_CAL_ID_TEMP;
                $resultSet[] = $validated;

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
            $errors = [];
            $PRESENT_DATE = date('Y-m-d');
            $validate = new Validate();
            $validated = $validate->validate($_POST, [
                'TAN_CAL_ID' => 'required',
            ]);
            if ($validated) {
                $sql = 'UPDATE T_TBETUCALENDAR 
                        SET DEL_FLG=1, 
                        RENKEI_YMD="' . $PRESENT_DATE . '" 
                        WHERE TAN_CAL_ID="' . $validated['TAN_CAL_ID'] . '"';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
            }

            if (empty($errors['msg'])) {
                $resultSet = [];
                $validated['status'] = 'success';
                $resultSet[] = $validated;

                $this->dbReference->sendResponse(200, json_encode($resultSet, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(200, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
                if ($this->result && $this->result->num_rows > 0) {
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
}
