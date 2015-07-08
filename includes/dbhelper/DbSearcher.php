<?php
class DbSearcher
{
    
    private $log;
    
    function DbSearcher() {
        $this->log = 'DbSearcher';
    }
    
    function searchDetails($db, $searchData) {
        
        $sql;
        $txtFieldOperator = " AND "; // change to OR when result set not satisfied
        $keywordOperator = " AND "; // change to OR when result set not satisfied
        $addOperator = false;

        $sqlSelect = $this->getPictureSelectSql();
        $sqlFrom = " FROM pictures ";
        $sqlWhere = "WHERE ";

        /* Search for Picture Information */
        if (!empty($searchData->txtPictureName)) {
            $sqlWhere .= $this->getPictureNameSearchSql($searchData->txtPictureName);
            $addOperator = true;
        }
        if (!empty($searchData->txtDescription)) {
            if ($addOperator) { $sqlWhere .= $txtFieldOperator;}
            $sqlWhere .= $this->getPictureDescriptionSearchSql($searchData->txtDescription);
            $addOperator = true;
        }

        /* Search for Artist Information */
        if (!empty($searchData->txtArtist)) {
            $sqlFrom .= ", artists";
            if ($addOperator) { $sqlWhere .= $txtFieldOperator;}
            $sqlWhere .= $this->getArtistSearchSql($searchData->txtArtist);
            $addOperator = true;
        }

        /* Search for Museum Information */
        if (!empty($searchData->txtMuseumOwnes)) {
            if ($addOperator) { $sqlWhere .= $txtFieldOperator;}
            $sqlWhere .= $this->getMuseumOwnesSearchSql($searchData->txtMuseumOwnes);
            $addOperator = true;
        }
        if (!empty($searchData->txtMuseumExhibits)) {
            if ($addOperator) { $sqlWhere .= $txtFieldOperator;}
            $sqlWhere .= $this->getMuseumExhibitsSearchSql($searchData->txtMuseumExhibits);
            $addOperator = true;
        }
        if (!empty($searchData->txtMuseumExhibits) || !empty($searchData->txtMuseumOwnes)) {
            $sqlFrom .= ", museums";
        }

        /* Search for Owner Information */
        if (!empty($searchData->txtOwner)) {
            if ($addOperator) { $sqlWhere .= $txtFieldOperator;}
            $sqlFrom .= ", owners";
            $sqlWhere .= $txtFieldOperator.$this->getOwnerSearchSql($search);
            $addOperator = true;
        }

        /* Search for KeyWords Information */

        /* Search for Categories Information */
        
        $sql = $sqlSelect.$sqlFrom.$sqlWhere;
        $result = $this->executeSql($db, $sql);
        $pictures = $this->getPicturesArrayFromResult($result);
        return $pictures;
    }

    function searchAll($db, $searchData){

        $sql;
        $allOperator = " OR "; // when user wants to search in all fields
        $keywordOperator = " AND "; // change to OR when result set not satisfied
        $addOperator = false;

        $sqlSelect = $this->getPictureSelectSql();
        $sqlFrom = " FROM pictures ";
        $sqlWhere = "WHERE ";

        if (!empty($searchData->txtDefault)) {
            $search = $searchData->txtDefault;

            if (empty($searchData->txtPictureName)) {
                if ($addOperator) { $sqlWhere .= $allOperator;}
                $sqlWhere .= $this->getPictureNameSearchSql($search);
            }
            if (empty($searchData->txtDescription)) {
                if ($addOperator) { $sqlWhere .= $allOperator;}
                $sqlWhere .= $this->getPictureDescriptionSearchSql($search);
            }
            if (empty($searchData->txtArtist)) {
                if ($addOperator) { $sqlWhere .= $allOperator;}
                $sqlWhere .= $this->getArtistSearchSql($search);
            }
            if (empty($searchData->txtMuseumOwnes)) {
                if ($addOperator) { $sqlWhere .= $allOperator;}
                $sqlWhere .= $this->getMuseumOwnesSearchSql($search);
            }
            if (empty($searchData->txtMuseumExhibits)) {
                if ($addOperator) { $sqlWhere .= $allOperator;}
                $sqlWhere .= $this->getMuseumExhibitsSearchSql($search);
            }
            if (empty($searchData->txtOwner)) {
                if ($addOperator) { $sqlWhere .= $allOperator;}
                $sqlWhere .= $this->getOwnerSearchSql($search);
            }
        }
        
        $sql = $sqlSelect.$sqlFrom.$sqlWhere;
        $result = $this->executeSql($db, $sql);
        $pictures = $this->getPicturesArrayFromResult($result);
        return $pictures;
    }

    /* ComparisonPicture as argument*/
    function compare($db, $picture){

        $id = $this->insertComparisonPicture($db, $picture);

        $sql = "SELECT pictures.name, ORDSYS.IMGScore(123) SCORE
                FROM pictures P, comparison_pictures C
                WHERE C.current_picture_id=$id 
                AND ORDSYS.IMGSimilar(P.image_sig, C.image_sig,
                    'color=\"$picture->weightColor\" 
                    location=\"$picture->weightColor\" 
                    shape=\"$picture->weightColor\" 
                    texture=\"$picture->weightColor\",
                    $picture->threshold, 123) = 1 ORDER BY SCORE ASC;'";

        $result = $this->executeSql($db, $sql);
        $pictures = $this->getPicturesArrayFromResult($result);
        return $pictures;
    }

    function getPictureSelectSql(){

        $sql = "SELECT name, description, 
                    image, image_sig, 
                    creation_date, upload_date, 
                    artist_fk, artist_safety_level,
                    museum_ownes_fk, museum_exhibits_fk, 
                    museum_exhibits_startdate, museum_exhibits_enddate, 
                    owner_fk";
        return $sql;
    }

    /* ComparisonPicture as argument*/
    /* Returns id of inserted picture*/
    function insertComparisonPicture($db, $picture) {

        $sql = "INSERT INTO comparison_pictures (
                comparison_picture_id, 
                image, image_sig)
                VALUES (comparison_pictures_seq.nextval, 
                ORDSYS.ORDImage.init('FILE', 'IMGDIR02', '$picture->name'), 
                ORDSYS.ORDImageSignature.init()";

        $sql .= "returning comparison_picture_id into :comparison_picture_id";

        echo "$this->log - $sql <br />";
        $stmt = oci_parse($db, $sql);

        $currentPictureId;
        OCIBindByName($stmt,":comparison_picture_id",$currentPictureId,32);

        oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        /** Load image data **/
            $this->dbImageUploader = new DbImageUploader();
            $this->dbImageUploader->uploadImageData($db, $picture->image_path.$picture->image_name, $currentPictureId, 'comparison_pictures');
        
        /** Create ImageSignature **/
        $sql = "DECLARE imageObj ORDSYS.ORDImage;
                        image_sigObj ORDSYS.ORDImageSignature;
                BEGIN
                    SELECT image, image_sig INTO imageObj, image_sigObj
                    FROM comparison_pictures WHERE comparison_picture_id = $currentPictureId FOR UPDATE;
                    image_sigObj.generateSignature(imageObj);
                UPDATE comparison_pictures SET image_sig = image_sigObj 
                WHERE comparison_picture_id = $currentPictureId;
                COMMIT; END;";

        echo "$this->log - $sql <br />";
        $stmt = oci_parse($db, $sql);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        
        oci_commit($db);
        return $currentPictureId;
    }

    function getPictureNameSearchSql($search) {
        
        return "UPPER(pictures.name) LIKE UPPER('%$search%') ";
    }

    function getPictureDescriptionSearchSql($search) {
        
        return "CONTAINS(pictures.description, '$search') > 0 ";
    }

    function getArtistSearchSql($search) {
        
        return "(
            pictures.artist_fk = artists.artist_id AND
            concat(concat(UPPER(artists.firstname), ' '), UPPER(artists.firstname)) LIKE UPPER('%$search%')
                    )";
    }

    function getMuseumOwnesSearchSql($search) {
        
        return "(
            pictures.museum_ownes_fk = museums.museum_id AND
            UPPER(museums.name) LIKE UPPER('%$search%')
                    )";
    }

    function getMuseumExhibitsSearchSql($search) {
        
        return "(
            pictures.museum_exhibits_fk = museums.museum_id AND
            UPPER(museums.name) LIKE UPPER('%$search%')
                    )";
    }
    
    function getKeywordSearchSql($search) {
        
        return "(
            pictures.picture_id = pictures_keywords.picture_fk AND
            keywords.keyword_id = pictures_keywords.picture_fk AND
            UPPER(keywords.title) LIKE UPPER('%$search%')
                    )";
    }
    
    function getOwnerSearchSql($search) {
        
        return "(
            pictures.owner_fk = owners.owner_id AND
            concat(concat(UPPER(owners.firstname), ' '), UPPER(owners.firstname)) LIKE UPPER('%$search%')
            )";
    }
    
    function executeSql($db, $sql) {
        
        echo "$this->log - $sql <br />";
        $stmt = oci_parse($db, $sql);
        oci_execute($stmt);
        return $stmt;
    }

    function getPicturesArrayFromResult($stmt){

        $pictures = array();
        while (oci_fetch($stmt)) {
            echo oci_result($stmt, 'NAME');
            $artist_fk = oci_result($stmt, 'artist_fk');
            $museum_owns_fk = oci_result($stmt, 'museum_owns_fk');
            $museum_exhibits_fk = oci_result($stmt, 'museum_exhibits_fk');
            $owner_fk = oci_result($stmt, 'owner_fk');
            $picture_id = oci_result($stmt, 'picture_id');

            $picture = new DisplayPicture();
            $picture->name = oci_result($stmt, 'NAME');
            $picture->description = oci_result($stmt, 'description');
            $picture->creation_date = oci_result($stmt, 'creation_date');
            $picture->upload_date = oci_result($stmt, 'upload_date');
            $picture->artist = $this->getArtistForId($artist_fk);
            $picture->artist_safety_level = oci_result($stmt, 'artist_safety_level');
            $picture->museum_owns = $this->getMuseumForId($museum_owns_fk);
            $picture->museum_exhibits = $this->getMuseumForId($museum_exhibits_fk);
            $picture->museum_exhibits_startdate = oci_result($stmt, 'museum_exhibits_startdate');
            $picture->museum_exhibits_enddate = oci_result($stmt, 'museum_exhibits_enddate');
            $picture->artist_safety_level = oci_result($stmt, 'artist_safety_level');
            $picture->owner = $this->getOwnerForId($owner_fk);
            $picture->image_data = $this->loadImageData($db, $picture_id, "pictures", "image");
            array_push($pictures, $picture);
        }
        return $pictures;
    }

    function loadImageData($db, $id, $table, $column){

        $imageRetriever = new DbImageRetriever();
        $data = $imageRetriever->retrieveImage($db, $id, $table, $column);
        return $data;
    }

    function getArtistForId($artist_id){

        $sql = "SELECT firstname, lastname, birth_date, death_date FROM artists WHERE artist_id = '$artist_id'";
        $result = $this->executeSql($db, $sql);

        $artist = new Artist();

        while (oci_fetch($result)) {
            $artist->firstname = oci_result($result, 'firstname');
            $artist->lastname = oci_result($result, 'lastname');
            $artist->birth_date = oci_result($result, 'birth_date');
            $artist->death_date = oci_result($result, 'death_date');
        }
        return $artist;
    }

    function getMuseumForId($museum_id){

        $sql = "SELECT name, adress, website FROM museums WHERE museum_id = '$museum_id'";
        $result = $this->executeSql($db, $sql);

        $museum = new Museum();

        while (oci_fetch($result)) {
            $museum->name = oci_result($result, 'name');
            $museum->adress = oci_result($result, 'adress');
            $museum->website = oci_result($result, 'website');
        }
        return $museum;
    }

    function getOwnerForId($owner_id){

        $sql = "SELECT firstname, lastname FROM owners WHERE owner_id = '$owner_id'";
        $result = $this->executeSql($db, $sql);

        $owner = new Owner();

        while (oci_fetch($result)) {
            $owner->firstname = oci_result($result, 'firstname');
            $owner->lastname = oci_result($result, 'lastname');
        }
        return $owner;
    }
}
?>