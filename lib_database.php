<?php

class Database
{
    private $m_name;
    private $m_db = null;

    //=========================================================================
    //  GET Database instance
    //=========================================================================
    static function getInstance( $name='default' )
    {
        static $dbs;

        if( !isset($dbs) )
            $dbs = array();

        if( !isset($dbs[$name]))
            $dbs[$name] = new Database( $name );

        return $dbs[$name];
    }

    //=========================================================================
    //  Construct
    //=========================================================================
    function __construct( $name )
    {
        $this->m_name = $name;

        $this->m_db = mysqli_connect( CFG_DATABASE_HOST, CFG_DATABASE_USER, CFG_DATABASE_PASS,CFG_DATABASE_DB );
        if( !$this->m_db )
            die( 'Erro ao conectar à base de dados: ' . mysql_error() );

    }


    //=========================================================================
    //  Execute query
    //=========================================================================
    function executeQuery( $sql )
    {
        if( !$this->m_db )
            return false;

        $res = mysqli_query( $this->m_db , $sql  );
        return $res;
    }

    //=========================================================================
    //  Get error
    //=========================================================================
    function getError()
    {
        if( !$this->m_db )
            return null;
        return mysqli_error( $this->m_db );
    }

    //=========================================================================
    //  GET number of records
    //=========================================================================
    function getNumRows( $result )
    {
        return mysqli_num_rows( $result );
    }

    //=========================================================================
    //  GET values of a row
    // return type can be = { MYSQLI_ASSOC, MYSQLI_NUM }
    //=========================================================================
    function fetchRow( $result , $return_type = MYSQLI_ASSOC )
    {
        return mysqli_fetch_array( $result, $return_type);

        // Numeric array = ( $result, MYSQLI_NUM);
        // Associative array = ( $result, MYSQLI_ASSOC);
    }

    //=========================================================================
    //  GET values of a row
    //  OUTPUT : Numeric array
    //=========================================================================
    function fetchRowNum( $result )
    {
        return mysqli_fetch_array( $result, MYSQLI_NUM);

    }

    //=========================================================================
    //  GET values of a row
    //  OUTPUT : Associative array
    //=========================================================================
    function fetchRowAssoc( $result )
    {
        return mysqli_fetch_array( $result, MYSQLI_ASSOC);

    }


    //=========================================================================
    // GET all data
    //=========================================================================
    function fetchArray( $result )
    {
        $rows = array();
        while( $row = $this->fetchRow( $result ) )
            $rows[] = $row;
        return $rows;
    }

    //=========================================================================
    //  Get last ID
    //=========================================================================
    function getInsertId()
    {
        return mysqli_insert_id( $this->m_db );
    }
	
	function beginTransaction($flag= MYSQLI_TRANS_START_READ_ONLY){
		mysqli_begin_transaction($this->m_db , $flag);
	}
	
	//=========================================================================
    //  Commit transction 
    //=========================================================================
	function commitTransaction(){
		return mysqli_commit($this->m_db);
	}

    //=========================================================================
    //  Commit transction
    //=========================================================================
    function rollbackdTransaction(){
        return mysqli_rollback($this->m_db);
    }
};

?>
