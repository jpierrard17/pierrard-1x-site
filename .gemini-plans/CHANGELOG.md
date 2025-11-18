# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Created `Integrations.vue`, `Integrations/Hevy.vue`, and `Integrations/Strava.vue` placeholder components.
- Added routes for `/integrations`, `/integrations/hevy`, and `/integrations/strava` within the authenticated middleware group.
- Updated `AppLayout.vue` to include an "Integrations" dropdown with "Hevy" and "Strava" links.
- Created `GuestLayout.vue` for public pages and updated `Home.vue` and `Professional.vue` to use it.
- Created public Home and Professional pages with their respective routes.
- Installed and configured Filament admin panel.
- Created remaining Hevy data models and migrations (`HevyRoutine`, `HevyRoutineExercise`, `HevyRoutineSet`, `HevyRoutineFolder`).
- Created Strava data models and migrations (`StravaActivity`, `StravaGear`).
- Integrated PrimeVue component library and PrimeIcons.
- Documented Hevy API endpoints and data structures (`hevy-plan.md`).
- Documented Strava API endpoints and data structures (`strava-plan.md`).

### Changed
- Refactored navigation to use `GuestLayout.vue` for unauthenticated users and `AppLayout.vue` for authenticated users, implementing specific link requirements.
- Removed user registration functionality from the application.
- Recreated database with the updated name `pierrard-1x-site` and re-ran all migrations.
- Updated database name to `pierrard-1x-site` in `.env` and `.env.example`.
- Refactored core Jetstream Vue components to use PrimeVue components (`PrimaryButton`, `SecondaryButton`, `DangerButton`, `TextInput`, `Checkbox`, `InputError`, `Modal`, `DialogModal`, `ConfirmationModal`).
- Updated `PLAN.md` to reflect the completion of all data models and readiness for API integrations.
- Refactored Hevy models and migrations into a modular architecture (`app/Modules/Hevy`).
- Updated `PLAN.md` to include architectural principles: Service-Oriented Controllers, Modular Integrations, and Flexible Visualizations.
- Updated `PLAN.md` to include PrimeVue in the technology stack.

### Fixed
- Resolved Integrations dropdown alignment issue in `AppLayout.vue`.
- Resolved Ziggy error by explicitly naming the home route (`/`) as 'home' in `routes/web.php`.
- Resolved `MYSQL_EXTRA_OPTIONS` warning in Sail by defining the variable in the `.env` and `.env.example` files.
- Resolved Vite manifest error by correcting the main app entry file to `.ts` and fixing PrimeVue theme imports.
