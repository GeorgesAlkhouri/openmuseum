CREATE TABLE ARTISTS (artist_id int PRIMARY KEY, firstname VARCHAR2(50), lastname VARCHAR2(50) NOT NULL, birth_date DATE, death_date DATE);
CREATE sequence ARTISTS_seq
START WITH 1 INCREMENT BY 1 MINVALUE 1 MAXVALUE 10000000;
CREATE TABLE OWNERS (owner_id int PRIMARY KEY, firstname VARCHAR2(50), lastname VARCHAR2(50) NOT NULL);
CREATE sequence OWNERS_seq
START WITH 1 INCREMENT BY 1 MINVALUE 1 MAXVALUE 10000000;
CREATE TABLE KEYWORDS (keyword_id int PRIMARY KEY, title VARCHAR2(30) NOT NULL);
CREATE sequence KEYWORDS_seq
START WITH 1 INCREMENT BY 1 MINVALUE 1 MAXVALUE 10000000;
CREATE TABLE MUSEUMS (museum_id int PRIMARY KEY, name VARCHAR2(30) NOT NULL, website VARCHAR2(50), adress VARCHAR2(80));
CREATE sequence MUSEUMS_seq
START WITH 1 INCREMENT BY 1 MINVALUE 1 MAXVALUE 10000000;
CREATE TABLE CATEGORIES (category_id int PRIMARY KEY, title VARCHAR2(30) NOT NULL, category_fk int REFERENCES categories(category_id));
CREATE sequence CATEGORIES_seq
START WITH 1 INCREMENT BY 1 MINVALUE 1 MAXVALUE 10000000;
CREATE TABLE PICTURES (picture_id int PRIMARY KEY, name VARCHAR2(50) NOT NULL, creation_date DATE, upload_date DATE, description VARCHAR2(1000), image ORDSYS.ORDImage, image_sig ORDSYS.ORDImageSignature, artist_fk int REFERENCES artists(artist_id), artist_safety_level int, museum_ownes_fk int REFERENCES museums(museum_id), museum_exhibits_fk int REFERENCES museums(museum_id), museum_exhibits_startdate date, museum_exhibits_enddate date, owner_fk int REFERENCES owners(owner_id));
CREATE sequence PICTURES_seq
START WITH 1 INCREMENT BY 1 MINVALUE 1 MAXVALUE 10000000;

BEGIN
  ctx_ddl.create_preference('mylexer', 'BASIC_LEXER');
  ctx_ddl.set_attribute ('mylexer', 'mixed_case', 'NO');
END;
/

BEGIN
  ctx_ddl.create_preference('mystore', 'BASIC_STORAGE');
  ctx_ddl.set_attribute('mystore', 'I_TABLE_CLAUSE', 'tablespace INDX');
  ctx_ddl.set_attribute('mystore', 'K_TABLE_CLAUSE', 'tablespace INDX');
  ctx_ddl.set_attribute('mystore', 'R_TABLE_CLAUSE', 'tablespace INDX');
  ctx_ddl.set_attribute('mystore', 'N_TABLE_CLAUSE', 'tablespace INDX');
  ctx_ddl.set_attribute('mystore', 'I_INDEX_CLAUSE', 'tablespace INDX');
  ctx_ddl.set_attribute('mystore', 'P_TABLE_CLAUSE', 'tablespace INDX');
END;
/

CREATE INDEX myIndex ON PICTURES (DESCRIPTION) INDEXTYPE IS CTXSYS.CONTEXT PARAMETERS ('LEXER mylexer STORAGE mystore SYNC (ON COMMIT)');
CREATE TABLE PICTURES_CATEGORIES (piccat_id int PRIMARY KEY, picture_fk int REFERENCES pictures(picture_id), category_fk int REFERENCES categories(category_id));
CREATE sequence PICTURES_CATEGORIES_seq
START WITH 1 INCREMENT BY 1 MINVALUE 1 MAXVALUE 10000000;
CREATE TABLE PICTURES_KEYWORDS (pickey_id int PRIMARY KEY, picture_fk int REFERENCES pictures(picture_id), keyword_fk int REFERENCES keywords(keyword_id));
CREATE sequence PICTURES_KEYWORDS_seq
START WITH 1 INCREMENT BY 1 MINVALUE 1 MAXVALUE 10000000;
CREATE TABLE COMPARISON_PICTURES (comparison_picture_id int PRIMARY KEY, image ORDSYS.ORDImage, image_sig ORDSYS.ORDImageSignature);
CREATE sequence COMPARISON_PICTURES_seq
START WITH 1 INCREMENT BY 1 MINVALUE 1 MAXVALUE 10000000;
