<?php

class DbSearcher{

	private $log;

    function DbSearcher(){
        $this->log = 'DbSearcher';
    }

    function search($db, $searchData){

        $sql = "SELECT name FROM pictures";

        echo "$this->log - $sql <br />";
        $stmt = oci_parse($db, $sql);
        oci_execute($stmt);

        while (oci_fetch($stmt)) {
            echo oci_result($stmt, 'NAME');
        }
        oci_free_statement($stmt);
        oci_close($db);
    }
}

?>