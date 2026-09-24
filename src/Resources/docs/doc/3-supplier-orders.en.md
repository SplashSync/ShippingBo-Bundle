---
lang: en
permalink: doc/supplier-orders
title: Supplier orders
description: ShippingBo replenishments, state mapping and line changes.
updated: 2026-09-24
---

### Overview

Supplier orders represent your **replenishments**: goods expected from your suppliers and received
in your ShippingBo warehouse.

| Operation | Default behaviour |
|---|---|
| Created in Splash | **Sent** to ShippingBo |
| Change made in Splash | **Sent** to ShippingBo |
| Change made in ShippingBo | **Reported** to Splash |
| Supplier order created directly in ShippingBo | **Not imported** |
| Deletion | **Not propagated** |

### State mapping

| ShippingBo state | Splash status |
|---|---|
| Draft (`draft`), Upload in progress (`uploading`) | Draft |
| Waiting for pickup (`waiting`), Sent to logistics (`sent_to_logistics`) | Processing |
| Dispatched (`dispatched`), On going (`ongoing`) | In transit |
| Received (`received`) | Delivered |
| Canceled (`canceled`) | Canceled |
| In trouble (`in_trouble`) | In error |

### Changing lines

The lines of a supplier order (expected products and quantities) can only be changed until it
leaves: **Draft**, **Upload in progress**, **Waiting for pickup** or **In trouble** states.

Beyond that, the lines are left untouched and an *Update of Supply Capsule Items not allowed* error
is logged.
