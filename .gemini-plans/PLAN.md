# Personal Website and Fitness Dashboard Plan

This document outlines the plan for building a personal website with an integrated fitness dashboard.

## 1. Project Vision

A personal website to showcase professional and personal interests, with a private admin section to track and analyze fitness data from various sources like Strava and Hevy.

## 2. High-Level Features

### Public-Facing Site
*   **Home Page:** A landing page that can display selected fitness activities.
*   **Professional Page:** A page detailing professional accomplishments, skills, and experience.
*   **Static Pages:** About Me, Contact, etc.

### Admin Dashboard (Private)
*   **User Authentication:** Secure login for the admin user.
*   **Strava Integration:**
    *   Fetch and store activities (runs, walks, cycling).
    *   Display activity history with charts and data visualizations.
    *   Generate reports on progress and trends.
*   **Hevy Integration:**
    *   Fetch and store lifting history.
    *   Display lifting progress and personal records.
    *   View current workout routines and exercises.
    *   A feature to create new routines and push them to the Hevy app.
*   **Data Synchronization:** Regularly sync data from Strava and Hevy.

## 3. Technology Stack

The application will be built using the following technologies:

*   **Backend Framework:** Laravel
*   **Frontend:** Vue.js with TypeScript, managed with Inertia.js
*   **UI Components:** PrimeVue
*   **Styling:** Tailwind CSS (no Bootstrap)
*   **Authentication & Scaffolding:** Laravel Jetstream
*   **Admin Panel:** Filament
*   **Database:** MySQL
*   **Development Environment:** Docker (via Laravel Sail)

## 4. Architectural Principles

*   **Service-Oriented Controllers:** To ensure a clean separation of concerns, controllers will remain lean. All business logic, data processing, and interactions with external APIs will be encapsulated within dedicated service classes (e.g., `StravaService`, `HevyService`).
*   **Modular & Extensible Integrations:** The application will be designed to easily accommodate new integrations. Each external service will have its own dedicated service class. These classes will implement a common `IntegrationInterface` to standardize methods for fetching, syncing, and processing data.
*   **Flexible Visualizations:** The data visualization components will be designed modularly, allowing for new charts, reports, and data views to be developed and added in the future with minimal friction.
*   **Test Coverage:** All code will adhere to the established requirement of a minimum 75% test coverage.

## 5. Display Units

All fitness data should be displayed using the following units (data may be stored in different units internally):

*   **Weight:** lbs (pounds) - stored as kg, converted for display
*   **Distance:** 
    *   Running/Cycling: km (kilometers)
    *   Short distances (e.g., Farmer's Walk): m (meters)
*   **Vertical/Height:** feet and inches

## 6. Development Roadmap

This is a high-level roadmap tailored for the chosen Laravel stack.

1.  **Phase 1: Project Setup & Public Pages**
    *   Set up a new Laravel project using Docker (Laravel Sail).
    *   Install and configure Laravel Jetstream with Inertia.js and Vue.js (with TypeScript).
    *   Configure the MySQL database connection.
    *   Create initial migrations for any public-facing content (if needed).
    *   Build the Vue components and routes for the public Home and Professional pages.
    *   Establish testing foundation with Pest/PHPUnit.

2.  **Phase 2: Admin Dashboard & Authentication**
    *   Jetstream will provide the core authentication system.
    *   Install and configure Filament for the admin panel.
    *   Secure the admin panel, ensuring only authorized users can access it.

3.  **Phase 3: API Integrations**
    *   Research and obtain API keys for Strava and Hevy.
    *   Design and implement a common `IntegrationInterface` to ensure all integration services have a consistent structure.
    *   Create database migrations for storing Strava activities and Hevy workout data.
    *   Develop the dedicated `StravaService` and `HevyService` classes that implement the interface.
    *   Create scheduled commands to regularly sync data from the APIs.

4.  **Phase 4: Data Visualization**
    *   Create Filament pages and resources to display the fitness data.
    *   Integrate a charting library (e.g., Chart.js) with the Vue components within Filament.
    *   Build flexible API endpoints in Laravel to provide data to the frontend charts.
    *   Ensure visualization components are modular to allow for future expansion.

5.  **Phase 5: Advanced Features**
    *   Implement the "Create Hevy Routine" feature within a dedicated Filament page. This will involve making POST requests back to the Hevy API via the `HevyService`.
    *   Develop the logic to select and publish specific activities to the public-facing home page.

## 7. Next Steps

*   All data models and migrations for Hevy and Strava integrations are now complete.
*   Proceed with Phase 3: API Integrations.
