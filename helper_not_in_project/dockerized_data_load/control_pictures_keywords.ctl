LOAD DATA
  INFILE *
  APPEND
  INTO TABLE PICTURES_KEYWORDS
  FIELDS TERMINATED BY ","
  (
    PICKEY_ID "PICTURES_KEYWORDS_SEQ.NEXTVAL",
    PICTURE_FK,
    KEYWORD_FK
  )
BEGINDATA
,1,1
,1,2
,1,3
,2,4
,2,5
,2,6
,2,8
,3,7
,3,8
,4,8
,4,9
,5,10
,6,10
,7,10
,8,11
,9,11
,10,11
,11,6
,12,6
,13,6
,14,6
,14,11
,15,12
,16,6
,17,12