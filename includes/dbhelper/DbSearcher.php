<?php
class DbSearcher
{
    
    private $log;
    
    function DbSearcher() {
        $this->log = 'DbSearcher';
    }
    
    function search($db, $searchData) {
        
        $sql;
        $txtFieldOperator = " AND "; // change to OR when result set not satisfied
        $allOperator = " OR "; // when user wants to search in all fields
        $keywordOperator = " AND "; // change to OR when result set not satisfied
        $addOperator = false;

        $sqlSelect = "SELECT DISTINCT pictures.name ";
        $sqlFrom = "FROM pictures";
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

        
        /* Search for All Information */
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
        
        executeSql($db, $sql);
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

        executeSql($db, $sql);
    }

    /* ComparisonPicture as argument*/
    /* Returns id of inserted picture*/
    function insertComparisonPicture($db, $picture) {
        
        $sql = "CREATE OR REPLACE DIRECTORY IMGDIR02 AS '$picture->path'";

        echo "$this->log - $sql <br />";
        $stmt = oci_parse($db, $sql);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);

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
        
        while (oci_fetch($stmt)) {
            echo oci_result($stmt, 'NAME');
        }
        oci_free_statement($stmt);
        oci_close($db);
    }
}
?>