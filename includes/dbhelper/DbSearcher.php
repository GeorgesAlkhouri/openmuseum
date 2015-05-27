<?php

class DbSearcher{

	private $log;

    function DbSearcher(){
        $this->log = 'DbSearcher';
    }

    function search($db, $searchData){

        $sql;

        /* Default search*/
        if (!empty($searchData->txtDefault) {
            $search = $searchData->txtDefault;
            $sql = "SELECT DISTINCT pictures.name 
                    FROM pictures, artists, museums, keywords, pictures_keywords, owners
                    WHERE 
                        UPPER(pictures.name) LIKE UPPER('%$search%') OR 
                        CONTAINS(pictures.description, '$search') > 0 OR
                    (
                        pictures.artist_fk = artists.artist_id AND
                        concat(concat(UPPER(artists.firstname), ' '), UPPER(artists.firstname)) LIKE UPPER('%$search%')
                    ) OR
                    (
                        pictures.museum_ownes_fk = museums.museum_id AND
                        UPPER(museums.name) LIKE UPPER('%$search%')
                    ) OR 
                    (
                        pictures.museum_exhibits_fk = museums.museum_id AND
                        UPPER(museums.name) LIKE UPPER('%$search%')
                    ) OR
                    (
                        pictures.picture_id = pictures_keywords.picture_fk AND
                        keywords.keyword_id = pictures_keywords.picture_fk AND
                        UPPER(keywords.title) LIKE UPPER('%$search%')
                    ) OR
                    (
                        pictures.owner_fk = owners.owner_id AND
                        concat(concat(UPPER(owners.firstname), ' '), UPPER(owners.firstname)) LIKE UPPER('%$search%')
                        )"
        }

        executeSql($db, $sql);
    }

    function executeSql($db, $sql){

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