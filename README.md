[![N|Solid](https://github.com/SplashSync/Php-Core/raw/3.0/img/github.jpg)](https://www.splashsync.com)

# Splash Sync ShippingBo Connector

This connector implements the ShippingBo API for working with Splash Sync.

It synchronizes the following objects between ShippingBo and your other applications:

| Object | Description |
|---|---|
| **Customer Order** | Orders sent to ShippingBo for preparation, with status & tracking updates |
| **Product** | Catalog, references, barcodes & stocks (stocks are read-only in production) |
| **Customer Address** | Shipping & billing addresses attached to orders |
| **Supplier Order** | ShippingBo supply capsules (replenishments) |

## Requirements

* PHP 8.1+
* ShippingBo API credentials (provided on demand by ShippingBo)
* An active Splash Sync Premium Account

## Documentation

The user documentation lives in [`src/Resources/docs/`](src/Resources/docs/) and is published on the
module page of [splashsync.com](https://www.splashsync.com). It covers installation, configuration
options, and the detailed behaviour of each synchronized object.

## Development

### Local environment

A complete development stack is provided with Docker Compose: a Splash Toolkit running the local
sources, and an OpenAPI sandbox faking the ShippingBo API.

```bash
docker compose up -d
```

For a faster access to containers, add these entries to your `/etc/hosts`:

```
172.108.0.100       toolkit.shipping-bo.local
172.108.0.200       sandbox.shipping-bo.local
```

### Quality & tests

```bash
make quality    # Linters, code style & PHPStan (GrumPHP)
make test       # Functional tests, run in the Toolkit container against the sandbox
```

### Manifest & OpenAPI definition

`splash.yml`, `splash.json` and `swagger.json` are generated from the running connector and read by
splashsync.com to build the module page. Regenerate them after any change to objects, fields or
profile, and commit them:

```bash
php bin/console splash:server:manifest
```

## Contributing

Any Pull requests are welcome!

This module is part of [SplashSync](https://www.splashsync.com) project.
