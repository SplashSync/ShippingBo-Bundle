---
lang: fr
permalink: configure/options
title: Options du connecteur
description: Fuseau horaire, filtres de commandes et de produits, emplacements d'entrepôt et champs additionnels.
updated: 2026-09-24
---

### Connexion

| Option | Rôle |
|---|---|
| **API User / Account ID** | Identifiant de compte ShippingBo, ou e-mail de l'utilisateur API. Obligatoire |
| **API Key / Token** | Clé API ShippingBo. Obligatoire, à garder confidentielle |
| **Fuseau horaire des dates** | Fuseau utilisé pour interpréter les dates échangées avec ShippingBo |
| **Méthode d'expédition par défaut** | Champ obligatoire : sélectionnez l'une des méthodes d'expédition de votre compte ShippingBo, dont la liste est chargée automatiquement |

### Filtrer les commandes

Les filtres s'appliquent **uniquement à la création** d'une commande dans ShippingBo. Une commande
écartée n'est pas envoyée ; une commande déjà présente dans ShippingBo n'est jamais retirée par un
filtre.

#### Date de création minimale

Les commandes dont la **date de création d'origine** est antérieure à cette date ne sont pas
envoyées à ShippingBo. Pratique pour démarrer la synchronisation sans reprendre l'historique.

> [!WARNING]
> Dès qu'une date minimale est définie, une commande qui arrive **sans date de création
> d'origine** est elle aussi écartée.

#### Origines de commandes connues

Associez un nom d'origine (le canal de vente, par exemple le nom d'une boutique ou d'une
marketplace) à une action :

| Action | Effet |
|---|---|
| **Envoyer à ShippingBo** | La commande est envoyée normalement |
| **REJETÉ** | La commande n'est pas envoyée |

Règles appliquées :

- **Liste vide** : toutes les commandes sont envoyées, quelle que soit leur origine.
- **Origine non listée** : la commande est envoyée.
- **Commande sans origine**, alors que la liste n'est pas vide : la commande est écartée.
- Le nom doit correspondre **exactement** à l'origine reçue, majuscules comprises. Seuls les
  espaces en début et en fin sont ignorés.

#### Transporteurs

Il est possible d'écarter les commandes d'un transporteur donné, ou de renommer un transporteur
avant l'envoi à ShippingBo. Ce réglage n'est pas accessible depuis votre espace :
**contactez le support Splash** pour le mettre en place.

### Filtrer les produits

#### Filtre fournisseurs

Associez un code fournisseur à une action :

| Action | Effet |
|---|---|
| **Synchroniser** | Les produits de ce fournisseur sont synchronisés |
| **Bloquer** | Les produits de ce fournisseur ne sont ni créés ni mis à jour dans ShippingBo |

Seuls les fournisseurs marqués **Bloquer** sont exclus : un fournisseur absent de la liste reste
synchronisé. La comparaison ignore les majuscules et les espaces en début et en fin.

> [!NOTE]
> Un produit bloqué à la **création** remonte une erreur dans vos journaux Splash. À la **mise à
> jour**, il est simplement ignoré, sans erreur.

#### Champs additionnels produits

Déclarez ici les champs additionnels que vous avez créés sur vos produits ShippingBo, avec leur
format :

| Format | Usage |
|---|---|
| **Booléen** | Case à cocher, oui / non |
| **Texte** | Valeur libre |

Chaque champ déclaré devient disponible dans Splash et peut être synchronisé comme n'importe quel
autre champ produit. Le nom doit être identique à celui défini dans ShippingBo.

#### Forcer le nombre de produits

Impose le nombre total de produits annoncé lorsque Splash lit la liste des produits.
À ne renseigner que sur indication du support.

### Emplacements d'entrepôt

Cette option ne concerne que la **gestion de stock avancée** de ShippingBo. Chargez d'abord vos
emplacements depuis le bloc **Warehouse Slots** (voir **Installer le connecteur**).

| Option | Rôle |
|---|---|
| **Emplacements d'entrepôt par défaut** | Emplacements dont le stock est décrémenté quand Splash clôture manuellement une commande. Voir « Forcer la livraison » dans la page **Commandes** |
