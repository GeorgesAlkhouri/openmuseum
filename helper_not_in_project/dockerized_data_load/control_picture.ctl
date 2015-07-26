LOAD DATA
CHARACTERSET UTF8
  INFILE *
  APPEND
  INTO TABLE PICTURES
  FIELDS TERMINATED BY "," OPTIONALLY ENCLOSED BY '"'
  (
    PICTURE_ID "PICTURES_SEQ.NEXTVAL",
    NAME,
    CREATION_DATE DATE 'dd.mm.yyyy',
    UPLOAD_DATE DATE 'dd.mm.yyyy',
    DESCRIPTION,
    IMAGE COLUMN OBJECT
    (
      SOURCE COLUMN OBJECT
      (
        localData_fname FILLER CHAR(128),
        localData LOBFILE(image.source.localData_fname) terminated by EOF
      )
    ),
    IMAGE_SIG FILLER,
    ARTIST_FK,
    ARTIST_SAFETY_LEVEL,
    MUSEUM_OWNES_FK,
    MUSEUM_EXHIBITS_FK,
    MUSEUM_EXHIBITS_STARTDATE DATE 'dd.mm.yyyy',
    MUSEUM_EXHIBITS_ENDDATE DATE 'dd.mm.yyyy',
    OWNER_FK
  )
BEGINDATA
,Mona Lisa,01.01.1503,22.01.1990,"Mona Lisa ist ein weltberühmtes Ölgemälde von Leonardo da Vinci. Das auf Italienisch als La Gioconda (dt. die Heitere) bekannte Bild wurde vermutlich nach der Florentinerin Lisa del Giocondo benannt.",Mona-Lisa.jpg,,1,100,,1,22.01.1990,22.01.1990,1
,Das weiße Haus bei Nacht,01.01.1823,22.01.1990,"Das weiße Haus bei Nacht (französisch: La maison blanche)[1] ist der Titel eines Gemäldes von Vincent van Gogh.",White-House.jpg,,2,100,,2,22.01.1990,22.01.1990,2
,Vitruvianischer Mensch,01.01.1202,22.01.1990,"Als vitruvianischer Mensch (lat. homo vitruvianus, auch: Vitruvianische Figur) wird eine Darstellung des Menschen nach den vom antiken Architekten und Ingenieur Vitruv(ius) formulierten und idealisierten Proportionen bezeichnet.",Vitruve-Man.jpg,,1,100,,3,22.01.1990,22.01.1990,3
,The Last Judgment,01.01.1541,22.01.1990,"The Last Judgment, or The Last Judgement (Italian: Il Giudizio Universale),[1] is a fresco by the Italian Renaissance master Michelangelo executed on the altar wall of the Sistine Chapel in Vatican City.",The-Last-Judgment.jpg,,3,100,,1,22.01.1990,22.01.1990,4
