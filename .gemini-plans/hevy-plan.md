# Hevy API Endpoint Analysis

This document outlines the available endpoints in the Hevy API, their data structures, and their potential use cases for the pierrard-1x-site project.

**API Base URL:** `https://api.hevyapp.com`
**Authentication:** Requires an API key, available to Hevy Pro users.

---

## Workouts

### `GET /v1/workouts`
*   **Description:** Retrieves a list of workouts.
*   **Parameters:**
    *   `limit` (integer): Number of workouts to return.
    *   `offset` (integer): Number of workouts to skip.
*   **Response Body (200 OK):**
    ```json
    {
      "workouts": [
        {
          "id": "string",
          "title": "string",
          "notes": "string",
          "start_time": "2025-11-17T15:30:00Z",
          "end_time": "2025-11-17T16:30:00Z",
          "exercises": [
            {
              "id": "string",
              "exercise_template_id": "string",
              "sets": [
                {
                  "id": "string",
                  "reps": 0,
                  "weight_kg": 0,
                  "distance_km": 0,
                  "duration_seconds": 0,
                  "is_warmup": false,
                  "is_dropset": false,
                  "is_failed": false,
                  "notes": "string"
                }
              ]
            }
          ]
        }
      ]
    }
    ```
*   **Potential Use Case:** Fetching all historical workouts to display in the admin dashboard and for data analysis.

### `POST /v1/workouts`
*   **Description:** Creates a new workout.
*   **Request Body:**
    ```json
    {
      "title": "string",
      "notes": "string",
      "start_time": "2025-11-17T15:30:00Z",
      "end_time": "2025-11-17T16:30:00Z",
      "exercises": [
        {
          "exercise_template_id": "string",
          "sets": [
            {
              "reps": 0,
              "weight_kg": 0,
              "distance_km": 0,
              "duration_seconds": 0,
              "is_warmup": false,
              "is_dropset": false,
              "is_failed": false,
              "notes": "string"
            }
          ]
        }
      ]
    }
    ```
*   **Response Body (201 Created):** The created workout object.
*   **Potential Use Case:** While our plan is to create routines, this could be used for creating individual, non-routine workouts if needed.

---

## Routines

### `GET /v1/routines`
*   **Description:** Retrieves a list of routines.
*   **Response Body (200 OK):**
    ```json
    {
      "routines": [
        {
          "id": "string",
          "name": "string",
          "notes": "string",
          "exercises": [
            {
              "exercise_template_id": "string",
              "sets": [
                {
                  "reps": 0,
                  "weight_kg": 0,
                  "is_warmup": false,
                  "notes": "string"
                }
              ]
            }
          ]
        }
      ]
    }
    ```
*   **Potential Use Case:** Fetching all existing routines to display in the admin dashboard.

### `POST /v1/routines`
*   **Description:** Creates a new routine.
*   **Request Body:**
    ```json
    {
      "name": "string",
      "notes": "string",
      "exercises": [
        {
          "exercise_template_id": "string",
          "sets": [
            {
              "reps": 0,
              "weight_kg": 0,
              "is_warmup": false,
              "notes": "string"
            }
          ]
        }
      ]
    }
    ```
*   **Response Body (201 Created):** The created routine object.
*   **Potential Use Case:** This is a core feature. We will use this to create new workout routines from our admin dashboard and push them to Hevy.

---

## Exercise Templates

### `GET /v1/exercise_templates`
*   **Description:** Retrieves a list of exercise templates.
*   **Parameters:**
    *   `search` (string): Search for an exercise by name.
*   **Response Body (200 OK):**
    ```json
    {
      "exercise_templates": [
        {
          "id": "string",
          "name": "string",
          "type": "reps_and_weight",
          "primary_muscles": ["string"],
          "secondary_muscles": ["string"],
          "equipment": ["string"]
        }
      ]
    }
    ```
*   **Potential Use Case:** This is crucial for creating new routines. We'll need to fetch available exercises to add to a routine.

---

## Exercise History

### `GET /v1/exercise_history`
*   **Description:** Retrieves the history for a specific exercise.
*   **Parameters:**
    *   `exercise_template_id` (string, required): The ID of the exercise to get the history for.
*   **Response Body (200 OK):**
    ```json
    {
      "exercise_history": [
        {
          "workout_id": "string",
          "start_time": "2025-11-17T15:30:00Z",
          "sets": [
            {
              "reps": 0,
              "weight_kg": 0,
              "is_warmup": false,
              "is_dropset": false,
              "is_failed": false,
              "notes": "string"
            }
          ]
        }
      ]
    }
    ```
*   **Potential Use Case:** This is a key feature for tracking progress on a specific lift (e.g., showing a chart of bench press progress over time).

---

## Webhooks

### `POST /v1/webhook_subscriptions`
*   **Description:** Creates a new webhook subscription.
*   **Request Body:**
    ```json
    {
      "url": "string",
      "event_types": ["string"]
    }
    ```
*   **Response Body (201 Created):**
    ```json
    {
      "id": "string",
      "url": "string",
      "event_types": ["string"]
    }
    ```
*   **Potential Use Case:** Could be used to automatically sync data from Hevy to our application in real-time, instead of relying on scheduled commands. This is a more advanced feature to consider.
