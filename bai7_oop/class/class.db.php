<?php
class DB {
    private $link;

    function __construct() {
        global $config;
        $host = $config['host'] ?? 'localhost';
        $user = $config['user'] ?? 'root';
        $pass = $config['pass'] ?? '';
        $data = $config['data'] ?? 'tintuc';

        $this->link = @mysqli_connect($host, $user, $pass, $data);
        if (!$this->link && $user === 'root') {
            $currentUser = get_current_user();
            $this->link = @mysqli_connect($host, $currentUser, '', $data);
        }

        if (!$this->link) {
            die("Can't connect: " . mysqli_connect_error());
        }

        mysqli_set_charset($this->link, "utf8mb4");
    }

    public function db_query($query) {
        $result = mysqli_query($this->link, $query);
        return $result;
    }

    public function db_fetch($result) {
        if ($result instanceof mysqli_result) {
            return mysqli_fetch_assoc($result);
        }
        return false;
    }

    public function escape_string($str) {
        return mysqli_real_escape_string($this->link, $str);
    }
}
?>

