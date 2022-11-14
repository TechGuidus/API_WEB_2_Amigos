<?php

class ApiView
{
    function response($data, $status = null)
    {
        header('Content-Type: application/json');
        header('HTTP/1.1' . isset($status) ? $status : 200 . ' ' . $this->_requestStatus($status));
        echo json_encode($data);
    }

    private function _requestStatus($code)
    {
        $status = array(
            200 => "OK",
            201 => "Created",
            202 => "Accepted",
            400 => "Bad Request",
            401 => "Unauthorized",
            404 => "Not Found",
            405 => "Method Not Allowed",
            409 => "Conflict", 
            500 => "Internal Server Error"
        );
        return (isset($status[$code])) ? $status[$code] : $status[500];
    }
}
