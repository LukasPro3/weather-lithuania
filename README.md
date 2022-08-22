# Weather in Lithuania

This application shows current weather and forecast in Lithuanian cities.
By typing to the search bar you can find cities and check weather for upcoming four days.
The application uses meteo API for data collection - which provides a wide variety of weather data.
Collected data is stored in MySQL database.

## How to Install and Run the Project

To run on a local server:
1. Install XAMPP (or any equivalent)
2. Move files to XAMPP folder where your server will run.
3. Launch file 'miestai_up.php' to get all available cities names to your DB.
4. As you have all lithuanian cities names now you can launch 'duomenys.php' to get all cities weather data. It can take quite a lot of time.
5. After all the weather data is collected from API you can fully use 'weather.php'. Type into search bar to find your city and check the weather.

I suggest to update data every 3 hours by setuping up cron job '0 */3 * * * ./duomenys.php' because API allows only 20k of requests in 24hours.
