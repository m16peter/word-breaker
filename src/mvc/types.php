<?php

class Json
{
    function __construct($json_id, $json_name, $source, $target)
    {
        $this->json_id = $json_id;
        $this->json_name = $json_name;
        $this->source = $source;
        $this->target = $target;
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
