<?php
class Dao {
    private $con;

    function __construct($user, $pass, $db) {
        $host = $_SERVER['SERVER_NAME'] ?? 'localhost';
        $this->con = @mysqli_connect($host, $user, $pass, $db);
        if (!$this->con) {
            $this->con = mysqli_connect('localhost', get_current_user(), '', $db);
        }
    }

    public function query($sql) {
        mysqli_query($this->con, "set names 'utf8mb4'");
        $rs = mysqli_query($this->con, $sql);
        return $rs;
    }

    function table($sql, $header) {
        $rs = $this->query($sql);
        if (!$rs) return;
        $fieldinfo = mysqli_fetch_fields($rs);
        $str = "<table><tr>";
        foreach ($fieldinfo as $val) {
            $name = $val->name;
            $str .= "<th>" . $name . "</th>";
        }
        $str .= "</tr>";
        while ($r = mysqli_fetch_array($rs)) {
            $str .= "<tr>";
            foreach ($fieldinfo as $val) {
                $name = $val->name;
                $str .= "<td>" . $r[$name] . "</td>";
            }
            $str .= "</tr>";
        }
        $str .= "</table>";
        echo "<h3>{$header}</h3>";
        echo $str;
    }
}
?>
