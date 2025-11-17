# Strava API Endpoint Analysis

This document outlines the relevant endpoints in the Strava API for the pierrard-1x-site project, focusing on data retrieval for analysis and visualization.

**API Base URL:** `https://www.strava.com/api/v3`
**Authentication:** OAuth 2.0

---

## Activities

### `GET /athlete/activities`
*   **Description:** Retrieves a list of activities for the authenticated athlete. This will be the primary endpoint for fetching user activity data.
*   **Parameters:** `before` (timestamp), `after` (timestamp), `page`, `per_page`.
*   **Key Response Fields:**
    ```json
    [
      {
        "id": "integer",
        "name": "string",
        "distance": "float", // meters
        "moving_time": "integer", // seconds
        "elapsed_time": "integer", // seconds
        "total_elevation_gain": "float", // meters
        "type": "string", // e.g., "Run", "Ride"
        "sport_type": "string",
        "start_date_local": "datetime",
        "timezone": "string",
        "start_latlng": ["float", "float"],
        "end_latlng": ["float", "float"],
        "map": {
          "id": "string",
          "summary_polyline": "string", // Encoded polyline
          "resource_state": "integer"
        },
        "gear_id": "string"
      }
    ]
    ```
*   **Potential Use Case:** Fetching all historical activities to build reports, calculate statistics (total distance, time, elevation), and display a list of recent activities.

### `GET /activities/{id}`
*   **Description:** Retrieves a specific activity by its ID.
*   **Parameters:** `include_all_efforts` (boolean).
*   **Key Response Fields:** Same as `/athlete/activities` but more detailed, including:
    ```json
    {
      // ... all fields from the list view
      "description": "string",
      "calories": "float",
      "average_speed": "float", // meters per second
      "max_speed": "float",
      "average_cadence": "float",
      "average_watts": "float",
      "average_heartrate": "float",
      "max_heartrate": "float",
      "map": {
        "id": "string",
        "polyline": "string", // Detailed, encoded polyline
        "resource_state": "integer"
      },
      "segment_efforts": [ /* Array of SegmentEffort objects */ ]
    }
    ```
*   **Potential Use Case:** Viewing the detailed page for a single activity, including its full route polyline and all segment efforts.

---

## Gears

### `GET /gear/{id}`
*   **Description:** Retrieves details for a specific piece of equipment (e.g., a bike or shoes) using its ID. The `gear_id` is available in the Activity object.
*   **Key Response Fields:**
    ```json
    {
      "id": "string",
      "primary": "boolean",
      "name": "string",
      "nickname": "string",
      "distance": "float", // meters
      "brand_name": "string",
      "model_name": "string",
      "description": "string"
    }
    ```
*   **Potential Use Case:** Correlating activities with specific gear to track mileage on shoes or bike components. For example, "Running shoes have 500km".

---

## Routes

### `GET /athletes/{id}/routes`
*   **Description:** Retrieves a list of routes created by the athlete.
*   **Key Response Fields:**
    ```json
    [
      {
        "id": "integer",
        "name": "string",
        "distance": "float",
        "elevation_gain": "float",
        "map": {
          "id": "string",
          "summary_polyline": "string"
        },
        "segments": [ /* Array of Segment objects */ ]
      }
    ]
    ```
*   **Potential Use Case:** Displaying a list of pre-defined routes the user has created.

---

## Segments & Segment Efforts

### `GET /segments/{id}`
*   **Description:** Retrieves details about a specific segment. Segments are portions of a road or trail where athletes can compete for time.
*   **Key Response Fields:**
    ```json
    {
      "id": "integer",
      "name": "string",
      "activity_type": "string",
      "distance": "float",
      "average_grade": "float",
      "elevation_high": "float",
      "elevation_low": "float",
      "map": {
        "polyline": "string"
      }
    }
    ```
*   **Potential Use Case:** Displaying details about segments that are part of an activity.

### `GET /segment_efforts/{id}`
*   **Description:** Retrieves a specific segment effort by its ID. An "effort" is an athlete's specific attempt at a segment.
*   **Potential Use Case:** While `GET /activities/{id}` can include all segment efforts, this could be used to fetch details of a specific effort if needed.

---

## Mapping Requirement

*   **Goal:** Display activity routes on a map.
*   **Data Source:** The `map.polyline` or `map.summary_polyline` fields from the Activity and Route objects contain encoded polylines.
*   **Implementation:**
    1.  Decode the polyline string into a series of latitude/longitude coordinates. Libraries are available for this (e.g., `polyline-decode` in JavaScript).
    2.  Use a mapping library like **OpenStreetMap** (via Leaflet.js or OpenLayers) to render the coordinates as a path on an interactive map.
    3.  This will be a key component of the activity detail view.
