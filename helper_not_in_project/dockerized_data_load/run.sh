#!/bin/bash

SQLLDR=/u01/app/oracle/product/11.2.0/xe/bin/sqlldr
SQLPLUS=/u01/app/oracle/product/11.2.0/xe/bin/sqlplus

echo "@create_tables.sql" | $SQLPLUS $dbuser/$dbpwd@HTWK

$SQLLDR -userid=$dbuser/$dbpwd@HTWK -control=control_artist.ctl
$SQLLDR -userid=$dbuser/$dbpwd@HTWK -control=control_category.ctl
$SQLLDR -userid=$dbuser/$dbpwd@HTWK -control=control_keyword.ctl
$SQLLDR -userid=$dbuser/$dbpwd@HTWK -control=control_museum.ctl
$SQLLDR -userid=$dbuser/$dbpwd@HTWK -control=control_owner.ctl

$SQLLDR -userid=$dbuser/$dbpwd@HTWK -control=control_picture.ctl

$SQLLDR -userid=$dbuser/$dbpwd@HTWK -control=control_pictures_categories.ctl
$SQLLDR -userid=$dbuser/$dbpwd@HTWK -control=control_pictures_keywords.ctl

touch create_signatures.sql

for i in `seq 1 17`;
  do
    echo "UPDATE PICTURES SET image_sig=ORDSYS.ORDImageSignature.init() WHERE picture_id=$i;" >> create_signatures.sql
    echo "DECLARE imageobj ordsys.ordimage;" >> create_signatures.sql
    echo "image_sigobj ordsys.ordimagesignature;" >> create_signatures.sql
    echo "BEGIN SELECT image, image_sig INTO imageobj, image_sigobj FROM pictures WHERE picture_id = $i FOR UPDATE;" >> create_signatures.sql
    echo "image_sigobj.Generatesignature(imageobj);" >> create_signatures.sql
    echo "UPDATE pictures SET image_sig = image_sigobj WHERE picture_id = $i;" >> create_signatures.sql
    echo "COMMIT;" >> create_signatures.sql
    echo "END;" >> create_signatures.sql
    echo "/" >> create_signatures.sql
done

echo "@create_signatures.sql" | $SQLPLUS $dbuser/$dbpwd@HTWK
