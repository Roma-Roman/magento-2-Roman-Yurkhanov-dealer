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

## Extension Screenshots
**Admin Panel:**

<img width="1792" alt="Screenshot 2024-09-23 at 13 18 57" src="https://github.com/user-attachments/assets/e7911fdb-22f4-4c98-aa92-111d1490016e">
<img width="1792" alt="Screenshot 2024-09-23 at 13 20 06" src="https://github.com/user-attachments/assets/d9caa754-cedf-4c98-bd5f-2b3cd36314d4">
<img width="1792" alt="Screenshot 2024-09-23 at 13 21 12" src="https://github.com/user-attachments/assets/4b65e3af-f920-4189-8fec-5c4baf9bfd50">
<img width="1792" alt="Screenshot 2024-09-23 at 13 21 50" src="https://github.com/user-attachments/assets/e720e85c-0632-4166-b325-5b7317351012">
<img width="1792" alt="Screenshot 2024-09-23 at 13 22 28" src="https://github.com/user-attachments/assets/982760b0-9cd4-4880-bd2a-bef54e8a63fb">
<img width="1792" alt="Screenshot 2024-09-23 at 13 22 54" src="https://github.com/user-attachments/assets/2bf25942-a63c-47f0-8a07-caa4b6bfc686">
<img width="1792" alt="Screenshot 2024-09-23 at 13 25 15" src="https://github.com/user-attachments/assets/a1e80332-d697-45c6-ad00-26f8d9ab6fe0">
<img width="1792" alt="Screenshot 2024-09-23 at 13 25 39" src="https://github.com/user-attachments/assets/6c606ee4-3b5a-43d6-83ed-ea658f9ea0ae">
<img width="1792" alt="Screenshot 2024-09-23 at 13 31 20" src="https://github.com/user-attachments/assets/437e8024-49e2-4b48-b10e-c0b08781aea5">
<img width="1792" alt="Screenshot 2024-09-23 at 13 31 47" src="https://github.com/user-attachments/assets/55cbc739-bb99-4a4f-b787-70fc5c9d9f33">


**Frontend:**

<img width="1792" alt="Screenshot 2024-09-23 at 13 37 53" src="https://github.com/user-attachments/assets/acaf426f-5122-4bdc-9de1-fbe58d470b7b">
<img width="1792" alt="Screenshot 2024-09-23 at 13 38 31" src="https://github.com/user-attachments/assets/ab1cb442-f42e-4c1b-9778-e2a2560a5c92">
<img width="772" alt="Screenshot 2024-09-23 at 13 41 26" src="https://github.com/user-attachments/assets/23b984e1-b9fd-49d6-8798-c0786a26a020">
<img width="1792" alt="Screenshot 2024-09-23 at 13 43 43" src="https://github.com/user-attachments/assets/8a598aaa-bec7-4759-b08f-1071d6e538e3">
<img width="1792" alt="Screenshot 2024-09-23 at 13 44 44" src="https://github.com/user-attachments/assets/6dc4a285-1cdd-49d2-adfd-c1ef952c8680">
<img width="1792" alt="Screenshot 2024-09-23 at 13 45 07" src="https://github.com/user-attachments/assets/a9d6211e-9c3f-45c1-a2fb-9125b184db70">
<img width="1792" alt="Screenshot 2024-09-23 at 13 45 28" src="https://github.com/user-attachments/assets/b372f1e3-138c-4737-95f3-7ff38eab05cf">
<img width="1792" alt="Screenshot 2024-09-23 at 13 46 50" src="https://github.com/user-attachments/assets/fe3f8118-2cd7-47d8-9fa0-1ac6551bb53b">
<img width="1792" alt="Screenshot 2024-09-23 at 13 47 34" src="https://github.com/user-attachments/assets/cb903ca8-8a40-4f0c-a7f1-30d8531561c1">
<img width="1792" alt="Screenshot 2024-09-23 at 13 51 04" src="https://github.com/user-attachments/assets/63372828-0fea-425a-b405-3eeb882c135a">


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
