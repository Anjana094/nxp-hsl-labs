# PLAN.md – Project Planning Document

## 1. Understanding of Project Scope
The goal is to build the provider dashboard for plastic surgeons to manage supplement business.The system will primarily be used by three types of users — Providers, their Staff members, and Patients. The system should allow them to manage product inventory, place wholesale orders, track patient's surgery timelines, monitor monthly subscriptions, and view payments/renewals.

---

## 2. Assumptions
- Providers and their staff will log in to access the dashboard.
- Monthly recurring billing model
- Payments and card charges will be simulated (Stripe/similar).
- Email notifications can be logged or stored locally.
- Each Provider will have its own inventory.

---

## 3. Main Modules
1. Authentication & Authorization: Provider login, basic profile, staff roles
2. Inventory Management: Track stock levels for each provider
3. Order Processing: Providers place wholesale orders to HSL Labs 
4. Patient Management: Track surgery date, plan start, renewal cycle
5. Billing & Subscription: Record payments, renewals (simulated)
6. Notifications: Email alerts for order confirmation or renewals

---

## 4. Key Questions for Stakeholders
1. Do Providers have staff accounts with different access levels?
2. Is inventory tracked per Provider?
3. What payment processor do you currently use?
4. Do subscriptions renew automatically or manually confirmed?
5. Multi-location support needed per provider?
6. Should notifications be Email only, or also SMS?

---

## 5. Proposed Milestones & Timeline
- Week 1: Requirement confirmation, database planning, ERD, folder structure
- Week 2-3: Set up Laravel project, migrations, models, basic routes & auth |
- Week 4: Implement one full vertical feature (Provider places order)
- Week 5-6: Write tests, refine documentation (README), final review

---

## 6. Selected Vertical Slice for Implementation
**Provider places a wholesale product order**
- Provider submits quantity and order details  
- Order is saved in DB  
- Provider’s inventory is updated  
- Event triggers email confirmation (mocked)  
- Feature tests for success + validation failure

---

## 7. Tools & Technology
- Laravel 10  
- MySQL
- PHPUnit for testing  
- Events, Policies, Form Requests for clean architecture

