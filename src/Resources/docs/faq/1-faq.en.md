---
lang: en
permalink: faq/questions
title: Frequently asked questions
description: Answers to common questions about the ShippingBo connector.
updated: 2026-09-24
---

## Frequently asked questions {.faq}

### An order was not sent to ShippingBo, why?

It was most likely excluded by one of the filters applied on creation:

- its **original creation date** is earlier than the minimum date, or it has no date while a
  minimum date is set;
- its **origin** is marked REJECTED, or it has no origin while the origin list is not empty;
- its **carrier** is rejected.

Your Splash logs show which filter applied. An excluded order is marked as rejected and is no
longer sent automatically.

### Changes made in ShippingBo are not reported to Splash

First check the **Webhooks Configuration** block of your connection: it must display
*Webhooks Configuration is OK !* Otherwise, click **Refresh Webhooks Configuration**.

Without valid webhooks, ShippingBo cannot notify Splash of its changes.

### Why is stock not updated in ShippingBo?

This is expected: stock is **read-only**. ShippingBo manages the physical inventory of your
warehouse and remains the reference. Stock flows from ShippingBo to your shops, never the other
way around.

### I cannot cancel an order

Splash can only cancel an order until it goes into preparation. Once preparation has started,
cancel it directly in ShippingBo.

### The lines of my order were not changed

Lines can only be changed in the first states of the order, before preparation. Beyond that,
Splash keeps the existing lines and logs the *Update of Order Items not allowed* error.

### A product is not linked to its ShippingBo counterpart

Splash finds products by their **reference**. Check that it is identical on both sides and that it
is not shared by several ShippingBo products.

### Where do I find my ShippingBo API credentials?

They are provided on demand by ShippingBo. Get in touch with your ShippingBo contact.
