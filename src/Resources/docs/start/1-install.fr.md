---
lang: fr
permalink: start/install
title: Installer le connecteur ShippingBo
description: Identifiants API, création de la connexion dans Splash, vérification et activation des webhooks.
updated: 2026-09-24
---

### Prérequis

- Un compte **ShippingBo** disposant d'un accès à l'API.
- Vos **identifiants API ShippingBo** : un identifiant de compte (ou l'e-mail de l'utilisateur API)
  et une clé API.
- Un compte **Splash Sync Premium** actif.

> [!IMPORTANT]
> Les identifiants API ShippingBo sont fournis **sur demande**. Adressez-vous à votre interlocuteur
> ShippingBo pour les obtenir avant de commencer.

### Étape 1 — Créer la connexion ShippingBo

Depuis votre compte Splash, ajoutez une nouvelle connexion **ShippingBo API V1**.

Le connecteur communique directement avec l'API ShippingBo : il n'y a rien à installer
côté ShippingBo.

### Étape 2 — Renseigner vos identifiants

Complétez les champs de connexion :

| Champ | Contenu |
|---|---|
| **API User / Account ID** | Votre identifiant de compte ShippingBo, ou l'e-mail de l'utilisateur API |
| **API Key / Token** | Votre clé API ShippingBo |
| **Fuseau horaire des dates** | Le fuseau utilisé pour interpréter les dates ShippingBo, par exemple `Europe/Paris` |

> [!CAUTION]
> La clé API donne un **accès complet** à votre compte ShippingBo. Ne la partagez pas et ne la
> communiquez jamais par e-mail.

Les autres options (filtres, emplacements d'entrepôt, champs additionnels) sont facultatives.
Elles sont détaillées dans la page **Options du connecteur**.

### Étape 3 — Vérifier la connexion

Enregistrez, puis lancez le test de configuration **ShippingBo Connector Configuration**.
Il vérifie que Splash parvient à joindre l'API ShippingBo avec vos identifiants.

Si le test échoue, contrôlez en priorité l'identifiant et la clé API : ce sont les causes
d'échec les plus fréquentes.

### Étape 4 — Activer les webhooks

Les webhooks permettent à ShippingBo de **prévenir Splash en temps réel** à chaque modification
d'une commande, d'un produit ou d'une commande fournisseur. Sans eux, les changements faits dans
ShippingBo ne remontent pas automatiquement.

Dans le bloc **Webhooks Configuration** de votre connexion, cliquez sur
**Refresh Webhooks Configuration**. Le connecteur crée ou met à jour les webhooks nécessaires
dans votre compte ShippingBo.

Le bloc doit ensuite afficher **Webhooks Configuration is OK !**

> [!TIP]
> Relancez **Refresh Webhooks Configuration** si le bloc indique une configuration incomplète,
> par exemple après une modification de vos identifiants.

### Étape 5 — Charger les emplacements d'entrepôt (facultatif)

Si vous utilisez la **gestion de stock avancée** de ShippingBo, cliquez sur
**Refresh Warehouse Slots List** dans le bloc **Warehouse Slots**. Le connecteur récupère la
liste de vos emplacements, qui devient alors sélectionnable dans les options.

Sans gestion de stock avancée, cette étape est inutile.

### Et ensuite ?

Votre connexion est prête. Réglez les **Options du connecteur** si vous devez filtrer certaines
commandes ou certains produits, puis activez la synchronisation des objets dont vous avez besoin.
