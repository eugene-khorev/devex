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
    Route::get('autocomplete', function (Request $request) {
        $status = 200;
        $result = [];
        $address = $request->get('address');
        
        try {
            $api = new GeoApi(config('geo.version'));
            $api->setLimit(config('geo.result.limit'));
            $api->setLang(config('geo.lang'));
            $api->setQuery($address);
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
            $status = 500;
            $result['error'] = $ex->getMessage();
        }
        
        return new JsonResponse($result, $status);
    })->name('autocomplete');
});
