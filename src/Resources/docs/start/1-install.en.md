---
lang: en
permalink: start/install
title: Install the ShippingBo connector
description: API credentials, connection setup in Splash, checks and webhooks activation.
updated: 2026-09-24
---

### Requirements

- A **ShippingBo** account with API access.
- Your **ShippingBo API credentials**: an account ID (or the API user e-mail) and an API key.
- An active **Splash Sync Premium** account.

> [!IMPORTANT]
> ShippingBo API credentials are provided **on demand**. Ask your ShippingBo contact for them
> before you start.

### Step 1 — Create the ShippingBo connection

From your Splash account, add a new **ShippingBo API V1** connection.

The connector talks directly to the ShippingBo API: there is nothing to install on the
ShippingBo side.

### Step 2 — Enter your credentials

Fill in the connection fields:

| Field | Content |
|---|---|
| **API User / Account ID** | Your ShippingBo account ID, or the API user e-mail |
| **API Key / Token** | Your ShippingBo API key |
| **Dates Timezone** | The timezone used to read ShippingBo dates, for example `Europe/Paris` |

> [!CAUTION]
> The API key grants **full access** to your ShippingBo account. Do not share it, and never send
> it by e-mail.

The other options (filters, warehouse slots, additional fields) are optional. They are described
in the **Connector options** page.

### Step 3 — Check the connection

Save, then run the **ShippingBo Connector Configuration** self-test. It checks that Splash can
reach the ShippingBo API with your credentials.

If the test fails, check the account ID and the API key first: they are the most common causes
of failure.

### Step 4 — Enable webhooks

Webhooks let ShippingBo **notify Splash in real time** whenever an order, a product or a supplier
order changes. Without them, changes made in ShippingBo are not automatically reported.

In the **Webhooks Configuration** block of your connection, click
**Refresh Webhooks Configuration**. The connector creates or updates the required webhooks in your
ShippingBo account.

The block should then display **Webhooks Configuration is OK !**

> [!TIP]
> Click **Refresh Webhooks Configuration** again if the block reports an incomplete
> configuration, for example after changing your credentials.

### Step 5 — Load warehouse slots (optional)

If you use ShippingBo **advanced stock management**, click **Refresh Warehouse Slots List** in the
**Warehouse Slots** block. The connector fetches your slots, which then become selectable in the
options.

Without advanced stock management, you can skip this step.

### What's next?

Your connection is ready. Adjust the **Connector options** if you need to filter some orders or
products, then enable synchronization for the objects you need.
