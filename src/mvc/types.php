<?php

class Json
{
    function __construct($json_id, $json_name)
    {
        $this->json_id = $json_id;
        $this->json_name = $json_name;
    }
}

class JsonArrayResponse
{
    function __construct($list)
    {
        $this->data = array();

        foreach($list as $item)
        {
            array_push($this->data, $item);
        }
    }
}
