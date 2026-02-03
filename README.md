The new ecoBuddy system is an ecological facility management system.

## Project Overview
ecoBuddy is a full stack web-based application which is aimed at mapping and managing ecological resources in communities. It offers a centralised network where users can learn about the existence of green facilities and managers can keep the data about facilities updated in real-time.

## Features
* Interactive Mapping: It is a Leaflet.js-powered OpenStreetMap. Has user geolocation, custom markers on different eco-facilities.
* Live Search: Live Search is an XML-based AJAX search engine with real time suggestions and dynamic result cards as you type.
* Role-Based Access Control: - **Users: - Can search and view facilities.
* Managers: Are able to add, update status and delete facility records.
* Live-time Updates: AJAX-based updates to show the status of a bin (e.g., bin is full).
* Responsive Design: It is created using Bootstrap 5 to make it compatible with both desktop and mobile gadgets.

## Technical Stack
* Backend: PHP (OOP/MVC Pattern).
* Frontend: JavaScript (ES6 Classes), HTML5 and CSS3, Bootstrap 5.
* Database: SQLite (relational structure).
* API/Data Formats: JSON/markers on map and XML/search results.

## Project File Structure
* JavaScripts/: Client-side code to the map, AJAX search and facility management.
* Models/s: Data processing, database connection (Singleton) and entity classes.
* Views/-: Page layout templates and custom CSS.
* PHP scripts which process authentication, map data, and search requests.
* ecobuddy.SQLite.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db.metadata.sqlite.db

## Setup Instructions
1. Requirements Server A web server running PHP and enabled with SQLite PDO.
2. **Installation:**
* Put the project directory in the root of your web server (e.g. htdocs or www).
* Grant status updates and deletions write permissions to ecobuddy.sqlite.
3. Accessibility: Open up your browser and go to http://localhost/index.php.

## Usage
* Searching: Enter your search in the search bar to submit your findings in terms of title, town, or category.
* Map Interaction: Clicking You are here centers the map or clicking the rows of the table centers on particular markers.
* **Management (Admin Only):**
* Login: Manager (e.g., Username: `Admin`, Password: 654321).
* Add Facility: Click on the add facility link in the navigation bar.
- Update/ Delete: Select the Update/ Delete buttons in the facility table.
