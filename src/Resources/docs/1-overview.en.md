---
lang: en
permalink: overview
title: ShippingBo Connector
description: Synchronize orders, products, addresses and supplier orders between Splash and ShippingBo.
updated: 2026-09-24
---

### Overview

ShippingBo is an order management and e-commerce logistics platform.
The ShippingBo connector links your ShippingBo account to Splash through the ShippingBo API:
your orders are sent to ShippingBo to be prepared, and their progress (status, parcel tracking)
automatically flows back to your other applications.

Nothing needs to be installed on the ShippingBo side: the connection is fully managed by Splash,
using your API credentials.

### Synchronized objects

| Object | Role |
|---|---|
| **Customer Order** | Sent to ShippingBo for preparation, then updated with its status and parcel tracking |
| **Product** | ShippingBo catalog, references, barcodes and stocks |
| **Customer Address** | Shipping and billing addresses attached to orders |
| **Supplier Order** | ShippingBo replenishments and their progress |

### How it works

- Splash creates orders in ShippingBo, then ShippingBo **notifies Splash in real time**
  (webhooks) on every change: validation, preparation, shipment, delivery.
- **Filters** let you exclude some orders before they are sent: by date, by origin or by
  carrier. See the **Connector options** page.
- In production, **product stock is read-only**: it is managed by ShippingBo and flows to your
  other applications, never the other way around.
- **Deleting an order** is never propagated to ShippingBo.

### Getting started

1. **Install the connector** and check the connection (*Getting started* section).
2. Adjust the **Connector options** to fit your organization (*Configuration* section).
3. Read the detailed behaviour of **Orders**, **Products** and **Supplier orders**
   (*Usage* section).
