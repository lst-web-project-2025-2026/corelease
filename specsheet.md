# Project Specification: Data Center Resource Management System (DCRMS)

This document provides an exhaustive, granular blueprint for the DCRMS. It covers the technical database architecture, user roles, and a page-by-page functional breakdown.

---

## 1. User Profiles & Role Definitions

### 1.1. Guest (Unauthenticated/Public)
*   **Definition:** Any visitor who has not logged in or whose application is still pending.
*   **Capabilities:** 
    *   **Catalog Browse:** View all resources in a read-only list. They can see specs (CPU, RAM, etc.) but the "Reserve" button is replaced with "Login to Reserve."
    *   **Account Application:** Submit a detailed registration form to join the platform.
    *   **Status Tracking:** Enter their email to check if their application is `Pending`, `Approved`, or `Rejected`.

### 1.2. Internal User (Engineer / Student / Researcher)
*   **Definition:** An individual whose application has been approved by an Admin.
*   **Capabilities:** 
    *   **Advanced Catalog:** Search and filter resources.
    *   **Dynamic Reservation:** Submit requests with custom configurations (for VMs/Storage).
    *   **Resource Management:** View their own active/past reservations.
    *   **Incident Reporting:** Report technical failures specifically for resources they are *currently* using.

### 1.3. Technical Resource Manager (Operator)
*   **Definition:** Staff responsible for hardware health and scheduling.
*   **Capabilities:**
    *   **Inventory Control:** Create, edit, and toggle (Enable/Disable) resources.
    *   **Reservation Moderation:** Approve or Reject requests with mandatory written justification.
    *   **Maintenance:** Schedule specific downtime windows for any resource.
    *   **Incident Handling:** Review user-reported issues and resolve them or escalate to maintenance.

### 1.4. Data Center Administrator (The Authority)
*   **Definition:** High-level controller of users and global system states.
*   **Capabilities:**
    *   **Vetting:** Review, Approve, or Reject new account applications with mandatory justification.
    *   **Account Security:** Deactivate or modify roles for any user.
    *   **System Lockdown:** Toggle "Facility-Wide Maintenance" to block all platform activity.
    *   **Global Logging:** View the complete audit trail of justifications and incidents.

---

## 2. Database Schema

### 2.1. `categories` (Resource Types)
*   `id`: Primary Key.
*   `name`: String (e.g., "Server", "VM").
*   `specs` (JSON): List of required spec keys (e.g., `["cpu", "ram", "os"]`).

### 2.2. `applications` (Account Requests)
*   `id`: Primary Key.
*   `name`: Applicant full name.
*   `email`: Unique email address.
*   `password`: Hashed password.
*   `profession`: String.
*   `user_justification`: Text.
*   `admin_justification`: Text.
*   `status`: Enum (`Pending`, `Approved`, `Rejected`).
*   `decided_by`: Foreign Key (Admin who handled it).
*   `timestamps`: `created_at`, `updated_at`, `deleted_at` (Soft Delete).

### 2.3. `users` (Active Accounts)
*   `id`: Primary Key.
*   `application_id`: Foreign Key.
*   `name`, `email`, `password`, `profession`.
*   `role`: Enum (`User`, `Manager`, `Admin`).
*   `is_active`: Boolean.
*   `timestamps`: `created_at`, `updated_at`, `deleted_at` (Soft Delete).

### 2.4. `resources` (The Inventory)
*   `id`: Primary Key.
*   `category_id`: Foreign Key (to `categories`).
*   `name`: String.
*   `specs` (JSON): Key-value pairs matching category requirements.
*   `status`: Enum (`Enabled`, `Disabled`).
*   `timestamps`: `created_at`, `updated_at`, `deleted_at` (Soft Delete).

### 2.5. `reservations` (Usage Records)
*   `id`: Primary Key.
*   `user_id`: Foreign Key.
*   `resource_id`: Foreign Key.
*   `start_date`, `end_date`: Datetime.
*   `user_justification`: Text.
*   `manager_justification`: Text.
*   `decided_by`: Foreign Key (Manager/Admin who handled it).
*   `configuration` (JSON): User's specific choices.
*   `status`: Enum (`Pending`, `Approved`, `Rejected`, `Completed`).
*   `timestamps`: `created_at`, `updated_at`, `deleted_at` (Soft Delete).

### 2.6. `maintenances` (Scheduled Downtime)
*   `id`: Primary Key.
*   `resource_id`: Foreign Key.
*   `user_id`: Foreign Key (Manager who scheduled it).
*   `start_date`, `end_date`: Datetime.
*   `description`: Text.

### 2.7. `incidents` (User Reports)
*   `id`: Primary Key.
*   `user_id`: Foreign Key.
*   `resource_id`: Foreign Key.
*   `reservation_id`: Foreign Key.
*   `description`: Text.
*   `status`: Enum (`Open`, `Resolved`).

### 2.8. `notifications` (In-App Alerts)
*   `id`: Primary Key.
*   `user_id`: Foreign Key.
*   `title`: String.
*   `content`: Text.
*   `status`: Enum (`Read`, `Unread`, `Dismissed`).
*   `timestamps`.

### 2.9. `audit_logs` (System Audit)
*   `id`: Primary Key.
*   `auditable_type`, `auditable_id`: Morph relationships.
*   `user_id`: Foreign Key (Who performed the action).
*   `event`: String (created, updated, deleted, status_changed).
*   `old_values`, `new_values`: JSON.
*   `timestamps`.

### 2.10. `settings` (System Config)
*   `key`: String.
*   `value`: String.

---

## 3. Page-by-Page Functional Breakdown

### 3.1. Public Pages
1.  **Home Page:** Visual overview of the Data Center. Displays a "Live Status" ticker showing total resources available vs. busy.
2.  **Public Catalog:** A searchable table of all resources.
    *   *Columns:* Category, Name, Specs.
    *   *Constraint:* No buttons to reserve; only a "Login to Book" link.
3.  **Application Page:** A large multi-step form. 
    *   *Step 1:* Account Credentials. 
    *   *Step 2:* Professional Details + Justification.
4.  **Application Status Checker:** A simple input where a guest enters their email to see the Admin’s decision and the `admin_justification`.

### 3.2. Internal User Dashboard (Authenticated)
1.  **User Home:** Summary cards for current active resources.
2.  **Resource Booking:**
    *   *Catalog View:* Grid of resource cards. 
    *   *Selection Flow:* Clicking a card opens a detailed view. 
    *   *Vanilla JS Logic:* If the resource is a VM (checked via JSON specs), the JS reveals a dropdown for OS selection. If it’s a Server, those fields stay hidden.
3.  **My Reservations:** A list grouped by "Current," "Pending," and "History."
4.  **Incident Reporting (Per-Resource):** 
    *   *Constraint:* This page can only be accessed by clicking "Report Issue" next to an **Active** reservation in the list.
    *   *Form:* Simple text area to describe the hardware/software failure.

### 3.3. Technical Manager Dashboard
1.  **Pending Approvals:** A queue of all reservation requests. 
    *   *Action:* Clicking "Review" opens a modal showing the User’s justification. Manager must type their own justification before clicking Approve/Reject.
2.  **Inventory Management:** 
    *   *Add Resource:* A form to define name, category, and build the `specs` JSON.
    *   *Status Toggle:* Switch resources between Enabled/Disabled.
3.  **Maintenance Calendar:** A list view of all upcoming downtime. A form to create new windows by selecting a resource and a date range.
4.  **Incidents Feed:** A list of Open incidents. Managers can "Acknowledge" (changing status) or link the incident to a new Maintenance entry.

### 3.4. System Admin Dashboard
1.  **Vetting Center:** A list of `applications` where `status = Pending`. 
    *   *Action:* Reading justifications and providing a final decision justification. 
    *   *Result:* Approving triggers the creation of a row in the `users` table.
2.  **Global User List:** Searchable list of all approved users. Includes the ability to disable an account (preventing login) or change a User to a Manager.
3.  **System Control Panel:** 
    *   *Global Maintenance Toggle:* A master switch that sets the `facility_maintenance` setting to 1.
    *   *Audit Log:* A master view of all `admin_justification` and `manager_justification` entries across the system for transparency.

---

## 4. Key Workflows & Business Logic

### 4.1. The Conflict Prevention Algorithm
Before a reservation is confirmed, Laravel executes a query to ensure:
1.  `resource.status == 'Enabled'`
2.  `settings.facility_maintenance == 0`
3.  `COUNT(maintenances) WHERE resource_id = X AND dates_overlap == 0`
4.  `COUNT(reservations) WHERE resource_id = X AND status = 'Approved' AND dates_overlap == 0`

### 4.2. JSON-Driven Dynamic UI (Vanilla JS)
On the booking page, the frontend scripts parse the `specs` JSON:
*   `if (specs.allow_os_choice)` -> `document.getElementById('os_section').style.display = 'block';`
*   `if (specs.storage_limit)` -> Generate a number input with `max = specs.storage_limit`.

### 4.3. Incident-to-Maintenance Flow
If a Manager sees an Incident (e.g., "Server Rack 4 overheating"), they click "Resolve via Maintenance." This:
1.  Marks the Incident as `Resolved`.
2.  Opens the Maintenance form with the Resource ID pre-filled.
3.  Prevents any new users from booking that rack until the maintenance end date.

---

# Page-by-Page Functional Breakdown (Detailed)

## 1. Public & Authentication Pages

### 1.1. Landing Home Page
*   **Hero Section:** A high-level summary of the Data Center (Total nodes, storage capacity).
*   **Live Status Component:** A grid of 4 "Status Cards":
    *   *Total Resources:* (Count from DB).
    *   *Available Now:* (Resources - Active Reservations).
    *   *Active Users:* (Count of approved users).
    *   *System Status:* (Green "Operational" or Red "Global Maintenance" via `settings` table).
*   **Quick Links:** Buttons for "View Catalog" (Guest) and "Request Access."

### 1.2. Public Catalog (Read-Only)
*   **Filter Sidebar:** 
    *   Checkboxes for Categories (Server, VM, etc.).
    *   Search bar for resource names.
*   **Resource Table/Grid:**
    *   Columns: Name, Category, Main Specs (CPU/RAM).
    *   **Status Badge:** Logic-based (Available / In Use / Maintenance).
    *   **"Login to Reserve" Tooltip:** Replaces the action button for unauthenticated users.

### 1.3. Multi-Step Registration Form
*   **Step 1 (Identity):** Full Name, Email, Password, Profession.
*   **Step 2 (Justification):** A `textarea` with a character counter (Vanilla JS) to ensure the user provides a detailed reason for access.
*   **Submission Handling:** Redirects to a "Success" page with a unique Application ID for tracking.

---

## 2. Internal User Dashboard (The "Client" Area)

### 2.1. User Overview (Main Dashboard)
*   **Active Reservations Widget:** A carousel or list of resources currently assigned to the user, showing time remaining (JS countdown timer).
*   **Notification Bell:** A dropdown showing the last 5 status changes (e.g., "Your VM request was approved").
*   **Usage Summary:** Progress bars showing used vs. allocated capacity (if applicable).

### 2.2. The Resource Configurator (Booking Page)
*   **Resource Detail Modal/View:** Triggered when a user clicks "Reserve."
*   **Dynamic Form Components (The JSON logic):**
    *   **Date Range Picker:** Two date inputs with JS validation to prevent selecting past dates or end dates before start dates.
    *   **Conditional Fieldset:** A container that stays empty unless the resource is a VM.
        *   *Vanilla JS Logic:* On page load, `fetch()` resource metadata. If `specs->allow_os == true`, inject a `<select>` with OS options into the DOM.
    *   **Configuration Preview:** A "Summary Box" that updates in real-time as the user selects dates and specs, calculating the total duration.
    *   **Justification Input:** Mandatory text field explaining the specific project need.

### 2.3. Personal History & Reports
*   **Filterable Table:** Columns for Resource, Period, Status, and Manager Feedback.
*   **Action Column:** 
    *   **"Report Incident" Button:** Only visible if the reservation is `Active`. Opens a simple form to submit to the `incidents` table.
    *   **"View Reason" Button:** Only visible if the reservation was `Rejected`. Opens a modal showing the manager's justification.

---

## 3. Technical Manager Interface (The "Operator" Area)

### 3.1. Reservation Moderation Queue
*   **Request Cards:** Each card displays:
    *   Requester Info (Name/Role).
    *   Requested Resource & Timeframe.
    *   **Conflict Warning:** A red text alert if another approved reservation overlaps with this request (calculated via backend query).
*   **Decision Workspace:** 
    *   Side-by-side view of the User's Justification vs. a Manager Response box.
    *   **Approval/Rejection Buttons:** Disabled via JS until the "Manager Justification" field is filled.

### 3.2. Inventory Builder
*   **Resource CRUD Form:**
    *   Basic Inputs: Name, Category, Physical Location.
    *   **Dynamic Spec Builder:** A UI where managers can add "Key-Value" pairs (e.g., "GPU: NVIDIA A100").
        *   *UI Component:* An "Add Row" button that appends two inputs (Key and Value) to a list. On Save, JS gathers these into a single JSON object to store in the `specs` column.
*   **Lifecycle Toggle:** A simple "Enable/Disable" switch. If disabled, the resource is hidden from the user catalog.

### 3.3. Maintenance Scheduler
*   **Calendar View:** A custom-built grid (or list) showing all resources.
*   **Maintenance Form:** 
    *   Dropdown to select a resource.
    *   Text area for "Maintenance Task Description."
    *   **Impact Check:** A "Check Impact" button that runs a JS-based fetch to see how many *existing* approved reservations will be cancelled if this maintenance is scheduled.

---

## 4. System Administrator Interface (The "Controller" Area)

### 4.1. Vetting Center (User Applications)
*   **Application Review Table:** Shows pending guest registrations.
*   **Profile Deep-Dive:** A view showing the applicant's history (if they were previously rejected) and their professional justification.
*   **Final Decision:** Admin assigns a role (User or Manager) upon approval.

### 4.2. Global Control Center
*   **System "Kill Switch":** A large, protected button to toggle `facility_maintenance`. 
    *   *Interaction:* Requires a confirmation pop-up ("Are you sure? This will block all new reservations").
*   **Role Management:** A searchable user list where roles can be swapped (e.g., promoting a User to a Manager).

### 4.3. Audit & Transparency Log
*   **The "Paper Trail":** A chronological list of every justification written in the system.
    *   *Columns:* Timestamp, Actor (Who), Action (Approved/Rejected/Modified), Target (User/Resource), and the Justification Text.
    *   **Search Filter:** Ability to filter logs by specific Manager or User to track patterns of behavior.

---

## 5. UI/UX Component Specifications (Non-Framework)

*   **Modals:** Use the HTML `<dialog>` element for all pop-ups (Approvals, Details, Incidents). It provides native focus management and "Escape" key closing.
*   **Notifications:** A fixed-position `div` container. Use Vanilla JS to create and append "Toast" elements that fade out after 5 seconds using `setTimeout`.
*   **Tables:** 
    *   Sticky headers for long inventory lists.
    *   `data-attribute` usage: Store Resource IDs in `data-id` attributes on buttons to simplify JS event delegation.
*   **Loading States:** Implement a "Loading" overlay for form submissions to prevent double-clicking and duplicate database entries.

## 6. Data Integrity Constraints (Logic Level)

*   **Justification Enforcement:** All `status` transitions (`Pending` -> `Approved/Rejected`) must be accompanied by a `request->input('justification')`. The database schema should not allow nulls for these specific action-logs.
*   **Role Hierarchy:** Laravel Middleware must ensure a `Manager` cannot access the `Admin` Vetting Center, and a `User` cannot access the `Inventory Builder`.
