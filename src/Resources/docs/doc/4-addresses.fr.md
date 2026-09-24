---
lang: fr
permalink: doc/addresses
title: Adresses
description: Adresses de livraison et de facturation rattachées aux commandes ShippingBo.
updated: 2026-09-24
---

### Présentation

Les adresses ShippingBo sont les adresses de **livraison** et de **facturation** rattachées à vos
commandes. Elles sont créées avec la commande et suivent son cycle de vie.

| Opération | Comportement par défaut |
|---|---|
| Création dans Splash | **Envoyée** à ShippingBo |
| Modification dans Splash | **Envoyée** à ShippingBo |
| Modification dans ShippingBo | **Remontée** vers Splash |
| Adresse créée directement dans ShippingBo | **Non importée** |
| Suppression | **Non répercutée** |

### Mise à jour avec la commande

Lorsqu'une commande est modifiée et que son adresse de livraison a changé, Splash met aussi à jour
l'adresse dans ShippingBo. L'adresse du colis reste ainsi alignée sur celle de votre boutique.
