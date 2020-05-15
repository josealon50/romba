<?php
namespace Bot\SQLite;

use Bot\Config\Config;

/**
 * SQLite connnection
 */
class SQLiteConnection {
    /**
     * PDO instance
     * @var type 
     */
    private $pdo;

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect() {
        if ($this->pdo == null) {
            $this->pdo = new \SQLite3(Config::PATH_TO_SQLITE_FILE);

        }
        return $this->pdo;
    }

}
