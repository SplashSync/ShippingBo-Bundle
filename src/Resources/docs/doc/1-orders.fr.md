---
lang: fr
permalink: doc/orders
title: Commandes
description: Envoi des commandes vers ShippingBo, correspondance des statuts, validation, annulation, suivi colis et limites.
updated: 2026-09-24
---

### Sens de synchronisation

| Opération | Comportement par défaut |
|---|---|
| Création d'une commande dans Splash | **Envoyée** à ShippingBo |
| Modification dans ShippingBo | **Remontée** vers Splash, en temps réel grâce aux webhooks |
| Commande créée directement dans ShippingBo | **Non importée** |
| Suppression | **Jamais répercutée** dans ShippingBo |

Avant l'envoi, chaque nouvelle commande passe par les filtres de date, d'origine et de
transporteur décrits dans la page **Options du connecteur**.

Une commande écartée par un filtre est marquée comme **rejetée** : Splash la considère comme
traitée et ne tentera plus de l'envoyer.

### Correspondance des statuts

Le statut ShippingBo est converti en statut Splash, compris par toutes vos autres applications :

| Statut ShippingBo | Statut Splash |
|---|---|
| `waiting_for_payment` | En attente de paiement |
| `waiting_for_stock` | En rupture de stock |
| `to_be_prepared`, `merged`, `sent_to_logistics`, `dispatched`, `splitted`, `in_preparation` | En cours de traitement |
| `partially_shipped`, `shipped`, `handed_to_carrier` | En transit |
| `at_pickup_location` | Disponible en point relais |
| `closed` | Livrée |
| `back_from_client` | Retournée |
| `rejected`, `canceled` | Annulée |
| `in_trouble` | Inconnu |

### Valider une commande

Quand une commande est validée dans votre boutique, Splash la passe à l'état **À préparer**
(`to_be_prepared`) dans ShippingBo.

La validation n'est acceptée que depuis ces états : `in_trouble`, `waiting_for_payment`,
`waiting_for_stock`, `rejected` et `canceled`. Depuis tout autre état, elle est ignorée et un
avertissement apparaît dans vos journaux Splash.

### Annuler une commande

Quand une commande est annulée dans votre boutique, Splash la passe à l'état **Annulée**
(`canceled`) dans ShippingBo.

L'annulation n'est acceptée que tant que la commande n'est pas partie en préparation : depuis
`in_trouble`, `waiting_for_payment`, `waiting_for_stock`, `to_be_prepared`, `dispatched` et
`rejected`. Au-delà, elle est ignorée avec un avertissement.

> [!IMPORTANT]
> Une commande déjà en préparation ou expédiée **ne peut pas être annulée par Splash**. Traitez-la
> directement dans ShippingBo.

### Modifier les lignes d'une commande

Les lignes (produits, quantités, prix) ne peuvent être modifiées que si la commande est dans l'un
de ces états : `in_trouble`, `waiting_for_payment`, `waiting_for_stock`, `to_be_prepared`,
`rejected` ou `canceled`.

Dans les autres cas, les lignes sont laissées intactes et une erreur
*Update of Order Items not allowed* est journalisée ; le reste de la commande est tout de même mis
à jour.

### Suivi colis

Dès qu'une expédition est créée dans ShippingBo, ces informations remontent vers Splash :

| Champ | Contenu |
|---|---|
| **Transporteur** | Nom du transporteur |
| **Numéro de suivi** | Référence du colis chez le transporteur |
| **URL de suivi** | Lien de suivi à transmettre au client |

### Forcer la livraison

Le champ **Force Delivered** permet de clôturer une commande depuis Splash, sans passer par le flux
logistique de ShippingBo. Il est réservé à la **gestion de stock avancée**.

Quand il est activé :

1. la commande doit être **validée** et **pas déjà livrée** ;
2. tous ses articles doivent être présents dans les **emplacements d'entrepôt par défaut**
   (voir **Options du connecteur**) ;
3. le stock de chaque article y est décrémenté, puis la commande passe à l'état `closed`.

Si un seul article manque dans les emplacements par défaut, **rien n'est modifié** : ni les stocks,
ni l'état de la commande.

### Prix et montants

ShippingBo stocke les montants en **centimes**. Splash convertit chaque prix en l'arrondissant au
centime le plus proche. Le montant d'une ligne correspond au prix unitaire multiplié par la
quantité.

> [!WARNING]
> ShippingBo n'accepte pas de montant supérieur à **21 474 836,47** (en valeur absolue). Au-delà,
> Splash plafonne le montant pour que la commande soit tout de même acceptée : la ligne concernée
> est alors envoyée avec un montant **tronqué**. Cela ne se produit qu'avec des quantités ou des
> prix anormaux, par exemple une quantité exprimée en centimètres.

### Adresses de livraison

L'adresse de livraison d'une commande est mise à jour dans ShippingBo en même temps que la
commande. Voir la page **Adresses** pour le détail.
