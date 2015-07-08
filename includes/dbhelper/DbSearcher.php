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

        echo "TXTDEFAULT: ".$searchData->txtDefault; 
        $searchData->txtDefault = "sd";

        if (!empty($searchData->txtDefault)) {

            $search = $searchData->txtDefault;
            $allOperator = " OR "; // when user wants to search in all fields
            $keywordOperator = " AND "; // change to OR when result set not satisfied
            $addOperator = false;

            $sql = "SELECT pictures.picture_id FROM pictures, artists WHERE ";
            $sql .= $this->getPictureNameSearchSql($search);
            $sql .= $allOperator;
            $sql .= $this->getPictureDescriptionSearchSql($search);
            $sql .= $allOperator;
            $sql .= $this->getArtistSearchSql($search);
            $sql .= " UNION SELECT pictures.picture_id FROM pictures, museums "." WHERE ";
            $sql .= $this->getMuseumOwnesSearchSql($search);
            $sql .= $allOperator;
            $sql .= $this->getMuseumExhibitsSearchSql($search);
            $sql .= " UNION SELECT pictures.picture_id FROM pictures, owners "." WHERE ";
            $sql .= $this->getOwnerSearchSql($search);  

            $result = $this->executeSql($db, $sql);
            $pictures = $this->getPicturesArrayFromResult($db, $result);
            return $pictures;
        }else{
            echo "$this->log - search word is empty";
        }
    }

    /* ComparisonPicture as argument*/
    function compare($db, $picture){

        $inserter = new DbInserter();
        $id = $inserter->insertComparisonPicture($db, $picture);

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
        $pictures = $this->getPicturesArrayFromResult($db, $result);
        return $pictures;
    }

    /*
    goes through result with picture ids, fetches all needed picture data and creates a display picture
    returns an array of display pictures
    */
    function getPicturesArrayFromResult($db, $stmt){

        $pictures = array();

        while (oci_fetch($stmt)) {
            echo "<br \> $this->log: result picture_id: ".oci_result($stmt, 'PICTURE_ID')."<br \>";

            $picture_id = oci_result($stmt, 'PICTURE_ID');
            $displayPic = $this->getDiplayPictureForId($db, $picture_id);
            array_push($pictures, $displayPic);
        }
        return $pictures;
    }

    function getDiplayPictureForId($db, $id){

        $sql = "SELECT name, description, creation_date, upload_date, 
                artist_fk, artist_safety_level, 
                museum_ownes_fk, museum_exhibits_fk, 
                museum_exhibits_startdate, museum_exhibits_enddate, 
                owner_fk
                FROM pictures WHERE picture_id = $id";
        $imgResult = $this->executeSql($db, $sql);

        oci_fetch_object($imgResult);

        $artist_fk = oci_result($imgResult, 'ARTIST_FK');
        $museum_owns_fk = oci_result($imgResult, 'MUSEUM_OWNES_FK');
        $museum_exhibits_fk = oci_result($imgResult, 'MUSEUM_EXHIBITS_FK');
        $owner_fk = oci_result($imgResult, 'OWNER_FK');

        $picture = new DisplayPicture();
        $picture->name = oci_result($imgResult, 'NAME');
        $picture->description = oci_result($imgResult, 'DESCRIPTION');
        $picture->creation_date = oci_result($imgResult, 'CREATION_DATE');
        $picture->upload_date = oci_result($imgResult, 'UPLOAD_DATE');
        $picture->artist = $this->getArtistForId($db, $artist_fk);
        $picture->artist_safety_level = oci_result($imgResult, 'ARTIST_SAFETY_LEVEL');
        $picture->museum_owns = $this->getMuseumForId($db, $museum_owns_fk);
        $picture->museum_exhibits = $this->getMuseumForId($db, $museum_exhibits_fk);
        $picture->museum_exhibits_startdate = oci_result($imgResult, 'MUSEUM_EXHIBITS_STARTDATE');
        $picture->museum_exhibits_enddate = oci_result($imgResult, 'MUSEUM_EXHIBITS_ENDDATE');
        $picture->artist_safety_level = oci_result($imgResult, 'ARTIST_SAFETY_LEVEL');
        $picture->owner = $this->getOwnerForId($db, $owner_fk);
        $picture->image_data = $this->loadImageData($db, $id, "pictures", "image");
        return $picture;
    }

    function loadImageData($db, $id, $table, $column){

        $imageRetriever = new DbImageRetriever();
        $data = $imageRetriever->retrieveImage($db, $id, $table, $column);
        return $data;
    }

    function getArtistForId($db, $artist_id){
        if (!empty($artist_id)) {
            $sql = "SELECT firstname, lastname, birth_date, death_date FROM artists WHERE artist_id = '$artist_id'";
            $result = $this->executeSql($db, $sql);

            $artist = new Artist();

            while (oci_fetch($result)) {
                $artist->firstname = oci_result($result, 'FIRSTNAME');
                $artist->lastname = oci_result($result, 'LASTNAME');
                $artist->birth_date = oci_result($result, 'BIRTH_DATE');
                $artist->death_date = oci_result($result, 'DEATH_DATE');
            }
            return $artist;
        }
    }

    function getMuseumForId($db, $museum_id){

        if (!empty($museum_id)){
            $sql = "SELECT name, adress, website FROM museums WHERE museum_id = '$museum_id'";
            $result = $this->executeSql($db, $sql);
    
            $museum = new Museum();
    
            while (oci_fetch($result)) {
                $museum->name = oci_result($result, 'NAME');
                $museum->adress = oci_result($result, 'ADRESS');
                $museum->website = oci_result($result, 'WEBSITE');
            }
            return $museum;
        }
    }

    function getOwnerForId($db, $owner_id){

        if (!empty($owner_id)){
            $sql = "SELECT firstname, lastname FROM owners WHERE owner_id = '$owner_id'";
            $result = $this->executeSql($db, $sql);

            $owner = new Owner();

            while (oci_fetch($result)) {
                $owner->firstname = oci_result($result, 'FIRSTNAME');
                $owner->lastname = oci_result($result, 'LASTNAME');
            }
            return $owner;
        }  
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
            concat(concat(UPPER(artists.firstname), ' '), UPPER(artists.lastname)) LIKE UPPER('%$search%')
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
            concat(concat(UPPER(owners.firstname), ' '), UPPER(owners.lastname)) LIKE UPPER('%$search%')
            )";
    }


    function executeSql($db, $sql) {
        
        echo "<br \> $this->log - $sql <br />";
        $stmt = oci_parse($db, $sql);
        oci_execute($stmt);
        return $stmt;
    }
}
?>