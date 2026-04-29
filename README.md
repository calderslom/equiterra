# Equiterra

A full-stack web application built for a real client — my brother, a Farrier (equine hoof
care practitioner) who specializes in show jumping horses. The system manages client
records, invoices, and horse records including photos, veterinary records, and shoeing protocols.

Originally developed as a team project, this repository is my maintained and containerized
version with additional improvements.

Built with PHP, HTML/CSS, and MySQL on Apache with Docker.

---


## Quick Start

#### Clone the repository
```bash
git clone https://github.com/your-username/equiterra.git
```
#### Navigate to the root directory
```bash
cd equiterra
```

#### Build and run the service
```bash
docker compose up
```

- Docker builds the images locally,
spins up MySQL, and injects the full database schema and demonstration seed 
data automatically on first run.

#### Access the service as either an Admin or Client

-   [Open the portal in a browser](http://localhost:8080) and login with either user role


### User Roles

| Role | Username | Password | Privileges | 
|---|---|---|---|
| **Admin (Farrier)** | `admin` | `open sesame!` | Full CRUD Access — add and edit all records, manage invoices, view all horses and clients |
| **Client** | `rdunmore` | `demo1234` | Identity-Based Read Access — can view only their own horses, shoeing protocols, analysis records, and invoices |


## Screenshots

### Login
![Login page — shows the Equiterra login form with username and password fields](docs/screenshots/login.png)

### Admin Dashboard
![Admin dashboard — shows the home page with navigation links to Horses, Barns, Clients, and Account](docs/screenshots/admin_dashboard.png)

### Horse List
![Horse list (admin) — shows a searchable table of all horses with their owner names](docs/screenshots/admin_horses.png)

### Horse Detail
![Horse detail page — shows full horse record including breed, discipline, height, conformation notes, and links to shoeing protocols, images, and analysis](docs/screenshots/horse_detail.png)

### Shoeing Protocol
![Shoeing protocol page — shows the current and historical shoeing protocols for a horse, with left front, right front, left rear, right rear fields and notes](docs/screenshots/shoeing_protocol.png)

### Image Gallery
![Image gallery — shows uploaded hoof and conformation photos for a specific horse with upload and delete controls for admins](docs/screenshots/images.png)

### Client Detail & Invoices
![Client detail page — shows client contact information and a table of invoices with paid/unpaid status and a change status button for admins](docs/screenshots/client_invoices.png)

### Add Invoice
![Add invoice page — shows the invoice creation form with farrier dropdown, status, date, and dynamically added service line items each with their own price](docs/screenshots/add_invoice.png)

---

## Background

This project originated as a three-person university group project for CPSC 471
(Database Management Systems) at the University of Calgary.

My core responsibilities were:

- Gathering requirements directly from the client (my brother) and translating them into
  use cases that drove the system design
- Iterative refinements of system features based on client feedback throughout the project
- Designing the database schema and refining it through normalization (1NF, 2NF, 3NF)
  and functional dependency analysis
- Building the Enhanced Entity-Relationship Diagram (EERD) and the Relational Model (RM)
  diagram from which the schema was derived
- All SQL — table definitions, triggers, stored procedures, and views
- All PHP related to database interaction: retrieval, insertion, update, and session
  management across every page


Recently I revived and containerized the project using Docker for two reasons: it makes
the project simple to demo locally, and it makes real-world deployment straightforward; 
For example, Docker images can be pushed directly to AWS ECR and deployed via ECS 
without any configuration specific to the environment.

Two improvements were made to the original system during this revival:

- **Invoices now support itemized line items** — each service performed has its own
  description and price, and the invoice total is calculated dynamically. Previously a
  single price field lived on the invoice itself.
- **Images are now stored locally** — uploaded files are saved to a structured directory
  (`uploads/images/horses_by_name/<horse>/`) and the file path is stored in the database.
  Previously images were stored as external URLs, which was a deadline workaround.

Additionally, a set of seed data is supplied and injected automatically when the service is first
deployed, populating the system with demo clients, horses, barns, invoices, and shoeing
protocols for demonstration purposes.



## Contributors

- **Aidan Sloman** — Backend development, database design, SQL, and client-driven system design  
- **Omar Ragab** — Frontend development (HTML/CSS layout and styling)  
- **Youssef Samaan** — Project documentation and reports 



## Tech Stack

- **Backend:** PHP 8.1
- **Database:** MySQL 8.0
- **Web Server:** Apache (via official `php:8.1-apache` Docker image)
- **Frontend:** HTML, CSS, JavaScript (no framework) — designed by Omar Ragab
- **Containerization:** Docker + Docker Compose



## Database Design

### Enhanced Entity-Relationship Diagram (EERD)
![EERD showing all entities, attributes, and relationships in the Equiterra system](docs/equiterra_db_EERD.png)

### Relational Model (RM)
![Relational model derived from the EERD showing all tables, primary keys, and foreign key relationships](docs/equiterra_db_RM.png)

---

## Database Overview

### Tables

| Table | Description |
|---|---|
| `Web_user` | Unified authentication table for all users. `User_type` is either `Admin` or `Client`. Admin was chosen over Farrier since not all admins are farriers, but all farriers in this system are admins. |
| `Farrier` | Farrier business identity, linked to `Web_user` by username |
| `Client` | Client records linked to their horses and invoices |
| `Horse` | Horse records linked to a client (owner) and barn |
| `Barn` | Barn records with full address and contact information |
| `Shoeing_Protocol` | Per-horse shoeing records. Only one protocol is active (`Status=1`) at a time — adding a new protocol automatically archives all previous ones via a stored procedure |
| `Analysis` | Per-horse analysis files (Equitage, Radiograph, Posture) with editable details |
| `Image` | File paths for uploaded horse images, organized by horse |
| `Invoice` | Invoices linked to a client and farrier with paid/unpaid status |
| `Invoice_Item` | Individual line items within an invoice, each with their own description and price |
| `Medical_Record` | Veterinary and practitioner records for a horse |
| `Practitioner` | Veterinarians and other practitioners referenced in medical records |

### Key Stored Procedures

| Procedure | Purpose |
|---|---|
| `GetUserLogin(username_or_email, password)` | Authenticate a user by username or email |
| `AddWebUser(...)` | Register a new client account |
| `GetHorses()` | List all horses with owner names (Admin) |
| `GetClientHorses(cusername)` | List horses owned by a specific client |
| `GetHorseDetails(hname)` | Full horse record including owner name |
| `AddShoeingProtocol(...)` | Add a new protocol and archive all previous ones |
| `GetShoeingProtocolDates(hname)` | List all protocol dates for a horse |
| `AddAnalysis(...)` | Add an analysis record |
| `GetHorseAnalysis(hname)` | List all analysis entries for a horse |
| `GetBarnHorses(bname)` | List horses stabled at a barn |
| `AddInvoice(...)` | Create an invoice |
| `AddInvoiceItem(...)` | Add a line item to an invoice |
| `GetInvoiceTotal(inumber)` | Sum all line item prices for an invoice |
| `ChangeStatus(status, invoice_number)` | Toggle invoice paid/unpaid |

### Views

| View | Description |
|---|---|
| `Num_clients_by_barn` | Count of distinct clients per barn |
| `Num_horses_by_barn` | Count of horses per barn |

---
