# openmuseum

## DbManager.php

- db connection (warning: don't commit connection data to git -> use global variables in Auth.php, is on ignore list)
- db helper handling

## includes/dbhelper/

- create all needed tables if they don't exist
- insert new data into tables (handle double objects) (TODO: insert OrdImage and update description index)
- search for data

## classes/model/

- models for entities (e.g. Artist.php, Picture.php )
- models for search data (SearchData.php, ComparisonPicture.php)

## helper_not_in_project/

- delete_all_tables.sql: deletes all tables, indexes, sequences (execute in sql developer)
- drop_preferences.sql: deletes preferences
