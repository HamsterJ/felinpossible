ALTER TABLE `fp_cat_fiche` DROP `photos`;
ALTER TABLE fp_cat_fiche ADD (
  notesPrivees text NULL,
  datePriseEnCharge date NULL,
  dateAntiPuces date NULL,
  dateVermifuge date NULL,
  statutVisite tinyint(1) NOT NULL DEFAULT 0,
  visitePostPar text NULL
  );

-- FA statut
CREATE TABLE fp_fa_statut (
  id int NOT NULL auto_increment,
  nom varchar(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE InnoDB;

ALTER TABLE fp_fa_fiche ENGINE = InnoDB;

INSERT INTO fp_fa_statut values(1, 'Active');
INSERT INTO fp_fa_statut values(2, 'Inactive');
INSERT INTO fp_fa_statut values(3, 'Disponible');
INSERT INTO fp_fa_statut values(4, 'Stand by');
INSERT INTO fp_fa_statut values(5, 'Indisponible');
INSERT INTO fp_fa_statut values(6, 'Indésirable');
INSERT INTO fp_fa_statut values(7, 'Candidature');

ALTER TABLE fp_fa_fiche ADD (idStatut int NOT NULL DEFAULT 7);
ALTER TABLE fp_fa_fiche ADD (notes text NULL);
ALTER TABLE fp_fa_fiche ADD (login text NULL);

ALTER TABLE fp_ad_fiche ADD (heureJoignable text NULL);
ALTER TABLE fp_ad_fiche ADD (login text NULL);

ALTER TABLE fp_fa_fiche ADD CONSTRAINT fk_fiche_statut FOREIGN KEY (idStatut) REFERENCES fp_fa_statut(id);
ALTER TABLE fp_fa_fiche ENABLE KEYS;

-- Chats par FA
ALTER TABLE fp_cat_fiche ENGINE = InnoDB;
CREATE TABLE fp_fa_cat(
idFa int NOT NULL ,
idChat int NOT NULL ,
PRIMARY KEY ( idFa, idChat ) ,
CONSTRAINT fk_fa_cat_fa FOREIGN KEY ( idFa ) REFERENCES fp_fa_fiche( id ) ON DELETE CASCADE,
CONSTRAINT fk_fa_cat_cat FOREIGN KEY ( idChat ) REFERENCES fp_cat_fiche( id ) ON DELETE CASCADE,
CONSTRAINT uniq_fa_cat UNIQUE ( idChat )
) ENGINE InnoDB;


-- Indispo statuts
CREATE TABLE fp_fa_indispo_statut (
  id int NOT NULL auto_increment,
  nom varchar(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE InnoDB;


INSERT INTO fp_fa_indispo_statut values(1, 'A venir');
INSERT INTO fp_fa_indispo_statut values(2, 'En cours');
INSERT INTO fp_fa_indispo_statut values(3, 'Terminée');

-- Indispo FA
CREATE TABLE fp_fa_indispo(
id int NOT NULL auto_increment,
idFa int NOT NULL ,
dateDeb date NOT NULL,
dateFin date NOT NULL,
idStatut int NOT NULL,
comment text NULL,
PRIMARY KEY (id),
CONSTRAINT fk_indispo_fa FOREIGN KEY (idFa) REFERENCES fp_fa_fiche(id) ON DELETE CASCADE,
CONSTRAINT fk_statut_indispo_fa FOREIGN KEY (idStatut) REFERENCES fp_fa_indispo_statut(id)
) ENGINE InnoDB;

-- ajout date rappel vaccins
ALTER TABLE fp_cat_fiche ADD (dateRappelVaccins date NULL);



--- 08/11/2009


ALTER TABLE fp_cat_fiche ADD (
  dateTests date NULL,
  dateSterilisation date NULL,
  declCession tinyint(1) NOT NULL DEFAULT 0
  );



-- 01/12/2009
ALTER TABLE fp_fa_fiche ADD (dateSubmit date NULL);
ALTER TABLE fp_ad_fiche ADD (dateSubmit date NULL);

-- 04/12/2009
ALTER TABLE fp_cat_fiche ADD (sterilise int NOT NULL DEFAULT 0);
UPDATE fp_cat_fiche set sterilise = 1 where dateSterilisation is not null;

-- 13/12/2009 - Ajout superficie
ALTER TABLE fp_fa_fiche ADD (superficie text NULL);
ALTER TABLE fp_ad_fiche ADD (superficie text NULL);

-- Requête users 
SELECT users.username, users.user_email, groups.group_name FROM phpbb_users users, phpbb_groups groups 
WHERE users.group_id = groups.group_id and groups.group_id != 6 and groups.group_id != 1 order by username asc


-- Lien adoptant <-> chat
ALTER TABLE fp_ad_fiche ENGINE = InnoDB;
CREATE TABLE fp_ad_cat(
idAd int NOT NULL ,
idChat int NOT NULL ,
PRIMARY KEY ( idAd, idChat ) ,
CONSTRAINT fk_ad_cat_ad FOREIGN KEY ( idAd ) REFERENCES fp_ad_fiche( id ) ON DELETE CASCADE,
CONSTRAINT fk_ad_cat_cat FOREIGN KEY ( idChat ) REFERENCES fp_cat_fiche( id ) ON DELETE CASCADE,
CONSTRAINT uniq_ad_cat UNIQUE ( idChat )
) ENGINE InnoDB;


-- Dates envoi rappels stérilisation + date Contrats
ALTER TABLE fp_cat_fiche ADD (
  dateEnvoiRappelVac date NULL,
  dateEnvoiRappelSte date NULL,
  dateContratAdoption date NULL
)

ALTER TABLE fp_fa_fiche ADD (dateContratFa date NULL);

-- Fiches vétérinaires
CREATE TABLE fp_veto_fiche(
id int NOT NULL auto_increment,
raison text,
adresse text,
cp text,
ville text,
fixe text,
portable text,
email text,
PRIMARY KEY ( id ) 
) ENGINE InnoDB;


-- Ajout dateApproximative
ALTER TABLE fp_cat_fiche ADD (dateApproximative int default 0);

ALTER TABLE fp_cat_fiche ADD (renomme text NOT NULL default "");

-- Ajout papierIdentificationRecu
ALTER TABLE fp_cat_fiche ADD (papierIdRecu int default 0);

-- Date de naissance plus obligatoire
ALTER TABLE fp_cat_fiche CHANGE date date date NULL;

-- Ajout champs pour filtres
ALTER TABLE fp_cat_fiche ADD (
  okChats tinyint(1) NOT NULL DEFAULT 0,
  okChiens tinyint(1) NOT NULL DEFAULT 0,
  okApparts tinyint(1) NOT NULL DEFAULT 0,
  okEnfants tinyint(1) NOT NULL DEFAULT 0
  );

-- Ajout site internet véto
ALTER TABLE fp_veto_fiche ADD (
  siteInternet text
);

-- Ajout changement propriétaire
ALTER TABLE fp_cat_fiche ADD (chgtProprio int default 0);

ALTER TABLE fp_ad_fiche ADD (connaissanceAssoDetail text NULL);
