LOAD DATA
CHARACTERSET UTF8
  INFILE *
  APPEND
  INTO TABLE MUSEUMS
  REENABLE
  FIELDS TERMINATED BY ","
  (MUSEUM_ID, NAME, WEBSITE, ADRESS)
BEGINDATA
1,Louvre,http://www.louvre.fr/en,Parisstr. 1a
2,The National Gallery London,http://www.nationalgallery.org.uk,Londonstr. 1a
3,Pergamonmuseum,http://www.pergamonmuseum.de,Berlinstr. 1a
