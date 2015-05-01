<?php

class DbAdapter {
//    private $host = "localhost"; // Host name 
//    private $username = "root"; // Mysql username 
//    private $password = ""; // Mysql password 
//    private $db_name = "petitiondb"; // Database name
    
    private $host = "wp090.webpack.hosteurope.de"; // or "localhost" 
    private $username = "db12343970-40748"; // Mysql username 
    private $password = "Mede1904u12u"; // Mysql password 
    private $db_name = "db12343970-oeffnen2"; // Database name
    /**
     *
     * @var type \PDO
     */
    private $instance = null;
    
    /**
     * 
     * @return type \PDO
     */
    public function getDB() {
        if (!$this->instance) {
            $this->instance = new \PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
        }
        return $this->instance;
    }
}
