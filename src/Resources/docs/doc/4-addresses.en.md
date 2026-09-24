---
lang: en
permalink: doc/addresses
title: Addresses
description: Shipping and billing addresses attached to ShippingBo orders.
updated: 2026-09-24
---

### Overview

ShippingBo addresses are the **shipping** and **billing** addresses attached to your orders. They
are created with the order and follow its lifecycle.

| Operation | Default behaviour |
|---|---|
| Created in Splash | **Sent** to ShippingBo |
| Change made in Splash | **Sent** to ShippingBo |
| Change made in ShippingBo | **Reported** to Splash |
| Address created directly in ShippingBo | **Not imported** |
| Deletion | **Not propagated** |

### Updated with the order

When an order is updated and its shipping address has changed, Splash also updates the address in
ShippingBo. The parcel address thus stays aligned with the one in your shop.
