<?php
namespace Bot\SQLite;

/**
 * PHP SQLite Insert Demo
 */
class SQLiteQuery {

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


    public function query( $table, $select, $params ){
        $stmt = 'SELECT ';
        
        if ( empty($select) ){
            $stmt .= '* ';
        }
        else{
            $stmt .= implode( ',', $select );
        }
        $stmt .= ' FROM ' . $table . ' ';
        if ( !empty($params) ){
            $stmt .= ' WHERE ';
            $first = true;
            foreach( $params as $key => $value ){
                if( strpos( $value, "'" ) > 0 ){
                    $value = str_replace( "'", "''", $value );
                } 
                if ( $first ){
                    $stmt .= $key . " = '" . $value . "' ";
                    $first = false;
                }
                else{
                    $stmt .= ' AND ' . $key . " = '" . $value . "' ";
                }
            }
        }
        $result = $this->pdo->query( $stmt );
        $arr = [];
        while ($row = $result->fetchArray(\PDO::FETCH_ASSOC)) {
            $arr[] = $row;
        }
        return $arr;

    }

}
