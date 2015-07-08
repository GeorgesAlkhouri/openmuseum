<?php

class DbImageRetriever{

	private $log;

	function DbImageRetriever() {
        $this->log = 'DbImageRetriever';
    }

	function retrieveImage($db, $id, $table, $column) {
        // the function OCINewDescriptor allocates storage to hold descriptors or
        // lob locators,
        // see http://www.php.net/manual/en/function.ocinewdescriptor.php
        $data;

        $blob = OCINewDescriptor ($db, OCI_D_LOB);

        // construct the sql query with which we will get the media's data
        $sql = "DECLARE
                        obj ORDSYS.ORDImage;
                BEGIN
                        SELECT $column INTO obj FROM $table WHERE id = :id;
                        :extblob := obj.getContent;
                END;";
        $sql = strtr ($sql,chr(13).chr(10)," ");

        $stmt = OCIParse($db, $sql);

        // the function OCIBindByName binds a PHP variable to a oracle placeholder
        // (wheter the variable will be used for input or output will be determined
        // run-time, and the necessary storage space will be allocated)
        // see http://www.php.net/manual/en/function.ocibindbyname.php
        OCIBindByName ($stmt, ':extBlob', $blob, -1, OCI_B_BLOB);
        OCIBindByName ($stmt, ':mimeType', $mimetype, 20);
        OCIBindByName ($stmt, ':id', $id);

        OCIExecute ($stmt, OCI_DEFAULT);

        // load the binary data
        $data = $blob->load();
        return $data;
    }
}

?>