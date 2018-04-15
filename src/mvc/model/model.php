<?php

class Model
{
    function __construct($db)
    {
        try {
            $this->db = $db;
        }
        catch (PDOException $e) {
            exit('');
        }
    }

    public function getUser($user_email, $user_password)
    {
        $sql = "SELECT `user_id` FROM `user` WHERE `user_email`=:user_email AND `user_password`=:user_password";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_email' => $user_email, ':user_password' => $user_password);
        $query->execute($parameters);
        $result = $query->fetch();

        return ($result == FALSE) ? (FALSE) : ($result->user_id);
    }

    public function addUser($user_email, $user_password)
    {
        $sql = "INSERT INTO `user` (`user_email`, `user_password`) VALUES (:user_email, :user_password)";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_email' => $user_email, ':user_password' => $user_password);
        $query->execute($parameters);
    }

    public function userExists($user_email)
    {
        $sql = "SELECT `user_id` FROM `user` WHERE `user_email`=:user_email";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_email' => $user_email);
        $query->execute($parameters);
        $result = $query->fetch();

        return !($result == FALSE);
    }

    public function jsonExists($user_id, $json_id)
    {
        $sql = "SELECT `json_id` FROM `json` WHERE `user_id`=:user_id AND `json_id`=:json_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id, ':json_id' => $json_id);
        $query->execute($parameters);
        $result = $query->fetch();

        return !($result == FALSE);
    }

    public function getJsonById($user_id, $json_id)
    {
        $sql = "SELECT * FROM `json` WHERE `user_id`=:user_id AND `json_id`=:json_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id, ':json_id' => $json_id);
        $query->execute($parameters);
        $result = $query->fetch();

        return ($result);
    }

    public function getJsonIdByName($user_id, $json_name)
    {
        $sql = "SELECT `json_id` FROM `json` WHERE `user_id`=:user_id AND `json_name`=:json_name";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id, ':json_name' => $json_name);
        $query->execute($parameters);
        $result = $query->fetch();

        return ($result->json_id);
    }

    public function getJsonList($user_id)
    {
        $sql = "SELECT `json_id`, `json_name` FROM `json` WHERE `user_id`=:user_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id);
        $query->execute($parameters);

        return ($query->fetchAll());
    }

    public function getLanguageKeyById($language_id)
    {
        $sql = "SELECT `language_key` FROM `language` WHERE `language_id`=:language_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':language_id' => $language_id);
        $query->execute($parameters);
        $result = $query->fetch();

        return ($result->language_key);
    }

    public function getLanguageNameById($language_id)
    {
        $sql = "SELECT `language_name` FROM `language` WHERE `language_id`=:language_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':language_id' => $language_id);
        $query->execute($parameters);
        $result = $query->fetch();

        return ($result->language_key);
    }

    public function getLanguageNameByKey($language_key)
    {
        $sql = "SELECT `language_name` FROM `language` WHERE `language_key`=:language_key";
        $query = $this->db->prepare($sql);
        $parameters = array(':language_key' => $language_key);
        $query->execute($parameters);
        $result = $query->fetch();

        return ($result->language_key);
    }

    public function addJson($user_id, $json_name)
    {
        $sql = "INSERT INTO `json` (`user_id`, `json_name`) VALUES (:user_id, :json_name)";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id, ':json_name' => $json_name);
        $query->execute($parameters);
    }

    public function deleteJson($json_id)
    {
        $sql = "DELETE FROM `json` WHERE json_id=:json_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':json_id' => $json_id);
        $query->execute($parameters);
    }

    public function install()
    {
        if (isset($_SESSION['user_id']))
        {
            if ($_SESSION['user_id'] == 1)
            {
                $files = scandir(SRC . 'sql');
                $files = array_values(array_diff($files, array(".", "..")));

                foreach($files as $file)
                {
                    $sql = file_get_contents(SRC . 'sql/' . $file);
                    $query = $this->db->prepare($sql);
                    $query->execute();
                }
            }
        }
    }
}
