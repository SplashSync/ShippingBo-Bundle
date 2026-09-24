---
lang: fr
permalink: overview
title: Connecteur ShippingBo
description: Synchronisez commandes, produits, adresses et commandes fournisseur entre Splash et ShippingBo.
updated: 2026-09-24
---

### Présentation

ShippingBo est une plateforme de gestion des commandes et de la logistique e-commerce.
Le connecteur ShippingBo relie votre compte ShippingBo à Splash au travers de l'API ShippingBo :
vos commandes partent vers ShippingBo pour être préparées, et leur avancement (statut, suivi
colis) revient automatiquement vers vos autres applications.

Aucun module n'est à installer côté ShippingBo : la connexion est entièrement gérée par Splash,
à partir de vos identifiants API.

### Objets synchronisés

| Objet | Rôle |
|---|---|
| **Commande client** | Envoyée vers ShippingBo pour préparation, puis mise à jour de son statut et de son suivi colis |
| **Produit** | Catalogue, références, codes-barres et stocks ShippingBo |
| **Adresse client** | Adresses de livraison et de facturation rattachées aux commandes |
| **Commande fournisseur** | Réapprovisionnements ShippingBo et leur avancement |

### Fonctionnement en bref

- Splash crée les commandes dans ShippingBo, puis ShippingBo **notifie Splash en temps réel**
  (webhooks) à chaque changement : validation, préparation, expédition, livraison.
- Des **filtres** permettent d'écarter certaines commandes avant leur envoi : par date,
  par origine ou par transporteur. Voir la page **Options du connecteur**.
- En production, le **stock produit est en lecture seule** : il est géré par ShippingBo et
  remonte vers vos autres applications, jamais l'inverse.
- La **suppression d'une commande** n'est jamais répercutée dans ShippingBo.

### Pour commencer

1. **Installer le connecteur** et vérifier la connexion (section *Démarrage*).
2. Régler les **Options du connecteur** selon votre organisation (section *Configuration*).
3. Consulter le fonctionnement détaillé des **Commandes**, des **Produits** et des
   **Commandes fournisseur** (section *Utilisation*).
