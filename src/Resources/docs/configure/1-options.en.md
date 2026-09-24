---
lang: en
permalink: configure/options
title: Connector options
description: Timezone, order and product filters, warehouse slots and additional fields.
updated: 2026-09-24
---

### Connection

| Option | Role |
|---|---|
| **API User / Account ID** | ShippingBo account ID, or the API user e-mail. Required |
| **API Key / Token** | ShippingBo API key. Required, keep it secret |
| **Dates Timezone** | Timezone used to read dates exchanged with ShippingBo |
| **Default Shipping Method** | Required field: select one of the shipping methods of your ShippingBo account, loaded automatically |

### Filtering orders

Filters apply **only when an order is created** in ShippingBo. An excluded order is not sent; an
order already in ShippingBo is never removed by a filter.

#### Min Object Create Date

Orders whose **original creation date** is earlier than this date are not sent to ShippingBo.
Useful to start synchronizing without importing your history.

> [!WARNING]
> As soon as a minimum date is set, an order received **without an original creation date** is
> excluded as well.

#### Known Order Origins

Map an origin name (the sales channel, for example a shop or a marketplace name) to an action:

| Action | Effect |
|---|---|
| **Send to ShippingBo** | The order is sent as usual |
| **REJECTED** | The order is not sent |

Rules applied:

- **Empty list**: every order is sent, whatever its origin.
- **Origin not listed**: the order is sent.
- **Order without an origin**, while the list is not empty: the order is excluded.
- The name must match the received origin **exactly**, including case. Only leading and trailing
  spaces are ignored.

#### Carriers

Orders from a given carrier can be excluded, or a carrier can be renamed before the order is sent
to ShippingBo. This setting is not available from your account: **contact Splash support** to set
it up.

### Filtering products

#### Supplier filter

Map a supplier code to an action:

| Action | Effect |
|---|---|
| **Synchronize** | Products from this supplier are synchronized |
| **Block** | Products from this supplier are neither created nor updated in ShippingBo |

Only suppliers marked **Block** are excluded: a supplier missing from the list is still
synchronized. The comparison ignores case, and leading and trailing spaces.

> [!NOTE]
> A product blocked on **creation** reports an error in your Splash logs. On **update**, it is
> simply skipped, without any error.

#### Products Additional Fields

Declare here the additional fields you created on your ShippingBo products, with their format:

| Format | Usage |
|---|---|
| **Boolean** | Checkbox, yes / no |
| **String** | Free value |

Each declared field becomes available in Splash and can be synchronized like any other product
field. Its name must be identical to the one defined in ShippingBo.

#### Force Product Count

Forces the total number of products reported when Splash reads the product list.
Only set it when instructed by support.

### Warehouse slots

This option only applies to ShippingBo **advanced stock management**. Load your slots first from
the **Warehouse Slots** block (see **Install the ShippingBo connector**).

| Option | Role |
|---|---|
| **Default Warehouse Slots** | Slots whose stock is decreased when Splash manually closes an order. See "Force delivery" in the **Orders** page |
