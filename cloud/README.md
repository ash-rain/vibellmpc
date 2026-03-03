# VibeLLMPC — Cloud Edge

The cloud-side Laravel application that powers `vibellmpc.com`. Handles device registration, QR-based pairing, user accounts, tunnel ingress, and the admin panel (Filament).

## Prerequisites

- PHP 8.2+ with SQLite extension
- Composer
- Node.js & npm
- The `vibellmpc/common` package (at `../packages/vibellmpc-common/`)

For production: MySQL and Redis (local dev uses SQLite + database drivers by default).

## Quick Start

```bash
composer setup
php artisan serve
```

This installs dependencies, creates `.env`, generates the app key, runs migrations, seeds test data, and builds frontend assets.

## Development

```bash
# Dev server with queue worker, log streaming, and Vite HMR
composer run dev

# Run tests
php artisan test

# Lint / format
./vendor/bin/pint
```

## Admin Panel

Filament admin panel is available at `/admin`. The seeder creates an admin user automatically:

- **Email:** `admin@vibellmpc.com`
- **Password:** `password`

The panel provides CRUD management for:
- **Devices** — view/edit registered devices, status, pairing info
- **Leads** — manage waitlist signups, export to CSV

## Device Pairing Flow

1. QR code on device encodes `https://vibellmpc.com/id/{uuid}`
2. User scans QR, gets redirected to login/register if not authenticated
3. User claims the unclaimed device
4. Cloud generates an encrypted Sanctum API token for the device
5. Device polls `GET /api/devices/{uuid}/status` to retrieve pairing token

## API Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/devices/{uuid}/status` | — | Device status and pending pairing token |
| `POST` | `/api/devices/{uuid}/claim` | Sanctum | Claim a device for the authenticated user |
| `GET` | `/api/user` | Sanctum | Current authenticated user |

## Project Structure

```
app/
├── Filament/
│   ├── Resources/            # DeviceResource, LeadResource (admin CRUD)
│   ├── Exporters/            # LeadExporter (CSV export)
│   └── Widgets/
├── Http/
│   ├── Controllers/
│   │   ├── DevicePairingController.php   # QR pairing web flow
│   │   └── Api/DeviceController.php      # Device API endpoints
│   └── Requests/
├── Livewire/
│   └── WaitlistForm.php      # Landing page email capture
├── Models/                   # User, Device, Lead
├── Services/
│   └── DeviceRegistryService.php  # Device lookup, claiming, registration
├── Exceptions/               # DeviceNotFoundException, DeviceAlreadyClaimedException
└── Providers/
    └── Filament/AdminPanelProvider.php
```

## Environment Variables

Key variables in `.env` (see `.env.example` for the full list):

| Variable | Local Default | Production | Description |
|---|---|---|---|
| `DB_CONNECTION` | `sqlite` | `mysql` | Database driver |
| `SESSION_DRIVER` | `database` | `redis` | Session storage |
| `QUEUE_CONNECTION` | `sync` | `redis` | Queue driver |
| `CACHE_STORE` | `database` | `redis` | Cache driver |
| `MAIL_MAILER` | `log` | `smtp` | Mail driver |

> For local dev, set `DB_CONNECTION=sqlite` and swap Redis drivers to `database`/`sync`. For production, use MySQL + Redis as shown in `.env.example`.

## Seeded Test Data

The `DeviceSeeder` creates test devices for local development:

| UUID | Status |
|---|---|
| `00000000-0000-0000-0000-000000000001` | Unclaimed (known test device) |
| `00000000-0000-0000-0000-000000000002` | Claimed |
| 3 random UUIDs | Unclaimed |
