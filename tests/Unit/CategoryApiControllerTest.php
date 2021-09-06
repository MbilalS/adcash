<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCategory()
    {
        $response = $this->json('POST', '/api/categories/store', ['name' => 'Data']);

        $response->assertStatus(201);
    }

    public function testUpdateCategory()
    {
        $this->json('POST', '/api/categories/store', ['name' => 'Data']);
        $response = $this->json('PUT', '/api/categories/update', ['id' => 2, 'name' => 'Data']);

        $response->assertStatus(200);
    }

    public function testDeleteCategory()
    {
        $this->json('POST', '/api/categories/store', ['name' => 'Data']);
        $response = $this->json('get', '/api/categories/');

        $category = $response->decodeResponseJson();

        $response = $this->json('delete', '/api/categories/destroy/'.$category[0]['id']);

        $response->assertStatus(200);
    }

    public function testGetAllCategory()
    {
        $this->json('POST', '/api/categories/store', ['name' => 'Data']);
        $response = $this->json('get', '/api/categories/');
        
        $response->assertStatus(200);
    }
}
