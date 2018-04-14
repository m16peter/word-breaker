<?php

/*
* API
*/
class Api extends Controller
{
    private $controller;

    function __construct()
    {
        $this->controller = new Controller();
    }

    public function index()
    {
        require VIEW . 'api.php';
    }

    public function login()
    {
        $_SESSION['error'] = array('user_email'=>'','user_password'=>'');
        $input = json_decode(file_get_contents('php://input'));

        try
        {
            $user_email = $input->data->email;

            if (empty($user_email))
            {
                echo json_encode(array('data' => 'user_email'), JSON_FORCE_OBJECT);
                return;
            }
            else
            {
                $_SESSION['user_email'] = $user_email;
            }

            $user_password = $input->data->password;

            if (empty($user_password))
            {
                echo json_encode(array('data' => 'user_password'), JSON_FORCE_OBJECT);
                return;
            }
            else
            {
                $user_password = hash('whirlpool', $user_password);
            }

            // find id if user exists
            $user_id = $this->controller->model->getUser($user_email, $user_password);

            if ($user_id == FALSE)
            {
                if ($this->controller->model->userExists($user_email))
                {
                    echo json_encode(array('data' => 'user_password'), JSON_FORCE_OBJECT);
                    return;
                }
                else
                {
                    // create user if not exists
                    $this->controller->model->addUser($user_email, $user_password);
                    $user_id = $this->controller->model->getUser($user_email, $user_password);

                    if ($user_id == FALSE)
                    {
                        echo json_encode(array('data' => 'undefined'), JSON_FORCE_OBJECT);
                    }
                }
            }

            $_SESSION['error'] = array('user_email' => '', 'user_password' => '');
            $_SESSION['user_id'] = $user_id;

            echo json_encode(array('data' => 'ok'), JSON_FORCE_OBJECT);
        }
        catch (Exception $e)
        {
            echo json_encode(array('data' => 'undefined'), JSON_FORCE_OBJECT);
        }
    }

    public function logout()
    {
        session_destroy();
        echo json_encode(array('data' => true), JSON_FORCE_OBJECT);
    }

    public function getDocuments()
    {
        $documents = $this->controller->model->getAllUsersJsons($_SESSION['user_id']);
        $list = array();

        foreach($documents as $document)
        {
            $source_language = $this->controller->model->getLanguage($document->language_id_source);
            $target_language = $this->controller->model->getLanguage($document->language_id_target);

            array_push($list, new Json($document->json_id, $document->json_name, $source_language, $target_language));
        }

        echo json_encode(new JsonArrayResponse($list));
    }

    public function newDocument()
    {
        // TODO: make this default in globals:
        $language_id_source = 1;
        $language_id_target = 2;
        $user_id = $_SESSION['user_id'];
        $json_name = 'New Document';
        $json_string = '{"data":[]}';

        $this->controller->model->addJson($language_id_source, $language_id_target, $user_id, $json_name, $json_string);

        $documents = $this->controller->model->getAllUsersJsons($user_id);
        $json_id = $documents[count($documents) - 1]->json_id;

        $source_language = $this->controller->model->getLanguage($language_id_source);
        $target_language = $this->controller->model->getLanguage($language_id_target);

        echo json_encode(array('data' => new Json($json_id, $json_name, $source_language, $target_language)), JSON_FORCE_OBJECT);
    }

    public function deleteDocument()
    {
        $input = json_decode(file_get_contents('php://input'));
        $json_id = $input->data;
        $user_id = $_SESSION['user_id'];

        if ($this->controller->model->getJson($user_id, $json_id) != FALSE)
        {
            $this->controller->model->deleteJson($json_id);
            echo json_encode(array('data' => true), JSON_FORCE_OBJECT);
        }
        else
        {
            echo json_encode(array('data' => false), JSON_FORCE_OBJECT);
        }
    }
}
