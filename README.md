# openmuseum

## DbManager.php

- db connection (warning: don't commit connection data to git)
- db helper handling

## dbhelper/

- create all needed tables if they don't exist
- insert new data into tables (handle double objects) (TODO: insert OrdImage and update description index)

## model/

- define needed entities and attributes

## index.php

- hardcoded user data only for testing (TODO: frontend data input and verification)

## helper_not_in_project/

- delete_all_tables.sql: for testing purposes - deletes all tables, indexes, sequences (execute in sql developer)
