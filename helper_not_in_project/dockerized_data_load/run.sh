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

for i in `seq 1 4`;
  do
    echo "UPDATE PICTURES SET image_sig=ORDSYS.ORDImageSignature.init() WHERE picture_id=$i;" | $SQLPLUS $dbuser/$dbpwd@HTWK
    echo "DECLARE imageobj ordsys.ordimage; image_sigobj ordsys.ordimagesignature; BEGIN SELECT image, image_sig INTO imageobj, image_sigobj FROM pictures WHERE picture_id = $i FOR UPDATE; image_sigobj.Generatesignature(imageobj); UPDATE pictures SET image_sig = image_sigobj WHERE picture_id = $i; COMMIT; END;" | $SQLPLUS $dbuser/$dbpwd@HTWK
done
