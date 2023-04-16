<?php

class sqlak
{
    var mysqli|false $db;

    private function where($where): string
    {
        if (!$where) {
            $where = "";
        } else {
            $where = " WHERE " . $where;
        }
        return $where;
    }

    private function sqlize($value): string
    {
        if (is_string($value)) {
            $value = "'$value'";
        } else {
            $value = "$value";
        }
        return $value;
    }

    function __construct($config, $db, $options = false)
    {
        $host = $config["host"];
        if ($host == "localhost" && !isset($config["user"]) && !isset($config["pass"])) {
            $user = "root";
            $pass = "";
        } else {
            $user = $config["user"];
            $pass = $config["pass"];
        }
        if (!$options) {
            if (isset($options["port"])) {
                $port = $options["port"];
            } else {
                $port = 3306;
            }
            if (isset($options["socket"])) {
                return $this->db = mysqli_connect($host, $user, $pass, $db, $port, $options["socket"]);
            } else {
                return $this->db = mysqli_connect($host, $user, $pass, $db, $port);
            }
        } else {
            return $this->db = mysqli_connect($host, $user, $pass, $db);
        }
    }

    function do($query): mysqli_result|bool
    {
        return mysqli_query($this->db, $query);
    }

    function set($table, $command, $where = false): bool|mysqli_result
    {
        $where = $this->where($where);
        $commands = "";
        for ($i = 0; $i < count($command["col"]); $i++) {
            $commands .= " " . $command["col"][$i] . " = " . $this->sqlize($command["val"][$i]);
        }
        return $this->do("UPDATE $table SET$commands$where");
    }

    function sel($table, $col = false, $where = false): mysqli_result|bool
    {
        $where = $this->where($where);
        if (!$col) {
            $col = "*";
        }
        return $this->do("SELECT $col FROM $table$where");
    }

    function give($table, $where = false, $col = false): array
    {
        $res = $this->sel($table, $col, $where);
        $t = [];
        while ($r = mysqli_fetch_assoc($res)) {
            array_push($t, $r);
        }
        return $t;
    }

    function del($table, $where = false): mysqli_result|bool
    {
        $where = $this->where($where);
        return $this->do("DELETE FROM $table$where");
    }

    function put($table, $command): mysqli_result|bool
    {
        $commands = ["", ""];
        for ($i = 0; $i < count($command["col"]); $i++) {
            if ($i + 1 == count($command["col"])) {
                $commands[0] .= $command["col"][$i];
                $commands[1] .= $this->sqlize($command["val"][$i]);
            } else {
                $commands[0] .= $command["col"][$i] . ", ";
                $commands[1] .= $this->sqlize($command["val"][$i]) . ", ";
            }
        }
        return $this->do("INSERT INTO $table (" . $commands[0] . ") VALUES (" . $commands[1] . ")");
    }

    function count($table, $where = false): int|string
    {
        return mysqli_num_rows($this->sel($table, '*', $where));
    }
}