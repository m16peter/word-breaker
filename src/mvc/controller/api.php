<?php

require MVC . 'types.php';

/*
* API
*/
class Api extends Controller
{

    public function index()
    {
        require VIEW . 'api.php';
    }

    // TODO
    public function login()
    {
        $_SESSION['auth'] = true;

        // 1. validate:
        // - $_POST['email']
        // - $_POST['password']

        // 2. create new user if not exists

        // 3. login

        echo json_encode(array('data' => true), JSON_FORCE_OBJECT);
    }

    public function logout()
    {
        if (isset($_SESSION['auth']))
        {
            unset($_SESSION['auth']);
            echo json_encode(array('data' => true), JSON_FORCE_OBJECT);
        }
    }

    // TODO: connect to db
    public function getDocuments()
    {
        // TODO: get json (id, name) from db
        echo json_encode(new Documents(array(
            new Document(1, 'New Document 1'),
            new Document(2, 'New Document 2')
        )));
    }

    // TODO: connect to db
    public function newDocument()
    {
        $json;
        $document_name;
        $document_id;

        try
        {
            $json = json_decode(file_get_contents('php://input'));
            $document_name = $json->data;
        }
        catch (Exception $e)
        {
            $document_name = 'New Document';
        }

        // TODO: insert in db new json document
        // - source-lang
        // - target-lang
        // - doc-name

        // TODO: get id from db
        $document_id = rand(1, 100);

        // TODO: if error occurs
        if (false)
        {
            echo '{}';
        }
        else
        {
            echo json_encode(array('data' => new Document($document_id, $document_name . ' ' . $document_id)), JSON_FORCE_OBJECT);
        }
    }

    // TODO: connect to db
    public function deleteDocument()
    {
        try
        {
            $json = json_decode(file_get_contents('php://input'));
            $document_id = $json->data;

            // TODO: delete json (id) from db

            echo json_encode(array('data' => true), JSON_FORCE_OBJECT);
        }
        catch (Exception $e)
        {
            echo json_encode(array('data' => false), JSON_FORCE_OBJECT);
        }
    }

}
