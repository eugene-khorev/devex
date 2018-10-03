<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiTest extends TestCase
{
    protected $baseURL;
    
    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->baseURL = route('autocomplete');
    }
    
    /**
     * Correct request test
     *
     * @return void
     */
    public function testCorrectRequest()
    {
        $response = $this->makeRequest('Невск');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'items' => [
                    [
                        'address',
                        'kind',
                        'latitude',
                        'longitude',
                    ],
                ],
            ]);
    }
    
    /**
     * Empty request test
     *
     * @return void
     */
    public function testEmptyRequest()
    {
        $response = $this->makeRequest('');

        $response
            ->assertStatus(200)
            ->assertJson([
                'items' => [],
            ]);
    }
    
    /**
     * Wrong request test
     *
     * @return void
     */
    public function testWrongRequest()
    {
        config(['geo.version' => '-']);
        
        $response = $this->makeRequest('nevermind');

        $response->assertStatus(500);
        $response->assertJsonStructure([ 'error' ]);
    }
    
    /**
     * Makes API request with given address
     * @param string $address
     * @return type
     */
    protected function makeRequest(string $address)
    {
        $url = $this->baseURL . '?address=' . $address;
        
        return $response = $this->json('GET', $url);
    }
}
