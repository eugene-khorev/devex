<?php

use Illuminate\Http\{ Request, JsonResponse };
use \Yandex\Geo\Api as GeoApi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function() {
    // Autocomplete API request
    Route::get('autocomplete', function (Request $request) {
        // Setup initial values
        $result = [];
        $address = $request->get('address');
        
        try {
            // Request GeoApi
            $api = new GeoApi(config('geo.version'));
            $api->setLimit(config('geo.result.limit'));
            $api->setLang(config('geo.lang'));
            $api->setQuery($address);
            $api->load();

            // Get GeoApi results
            $predictions = $api->getResponse()->getList();
            
            // Generate API response
            $result['items'] = [];
            foreach ($predictions as $item) {
                $result['items'][] = [
                    'kind' => $item->getKind(),
                    'address' => $item->getAddress(),
                    'latitude' => $item->getLatitude(),
                    'longitude' => $item->getLongitude(),
                ];
            }
        } catch (Exception $ex) {
            // Generate error response
            $result['error'] = $ex->getMessage();
        }
        
        return new JsonResponse($result);
    })->name('autocomplete');
});
