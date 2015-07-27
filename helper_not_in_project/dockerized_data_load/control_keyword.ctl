LOAD DATA
CHARACTERSET UTF8
  INFILE *
  APPEND
  INTO TABLE KEYWORDS
  REENABLE
  FIELDS TERMINATED BY ","
  (KEYWORD_ID "KEYWORDS_SEQ.NEXTVAL",
   TITLE)
BEGINDATA
,Frau
,Gesicht
,Lachen
,Weiß
,Nacht
,Haus
,Mann
,Mensch
,Gericht
,Landschaft
,Ziegel
,Stillleben
