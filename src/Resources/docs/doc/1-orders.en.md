---
lang: en
permalink: doc/orders
title: Orders
description: Sending orders to ShippingBo, status mapping, validation, cancellation, parcel tracking and limits.
updated: 2026-09-24
---

### Synchronization direction

| Operation | Default behaviour |
|---|---|
| Order created in Splash | **Sent** to ShippingBo |
| Change made in ShippingBo | **Reported** to Splash, in real time thanks to webhooks |
| Order created directly in ShippingBo | **Not imported** |
| Deletion | **Never propagated** to ShippingBo |

Before being sent, every new order goes through the date, origin and carrier filters described in
the **Connector options** page.

An order excluded by a filter is marked as **rejected**: Splash considers it handled and will not
try to send it again.

### Status mapping

The ShippingBo status is converted into a Splash status, understood by all your other
applications:

| ShippingBo status | Splash status |
|---|---|
| `waiting_for_payment` | Payment due |
| `waiting_for_stock` | Out of stock |
| `to_be_prepared`, `merged`, `sent_to_logistics`, `dispatched`, `splitted`, `in_preparation` | Processing |
| `partially_shipped`, `shipped`, `handed_to_carrier` | In transit |
| `at_pickup_location` | Ready for pickup |
| `closed` | Delivered |
| `back_from_client` | Returned |
| `rejected`, `canceled` | Canceled |
| `in_trouble` | Unknown |

### Validating an order

When an order is validated in your shop, Splash moves it to the **To be prepared**
(`to_be_prepared`) state in ShippingBo.

Validation is only accepted from these states: `in_trouble`, `waiting_for_payment`,
`waiting_for_stock`, `rejected` and `canceled`. From any other state it is ignored, and a warning
shows up in your Splash logs.

### Canceling an order

When an order is canceled in your shop, Splash moves it to the **Canceled** (`canceled`) state in
ShippingBo.

Cancellation is only accepted as long as the order has not gone into preparation: from
`in_trouble`, `waiting_for_payment`, `waiting_for_stock`, `to_be_prepared`, `dispatched` and
`rejected`. Beyond that, it is ignored with a warning.

> [!IMPORTANT]
> An order already in preparation or shipped **cannot be canceled by Splash**. Handle it directly
> in ShippingBo.

### Changing order lines

Lines (products, quantities, prices) can only be changed while the order is in one of these
states: `in_trouble`, `waiting_for_payment`, `waiting_for_stock`, `to_be_prepared`, `rejected` or
`canceled`.

Otherwise the lines are left untouched and an *Update of Order Items not allowed* error is logged;
the rest of the order is still updated.

### Parcel tracking

As soon as a shipment is created in ShippingBo, this information flows back to Splash:

| Field | Content |
|---|---|
| **Carrier name** | Name of the carrier |
| **Tracking Number** | Parcel reference at the carrier |
| **Tracking Url** | Tracking link to share with the customer |

### Force delivery

The **Force Delivered** field closes an order from Splash, without going through the ShippingBo
logistics flow. It is reserved for **advanced stock management**.

When it is set:

1. the order must be **validated** and **not already delivered**;
2. all its items must be available in the **Default Warehouse Slots** (see
   **Connector options**);
3. the stock of each item is decreased there, then the order moves to the `closed` state.

If a single item is missing from the default slots, **nothing is changed**: neither stocks nor
the order state.

### Prices and amounts

ShippingBo stores amounts in **cents**. Splash converts every price by rounding it to the nearest
cent. The amount of a line is the unit price multiplied by the quantity.

> [!WARNING]
> ShippingBo does not accept amounts above **21,474,836.47** (in absolute value). Beyond that,
> Splash caps the amount so that the order is still accepted: the affected line is then sent with
> a **truncated** amount. This only happens with abnormal quantities or prices, for example a
> quantity expressed in centimeters.

### Shipping addresses

The shipping address of an order is updated in ShippingBo together with the order. See the
**Addresses** page for details.
