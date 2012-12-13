-- Update topic_id
UPDATE fp_cat_fiche set topic_id = SUBSTRING(SUBSTRING_INDEX(SUBSTRING(topic FROM LOCATE('t=', topic)), '&', 1), 3)
  where topic_id is null;

-- Update chats adoptes
update fp_cat_fiche cf, phpbb_topics t, phpbb_forums f set cf.adopte=1, cf.reserve=0, cf.parrain=0, cf.disparu=0 where cf.topic_id = t.topic_id and t.forum_id = f.forum_id and (f.forum_id = 11 or f.parent_id = 11);

-- Update chats reserves
update fp_cat_fiche cf, phpbb_topics t set cf.adopte=0, cf.reserve=1, cf.disparu=0 where cf.topic_id = t.topic_id and t.forum_id = 54;

-- Update chats disparus
update fp_cat_fiche cf, phpbb_topics t set cf.adopte=0, cf.reserve=0, cf.parrain=0, cf.disparu=1 where cf.topic_id = t.topic_id and t.forum_id = 12;

-- Update chats a l'adoption
update fp_cat_fiche cf, phpbb_topics t set cf.adopte=0, cf.reserve=0, cf.disparu=0 where cf.topic_id = t.topic_id and t.forum_id = 10;

-- Update date d'adoption (date de déplacement du sujet)
update fp_cat_fiche fiche set fiche.date_adoption = 
 (select max(from_unixtime(log.log_time)) 
  from phpbb_log log, phpbb_forums f 
  where fiche.topic_id = log.topic_id
        and log.forum_id = f.forum_id
        and (f.forum_id = 11 or f.parent_id = 11)
        and log.log_operation = "LOG_MOVE" 
        group by log.topic_id)
  where fiche.date_adoption is null
      and fiche.adopte = 1;
      
-- Création des nouvelles fiches
insert into fp_cat_fiche (nom, idSexe, race, idCouleur, yeux, tests, vaccins, tatouage, caractere, commentaires, miniature, topic, topic_id, post_id, to_check )
  select TRIM(substring_index( result.subject, '-', 1 )) AS NOM,
          CAST( result.text REGEXP '^(.)*[S|s]exe(.)*[F|f]emelle' AS CHAR ) +1 AS SEXE,
          'Type européen',
          1,
          '',
          '',
          '',
          '',
          '',
          '',
          '',
          CONCAT('http://www.felinpossible.fr/forum/viewtopic.php?f=10&t=', result.topic_id) AS TOPIC,
          result.topic_id,
          result.post_id,
          1 as TO_CHECK
  FROM (
    SELECT post.post_id, post.post_subject AS subject, post.post_text AS text, post.topic_id AS topic_id
    FROM phpbb_posts post, phpbb_topics topic
    LEFT JOIN fp_cat_fiche fiche ON fiche.topic_id = topic.topic_id
    WHERE fiche.id IS NULL
      AND (topic.forum_id = 10 or topic.forum_id = 54 or topic.forum_id = 108 or topic.forum_id = 66)
      AND post.topic_id = topic.topic_id
      AND topic.topic_type = 0
    ORDER BY post.post_id
  ) result
  GROUP BY result.topic_id
