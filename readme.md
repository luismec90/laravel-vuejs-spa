# Meal Calories App

----
## Requirementes


 * User must be able to create an account and log in.
 * When logged in, a user can see a list of his meals, also he should be able to add, edit and delete meals. (user enters calories manually, no auto calculations!)
 * Implement at least three roles with different permission levels: a regular user would only be able to CRUD on their owned records, a user manager would be able to CRUD users, and an admin would be able to CRUD all records and users.
 * Each entry has a date, time, text, and num of calories.
Filter by dates from-to, time from-to (e.g. how much calories have I had for lunch each day in the last month if lunch is between 12 and 15h).
 * User setting – Expected number of calories per day.
 * When displayed, it goes green if the total for that day is less than expected number of calories per day, otherwise goes red.
REST API.
 * Make it possible to perform all user actions via the API, including authentication
 * In any case, you should be able to explain how a REST API works and demonstrate that by creating functional tests that use the REST Layer directly. Please be prepared to use REST clients like Postman, cURL, etc. for this purpose.
 * All actions need to be done client side using AJAX (you must implement a single-page app experience), refreshing the page is not acceptable.
 * Minimal UI/UX design is needed. You will not be marked on graphic design. However, do try to keep it as tidy as possible.
 * Bonus: unit and e2e tests.

## Stack
 * Mysql
 * Laravel
 * VueJs


## Usage
 * composer install
 * php artisan key:generate
 * php artisan jwt:secret
 * php artisan migrate:refresh
 * php artisan db:seed

----
## Routes
![Routes](https://content.screencast.com/users/luismec90/folders/Snagit/media/0d45f397-bccc-4c8d-b016-25244689e371/12.30.2017-12.32.png "Routes")
