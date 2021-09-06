<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePostWithoutCategory()
    {
        $response = $this->json('POST', '/api/posts/store', ['title' => 'post1', 'category' => [null]]);

        $response->assertStatus(201);
    }

    public function testCreatePostWithCategory()
    {
        $category = $this->json('POST', '/api/categories/store', ['name' => 'Data']);
        $response = $this->json('POST', '/api/posts/store', ['title' => 'post1', 'category' => [$category->decodeResponseJson()->json()]]);

        $response->assertStatus(201);
    }

    public function testUpdatePostWithoutCategory()
    {
        $response = $this->json('POST', '/api/posts/store', ['title' => 'post1', 'category' => [null]]);
        $response = $this->json('PUT', '/api/posts/update', ['id' => 3, 'title' => 'post1', 'category' => [null]]);

        $response->assertStatus(200);
    }

    public function testUpdatePostWithCategory()
    {
        $category = $this->json('POST', '/api/categories/store', ['name' => 'Data']);
        $response = $this->json('POST', '/api/posts/store', ['title' => 'post1', 'category' => [$category->decodeResponseJson()->json()]]);

        $response = $this->json('PUT', '/api/posts/update', ['id' => 3, 'title' => 'post1', 'category' => [$category->decodeResponseJson()->json()]]);

        $response->assertStatus(200);
    }

    public function testDeletePostsWithoutCategory()
    {
        $post = $this->json('POST', '/api/posts/store', ['title' => 'post1', 'category' => [null]]);
        $post = $this->json('get', '/api/posts/');

        $post = $post->decodeResponseJson()->json();

        $response = $this->json('delete', '/api/posts/destroy/'.$post[0]['id']);

        $response->assertStatus(200);
    }
     
    public function testDeletePostsWithCategory()
    {
        $category = $this->json('POST', '/api/categories/store', ['name' => 'Data']);
        $post = $this->json('POST', '/api/posts/store', ['title' => 'post1', 'category' => [$category->decodeResponseJson()->json()]]);
        
        $post = $post->decodeResponseJson()->json();
        $response = $this->json('delete', '/api/posts/destroy/'.$post['id']);
        
        $response->assertStatus(200);
    }

    public function testGetAllPostsWithoutCategory()
    {
        $this->json('POST', '/api/posts/store', ['title' => 'post1', 'category' => [null]]);
        $response = $this->json('get', '/api/posts/');
        
        $response->assertStatus(200);
    }

    public function testGetAllPostsWithCategory()
    {
        $category = $this->json('POST', '/api/categories/store', ['name' => 'Data']);
        $this->json('POST', '/api/posts/store', ['title' => 'post1', 'category' => [$category->decodeResponseJson()->json()]]);
        $response = $this->json('get', '/api/posts/');
         
        $response->assertStatus(200);
    }
}
