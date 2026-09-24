---
lang: fr
permalink: doc/supplier-orders
title: Commandes fournisseur
description: Réapprovisionnements ShippingBo, correspondance des états et modification des lignes.
updated: 2026-09-24
---

### Présentation

Les commandes fournisseur représentent vos **réapprovisionnements** : les marchandises attendues
de vos fournisseurs et réceptionnées dans votre entrepôt ShippingBo.

| Opération | Comportement par défaut |
|---|---|
| Création dans Splash | **Envoyée** à ShippingBo |
| Modification dans Splash | **Envoyée** à ShippingBo |
| Modification dans ShippingBo | **Remontée** vers Splash |
| Commande fournisseur créée directement dans ShippingBo | **Non importée** |
| Suppression | **Non répercutée** |

### Correspondance des états

| État ShippingBo | Statut Splash |
|---|---|
| Brouillon (`draft`), Envoi en cours (`uploading`) | Brouillon |
| En attente d'enlèvement (`waiting`), Transmise à la logistique (`sent_to_logistics`) | En cours de traitement |
| Expédiée (`dispatched`), En cours (`ongoing`) | En transit |
| Réceptionnée (`received`) | Livrée |
| Annulée (`canceled`) | Annulée |
| En anomalie (`in_trouble`) | En erreur |

### Modifier les lignes

Les lignes d'une commande fournisseur (produits et quantités attendues) ne peuvent être modifiées
que tant qu'elle n'est pas partie : états **Brouillon**, **Envoi en cours**,
**En attente d'enlèvement** ou **En anomalie**.

Au-delà, les lignes sont laissées intactes et une erreur
*Update of Supply Capsule Items not allowed* est journalisée.
