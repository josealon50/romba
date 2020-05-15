<?php
namespace Bot\SQLite;

/**
 * PHP SQLite Insert Demo
 */
class SQLiteInsert {

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * Initialize the object with a specified PDO object
     * @param \PDO $pdo
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Insert a new link into the bot link table
     * @param string $projectName
     * @return the id of the new project
     */
    public function insertSpiderBotLink( $name, $url ) {
        try{
            $sql = 'INSERT INTO spider_bot_links(name, url) VALUES(:name, :url)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':url', $url);
            $stmt->execute();
            
            return; 
        }
        catch( Exception $e ){
            var_dump($e);
        }
    }


}
