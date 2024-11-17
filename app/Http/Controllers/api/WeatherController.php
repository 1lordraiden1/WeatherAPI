<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WeatherAPI;
use App\Models\Weather;
use Brick\Math\BigInteger;
use Cache;
use Illuminate\Http\Request;
use Log;

class WeatherController extends Controller
{
    use WeatherAPI;

    public $countries = [
        "Afghanistan",
        "Albania",
        "Algeria",
        "Andorra",
        "Angola",
        "Antigua and Barbuda",
        "Argentina",
        "Armenia",
        "Australia",
        "Austria",
        "Azerbaijan",
        "Bahamas",
        "Bahrain",
        "Bangladesh",
        "Barbados",
        "Belarus",
        "Belgium",
        "Belize",
        "Benin",
        "Bhutan",
        "Bolivia",
        "Bosnia and Herzegovina",
        "Botswana",
        "Brazil",
        "Brunei",
        "Bulgaria",
        "Burkina Faso",
        "Burundi",
        "Cabo Verde",
        "Cambodia",
        "Cameroon",
        "Canada",
        "Central African Republic",
        "Chad",
        "Chile",
        "China",
        "Colombia",
        "Comoros",
        "Congo (Congo-Brazzaville)",
        "Costa Rica",
        "Croatia",
        "Cuba",
        "Cyprus",
        "Czechia (Czech Republic)",
        "Denmark",
        "Djibouti",
        "Dominica",
        "Dominican Republic",
        "Ecuador",
        "Egypt",
        "El Salvador",
        "Equatorial Guinea",
    ];
    public function index()
    {
        $params = [
            "q" => "London",
            "aqi" => "no"
        ];

        $endpoint = "current.json";

        $num_of_requests = 5;

        $countries = [
            "london",
            "canada",
            "cairo",
            "berlin",
            "london",
            "canada",
            "cairo",
            "berlin",
            "london",
            "canada",
            "cairo",
            "berlin",
            "london",
            "canada",
            "cairo",
            "berlin",
        ];

        $cached_keys = $countries;

        $results = [];

        array_walk(
            $cached_keys,
            function ($country) use ($endpoint) {
                return $endpoint . "." . $country;
            }
        );

        if (Cache::has($cached_keys)) {
            $data = Cache::get($cached_keys);
            return $data;
        }

        foreach ($countries as $country) {
            $params["q"] = $country;
            $result = $this->WeatherAPI('get', $endpoint, $params);

            $test = $this->caching("$endpoint.$country", $result);

            array_push($results, $test);
        }
        return $results;
    }

    public function data()
    {
        #Args
        $params = [
            "q" => "London",
            "aqi" => "no"
        ];

        $endpoint = "current.json";

        # check if requested weather exists in DB

        $weather = Weather::where("name" , "==" , $params["q"])->first();
        if($weather && !$weather->is_expired()){
            return 
        }

        #check if data stored

        

        foreach ($this->countries as $country) {
            $params["q"] = $country;
            $result = $this->WeatherAPI('get', $endpoint, $params);

            $test = $this->caching("$endpoint.$country", $result);

            array_push($results, $test);
        }
        return $results;
    }
}
