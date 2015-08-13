<?php

abstract class Database {
    
    private static $db;
    
    private $connection;
    
    public static function GetInstance() {
        if (!isset(self::$db)) {
            self::$db = new static();
        }
        return self::$db;
    }
    
    protected abstract function dbname();
    
    private function __construct() {
        $dbinfo = parse_ini_file(CONFIG . "database.ini");
        $this->connection = mysqli_connect($dbinfo["host"], $dbinfo["username"], $dbinfo["password"], $this->dbname());
        $this->connection->set_charset("utf8");
    }
    
    public function escape($str) {
        return $this->connection->escape_string($str);
    }
    
    public function query($query) {
        return $this->connection->query($query);
    }
    
    public function sel($table, array $where = array(), $limit = 0, $limitOffset = 0, $groupBy=false, $orderBy = array(), $select="*") {
        $select = $this->escape($select);
        $stmt = "SELECT $select FROM " . $this->escape($table) . " WHERE 1=1";
        
        $where_values = array();        
        foreach(array_keys($where) as $where_field) {
            $stmt .= " AND " . $this->escape($where_field);
            if (is_array($where[$where_field])) {
                $where_values[] = &$where[$where_field][0];
                $stmt .= $where[$where_field][1] ? " = ?" : " != ?";
            } else {
                $where_values[] = &$where[$where_field];
                $stmt .= " = ?";
            }
        }
        
        if ($groupBy) {
            $groupBy = $this->escape($groupBy);
            $stmt .= " GROUP BY $groupBy";
        }
        
        if (!is_array($orderBy) && strlen($orderBy) > 0) {
            $orderBy = $this->escape($orderBy);
            $stmt .= " ORDER BY $orderBy ASC";
        } else if (sizeof($orderBy) > 0) {
            $stmt .= " ORDER BY ";
            foreach($orderBy as $order) {
                if (is_array($order)) {
                    $by = $this->escape($order[0]);
                    $dir = strtolower($this->escape($order[1])) == "desc" ? "DESC" : "ASC";
                } else {
                    $by = $this->escape($order);
                    $dir = "ASC";
                }
                $stmt .= "$by $dir, ";
            }
            $stmt = substr($stmt, 0, strlen($stmt)-2);
        }
        
        if ($limit > 0) {
            $stmt .= " LIMIT ";
            if ($limitOffset > 0)
                $stmt .= $limitOffset . ", ";
            $stmt .= $limit;
        }

        $prep = $this->connection->prepare($stmt);
        if (count($where) > 0) {
            array_unshift($where_values, str_repeat("s", count($where_values)));
            call_user_func_array(array($prep, "bind_param"), $where_values);
        }
        
        $prep->execute();
        return $prep->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function count($table, array $where = array()) {
        $stmt = "SELECT COUNT(*) FROM " . $this->escape($table) . " WHERE 1=1";
        
        $where_values = array();        
        foreach(array_keys($where) as $where_field) {
            $stmt .= " AND " . $this->escape($where_field);
            if (is_array($where[$where_field])) {
                $where_values[] = &$where[$where_field][0];
                $stmt .= $where[$where_field][1] ? " = ?" : " != ?";
            } else {
                $where_values[] = &$where[$where_field];
                $stmt .= " = ?";
            }
        }
        
        $prep = $this->connection->prepare($stmt);
        if (count($where) > 0) {
            array_unshift($where_values, str_repeat("s", count($where_values)));
            call_user_func_array(array($prep, "bind_param"), $where_values);
        }
        
        $prep->execute();
        return $prep->get_result()->fetch_row()[0];
    }
    
    
    /* Convenience functions */
    
    public function select(array $params) {
        if (!$params["table"])
            throw new InvalidArgumentException("No table name given.");
        $table = $params["table"];
        $where = $params["where"] ?: array();
        $limit = $params["limit"] ?: 0;
        $limitOffset = $params["limitOffset"] ?: 0;
        $groupBy = $params["groupBy"] ?: false;
        $orderBy = $params["orderBy"] ?: array();
        $select = $params["select"] ?: "*";
        if (is_array($select) && sizeof($select) > 0) {
            $sel = "";
            foreach($select as $item) {
                $sel .= ", ".$item;
            }
            $select = substr($sel, 2);
        }
        return $this->sel($table, $where, $limit, $limitOffset, $groupBy, $orderBy, $select);
    }
    
    public function first($table, array $where = array()) {
        $res = $this->sel($table, $where, 1);
        if (count($res) > 0)
            return $res[0];
        else return false;
    }
    
    public function getById($table, $id) {
        return $this->first($table, array("id" => $id));
    }
    
    public function any($table, array $where = array()) {
        return $this->count($table, $where) > 0;
    }
    
    public function anyById($table, $id) {
        return $this->any($table, array("id" => $id));
    }
    
}