<?php

class DbImageUploader{

	private $log;

	function DbImageUploader() {
        $this->log = 'DbImageUploader';
    }

	function uploadImageData($db, $file, $currentPictureId, $table) {

        // insert the new record into the media's table and load the
        // corresponding blob with the media's data
        // (we use oracle's pseudo column rowid which identifies a row
        // within a table (but not within a database) to refer to the
        // right record later on)
        $sql ="DECLARE
                        obj ORDSYS.ORDImage;
                        iblob BLOB;
                BEGIN
                        SELECT image INTO obj FROM $table
                        WHERE PICTURE_ID = $currentPictureId FOR UPDATE;

                        iblob := obj.source.localData;
                        :extblob := iblob;

                        UPDATE pictures SET image = obj WHERE PICTURE_ID = $currentPictureId;
                END;";

        // the function OCINewDescriptor allocates storage to hold descriptors or
        // lob locators.
        // see http://www.php.net/manual/en/function.ocinewdescriptor.php
        $blob = OCINewDescriptor ($db, OCI_D_LOB);

        $sql = strtr ($sql,chr(13).chr(10)," ");
        $stmt = OCIParse($db, $sql);

        // the function OCIBindByName binds a PHP variable to a oracle placeholder
        // (whether the variable will be used for input or output will be determined
        // run-time, and the necessary storage space will be allocated)
        // see http://www.php.net/manual/en/function.ocibindbyname.php
        OCIBindByName($stmt, ':extblob', $blob, -1, OCI_B_BLOB);

        echo "$this->log - $sql <br />";

        OCIExecute ($stmt, OCI_DEFAULT);

        // read the files data and load it into the blob

        $blob->savefile($file);

        OCIFreeStatement ($stmt);
        $blob->free();
    }
}

?>