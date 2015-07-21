<?php
class DbTableCreator
{
    private $log;

    function DbTableCreator(){
        $this->log = 'DbTableCreator';
    }

    public function createTablesIfNeeded($db) {

        $this->createArtistsTableIfNeeded($db);
        $this->createOwnersTableIfNeeded($db);
        $this->createKeywordsTableIfNeeded($db);
        $this->createMuseumsTableIfNeeded($db);
        $this->createCategoriesTableIfNeeded($db);
        $this->createPicturesTableIfNeeded($db);
        $this->createPicturesCategoriesTableIfNeeded($db);
        $this->createPicturesKeywordsTableIfNeeded($db);
        $this->createComparisonImagesTableIfNeeded($db);
    }

    function createPicturesTableIfNeeded($db) {

        $table = 'pictures';
        $index = 'description_indx';

        $table = strtoupper($table);
        if (!$this->tableExists($db, $table)) {

            $sql = "CREATE TABLE $table (
                picture_id int PRIMARY KEY,
                name VARCHAR2(50) NOT NULL,
                creation_date DATE,
                upload_date DATE,
                description VARCHAR2(1000),
                image ORDSYS.ORDImage,
                image_sig  ORDSYS.ORDImageSignature,
                artist_fk int REFERENCES artists(artist_id),
                artist_safety_level int,
                museum_ownes_fk int REFERENCES museums(museum_id),
                museum_exhibits_fk int REFERENCES museums(museum_id),
                museum_exhibits_startdate date,
                museum_exhibits_enddate date,
                owner_fk int REFERENCES owners(owner_id)
                )";
            $this->executeSql($db, $sql);

            $this->createSequence($db, $table . "_seq");
        }

        $index = strtoupper($index);
        if (!$this->indexExists($db, $index)) {

            //create index for description
            $sql = "begin
                        ctx_ddl.create_preference('mylexer', 'BASIC_LEXER' );
                        ctx_ddl.set_attribute ( 'mylexer', 'mixed_case', 'NO' );
                    end;";

            echo "$this->log - $sql <br />";

            $stmt = oci_parse($db, $sql);
            oci_execute($stmt, OCI_NO_AUTO_COMMIT);

            $sql = "begin
                        ctx_ddl.create_preference('mystore', 'BASIC_STORAGE');
                       ctx_ddl.set_attribute('mystore', 'I_TABLE_CLAUSE', 'tablespace INDX');
                       ctx_ddl.set_attribute('mystore', 'K_TABLE_CLAUSE', 'tablespace INDX');
                       ctx_ddl.set_attribute('mystore', 'R_TABLE_CLAUSE', 'tablespace INDX');
                       ctx_ddl.set_attribute('mystore', 'N_TABLE_CLAUSE', 'tablespace INDX');
                       ctx_ddl.set_attribute('mystore', 'I_INDEX_CLAUSE', 'tablespace INDX');
                       ctx_ddl.set_attribute('mystore', 'P_TABLE_CLAUSE', 'tablespace INDX');
                    end;";

            echo "$this->log - $sql <br />";

            $stmt = oci_parse($db, $sql);
            oci_execute($stmt, OCI_NO_AUTO_COMMIT);

            $sql = "CREATE INDEX myIndex ON PICTURES ( DESCRIPTION )
                       INDEXTYPE IS CTXSYS.CONTEXT
                       PARAMETERS ( 'LEXER mylexer STORAGE mystore SYNC (ON COMMIT)' )";

            echo "$this->log - $sql <br />";
            $stmt = oci_parse($db, $sql);
            oci_execute($stmt, OCI_NO_AUTO_COMMIT);
            oci_commit($db);
        }
    }

    function createComparisonImagesTableIfNeeded($db){

        $table = 'comparison_pictures';
        $table = strtoupper($table);

        if (!$this->tableExists($db, $table)) {

            $sql = "CREATE TABLE $table (
                comparison_picture_id int PRIMARY KEY,
                image ORDSYS.ORDImage,
                image_sig  ORDSYS.ORDImageSignature
                )";
            $this->executeSql($db, $sql);

            $this->createSequence($db, $table . "_seq");
        }
    }

    function createArtistsTableIfNeeded($db) {

        $table = 'artists';

        $table = strtoupper($table);
        if (!$this->tableExists($db, $table)) {

            $sql = "CREATE TABLE $table (
                artist_id int PRIMARY KEY,
                firstname VARCHAR2(50),
                lastname VARCHAR2(50) NOT NULL,
                birth_date DATE,
                death_date DATE
                )";
            $this->executeSql($db, $sql);

            $this->createSequence($db, $table . "_seq");
        }
    }

    function createOwnersTableIfNeeded($db) {

        $table = 'owners';

        $table = strtoupper($table);
        if (!$this->tableExists($db, $table)) {

            $sql = "CREATE TABLE $table (
                owner_id int PRIMARY KEY,
                firstname VARCHAR2(50),
                lastname VARCHAR2(50) NOT NULL
                )";
            $this->executeSql($db, $sql);

            $this->createSequence($db, $table . "_seq");
        }
    }

    function createKeywordsTableIfNeeded($db) {

        $table = 'keywords';

        $table = strtoupper($table);
        if (!$this->tableExists($db, $table)) {

            $sql = "CREATE TABLE $table (
                keyword_id int PRIMARY KEY,
                title VARCHAR2(30) NOT NULL
                )";
            $this->executeSql($db, $sql);

            $this->createSequence($db, $table . "_seq");
        }
    }

    function createPicturesKeywordsTableIfNeeded($db) {

        $table = 'pictures_keywords';

        $table = strtoupper($table);
        if (!$this->tableExists($db, $table)) {

            $sql = "CREATE TABLE $table (
                pickey_id int PRIMARY KEY,
                picture_fk int REFERENCES pictures(picture_id),
                keyword_fk int REFERENCES keywords(keyword_id)
                )";
            $this->executeSql($db, $sql);

            $this->createSequence($db, $table . "_seq");
        }
    }

    function createMuseumsTableIfNeeded($db) {

        $table = 'museums';

        $table = strtoupper($table);
        if (!$this->tableExists($db, $table)) {

            $sql = "CREATE TABLE $table (
                museum_id int PRIMARY KEY,
                name VARCHAR2(30) NOT NULL,
                website VARCHAR2(50),
                adress VARCHAR2(80)
                )";
            $this->executeSql($db, $sql);

            $this->createSequence($db, $table . "_seq");
        }
    }

    function createCategoriesTableIfNeeded($db) {

        $table = 'categories';

        $table = strtoupper($table);
        if (!$this->tableExists($db, $table)) {

            $sql = "CREATE TABLE $table (
                category_id int PRIMARY KEY,
                title VARCHAR2(30) NOT NULL,
                category_fk int REFERENCES categories(category_id)
                )";
            $this->executeSql($db, $sql);

            $this->createSequence($db, $table . "_seq");
        }
    }

    function createPicturesCategoriesTableIfNeeded($db) {

        $table = 'pictures_categories';

        $table = strtoupper($table);
        if (!$this->tableExists($db, $table)) {

            $sql = "CREATE TABLE $table (
                piccat_id int PRIMARY KEY,
                picture_fk int REFERENCES pictures(picture_id),
                category_fk int REFERENCES categories(category_id)
                )";
            $this->executeSql($db, $sql);

            $this->createSequence($db, $table . "_seq");
        }
    }

    function executeSql($db, $sql) {

        echo "$this->log - $sql <br />";
        $stmt = oci_parse($db, $sql);
        oci_execute($stmt);
    }

    function createSequence($db, $sequence) {

        $sql = "Create sequence $sequence start with 1 increment by 1 minvalue 1 maxvalue 10000000";
        $this->executeSql($db, $sql);
    }

    /******************* Check if table or index exists *********************/

    function indexExists($db, $index) {

        $sql = "SELECT COUNT(*) as count FROM user_indexes WHERE index_name = '$index'";
        return $this->tableExistsForSqlStmt($db, $sql);
    }

    function tableExists($db, $table) {

        $sql = "SELECT COUNT(*) as count FROM user_tables WHERE table_name = '$table'";
        return $this->tableExistsForSqlStmt($db, $sql);
    }

    function tableExistsForSqlStmt($db, $sql) {

        //echo "$this->log - $sql <br />";
        $stmt = oci_parse($db, $sql);
        oci_execute($stmt);

        $count = 0;
        if (oci_fetch($stmt)) {
            $count = oci_result($stmt, 1);
            if ($count == 0) {
                return false;
            }
            else {
                return true;
            }
        }
    }
}
?>
