LOAD DATA
CHARACTERSET UTF8
  INFILE *
  APPEND
  INTO TABLE KEYWORDS
  REENABLE
  FIELDS TERMINATED BY ","
  (KEYWORD_ID, TITLE)
BEGINDATA
1,Frau
2,Gesicht
3,Lachen
4,Weiß
5,Nacht
6,Haus
7,Mann
8,Mensch
9,Gericht
