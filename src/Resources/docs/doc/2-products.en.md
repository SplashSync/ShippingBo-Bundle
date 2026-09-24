---
lang: en
permalink: doc/products
title: Products
description: Product identification, read-only stocks, warehouse slots, barcodes and additional fields.
updated: 2026-09-24
---

### Synchronization direction

| Operation | Default behaviour |
|---|---|
| Product created in Splash | **Sent** to ShippingBo |
| Change made in Splash | **Sent** to ShippingBo |
| Change made in ShippingBo | **Reported** to Splash |
| Product created directly in ShippingBo | **Not imported** |
| Deletion | **Not propagated** |

The supplier filter described in **Connector options** can exclude products from some suppliers.

### Product identification

Each ShippingBo product is identified by its **reference** (`user_ref`), which is required.
Splash uses it to find an existing product and avoid duplicates: make sure it is **unique** and
identical across your applications.

> [!IMPORTANT]
> If several ShippingBo products share the same reference, Splash cannot tell them apart and links
> none of them.

### Main fields

| Field | Content |
|---|---|
| **Reference** | Unique product reference. Required |
| **Title** | Product label |
| **EAN13** | Main barcode |
| **Multi-Ean** | List of EAN barcodes attached to the product |
| **Add. Refs** | Other known references for this product |
| **Location** | Storage location |
| **HS Code** | Harmonized System customs code |
| **Supplier** | Product supplier code |

### Stocks

The **available stock** is managed by ShippingBo, which holds the physical inventory. It is
**read-only**: it flows from ShippingBo to your other applications, but Splash never changes it in
ShippingBo.

> [!NOTE]
> For your shops to display the right stock, synchronize it **from** ShippingBo **to** your shops,
> not the other way around.

#### Stock per warehouse slot

With **advanced stock management**, Splash can expose the stock of each warehouse slot in a
dedicated field, named *Stock on* followed by the slot name. These fields are read-only, except
for slots explicitly opened for writing.

Which slots are tracked and writable is set up with **Splash support**.

### Additional fields

The additional fields declared in **Connector options** show up as regular product fields, in
boolean or string format. They are synchronized like any other field.
