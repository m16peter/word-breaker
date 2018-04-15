<?php

class Json
{
    function __construct($json_id, $json_name, $json_string, $source_language, $target_language)
    {
        $this->json_id = $json_id;
        $this->json_name = $json_name;
        $this->json_string = $json_string;
        $this->source_language = $source_language;
        $this->target_language = $target_language;
    }
}

class JsonDocument
{
    function __construct($json_id, $json_name)
    {
        $this->json_id = $json_id;
        $this->json_name = $json_name;
    }
}

class JsonArrayResponse
{
    public $data = array();

    function __construct($list)
    {
        foreach($list as $item)
        {
            array_push($this->data, $item);
        }
    }
}
