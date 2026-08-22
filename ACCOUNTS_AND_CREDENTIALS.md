# All Season Garden - System Accounts, Sales Guide & Credentials

This document provides a comprehensive overview of user accounts, access roles, default login credentials, sales staff workflows, and security instructions for the **All Season Garden** Restaurant & Hospitality Management System.

---

## 1. Production Web & Portal Links

| Portal / Module | Production URL | Access Permissions |
| :--- | :--- | :--- |
| **Public Website / Home** | [https://alltheseasongarden.rw/](https://alltheseasongarden.rw/) | Public Access |
| **Staff & User Login** | [https://alltheseasongarden.rw/auth/login](https://alltheseasongarden.rw/auth/login) | All Registered Users |
| **Admin & Sales Dashboard** | [https://alltheseasongarden.rw/admin](https://alltheseasongarden.rw/admin) | `global_admin`, `admin`, `sales` |
| **Point of Sale (POS Register)** | [https://alltheseasongarden.rw/admin/pos](https://alltheseasongarden.rw/admin/pos) | `sales`, `admin`, `global_admin` |
| **Orders Management** | [https://alltheseasongarden.rw/admin/orders](https://alltheseasongarden.rw/admin/orders) | `sales`, `admin`, `global_admin` |
| **Table Bookings** | [https://alltheseasongarden.rw/admin/table-bookings](https://alltheseasongarden.rw/admin/table-bookings) | `sales`, `admin`, `global_admin` |
| **Customer Registration** | [https://alltheseasongarden.rw/customer/create-account](https://alltheseasongarden.rw/customer/create-account) | Public Signup |
| **Password Reset Request** | [https://alltheseasongarden.rw/auth/password/request](https://alltheseasongarden.rw/auth/password/request) | Public Request |

---

## 2. Default Super Admin Account (Seeded Account)

The system includes a default Global Administrator account created during initial deployment:

| Property | Value / Detail |
| :--- | :--- |
| **Role Name** | Global Administrator (`global_admin`) |
| **Email / Username** | `info@alltheseasongarden.rw` |
| **Default Password** | `12345678` |
| **Phone Number** | `+250788458102` |
| **Access Rights** | Full system permissions (POS, Orders, User Management, Global Reports, Kitchen, Bar, Stock, Payroll, & System Settings) |
| **Login Link** | [https://alltheseasongarden.rw/auth/login](https://alltheseasongarden.rw/auth/login) |

> [!CAUTION]
> **Security Notice:** Change the default Super Admin password immediately upon initial deployment via **Admin Panel > Account > Change Password**.

---

## 3. User Roles & Access Control Summary

| Role | Role Code | Primary Users | Access Scope & Permissions |
| :--- | :--- | :--- | :--- |
| **Global Admin** | `global_admin` | CEO, Owner, General Manager | Unlimited control over all modules, staff accounts, payroll, global daily reports, system configurations, and order deletion privileges. |
| **Admin** | `admin` | Restaurant Managers | Full operational management: menu editing, venue/room management, stock inventory, supplier management, order management, and waiters. |
| **Sales** | `sales` | Sales Agents, POS Operators, Receptionists | Front-of-house sales tools: POS order placement, live orders management, thermal receipt printing, table bookings, customer inquiries. Restricted from deleting orders or changing global settings. |
| **Customer** | `customer` | Guests, Diners, Room Bookers | Online food ordering, table/room/venue reservation requests, viewing past orders, and managing personal user account profile. |

---

## 4. Sales Staff Guide & Operational Workflows

The **Sales Role (`sales`)** is specifically tailored for front-of-house team members, cashiers, waitstaff managers, and sales agents operating the restaurant daily.

### 4.1. Sales Navigation & Dashboard Redirects
* When a Sales user logs into `https://alltheseasongarden.rw/auth/login`, they are automatically taken to their primary workstation: **Point of Sale (`/admin/pos`)**.
* If a Sales user attempts to open restricted management URLs (like Payroll or User Settings), the system safely redirects them back to the POS register.

### 4.2. Point of Sale (POS) Workflow (`/admin/pos`)
1. **Adding Items to Order**:
   - Browse menu categories or search food & beverage items dynamically.
   - Click items to add them to the active ticket cart.
   - Adjust quantities, add custom cooking/drink instructions or notes (e.g. *"Extra spicy"*, *"No ice"*).
2. **Selecting Table & Waiter**:
   - Assign the specific restaurant table number or mark as a **Dine-in**, **Takeaway**, or **Delivery** order.
   - Select the assigned waiter responsible for the service.
3. **Placing & Printing Orders**:
   - Submit the order to send kitchen & bar tickets automatically.
   - Generate thermal customer receipt copies for payment processing.

### 4.3. Orders Management (`/admin/orders`)
* **Live Order Tracking**: Filter orders by status (*Pending*, *Dine-in*, *Delivery*).
* **Updating Status**: Mark orders as *Processing*, *Completed*, or *Paid*.
* **Receipt Re-Printing**: Print thermal receipts (`/admin/orders/{id}/receipt`) anytime for customers.
* **Order Security Restriction**: Sales staff **cannot delete orders** from the system. Order deletion is strictly audit-locked and reserved for Global Administrators to prevent revenue tampering.

### 4.4. Table Bookings Management (`/admin/table-bookings`)
* View incoming table reservation requests from customers.
* Create walk-in or phone reservations with guest count, reserved time, and contact phone number.
* Update booking statuses (*Confirmed*, *Seated*, *Cancelled*).

---

## 5. Account Provisioning & Password Rules

### 5.1. How New Staff / Sales Accounts Are Created
New Sales or Admin accounts are created by a Global Administrator via **Admin Panel > Admins** (`https://alltheseasongarden.rw/admin/users`):

1. **Initial Temporary Password**: The system automatically sets the temporary password equal to the user's **email address**.
   * *Example*: Email `sales.desk@alltheseasongarden.rw` $\rightarrow$ Password `sales.desk@alltheseasongarden.rw`
2. **Automated Welcome Email**: An activation email (`NewAccountNotification`) is dispatched to the staff member with login credentials.
3. **First-Time Password Change**: Upon first logging into `https://alltheseasongarden.rw/auth/login`, the user is prompted to set a new personal password before system access is granted.

### 5.2. Customer Self-Registration
Customers can sign up freely through the web portal:
* **Registration Page**: [https://alltheseasongarden.rw/customer/create-account](https://alltheseasongarden.rw/customer/create-account)
* Customers choose their own password during account registration.

---

## 6. Full URL Directory

| Action / Module | Full URL |
| :--- | :--- |
| **Main Website** | `https://alltheseasongarden.rw/` |
| **User Sign In** | `https://alltheseasongarden.rw/auth/login` |
| **User Logout** | `https://alltheseasongarden.rw/auth/logout` |
| **Password Reset** | `https://alltheseasongarden.rw/auth/password/request` |
| **Customer Dashboard** | `https://alltheseasongarden.rw/customer` |
| **POS Register** | `https://alltheseasongarden.rw/admin/pos` |
| **Orders List** | `https://alltheseasongarden.rw/admin/orders` |
| **Table Reservations** | `https://alltheseasongarden.rw/admin/table-bookings` |
| **Change Password (Staff)** | `https://alltheseasongarden.rw/admin/change-password` |
| **Change Password (Customer)**| `https://alltheseasongarden.rw/customer/change-password` |

---

## 7. Security Best Practices

1. **Password Integrity**: Use a strong password with a minimum of 8 characters containing letters, numbers, and symbols.
2. **Account Confidentiality**: Never share Global Admin or Sales POS logins between staff members. Each staff member should have their own individual account for accountability.
3. **Password Updates**: Users can update their password anytime via the **Change Password** page in their account sidebar.
4. **Forgot Password**: If a staff member or customer loses their password, use the **Forgot Password** feature on the login page or ask a Global Admin to reset user details.

---
*Document prepared for All Season Garden — Restaurant & Hospitality Management System.*
