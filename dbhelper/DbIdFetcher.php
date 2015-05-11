<?php
class DbIdFetcher
{
    private $log;

    function DbIdFetcher(){
        $this->log = 'DbIdFetcher';
    }
    
    /******************* Fetch ids *********************/
    
    function fetchArtistId($db, $artist) {
        
        $sql = "SELECT artist_id, firstname, lastname FROM artists WHERE firstname = '$artist->firstname' AND lastname = '$artist->lastname'";
        return $this->getId($db, $sql, 'artist_id');
    }
    
    function fetchOwnerId($db, $owner) {
        
        $sql = "SELECT owner_id, firstname, lastname FROM owners WHERE firstname = '$owner->firstname' AND lastname = '$owner->lastname'";
        return $this->getId($db, $sql, 'owner_id');
    }
    
    function fetchMuseumId($db, $museum) {
        
        $sql = "SELECT museum_id, name FROM museums WHERE name = '$museum->name'";
        return $this->getId($db, $sql, 'museum_id');
    }
    
    function fetchKeywordId($db, $title) {
        
        $sql = "SELECT keyword_id, title FROM keywords WHERE title = '$title'";
        return $this->getId($db, $sql, 'keyword_id');
    }
    
    function fetchCategoryId($db, $title) {
        
        $sql = "SELECT category_id FROM categories WHERE title = '$title'";
        return $this->getId($db, $sql, 'category_id');
    }
    
    function fetchPictureId($db, $picture) {
        
        $sql = "SELECT picture_id FROM pictures WHERE name = '$picture->name'";
        return $this->getId($db, $sql, 'picture_id');
    }
    
    function fetchPicCatId($db, $picture_id, $category_id) {
        
        $sql = "SELECT piccat_id FROM pictures_categories WHERE picture_fk = $picture_id AND category_fk = $category_id";
        return $this->getId($db, $sql, 'piccat_id');
    }
    
    function fetchPicKeyId($db, $picture_id, $keyword_id) {
        
        $sql = "SELECT pickey_id FROM pictures_keywords WHERE picture_fk = $picture_id AND keyword_fk = $keyword_id";
        return $this->getId($db, $sql, 'pickey_id');
    }
    
    function getId($db, $sql, $idName) {
        
        echo "$this->log - $sql <br />";
        $idName = strtoupper($idName);
        $stid = oci_parse($db, $sql);
        oci_execute($stid);
        $id = NULL;
        while (oci_fetch($stid)) {
            
            //echo "dbfunctions.php - oci_result for $idName: " . oci_result($stid, $idName) . '<br />';
            $id = oci_result($stid, $idName);
        }
        oci_free_statement($stid);
        
        return $id;
    }
}
?>