-- Suppression des chats adoptés ou disparus des FA.
DELETE fp_fa_cat facat 
  FROM fp_fa_cat facat, fp_cat_fiche chat
  WHERE chat.id = facat.idChat 
        AND (chat.adopte = 1 OR chat.disparu = 1)
;
  
-- Statut active
UPDATE fp_fa_fiche fa, fp_fa_cat facat 
  -- Active
  SET fa.idStatut = 1
  WHERE fa.id = facat.idFa;

-- Statut disponible
UPDATE fp_fa_fiche fa
  LEFT JOIN fp_fa_cat facat ON fa.id = facat.idFa
  SET fa.idStatut = 3
  WHERE fa.idStatut = 1
  AND facat.idFa IS NULL;

-- Mise à jour des statuts si indisponibilité en cours
UPDATE fp_fa_indispo indispo 
  -- En cours
  SET indispo.idStatut = 2
  WHERE indispo.dateDeb < NOW() 
    AND NOW() < indispo.dateFin;

UPDATE fp_fa_indispo indispo 
  -- Terminée
  SET indispo.idStatut = 3
  WHERE NOW() > indispo.dateFin;
    
-- Mise à jour des statuts FA si indisponibilité en cours
UPDATE fp_fa_fiche fa, fp_fa_indispo indispo 
  -- Indisponible
  SET fa.idStatut = 5
  WHERE fa.id = indispo.idFa
    AND fa.idStatut <> 2 AND fa.idStatut <> 4
    AND indispo.idStatut = 2;

-- Mise à jour des statuts FA si aucune indispo en cours
UPDATE fp_fa_fiche fa
 LEFT JOIN   fp_fa_indispo indispo ON fa.id = indispo.idFa
    SET fa.idStatut = 3
  WHERE fa.idStatut = 5
    AND (indispo.idStatut <> 2
    OR indispo.idFa IS NULL);

-- Maj login de la FA
update fp_fa_fiche fa, `phpbb_users` user 
  set fa.login=user.username  
  where user.user_email = fa.email 
        and fa.email is not null 
        and (fa.login is null or fa.login = '')
        and fa.email <> '';
        
-- Maj login adoptant
update fp_ad_fiche ad, `phpbb_users` user 
  set ad.login=user.username  
  where user.user_email = ad.email 
        and ad.email is not null 
        and (ad.login is null or ad.login = '')
        and ad.email <> '';
        