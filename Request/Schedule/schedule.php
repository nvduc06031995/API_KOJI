<?php
include('../../System/systemConfig.php');
include('../../System/systemEditor.php');
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                T_TBETUCALENDAR.MEMO_CD, 
                M_KBN.KBNMSAI_CD, 
                M_KBN.KBNMSAI_NAME, 
                M_KBN.YOBIKOMOKU1          
                 FROM T_TBETUCALENDAR 
                 CROSS JOIN M_KBN ON T_TBETUCALENDAR.TAG_KBN=M_KBN.KBN_CD AND T_TBETUCALENDAR.MEMO_CD=M_KBN.KBNMSAI_CD
                 WHERE T_TBETUCALENDAR.YMD >= "' . $start_date . '"  
                     AND T_TBETUCALENDAR.YMD <= "' . $end_date . '" 
                     AND T_TBETUCALENDAR.DEL_FLG=0 
                     AND JYOKEN_CD="' . $KOJIGYOSYA_CD . '"
                     AND M_KBN.KBN_CD="06"
                     AND T_TBETUCALENDAR.JYOKEN_SYBET_FLG=1';
                $this->result = $this->dbConnect->query($sql);
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
                    // output data of each row
                    while ($row = $this->result->fetch_assoc()) {
                        $TBETUCALENDAR_YMD = $row['YMD'];
                        $data = array();
                        $data['START_TIME'] = $row['START_TIME'];
                        $data['END_TIME'] = $row['END_TIME'];
                        $data['NAIYO'] = $row['NAIYO'];
                        $data['KBNMSAI_CD'] = $row['KBNMSAI_CD'];
                        $data['KBNMSAI_NAME'] = $row['KBNMSAI_NAME'];
                        $data['YOBIKOMOKU1'] = $row['YOBIKOMOKU1'];
                        $data['YMD'] = $row['YMD'];
                        $data['TAN_CAL_ID'] = $row['TAN_CAL_ID'];
                        $data['JYOKEN_CD'] = $row['JYOKEN_CD'];
                        $data['ALL_DAY_FLG'] = $row['ALL_DAY_FLG'];
                        $data['MEMO_CD'] = $row['MEMO_CD'];
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
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
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                    if ($this->result && $this->result->num_rows > 0) {
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

            if (isset($_GET['YMD']) && isset($_GET['ID'])) {
                $YMD = $_GET['YMD'];
                $ID = $_GET['ID'];
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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
                if (!empty($this->dbConnect->error)) {
                    $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                }
                if ($this->result && $this->result->num_rows > 0) {
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

            $sql = ' SELECT KOJIGYOSYA_CD,KOJIGYOSYA_NAME FROM M_GYOSYA';
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
                        $sql = 'SELECT JYUCYU_ID, 
                        T_KOJI.SITAMI_YMD, 
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
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['YMD'] = $row['SITAMI_YMD'];
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
                        T_KOJI.KOJI_YMD,
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
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
                            // output data of each row
                            while ($row = $this->result->fetch_assoc()) {
                                $data = array();
                                $data['JYUCYU_ID'] = $row['JYUCYU_ID'];
                                $data['YMD'] = $row['KOJI_YMD'];
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
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
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
                        if (!empty($this->dbConnect->error)) {
                            $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                        }
                        if ($this->result && $this->result->num_rows > 0) {
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

            $validated = $validate->validate($_POST , [
                'JYUCYU_ID' => 'required',
                'KBN' => 'required',
                'HOMONJIKAN' => 'required',
                'HOMONJIKAN_END' => 'required',
                'JININ' => 'required',
                'KANSAN_POINT' => 'required',
                'MEMO' => 'required',
                'ALL_DAY_FLG' => 'nullable',
                'HOMON_SBT' => 'required',
                'JIKAN' => 'required',
                'KBNMSAI_CD' => 'required',
                'TAG_KBN' => 'required',
                'LOGIN_ID' => 'required'
            ]);

            if ($validated) {                                                                                                                            
                $SKJ_RENKEI_YMD = date("Y-m-d");
                $UPD_PGID = 'KOJ1110F';
                $UPD_YMD = date("Y-m-d H:i:s");

                if ($validated['HOMON_SBT'] == "01") {
                    $sql = ' UPDATE T_KOJI
                    SET TAG_KBN="' . $validated['TAG_KBN'] . '",
                    SITAMIAPO_KBN="' . $validated['KBN'] . '",
                    SITAMIHOMONJIKAN="' . $validated['HOMONJIKAN'] . '",
                    SITAMIHOMONJIKAN_END="' . $validated['HOMONJIKAN_END'] . '",
                    SITAMI_JININ=' . $validated['JININ'] . ',
                    SITAMI_KANSAN_POINT=' . $validated['KANSAN_POINT'] . ',
                    ALL_DAY_FLG=' . $validated['ALL_DAY_FLG'] . ',
                    SKJ_RENKEI_YMD="' . $SKJ_RENKEI_YMD . '",
                    UPD_PGID= "' . $UPD_PGID . '",
                    UPD_TANTCD="' . $validated['LOGIN_ID'] . '",
                    UPD_YMD="' . $UPD_YMD . '",
                    MEMO="' . $validated['MEMO'] . '",
                    SITAMI_JIKAN="' . $validated['JIKAN'] . '"                 
                    WHERE JYUCYU_ID="' . $validated['JYUCYU_ID'] . '"
                    AND DEL_FLG=0';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }

                if ($validated['HOMON_SBT'] == "02") {
                    $sql = 'UPDATE T_KOJI 
                    SET TAG_KBN="' . $validated['TAG_KBN'] . '",
                    KOJIAPO_KBN="' . $validated['KBN'] . '",
                    KOJIHOMONJIKAN="' . $validated['HOMONJIKAN'] . '",
                    KOJIHOMONJIKAN_END="' . $validated['HOMONJIKAN_END'] . '",
                    KOJI_JININ=' . $validated['JININ'] . ',
                    KOJI_KANSAN_POINT=' . $validated['KANSAN_POINT'] . ',
                    ALL_DAY_FLG=' . $validated['ALL_DAY_FLG'] . ',
                    SKJ_RENKEI_YMD="' . $SKJ_RENKEI_YMD . '",
                    UPD_PGID= "' . $UPD_PGID . '",
                    UPD_TANTCD="' . $validated['LOGIN_ID'] . '",            
                    UPD_YMD="' . $UPD_YMD . '",
                    MEMO="' . $validated['MEMO'] . '" ,
                    KOJI_JIKAN="' . $validated['JIKAN'] . '"       
                    WHERE JYUCYU_ID="' . $validated['JYUCYU_ID'] . '"
                    AND DEL_FLG=0';
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
            $validate = new Validate();
            $validated = $validate->validate($_POST, [
                'JYOKEN_CD' => 'required',
                'YMD' => 'required',
                'TAG_KBN' => 'required',
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
                $RENKEI_YMD = date('Y-m-d');
                $ADD_PGID = "KOJ1110F";
                $ADD_YMD = date('Y-m-d H:i:s');
                $UPD_PGID = "KOJ1110F";
                $UPD_YMD  = date('Y-m-d H:i:s');

                if (isset($validated['TAN_EIG_ID']) && !is_null($validated['TAN_EIG_ID'])) {
                    $sql = ' UPDATE T_EIGYO_ANKEN SET                     
                        TAG_KBN="' . $validated['TAG_KBN'] . '",
                        START_TIME="' .  $validated['START_TIME'] . '",
                        END_TIME="' .  $validated['END_TIME'] . '",
                        JININ="' .  $validated['JININ'] . '",
                        JIKAN="' .  $validated['JIKAN'] . '",
                        GUEST_NAME="' .  $validated['GUEST_NAME'] . '",
                        ATTEND_NAME1="' .  $validated['ATTEND_NAME1'] . '",
                        ATTEND_NAME2="' .  $validated['ATTEND_NAME2'] . '",
                        ATTEND_NAME3="' .  $validated['ATTEND_NAME3'] . '",
                        ALL_DAY_FLG=' .  $validated['ALL_DAY_FLG'] . ',
                        RENKEI_YMD="' . $RENKEI_YMD . '",
                        UPD_PGID="' . $UPD_PGID . '",
                        UPD_TANTCD="' . $validated['LOGIN_ID'] . '",
                        UPD_YMD="' . $UPD_YMD . '" 
                        WHERE TAN_EIG_ID="' . $validated['TAN_EIG_ID'] . '"                
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
                        "' . $validated['JYOKEN_CD'] . '",
                        ' . $validated['JYOKEN_SYBET_FLG'] . ',
                        "' . $validated['YMD'] . '",
                        "' . $validated['TAG_KBN'] . '",
                        "' . $validated['START_TIME'] . '",
                        "' . $validated['END_TIME'] . '",
                        "' . $validated['JININ'] . '",
                        "' . $validated['JIKAN'] . '",
                        "' . $validated['GUEST_NAME'] . '",
                        "' . $validated['ATTEND_NAME1'] . '",
                        "' . $validated['ATTEND_NAME2'] . '",
                        "' . $validated['ATTEND_NAME3'] . '",
                        ' . $validated['ALL_DAY_FLG'] . ',  
                        0,                 
                        "' . $ADD_PGID . '",
                        "' . $validated['LOGIN_ID'] . '",
                        "' . $ADD_YMD . '",
                        "' . $UPD_PGID . '",
                        "' . $validated['LOGIN_ID'] . '",
                        "' . $UPD_YMD . '" )';
                    $this->result = $this->dbConnect->query($sql);
                    if (!empty($this->dbConnect->error)) {
                        $errors['msg'][] = 'sql errors : ' . $this->dbConnect->error;
                    }
                }
            }

            if (empty($errors['msg'])) {
                $this->dbReference->sendResponse(200, json_encode('sucess', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            } else {
                $this->dbReference->sendResponse(400, json_encode($errors, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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
                if ($this->result && $this->result->num_rows > 0) {
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
