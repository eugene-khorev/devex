<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">{{ lang.header }}</div>

                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <div class="col-sm">
                                    <autocomplete-input 
                                        :url="url"
                                        :property="property"
                                        :min-letters="minLetters"
                                        :max-items="maxItems"
                                        :placeholder="lang.type_address"
                                        v-on:select="addressSelected">
                                    </autocomplete-input>
                                </div>
                            </div>
                        </form>
                        
                        <yandex-map v-if="mapCoords"
                            style="width: 100%; height: 600px;"
                            :zoom="mapZoom"
                            :coords="mapCoords">
                            
                            <yandex-map-marker
                                marker-type="placemark"
                                :coords="mapCoords"
                                marker-id="1">
                            </yandex-map-marker>
                        </yandex-map>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [ 'lang' ], // Language strings for localization
        
        data: function () {
            return {
                url: '/api/v1/autocomplete', // API autocomplete url
                property: 'address', // API request GET parameter name
                minLetters: 3, // Minimum number of letters to run API request
                maxItems: 6, // Maximum items to display
                requestDelay: 1000, // Debounce timeout (milliseconds)
                mapCoords: null, // Map coordinates
                mapZoom: 10, // Map scale
            };
        },
        
        methods: {
            // Event handler for address selection
            addressSelected: function (data) {
                if (data) {
                    // Set map coordinates
                    this.mapCoords = [ data.latitude, data.longitude ];
                    
                    // Set map scale
                    switch (data.kind) {
                        case 'house':
                        case 'metro':
                            this.mapZoom = 18;
                            break;
                            
                        case 'street':
                        case 'district':
                        case 'hydro':
                            this.mapZoom = 14;
                            break;
                            
                        case 'locality':
                        default:
                            this.mapZoom = 10;
                    }
                } else {
                    this.mapCoords = null;
                }
                
            },
        },
        
    }
</script>
