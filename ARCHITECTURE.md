# ARCHITECTURE.md – System Architecture Overview

## 1. Entity Relationship Diagram (ERD)
Figma Link: https://www.figma.com/board/6teR6tPda1vM2Aj3Xxph4c/Untitled?node-id=0-1&p=f&t=aQwZa1RmGXcjVHH8-0

Providers (Plastic Surgeons)
  - hasMany → Staff
  - hasMany → Patients
  - hasMany → Orders
  - hasOne  → Inventory

Patients
  - belongsTo → Provider
  - hasMany   → Subscriptions

Orders
  - belongsTo → Provider
  - affects   → Inventory (stock increases)

Inventory
  - belongsTo → Provider

Subscriptions (Recurring Monthly Patient Plans)
  - belongsTo → Patient
  - tracks    → next_renewal_date, status

## 2. System Explanation 
The system allows licensed plastic surgeons (Providers) to manage their supplement distribution. Each provider has their own inventory, can place wholesale orders to HSL Labs, and sells to their patients. Patients are linked to a surgery date, and their supplement subscription begins 10 days before surgery and renews every month. To support this, the system tracks orders, subscriptions, payments, and inventory in real time for each provider.

When a provider places a wholesale order, the system creates an order record, updates product stock in their inventory, and triggers a confirmation notification email. Future enhancements can include payment gateway integration, automated renewals, low-inventory alerts, and role-based staff access within provider clinics.

## 3. Laravel Project Structure
```
nxp-hsl-labs/
 ├── app/
 │   ├── Models/
 │   │    ├── Provider.php
 │   │    ├── Order.php
 │   │    ├── Inventory.php
 │   │    └── Patient.php
 │   ├── Http/
 │   │    ├── Controllers/Api/OrderController.php
 │   │    ├── Requests/OrderStoreRequest.php
 │   │    └── Middleware/
 │   ├── Services/
 │   │    └── OrderService.php        // Business logic
 │   ├── Policies/
 │   │    └── OrderPolicy.php         // Authorization
 │   ├── Events/
 │   │    └── OrderPlaced.php
 │   ├── Listeners/
 │   │    └── SendOrderConfirmation.php
 │   └── Mail/
 │        └── OrderConfirmationMail.php
 ├── database/
 │   ├── migrations/
 │   └── seeders/
 ├── routes/
 │   └── web.php
 ├── tests/Feature/OrderFeatureTest.php
 ├── PLAN.md
 ├── ARCHITECTURE.md
 └── README.md
```

## 4. Why This Structure?

Separation of Concerns – Controllers stay thin; all business logic is managed inside the Service layer.

Scalable & Clean – Events, Listeners, Policies, and Form Requests make the code modular and testable.

Industry-standard approach – Matches common Laravel enterprise structure used in multi-module applications.

Future-ready – Can easily extend for payments, notifications, and multi-tenant providers.
