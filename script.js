
var unitIsCelcius = true;
var globalForecast = [];

// Maps the API's icons to the ones from https://erikflowers.github.io/weather-icons/
var weatherIconsMap = {
  "giedra": "wi-day-sunny",
  "mažai debesuota": "wi-day-cloudy",
  "debesuota": "wi-cloudy",
  "nedidelis lietus": "wi-showers",
  "lietus": "wi-day-hail",
  "smarkus lietus": "wi-thunderstorm",
  "nedidelis sniegas": "wi-snow",
  "sniegas": "wi-snow",
  "smarkus sniegas": "wi-snow",
  "rūkas": "wi-fog",
};

$(document).ready(function() {
  setInterval(time, 1000);
});

function time() {
  $.ajax({
      url: 'http://localhost/orai/galutinis.php',
      success: function(data) {
          $('#time').html(data);
      },
  });
}