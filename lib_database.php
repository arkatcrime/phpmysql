<?php

class Database
{
    private $m_name;
    private $m_db = null;

    //=========================================================================
    //  Obter uma instancia da base de dados
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
    //  Constructor
    //=========================================================================
    function __construct( $name )
    {
        $this->m_name = $name;

        $this->m_db = mysqli_connect( CFG_DATABASE_HOST, CFG_DATABASE_USER, CFG_DATABASE_PASS,CFG_DATABASE_DB );
        if( !$this->m_db )
            die( 'Erro ao conectar à base de dados: ' . mysql_error() );

    }


    //=========================================================================
    //  Executar query
    //=========================================================================
    function executeQuery( $sql )
    {
        if( !$this->m_db )
            return false;

        $res = mysqli_query( $this->m_db , $sql  );
        return $res;
    }

    //=========================================================================
    //  Obter erro
    //=========================================================================
    function getError()
    {
        if( !$this->m_db )
            return null;
        return mysqli_error( $this->m_db );
    }

    //=========================================================================
    //  Obter o número de registos
    //=========================================================================
    function getNumRows( $result )
    {
        return mysqli_num_rows( $result );
    }

    //=========================================================================
    //  Obter dados da linha obtida
    // return type can be = { MYSQLI_ASSOC, MYSQLI_NUM }
    //=========================================================================
    function fetchRow( $result , $return_type = MYSQLI_ASSOC )
    {
        return mysqli_fetch_array( $result, $return_type);

        // Numeric array = ( $result, MYSQLI_NUM);
        // Associative array = ( $result, MYSQLI_ASSOC);
    }

    //=========================================================================
    //  Obter dados da linha obtida
    //  OUTPUT : Numeric array
    //=========================================================================
    function fetchRowNum( $result )
    {
        return mysqli_fetch_array( $result, MYSQLI_NUM);

    }

    //=========================================================================
    //  Obter dados da linha obtida
    //  OUTPUT : Associative array
    //=========================================================================
    function fetchRowAssoc( $result )
    {
        return mysqli_fetch_array( $result, MYSQLI_ASSOC);

    }


    //=========================================================================
    //  Obter dados todos
    //=========================================================================
    function fetchArray( $result )
    {
        $rows = array();
        while( $row = $this->fetchRow( $result ) )
            $rows[] = $row;
        return $rows;
    }

    //=========================================================================
    //  Obter ID da última inserção
    //=========================================================================
    function getInsertId()
    {
        return mysqli_insert_id( $this->m_db );
    }
};

?>
