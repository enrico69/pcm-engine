<?php

class controllers_Error {

    private $thePage = "";

    public function __construct() {
        $this->thePage = new views_Page();
    }

    public function start($Message, $Error = "") {
        $myMeta = array('Title' => "Error...", 'Index' => 0);
        if ($Error != ""){
            switch($Error){
                case '403' : header("HTTP/1.1 403 Unauthorized"); break;
                case '404' : header("HTTP/1.1 404 Not Found"); break;
                case '406' : header("HTTP/1.1 406 Not Acceptable"); break;
                default : break;
            // For a complete liste, see below...
            }
        }
        //Note : by default your message is displayed in the global view (layout). But you can specify a template...
        $this->thePage->display($Message, $myMeta);
        exit;
    }

}
/* Header Complete List :  (note that you can use the method 'redirect' + header, for redirection. See engine_Controllers_Abastract class
100 => 'Continue',
 101 => 'Switching Protocols',
 102 => 'Processing',
 200 => 'OK',
 201 => 'Created',
 202 => 'Accepted',
 203 => 'Non-Authoritative Information',
 204 => 'No Content',
 205 => 'Reset Content',
 206 => 'Partial Content',
 207 => 'Multi-Status',
 300 => 'Multiple Choices',
 301 => 'Moved Permanently',
 302 => 'Found',
 303 => 'See Other',
 304 => 'Not Modified',
 305 => 'Use Proxy',
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
 422 => 'Unprocessable Entity',
 423 => 'Locked',
 424 => 'Failed Dependency',
 426 => 'Upgrade Required',
 500 => 'Internal Server Error',
 501 => 'Not Implemented',
 502 => 'Bad Gateway',
 503 => 'Service Unavailable',
 504 => 'Gateway Timeout',
 505 => 'HTTP Version Not Supported',
 506 => 'Variant Also Negotiates',
 507 => 'Insufficient Storage',
 509 => 'Bandwidth Limit Exceeded',
 510 => 'Not Extended'
 */
?>
