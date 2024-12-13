<?php

namespace App\Models;

class ApiResponse
{
    private $data;
    private $message;
    private $code;
    private $type;

    public function __construct($data = [], $message = '', $code = 200, $type = 'success')
    {
        $this->data = $data;
        $this->message = $message;
        $this->code = $code;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setErrorMessage($message, $code = 500, $type = 'error')
    {
        $this->message = $message;
        $this->code = $code;
        $this->type = $type;
    }

    public function setSuccessMessage($message, $code = 200, $type = 'success')
    {
        $this->message = $message;
        $this->code = $code;
        $this->type = $type;
    }

    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'type' => $this->type,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
