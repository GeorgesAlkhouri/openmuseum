<?php

include_once 'dbhelper/DbIdFetcher.php';
include_once 'dbhelper/DbInserter.php';
include_once 'dbhelper/DbTableCreator.php';
include_once 'dbhelper/DbSearcher.php';
include_once 'dbhelper/DbImageRetriever.php';
include_once 'dbhelper/DbImageUploader.php';
include_once 'Auth.php';

final class DbManager
{
    
    private $db;

    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new DbManager();
        }
        return $inst;
    }

    private function __construct()
    {
        if ($this->db = oci_connect(Auth::$stUser, Auth::$stPW, '//ora10glv.imn.htwk-leipzig.de:1521/ora10glv')) {
            echo "dbconnection.php - connection to database succeded <br />";
        } 
        else {
            die("connection failed");
        }
        
        $tableCreator = new DbTableCreator();
        $tableCreator->createTablesIfNeeded($this->db);
    }

    
    function insertUserDataInDb($picture, $artist, $museum, $owner, $keywords, $category_ids) {
        
        $dbInserter = new DbInserter();
        $idFetcher = new DbIdFetcher();

        /********** ARTIST ****************/
        $dbInserter->insertArtistIfDoesNotExists($this->db, $artist);
        $picture->artist_fk = $idFetcher->fetchArtistId($this->db, $artist);
        
        /********** OWNER ****************/
        $dbInserter->insertOwnerIfDoesNotExists($this->db, $owner);
        $picture->owner_fk = $idFetcher->fetchOwnerId($this->db, $owner);
        
        /********** MUSEUM ****************/
        if ($museum->isExhibitor) {
            $dbInserter->insertMuseumExhibitsIfNotExists($this->db, $museum);
            $picture->museum_exhibits_fk = $idFetcher->fetchMuseumId($this->db, $museum);
        }
        if ($museum->isOwner) {
            $dbInserter->insertMuseumOwnsIfNotExists($this->db, $museum);
            $picture->museum_owns_fk = $idFetcher->fetchMuseumId($this->db, $museum);
        }

        /********** KEYWORDS ****************/
        $keyword_ids = array();
        foreach ($keywords as $keyword_title) {
            $dbInserter->insertKeyWordIfNotExists($this->db, $keyword_title);
            $keyword_id = $idFetcher->fetchKeywordId($this->db, $keyword_title);
            array_push($keyword_ids, $keyword_id);
        }

        /********** CATEGORIES ****************/
        $dbInserter->insertCategoriesIfNeeded($this->db);

        /********** PICTURE ****************/
        $dbInserter->insertPictureIfNotExists($this->db, $picture);
        $picture->id = $idFetcher->fetchPictureId($this->db, $picture);

        /********** PICTURES_CATEGORIES ****************/
        $dbInserter->insertPictureCategoriesIfNotExists($this->db, $picture->id, $category_ids);

        /********** PICTURES_KEYWORDS ****************/
        $dbInserter->insertPictureKeywordsIfNotExists($this->db, $picture->id, $keyword_ids);
    }

    /*
    searchData is an instance of SearchData class
    return an array of DiplayPicture objects
    */
    function searchDetails($searchData){

        $dbSearcher = new DbSearcher();
        return $dbSearcher->searchDetails($this->db, $searchData);
    }

    function searchAll($searchData){

        $dbSearcher = new DbSearcher();
        return $dbSearcher->searchAll($this->db, $searchData);
    }

    /*
    comparePicture is an instance of ComparePicture class
    return an array of DiplayPicture objects
    */
    function compare($comparePicture){

        $dbSearcher = new DbSearcher();
        $dbSearcher->compare($this->db, $comparePicture);
    }
}
?>
