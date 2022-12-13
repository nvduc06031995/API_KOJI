<?php

class systemConfig
{
    var $dbConnect;

    function __construct()
    {
    }

    function __destruct()
    {
        $this->dbConnect->close();
    }

    function connectDB()
    {
        // $this->dbConnect = new mysqli('localhost', 'mviawjlo_lifeone_kojibuapp', 'Eu*u1z,O&(+h', 'mviawjlo_lifeone_kojibuapp');
        // $this->dbConnect = new mysqli('45.252.249.30', 'mviawjlo_lifeone_kojibuapp', 'Eu*u1z,O&(+h', 'mviawjlo_lifeone_kojibuapp');
        $this->dbConnect = new mysqli('153.127.238.30', 'koujiapp', '7Us%Rich.8H#', 'lifeone_kojibuapp');
        // $this->dbConnect = new mysqli('localhost', 'root', '', 'mviawjlo_lifeone_kojibuapp');
        $this->dbConnect->set_charset("utf8");

        if ($this->dbConnect->connect_errno) {
            return null;
        } else {
            return $this->dbConnect;
        }
    }

    function getStatusCodeMeeage($status)
    {
        $codes = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'データが取得できませんでした。アプリを再起動してください。',
            507 => 'データ登録ができませんでした。',
            508 => 'データが取得できませんでした。アプリを再起動してください。'
        );

        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    function sendResponse($status = 200, $body = '', $content_type = 'application/json')
    {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMeeage($status);
        header($status_header);
        header('Content-type: ' . $content_type);
        echo $body;
    }
}
