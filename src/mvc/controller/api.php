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
        $input = json_decode(file_get_contents('php://input'));

        try
        {
            $user_email = $input->data->email;

            if (empty($user_email))
            {
                echo json_encode(array('data' => 'user_email'), JSON_FORCE_OBJECT);
                return;
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
                    $this->controller->model->addUser($user_email, $user_password);
                    $user_id = $this->controller->model->getUser($user_email, $user_password);

                    if ($user_id == FALSE)
                    {
                        echo json_encode(array('data' => 'undefined'), JSON_FORCE_OBJECT);
                    }
                }
            }

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
        $user_id = $_SESSION['user_id'];
        $list = $this->controller->model->getJsonList($user_id);
        $response = array();

        foreach($list as $item)
        {
            array_push($response, new JsonDocument($item->json_id, $item->json_name));
        }

        echo json_encode(new JsonArrayResponse($response));
    }

    public function newDocument()
    {
        $user_id = $_SESSION['user_id'];

        $documents = $this->controller->model->getJsonList($user_id);

        $index = '';
        $i = 0;
        $n = count($documents);

        while ($i < $n)
        {
            if (array_search(('New Document' . $index), array_column($documents, 'json_name')) === FALSE)
            {
                break;
            }
            $index = ' ' . ++$i;
        }

        $json_name = 'New Document' . $index;

        $this->controller->model->addJson($user_id, $json_name);

        $json_id = $this->controller->model->getJsonIdByName($user_id, $json_name);

        echo json_encode(array('data' => new JsonDocument($json_id, $json_name)), JSON_FORCE_OBJECT);
    }

    public function deleteDocument()
    {
        $input = json_decode(file_get_contents('php://input'));
        $json_id = $input->data;
        $user_id = $_SESSION['user_id'];

        if ($this->controller->model->jsonExists($user_id, $json_id))
        {
            $this->controller->model->deleteJson($json_id);
            echo json_encode(array('data' => true), JSON_FORCE_OBJECT);
        }
        else
        {
            echo json_encode(array('data' => false), JSON_FORCE_OBJECT);
        }
    }

    public function selectDocument()
    {
        $input = json_decode(file_get_contents('php://input'));
        $json_id = $input->data;
        $user_id = $_SESSION['user_id'];

        if ($this->controller->model->jsonExists($user_id, $json_id))
        {
            $_SESSION['json_id'] = $json_id;
            echo json_encode(array('data' => true), JSON_FORCE_OBJECT);
        }
        else
        {
            var_dump($json_id);
            var_dump($user_id);
            echo json_encode(array('data' => false), JSON_FORCE_OBJECT);
        }
    }

    public function getJson()
    {
        $user_id = $_SESSION['user_id'];
        $json_id = $_SESSION['json_id'];

        $json = $this->controller->model->getJsonById($user_id, $json_id);

        if ($json == FALSE)
        {
            echo json_encode(array('data' => false), JSON_FORCE_OBJECT);
        }
        else
        {
            echo json_encode(
                array('data' => New Json(
                    $json_id,
                    $json->json_name,
                    $json->json_string,
                    $this->controller->model->getLanguageKeyById($json->language_id_source),
                    $this->controller->model->getLanguageKeyById($json->language_id_target)
                )
            ), JSON_FORCE_OBJECT);
        }
    }

    /*
     public function translate()
    {
        $input = file_get_contents('php://input');
        $input = json_decode($input);

        if ($input != null && is_string($input->data) && $input->data != '')
        {
            // search local db
            $txt = rawurlencode($input->data);
            $db_data = DB::table('dictionary')->where('source', $txt)->value('target');

            if ($db_data == null)
            {
                // curl -> google translate api
                $url = 'https://translation.googleapis.com/language/translate/v2';
                $source = 'sk';
                $target = 'ro';
                $key = 'AIzaSyDEecuqDeU1w2YmY7URVP9vpgypEFBjKQw';

                $handle = curl_init($url.'?q='.$txt.'&key='.$key.'&source='.$source.'&target='.$target);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                $g_data = curl_exec($handle);
                $responseDecoded = json_decode($g_data, true);
                $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                curl_close($handle);

                if ($responseCode == 200)
                {
                    $response->data = $responseDecoded['data']['translations'][0]['translatedText'];

                    DB::table('dictionary')->insert([
                        'source'     => $txt,
                        'target'     => $response->data,
                        'created_at' => date("Y-m-d H:i:s")
                    ]);
                }
            }
            else
            {
                $response->data = rawurldecode($db_data);
            }
        }

        return json_encode($response);
    }
    */

    /*
    public function update()
    {
        $input = file_get_contents('php://input');
        $input = json_decode($input);

        if ($input != null && is_string($input->data->source) && is_string($input->data->target))
        {
            // init
            $source = rawurlencode($input->data->source);
            $target = rawurlencode($input->data->target);

            // search local db
            $db_data = DB::table('dictionary')->where('source', $source)->value('target');

            if ($db_data == null)
            {
                DB::table('dictionary')->insert([
                    'source'     => $source,
                    'target'     => $target,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            }
            else
            {
                DB::table('dictionary')->where('source', $source)->update([
                    'target'     => $target,
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            }

            $response->data = 'success';
        }

        return json_encode($response);
    }
    */
}
