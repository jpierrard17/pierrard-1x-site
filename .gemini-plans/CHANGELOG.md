# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Created `settings` table migration (`create_settings_table`).
- Created `add_first_last_name_to_users_table` migration.
- Created `Setting` Eloquent model.
- Created Hevy integration scaffolding: `HevyController.php`, `HevyService.php`, `HevyAuthRequest.php`, and `config/hevy.php`.
- Added routes for Hevy integration (`/integrations/hevy`, `/integrations/hevy/api-key`, `/integrations/hevy/disconnect`) in `routes/web.php`.
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
- Updated `.env` with the new `HEVY_API_KEY`.
- Implemented Hevy data visualization frontend in `Integrations/Hevy.vue` with data fetching, loading states, and display.
- Added `fetchData` method to `HevyController` to fetch data from `HevyService` and return it as JSON.
- Added `GET /integrations/hevy/data` route to `routes/web.php`.
- Refactored `HevyService` to accept API key in constructor and implemented actual API call for `verifyApiKey`.
- Updated `HevyController` to instantiate `HevyService` with the user's API key and to use `HevyService` for API key verification.
- Modified `User` model to include `first_name` and `last_name`, remove `name`, add a `settings` relationship, and implement `hevy_api_key` accessor/mutator.
- Modified `create_settings_table` migration to include `user_id`, `key`, `value`, and `type` columns.
- Modified `add_first_last_name_to_users_table` migration to add `first_name` and `last_name` and drop the `name` column.
- Implemented the Hevy integration frontend in `Integrations/Hevy.vue`, allowing users to connect/disconnect their Hevy account and submit API keys.
- Updated `.env` and `.env.example` with `HEVY_API_KEY` and `HEVY_API_URL`.
- Transformed `Professional.vue` into a personal portfolio and CV page, incorporating detailed information from the user's resume.
- Reconfigured `AppLayout.vue` to remove the default Laravel `ApplicationMark` from the left side of the navbar, and updated the user profile dropdown to be triggered by the user's profile photo.
- Removed the default Laravel `ApplicationMark` from `GuestLayout.vue`.
- Enabled Jetstream profile photo management by uncommenting `Features::profilePhotos()` in `config/jetstream.php`.
- Refactored navigation to use `GuestLayout.vue` for unauthenticated users and `AppLayout.vue` for authenticated users, implementing specific link requirements.
- Removed user registration functionality from the application.
- Recreated database with the updated name `pierrard-1x-site` and re-ran all migrations.
- Updated database name to `pierrard-1x-site` in `.env` and `.env.example`.
- Refactored core Jetstream Vue components to use PrimeVue components (`PrimaryButton`, `SecondaryButton`, `DangerButton`, `TextInput`, `Checkbox`, `InputError`, `Modal`, `DialogModal`, `ConfirmationModal`).
- Updated `PLAN.md` to reflect the completion of all data models and readiness for API integrations.
- Refactored Hevy models and migrations into a modular architecture (`app/Modules/Hevy`).
- Updated `PLAN.md` to include architectural principles: Service-Oriented Controllers, Modular Integrations, and Flexible Visualizations.
- Updated `PLAN.md` to include PrimeVue in the technology stack.

### Added
- Implemented Hevy data visualizations: Workout Frequency (bar chart) and Volume Progress (line chart).
- Added `fetchChartData` endpoint to `HevyController` and aggregation methods to `HevyService`.
- Installed `chart.js` dependency for Hevy data visualizations.
- Updated `hevy-plan.md` with visualization strategy.

### Fixed
- Fixed Hevy API connection issue by updating authentication header to `api-key` and using `/routines` endpoint for verification.
- Resolved `HTTP request returned status code 404` for Hevy API key verification by updating the endpoint in `HevyService.php` to `/me`.
- Resolved `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'name'` error by updating `UpdateUserProfileInformation.php` and `UpdateProfileInformationForm.vue` to use `first_name` and `last_name`.
- Fixed "Element is missing end tag" syntax error in `UpdateProfileInformationForm.vue`.
- Updated `AppLayout.vue` to use `first_name` and `last_name` for displayed user names and profile photo alt attributes.
- Removed old `2025_11_18_012855_add_hevy_api_key_to_users_table.php` migration file, as its functionality was replaced by the new settings table.
- Resolved `TypeError: can't access property "success", l.$page.props.flash is undefined` in `Integrations/Hevy.vue` by adding a conditional check for `$page.props.flash`.
- Resolved profile photo not loading on the Professional page by using the dynamic `$page.props.auth.user.profile_photo_url`.
- Resolved `TypeError: can't access property "name", s.$page.props.auth.user is null` in `AppLayout.vue` by conditionally rendering navigation elements based on user authentication status.
- Resolved navigation issue where authenticated users were seeing guest navigation on Home and Professional pages by reverting `Home.vue` and `Professional.vue` to use `AppLayout.vue`.
- Resolved Integrations dropdown alignment issue in `AppLayout.vue`.
- Resolved Ziggy error by explicitly naming the home route (`/`) as 'home' in `routes/web.php`.
- Resolved `MYSQL_EXTRA_OPTIONS` warning in Sail by defining the variable in the `.env` and `.env.example` files.
- Resolved Vite manifest error by correcting the main app entry file to `.ts` and fixing PrimeVue theme imports.
- Fixed Hevy sync pagination by using `page`/`pageSize` parameters instead of `offset`/`limit` (API requirement).
- Fixed Hevy API page indexing to start from page 0 (0-based indexing).
- Fixed missing `HasFactory` trait imports in all Hevy models (`HevyWorkout`, `HevyWorkoutExercise`, `HevyWorkoutSet`, `HevyExerciseTemplate`).
- Fixed missing `HasFactory` trait imports in Strava models (`StravaActivity`, `StravaGear`).
- Fixed `HevyWorkoutSet` migration to make `weight_kg`, `reps`, `distance_meters`, `duration_seconds`, and `rpe` nullable (supports cardio/stretching exercises).
- Fixed missing fillable fields in `HevyWorkoutSet` model (`index`, `set_type`, `distance_meters`, `rpe`).
- Fixed date casting for `start_time` and `end_time` in `HevyWorkout` model.
- Fixed foreign key constraint violation by auto-creating missing exercise templates during sync.
- Fixed "Sync Now" button type to prevent page reloads in Hevy and Strava integration pages.

### Added
- Implemented `syncWorkouts` method in `HevyService` with delta sync logic (only fetches new workouts).
- Added Strava access/refresh token accessors/mutators to `User` model.
- Added Strava configuration to `config/services.php` and `.env.example`.
- Created `StravaController` for Strava OAuth and data sync.
- Created `StravaService` for Strava API interactions.

### Changed
- Refactored Hevy visualizations to query local database instead of calling API directly.
- Updated Hevy sync to use page size of 10 (API maximum).
- **Display weights in lbs instead of kg for all Hevy visualizations.**

### Added
- **Implemented exercise-specific progress visualizations for Hevy integration:**
  - Exercise dropdown with search/filter functionality
  - Max weight chart showing progression over time
  - Volume per workout chart showing total weight × reps
  - Estimated 1RM chart using Epley formula (weight × (1 + reps/30))
- Added `getAvailableExercises()` method to HevyService
- Added `getExerciseProgressData()` method to HevyService
- Added `calculateEstimated1RM()` helper method
- Added `/integrations/hevy/exercises` endpoint
- Added `/integrations/hevy/exercise-progress` endpoint

### Fixed
- Fixed query issue in `getExerciseProgressData` using `whereHas` instead of `join` to avoid duplicate workouts

---

## [Unreleased] - Strava Visualizations (In Progress)

### Added
- **Strava visualization backend:**
  - Added `getActivityFrequencyData()` method to StravaService (activities per month)
  - Added `getDistanceProgressData()` method (km per month with unit conversion)
  - Added `getElevationProgressData()` method (feet per month with unit conversion)
  - Added `getActivityTypeBreakdown()` method (count and distance by type)
  - Added `getPaceAnalysis()` method (average pace for runs in min/km)
  - Added `getActivitiesWithRoutes()` method for route mapping
  - Added `getHeatmapData()` method for route frequency visualization
- **Strava controller endpoints:**
  - Added `GET /integrations/strava/charts` endpoint
  - Added `GET /integrations/strava/activities-with-routes` endpoint
  - Added `GET /integrations/strava/heatmap` endpoint
- **Dependencies:**
  - Installed Leaflet.js for interactive mapping
  - Installed polyline-encoded for Strava polyline decoding
- **Strava visualization frontend:**
  - Implemented 5 Chart.js visualizations (frequency, distance, elevation, activity types, pace)
  - Added interactive Leaflet.js maps for individual activity routes
  - Added heatmap view showing frequently-run routes (≥5 occurrences)
  - Added activity list with click-to-view route functionality
  - Integrated polyline decoding for Strava's encoded route data
  - Applied unit conversions (km for distance, feet for elevation, min/km for pace)

### Fixed
- Fixed heatmap rendering to properly handle polyline array
- Fixed heatmap initial center to use first route coordinates instead of hardcoded NYC location
- Added error handling for polyline decoding
- **Fixed backend to return polylines as proper JSON array using `values()` method**

### Changed
- Enhanced activity details display to show distance, moving time, pace, elevation gain, and heart rate metrics


