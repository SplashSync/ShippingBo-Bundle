---
lang: fr
permalink: doc/products
title: Produits
description: Identification des produits, stocks en lecture seule, emplacements d'entrepôt, codes-barres et champs additionnels.
updated: 2026-09-24
---

### Sens de synchronisation

| Opération | Comportement par défaut |
|---|---|
| Création d'un produit dans Splash | **Envoyé** à ShippingBo |
| Modification dans Splash | **Envoyée** à ShippingBo |
| Modification dans ShippingBo | **Remontée** vers Splash |
| Produit créé directement dans ShippingBo | **Non importé** |
| Suppression | **Non répercutée** |

Le filtre fournisseurs décrit dans **Options du connecteur** peut exclure les produits de certains
fournisseurs.

### Identification des produits

Chaque produit ShippingBo est identifié par sa **référence** (`user_ref`), qui est obligatoire.
C'est elle que Splash utilise pour retrouver un produit existant et éviter les doublons : veillez
à ce qu'elle soit **unique** et identique dans vos différentes applications.

> [!IMPORTANT]
> Si plusieurs produits ShippingBo partagent la même référence, Splash ne peut pas les départager
> et n'en rattache aucun.

### Principaux champs

| Champ | Contenu |
|---|---|
| **Référence** | Référence unique du produit. Obligatoire |
| **Titre** | Libellé du produit |
| **EAN13** | Code-barres principal |
| **Multi-Ean** | Liste des codes-barres EAN rattachés au produit |
| **Références additionnelles** | Autres références connues pour ce produit |
| **Emplacement** | Emplacement de stockage |
| **Code SH** | Code douanier du système harmonisé |
| **Fournisseur** | Code du fournisseur du produit |

### Stocks

Le **stock disponible** est géré par ShippingBo, qui détient l'inventaire physique. Il est en
**lecture seule** : il remonte de ShippingBo vers vos autres applications, mais Splash ne le
modifie jamais dans ShippingBo.

> [!NOTE]
> Pour que vos boutiques affichent le bon stock, synchronisez-le **depuis** ShippingBo **vers** vos
> boutiques, et non l'inverse.

#### Stock par emplacement d'entrepôt

Avec la **gestion de stock avancée**, Splash peut exposer le stock de chaque emplacement
d'entrepôt dans un champ dédié, nommé *Stock on* suivi du nom de l'emplacement. Ces champs sont en
lecture seule, sauf pour les emplacements explicitement ouverts en écriture.

Le choix des emplacements suivis et modifiables se fait avec le **support Splash**.

### Champs additionnels

Les champs additionnels déclarés dans **Options du connecteur** apparaissent comme des champs
produit à part entière, au format booléen ou texte. Ils se synchronisent comme n'importe quel
autre champ.
