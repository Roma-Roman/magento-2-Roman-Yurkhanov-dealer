# RomanYurkhanov Dealer for Magento 2
#### RomanYurkhanov Dealer module for Magento 2

[![Latest Stable Version](http://poser.pugx.org/romanyurkhanov/module-dealer/v)](https://packagist.org/packages/romanyurkhanov/module-dealer)
[![Total Downloads](http://poser.pugx.org/romanyurkhanov/module-dealer/downloads)](https://packagist.org/packages/romanyurkhanov/module-dealer)

**Roman Yurkhanov's Dealer Module for Magento 2** extends the platform’s capabilities by enabling the sale of products from
third-party organizations (dealers). Each dealer is provided with a dedicated, personalized page that includes their
name, contact details, logo, and website link. Customers have the option to leave reviews about dealers directly on
their personal pages.

## Features

1. **Dealer Entity Creation:** Allows for the creation of dealer entities, with a "Default Dealer" assigned by default.
2. **Dealer Assignment to Products:** Each product can be assigned to a specific dealer, with "Default Dealer" being
   automatically assigned to all products initially (via data patch).
3. **Comprehensive Admin Management:** Admins can create, read, update, and delete dealer entities through a user-friendly
   interface featuring an entity grid and settings. Dealer information is also added to the order details.
4. **Frontend Enhancements:** Dealer information is seamlessly integrated into the Product Listing Page (PLP), Product
   Detail Page (PDP), Checkout, Order Success Page, Header Navigation Menu, and Customer Account sections. Individual dealer listing and profile
   pages are also available for customer review and contain attached products.
5. **Dealer Reviews:** Customers can submit reviews for dealers on their personal pages. Reviews require admin approval
   before being published.

## Features Details

## Extension Screenshots and Videos

## How to install & upgrade RomanYurkhanov_Dealer

### 1. Install via composer (recommend)

I recommend you to install RomanYurkhanov_Dealer module via composer. It is easy to install, update and maintenance.

Run the following command in Magento 2 root folder.

#### 1.1 Install

```
composer require romanyurkhanov/module-dealer
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

#### 1.2 Upgrade

```
composer update romanyurkhanov/module-dealer
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

Run compile if your store in Product mode:

```
php bin/magento setup:di:compile
```

### 2. Copy and paste

If you don't want to install via composer, you can use this way. 

- Download [the latest version here](https://github.com/Roma-Roman/magento-2-Roman-Yurkhanov-dealer/archive/main.zip)
- Extract `main.zip` file to `app/code/RomanYurkhanov/Dealer` ; You should create a folder path `app/code/RomanYurkhanov/Dealer` if not exist.
- Go to Magento root folder and run upgrade command line to install `RomanYurkhanov_Dealer`:

```
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
