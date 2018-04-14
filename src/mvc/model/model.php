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

    public function getJson($user_id, $json_id)
    {
        $sql = "SELECT `json_id` FROM `json` WHERE `user_id`=:user_id AND `json_id`=:json_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id, ':json_id' => $json_id);
        $query->execute($parameters);
        $result = $query->fetch();

        return ($result == FALSE) ? (FALSE) : ($result->json_id);
    }

    public function getAllUsersJsons($user_id)
    {
        $sql = "SELECT `json_id`, `language_id_source`, `language_id_target`, `json_name` FROM `json` WHERE `user_id`=:user_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':user_id' => $user_id);
        $query->execute($parameters);

        return ($query->fetchAll());
    }

    public function getLanguage($language_id)
    {
        $sql = "SELECT `language_name` FROM `language` WHERE `language_id`=:language_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':language_id' => $language_id);
        $query->execute($parameters);
        $result = $query->fetch();

        return ($result->language_name);
    }

    public function addJson($language_id_source, $language_id_target, $user_id, $json_name, $json_string)
    {
        $sql = "INSERT INTO `json` (`language_id_source`, `language_id_target`, `user_id`, `json_name`, `json_string`) VALUES (:language_id_source, :language_id_target, :user_id, :json_name, :json_string)";
        $query = $this->db->prepare($sql);
        $parameters = array(':language_id_source' => $language_id_source, ':language_id_target' => $language_id_target, ':user_id' => $user_id, ':json_name' => $json_name, ':json_string' => $json_string);
        $query->execute($parameters);
    }

    public function deleteJson($json_id)
    {
        $sql = "DELETE FROM `json` WHERE json_id=:json_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':json_id' => $json_id);
        $query->execute($parameters);
    }

    /**
     * Update a song in database
     * // TODO put this explaination into readme and remove it from here
     * Please note that it's not necessary to "clean" our input in any way. With PDO all input is escaped properly
     * automatically. We also don't use strip_tags() etc. here so we keep the input 100% original (so it's possible
     * to save HTML and JS to the database, which is a valid use case). Data will only be cleaned when putting it out
     * in the views (see the views for more info).
     * @param string $artist Artist
     * @param string $track Track
     * @param string $link Link
     * @param int $song_id Id
     */
    public function updateSong($artist, $track, $link, $song_id)
    {
        $sql = "UPDATE song SET artist = :artist, track = :track, link = :link WHERE id = :song_id";
        $query = $this->db->prepare($sql);
        $parameters = array(':artist' => $artist, ':track' => $track, ':link' => $link, ':song_id' => $song_id);

        // useful for debugging: you can see the SQL behind above construction by using:
        // echo '[ PDO DEBUG ]: ' . Helper::debugPDO($sql, $parameters);  exit();

        $query->execute($parameters);
    }

    public function createDB()
    {
        if (isset($_SESSION['user_id']))
        {
            if ($_SESSION['user_id'] == 1)
            {
                $files = scandir(SRC . 'sql/create');
                $files = array_values(array_diff($files, array(".", "..")));

                foreach($files as $file)
                {
                    $sql = file_get_contents(SRC . 'sql/create/' . $file);
                    $query = $this->db->prepare($sql);
                    $query->execute();
                }

            }
        }
    }

    public function seedDB()
    {
        if (isset($_SESSION['user_id']))
        {
            if ($_SESSION['user_id'] == 1)
            {
                $files = scandir(SRC . 'sql/seed');
                $files = array_values(array_diff($files, array(".", "..")));

                foreach($files as $file)
                {
                    $sql = file_get_contents(SRC . 'sql/seed/' . $file);
                    $query = $this->db->prepare($sql);
                    $query->execute();
                }
            }
        }
    }
}
