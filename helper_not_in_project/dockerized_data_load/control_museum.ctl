LOAD DATA
CHARACTERSET UTF8
  INFILE *
  APPEND
  INTO TABLE MUSEUMS
  REENABLE
  FIELDS TERMINATED BY ","
  (MUSEUM_ID "MUSEUMS_SEQ.NEXTVAL" ,
   NAME, WEBSITE, ADRESS)
BEGINDATA
,Louvre,http://www.louvre.fr/en,Parisstr. 1a
,The National Gallery London,http://www.nationalgallery.org.uk,Londonstr. 1a
,Pergamonmuseum,http://www.pergamonmuseum.de,Berlinstr. 1a
