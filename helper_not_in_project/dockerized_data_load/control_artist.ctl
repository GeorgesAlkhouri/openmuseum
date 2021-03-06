LOAD DATA
CHARACTERSET UTF8
  INFILE *
  APPEND
  INTO TABLE ARTISTS
  REENABLE
  FIELDS TERMINATED BY ","
  (ARTIST_ID "ARTISTS_SEQ.NEXTVAL",
  FIRSTNAME, LASTNAME,
  BIRTH_DATE DATE 'dd.mm.yyyy',
  DEATH_DATE DATE 'dd.mm.yyyy')
BEGINDATA
,Leonardo,Da Vinci,01.01.1452,01.01.1519
,Vincent,van Gogh,01.01.1853,01.01.1890
,Michelangelo,Buonarroti,01.01.1475,01.01.1564
,Paula,Landscapa,01.01.1900,01.01.2000
,Brick, Maker,01.01.1900,01.01.2000
,Hans, Hausbauer,01.01.1900,01.01.2000
,Paul, Cézanne,01.01.1452,01.01.1519
