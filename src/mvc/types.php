<?php

class Document
{
    function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}

class Documents
{
    public $data = array();

    function __construct($documents)
    {
        foreach($documents as $document)
        {
            array_push($this->data, $document);
        }
    }
}
