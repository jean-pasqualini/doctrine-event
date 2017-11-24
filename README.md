##### DEMO DOCTRINE EVENT

[![Build Status](https://travis-ci.org/jean-pasqualini/doctrine-event.svg?branch=master)](https://travis-ci.org/jean-pasqualini/doctrine-event)

###### Doctrine event reference

- http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html

```
Le DQL et le SQL manuel ne déclanche aucun de ces events

preRemove - Dès qu'une entité à été marqué pour suppression par preRemove
! Restriction Pas CLAIR (voir doc)
postRemove - Après l'instruction SQL DELETE de chaque entité mais bien avant toute instruction SQL COMMIT
+ Pour toutes opérations qui ne modifie pas les entités (logging, webservice, session, ...)
! Ne pas éffectué d'opération de modification des entité
prePersist - Dès qu'une entité à été marqué pour persistance et uniquement si elle n'existe pas encore en BDD
- La clé primaire n'est pas encore généré
- Les modifications apporté aux relations n'y sont pas reconnu (ajout, remplacement, suppression)
postPersist - Après l'instruction SQL INSERT de chaque enité mais bien avant toute instruction SQL COMMIT
! Ne pas éffectué d'opération de modification des entité
+ La clé primaire généré par la BDD est disponible
+ Pour toutes opérations qui ne modifie pas les entités (logging, webservice, session, ...)
preUpdate - Après l'event onFlush et l'instruction SQL 'START TRANSACTION' seulement si le changeset calculé n'est pas vide
! Le changeset est déjà calculé
! Attention très restrictif car appelé l'ors du flush
! Ne pas modifier les relations dans un preUpdate
! Les modifications apportée aux relations ne sont plus reconnu
! L'appel à persist ou remove est fortement déconseillé
+ Il est possible de changer la valeur d'un primitif uniquement via $event->setNewValue('property', 'value');
L'événement preUpdate se produit avant les opérations de mise à jour de la base de données vers les données d'entité. Il n'est pas appelé pour une instruction DQL UPDATE ni quand le changeset calculé est vide.
postUpdate - Après l'instruction SQL UPDATE de chaque entité mais bien avant toute instruction SQL COMMIT
! Ne pas éffectué d'opération de modification des entité
+ Pour toutes opérations qui ne modifie pas les entités (logging, webservice, session, ...)
postLoad - Après qu'une entité ai été chargée depuis la bdd ou qu'un $em->refresh ai été fait sur une entité
! Si l'entité est déjà en mémoire, un simple repository->findAll() ne déclanchera pas l'event
loadClassMetadata - après le chargement des information de mappage des metadata d'une entité
+ Il est possible de changer les informations de mappage a ce moment la
onClassMetadataNotFound - Quand le chargement des métadata d'une classe à échoué
+ Il est possible d'y fournir des métadata
preFlush - Dès l'appel du flush
! n'appeler pas flush ici
+ il est possible d'appeler persist sans risque ici
onFlush - Après le calcul des jeux de modifications de toutes les entité gérée
! L'opération flush ne doit pas y être appelé
- Crée et persister une entité ne suffit pas, il faut calculer les changements avec computeChangeSet
- Changer une entité ou ces association néscéssite de recalculer les changements avec recomputeSingleEntityChangeSet
L'événement onFlush se produit après le calcul des jeux de modifications de toutes les entités gérées. Cet événement n'est pas un rappel de cycle de vie.
postFlush - Après l'opération SQL COMMIT
! L'opération flush ne doit pas y être appelé
- Toute opération de bdd à été flush
+ Pour toute opération après le flush qui ne modifie pas les entité
onClear - après l'appel à $em->clear(), une fois que tous les références ont été détaché
```

```
Je veux crée une entitié dans un event doctrine
```