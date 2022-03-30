# Document Module for Magento 2

[![Latest Stable Version](https://img.shields.io/packagist/v/opengento/module-document-product-link.svg?style=flat-square)](https://packagist.org/packages/opengento/module-document-product-link)
[![License: MIT](https://img.shields.io/github/license/opengento/magento2-document-product-link.svg?style=flat-square)](./LICENSE) 
[![Packagist](https://img.shields.io/packagist/dt/opengento/module-document-product-link.svg?style=flat-square)](https://packagist.org/packages/opengento/module-document-product-link/stats)
[![Packagist](https://img.shields.io/packagist/dm/opengento/module-document-product-link.svg?style=flat-square)](https://packagist.org/packages/opengento/module-document-product-link/stats)

This module aims to help merchants to link their documents to products in Magento 2.

 - [Setup](#setup)
   - [Composer installation](#composer-installation)
   - [Setup the module](#setup-the-module)
 - [Features](#features)
 - [Settings](#settings)
 - [Documentation](#documentation)
 - [Support](#support)
 - [Authors](#authors)
 - [License](#license)

## Setup

Magento 2 Open Source or Commerce edition is required.

### Composer installation

Run the following composer command:

```
composer require opengento/module-document-product-link
```

### Setup the module

Run the following magento command:

```
bin/magento setup:upgrade
```

**If you are in production mode, do not forget to recompile and redeploy the static resources.**

## Features

This module aims to help merchants to link their documents to products in Magento 2.  
Documents can be linked to products from the edit view page.

- Manage product links:
  - from the back-office
  - from pivot field values on indexation

## Documentation

Settings are available at: `Stores > Configurations > Catalog > Catalog > Product Documents`  

*Document Product Link*: Defines what product attribute will be used to match with document type's pivot field.

## Support

Raise a new [request](https://github.com/opengento/magento2-document-product-link/issues) to the issue tracker.

## Authors

- **Opengento Community** - *Lead* - [![Twitter Follow](https://img.shields.io/twitter/follow/opengento.svg?style=social)](https://twitter.com/opengento)
- **Thomas Klein** - *Maintainer* - [![GitHub followers](https://img.shields.io/github/followers/thomas-kl1.svg?style=social)](https://github.com/thomas-kl1)
- **Contributors** - *Contributor* - [![GitHub contributors](https://img.shields.io/github/contributors/opengento/magento2-document-product-link.svg?style=flat-square)](https://github.com/opengento/magento2-document-product-link/graphs/contributors)

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) details.

***That's all folks!***
