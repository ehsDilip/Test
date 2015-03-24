<?php

class rmsConnection {

    var $host = 'localhost';  //hostname add here
    var $username = 'root';   //username add here
    var $password = '';    //password add here
    var $dbname = 'perspektus'; //database add here
    var $admintitle = "Perspektus";

    //constructor initialize the connection
    function rmsConnection() {
        try {
            $this->con = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname . "", $this->username, $this->password);
            return $this->con;
        } catch (PDOException $e) {
            return $e->getMessage(PDO::FETCH_ASSOC);
        }
    }

    // select rows
    function select($sql) {
        try {
            $sth = $this->con->prepare($sql);
            $sth->execute();
            $result = $sth->fetchAll();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // insert row
    function insert($table, $values) {
        try {
            $this->tableName = trim($table);
            $this->value = $values;
            if (!is_array($this->value)) {
                return 0;
            }
            $count = 0;
            foreach ($this->value as $key => $val) {
                if ($count == 0) {
                    $this->field1 = ":" . $key . "";
                    $this->field2 = "`" . $key . "`";
                    $this->fieldsValues = $val;
                } else {
                    $this->fieldsValues.= ", " . $val . " ";
                    $this->field1.= ",:" . $key . "";
                    $this->field2.= ",`" . $key . "`";
                }
                $count++;
            }
            $this->query = sprintf("insert into %s (%s) values (%s)", $this->tableName, $this->field2, $this->fieldsValues);
            $res = $this->con->query($this->query);
            return $this->con->lastInsertId();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // update row
    function update($table, $values, $where = 1, $limit = 1) {
        try {
            $this->tableName = trim($table);
            $this->value = $values;
            $this->where = $where;
            $this->limit = $limit;

            if (!is_array($this->value)) {
                return 0;
            }
            $count = 0;
            $this->query = 'update ' . $this->tableName . ' set ';

            foreach ($this->value as $key => $val) {
                if ($count == 0) {
                    $this->query.=" `$key`= " . $val . " ";
                } else {
                    $this->query.=" , `$key`= " . $val . " ";
                }
                $count++;
            }

            $this->query.="  WHERE $this->where  LIMIT $this->limit ";
            $res = $this->con->query($this->query);
            return $res;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // delete row
    function delete($table, $where) {
        try {
            $this->table = trim($table);
            $this->where = $where;
            $this->query = "DELETE FROM " . $this->table . " WHERE " . $this->where;
            $res = $this->con->query($this->query);
            return $res;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // close connection
    function close() {
        try {
            $this->con = null;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // sql safe
    function sqlSafe($value, $quote = "'") {
        try {
            $value = str_replace(array("\'", "'"), "&#39;", $value);
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value);
            }
            $value = $quote . $value . $quote;
            return $value;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // get page title
    function GetPageTitle($fileName, $adminTitle) {
        try {
            $this->fileName = trim($fileName);
            $this->adminTitle = trim($adminTitle);
            $pagename = $this->select("SELECT page_name, page_title FROM " . TAB_CMS . " WHERE page_name = '" . $this->fileName . "'");
            $page_title = $pagename[0]['page_title'] . " | " . $this->adminTitle;
            return $page_title;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // get page name
    function GetPageName($fileName) {
        try {
            $this->fileName = trim($fileName);
            $pagename = $this->select("SELECT page_name FROM " . TAB_CMS . " WHERE page_name = '" . $this->fileName . "'");
            $page_name = $pagename[0]['page_name'];
            return $page_name;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // get page name
    function GetCurrentPageClass($fileName) {
        try {
            if (is_array($fileName)) {
                if (in_array(SCRIPTNAME, $fileName)) {
                    $current_class = 'current';
                } else {
                    $current_class = '';
                }
            } else {
                if (SCRIPTNAME == $fileName) {
                    $current_class = 'current';
                } else {
                    $current_class = '';
                }
            }
            return $current_class;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // get page name
    function GetCmsContent($fileName) {
        try {
            $this->fileName = trim($fileName);
            $pagename = $this->select("SELECT page_content FROM " . TAB_CMS . " WHERE page_name = '" . $this->fileName . "'");
            $page_content = $pagename[0]['page_content'];
            return $page_content;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // get json encoded data
    function GetJsonEncode($result) {
        try {
            if (is_array($result)) {
                return json_encode($result);
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

}

// object intilization
$dbh = new rmsConnection();
?> 
