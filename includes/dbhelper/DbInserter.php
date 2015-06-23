<?php
include_once 'DbIdFetcher.php';

class DbInserter
{
    private $log;
    private $dbIdFetcher;
    
    function DbInserter() {
        $this->log = 'DbInserter';
        $this->dbIdFetcher = new DbIdFetcher();
    }
    
    function insertArtistIfDoesNotExists($db, $artist) {
        
        $artist_id = $this->dbIdFetcher->fetchArtistId($db, $artist);
        if (is_null($artist_id)) {
            $sql = "INSERT INTO artists (artist_id, firstname, lastname, birth_date, death_date) 
            VALUES (artists_seq.nextval, '$artist->firstname', '$artist->lastname', TO_DATE('$artist->birth_date', 'dd.mm.yyyy'), TO_DATE('$artist->death_date', 'dd.mm.yyyy'))";
            $this->executeSql($db, $sql);
        }
    }
    
    function insertOwnerIfDoesNotExists($db, $owner) {
        
        $owner->id = $this->dbIdFetcher->fetchOwnerId($db, $owner);
        if (is_null($owner->id)) {
            $sql = "INSERT INTO owners (owner_id, firstname, lastname) 
            VALUES (owners_seq.nextval, '$owner->firstname', '$owner->lastname')";
            $this->executeSql($db, $sql);
        }
    }
    
    function insertMuseumExhibitsIfNotExists($db, $museum) {
        
        $museum->exhibits_id = $this->dbIdFetcher->fetchMuseumId($db, $museum);
        if (is_null($museum->exhibits_id)) {
            $sql = "INSERT INTO museums (museum_id, name, adress, website) 
            VALUES (museums_seq.nextval, '$museum->name', '$museum->adress', '$museum->website')";
            $this->executeSql($db, $sql);
        }
    }
    
    function insertMuseumOwnsIfNotExists($db, $museum) {
        
        $museum->owns_id = $this->dbIdFetcher->fetchMuseumId($db, $museum);
        if (is_null($museum->owns_id)) {
            $sql = "INSERT INTO museums (museum_id, name, adress, website) 
            VALUES (artists_seq.nextval, '$museum->name', '$museum->adress', '$museum->website')";
            $this->executeSql($db, $sql);
        }
    }
    
    function insertKeyWordIfNotExists($db, $keyword_title) {
        
        $keyword_id = $this->dbIdFetcher->fetchKeywordId($db, $keyword_title);
        if (is_null($keyword_id)) {
            $sql = "INSERT INTO keywords (keyword_id, title) 
            VALUES (keywords_seq.nextval, '$keyword_title')";
            $this->executeSql($db, $sql);
        }
    }
    
    function insertPictureIfNotExists($db, $picture) {
        
        $picture->id = $this->dbIdFetcher->fetchPictureId($db, $picture);
        if (is_null($picture->id)) {

            $sql = "INSERT INTO pictures (
                    picture_id, name, description, 
                    image, image_sig, 
                    creation_date, upload_date, 
                    artist_fk, artist_safety_level,
                    museum_ownes_fk, museum_exhibits_fk, 
                    museum_exhibits_startdate, museum_exhibits_enddate, 
                    owner_fk)
                    VALUES (pictures_seq.nextval, 
                    '$picture->name', 
                    '$picture->description', 
                    ORDSYS.ORDImage.init(), 
                    ORDSYS.ORDImageSignature.init(),
                    TO_DATE('$picture->creation_date', 'dd.mm.yyyy'), 
                    TO_DATE('$picture->upload_date', 'dd.mm.yyyy'), 
                    $picture->artist_fk, 
                    $picture->artist_safety_level,";

            /** Add Optional Parameters **/
            if (empty($picture->museum_owns_fk)) {
                $sql .= "NULL, ";
            }else{
                $sql .= "$picture->museum_owns_fk , ";
            }
            if (empty($picture->museum_exhibits_fk)) {
                $sql .= "NULL, NULL, NULL, ";
            }else{
                $sql .= "$picture->museum_exhibits_fk ,
                    TO_DATE('$picture->museum_exhibits_startdate', 'dd.mm.yyyy'), 
                    TO_DATE('$picture->museum_exhibits_enddate', 'dd.mm.yyyy'), ";
            }
            if (empty($picture->owner_fk)) {
                $sql .= "NULL)";
            }else{
                $sql .= "$picture->owner_fk)";
            }

            $sql .= "returning picture_id into :picture_id";

            echo "$this->log - $sql <br />";
            $stmt = oci_parse($db, $sql);

            $currentPictureId;
            OCIBindByName($stmt,":picture_id",$currentPictureId,32);

            oci_execute($stmt, OCI_NO_AUTO_COMMIT);


            /** Load image data **/
            
            $this->uploadImageData($db, $picture->image_path.$picture->image_name, $currentPictureId);

            /** Create ImageSignature **/
            $sql = "DECLARE imageObj ORDSYS.ORDImage;
                            image_sigObj ORDSYS.ORDImageSignature;
                    BEGIN
                        SELECT image, image_sig INTO imageObj, image_sigObj
                        FROM pictures WHERE picture_id = $currentPictureId FOR UPDATE;
                        image_sigObj.generateSignature(imageObj);
                    UPDATE pictures SET image_sig = image_sigObj 
                    WHERE picture_id = $currentPictureId;
                    COMMIT; END;";

            echo "$this->log - $sql <br />";
            $stmt = oci_parse($db, $sql);
            oci_execute($stmt, OCI_NO_AUTO_COMMIT);
            
            oci_commit($db);
        }
    }
    
    function insertPictureCategoriesIfNotExists($db, $picture_id, $category_ids) {
        
        foreach ($category_ids as $category_id => $value) {
            $piccat_id = $this->dbIdFetcher->fetchPicCatId($db, $picture_id, $category_id);
            if (is_null($piccat_id)) {
                $sql = "INSERT INTO PICTURES_CATEGORIES (piccat_id, picture_fk, category_fk) 
            VALUES (pictures_categories_seq.nextval, $picture_id, $category_id)";
                $this->executeSql($db, $sql);
            }
        }
    }
    
    function insertPictureKeywordsIfNotExists($db, $picture_id, $keyword_ids) {
        
        foreach ($keyword_ids as $keyword_id) {
            $pickey_id = $this->dbIdFetcher->fetchPicKeyId($db, $picture_id, $keyword_id);
            if (is_null($pickey_id)) {
                $sql = "INSERT INTO PICTURES_KEYWORDS (pickey_id, picture_fk, keyword_fk) 
            VALUES (pictures_keywords_seq.nextval, $picture_id, $keyword_id)";
                $this->executeSql($db, $sql);
            }
        }
    }
    
    /********** Fill Db with Predefined Values ****************/
    
    function insertCategoriesIfNeeded($db) {
        
        $title = 'Bildepoche';
        if (is_null($this->dbIdFetcher->fetchCategoryId($db, $title))) {
            $sql = "INSERT ALL
            INTO categories (category_id, title, category_fk) VALUES (1, '$title', NULL)
            INTO categories (category_id, title, category_fk) VALUES (2, 'Klassische Malerei', 1)
            INTO categories (category_id, title, category_fk) VALUES (3, 'Moderne Kunst', 1)
            INTO categories (category_id, title, category_fk) VALUES (4, 'Romanik', 2)
            INTO categories (category_id, title, category_fk) VALUES (5, 'Renaissance', 2)
            INTO categories (category_id, title, category_fk) VALUES (6, 'Expressionismus', 3)
            INTO categories (category_id, title, category_fk) VALUES (7, 'Surrealismus', 3)
            SELECT * FROM dual";
            
            $this->executeSql($db, $sql);
        }
    }
    
    function executeSql($db, $sql) {
        
        echo "$this->log - $sql <br />";
        $stmt = oci_parse($db, $sql);
        oci_execute($stmt);
    }

    function uploadImageData($db, $file, $currentPictureId) {

                // insert the new record into the media's table and load the
                // corresponding blob with the media's data
                // (we use oracle's pseudo column rowid which identifies a row
                // within a table (but not within a database) to refer to the
                // right record later on)
                $sql ="DECLARE
                                obj ORDSYS.ORDImage;
                                iblob BLOB;
                        BEGIN
                                SELECT image INTO obj FROM pictures
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