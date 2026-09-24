---
lang: fr
permalink: faq/questions
title: Questions fréquentes
description: Réponses aux questions courantes sur le connecteur ShippingBo.
updated: 2026-09-24
---

## Questions fréquentes {.faq}

### Une commande n'a pas été envoyée à ShippingBo, pourquoi ?

Elle a très probablement été écartée par l'un des filtres, appliqués à la création :

- sa **date de création d'origine** est antérieure à la date minimale, ou elle n'a pas de date
  alors qu'une date minimale est définie ;
- son **origine** est marquée REJETÉ, ou elle n'a pas d'origine alors que la liste des origines
  n'est pas vide ;
- son **transporteur** fait l'objet d'un rejet.

Vos journaux Splash indiquent le filtre en cause. Une commande écartée est marquée comme rejetée
et n'est plus renvoyée automatiquement.

### Les changements faits dans ShippingBo ne remontent pas dans Splash

Vérifiez d'abord le bloc **Webhooks Configuration** de votre connexion : il doit afficher
*Webhooks Configuration is OK !* Sinon, cliquez sur **Refresh Webhooks Configuration**.

Sans webhooks valides, ShippingBo ne peut pas prévenir Splash de ses modifications.

### Pourquoi le stock ne se met-il pas à jour dans ShippingBo ?

C'est normal : le stock est **en lecture seule**. ShippingBo gère l'inventaire physique de votre
entrepôt et reste la référence. Le stock remonte de ShippingBo vers vos boutiques, jamais l'inverse.

### Je ne parviens pas à annuler une commande

L'annulation par Splash n'est possible que tant que la commande n'est pas partie en préparation.
Une fois la préparation lancée, annulez-la directement dans ShippingBo.

### Les lignes de ma commande n'ont pas été modifiées

Les lignes ne sont modifiables que dans les premiers états de la commande, avant la préparation.
Au-delà, Splash conserve les lignes existantes et journalise l'erreur
*Update of Order Items not allowed*.

### Un produit n'est pas rattaché à son équivalent ShippingBo

Splash retrouve les produits par leur **référence**. Vérifiez qu'elle est identique des deux côtés
et qu'elle n'est pas partagée par plusieurs produits ShippingBo.

### Où trouver mes identifiants API ShippingBo ?

Ils sont fournis sur demande par ShippingBo. Rapprochez-vous de votre interlocuteur ShippingBo.
