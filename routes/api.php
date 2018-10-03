<?php

use Illuminate\Http\Request;
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
    Route::get('autocomplete', function (Request $request) {
        $result = [];
        $address = $request->get('address');
        
        try {
            $api = new GeoApi();
            $api->setQuery($address);
            $api->setLimit(config('geo.result.limit'));
            $api->setLang(config('geo.lang'));
            $api->load();

            $predictions = $api->getResponse()->getList();
            
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
            $result['error'] = $ex->getMessage();
        }
        
        return $result;
    });
});
