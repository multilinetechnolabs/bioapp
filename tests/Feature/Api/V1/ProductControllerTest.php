<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\Product;
use App\Models\User;

class ProductControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexActionAsRegularUser()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();

        $products = factory(Product::class, 5)->create(['user_id' => $user->id]);
        $this->assertEquals(Product::count(), $products->count());
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.products.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testIndexActionAsAdministrator()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        Passport::actingAs($user);
        $token = $user->apiToken();

        $products = factory(Product::class, 5)->create(['user_id' => $user->id]);
        $this->assertEquals(Product::count(), $products->count());
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.products.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            ['user_id' => $user->id],
            ['user_id' => $user->id],
            ['user_id' => $user->id]
        ]);
        $response->assertJsonStructure([
            '*' => ['id', 'user_id', 'name', 'description', 'category', 'unit_price', 'size',
                    'created_at', 'updated_at', 'deletable', 'editable'],
        ]);
    }

    /**
     * @return void
     */
    public function testStoreActionAsRegularUser()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();

        $this->assertEquals(Product::count(), 0);
        $products_count = Product::count();
        
        $body = [
            'user_id' => $user->id,
            'name' => 'Product 1',
            'description' => 'Product 1',
            'category' => 'Product Category',
            'unit_price' => 100,
            'size' => 'large'
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.products.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Product::count(), $products_count);
    }

    /**
     * @return void
     */
    public function testStoreActionAsAdministrator()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        Passport::actingAs($user);
        $token = $user->apiToken();

        $this->assertEquals(Product::count(), 0);
        $products_count = Product::count();
        
        $anotherUser = factory(User::class)->create();

        $body = [
            'user_id' => $anotherUser->id,
            'name' => 'Product 1',
            'description' => 'Product 1',
            'category' => 'Product Category',
            'unit_price' => 100,
            'size' => 'large'
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.products.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'user_id' => $anotherUser->id,
            'name' => 'Product 1',
            'description' => 'Product 1',
            'category' => 'Product Category',
            'unit_price' => 100,
            'size' => 'large'
        ]);
        $this->assertEquals(Product::count(), $products_count + 1);
    }

    /**
     * @return void
     */
    public function testShowActionAsRegularUser()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $product = factory(Product::class)->create(['user_id' => $user->id]);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.products.show', ['id'=> $product->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $product->id,
            'user_id' => $product->user_id,
            'name' => $product->name,
            'description' => $product->description,
            'category' => $product->category,
            'unit_price' => $product->unit_price,
            'size' => $product->size
        ]);
    }

    public function testShowActionAsAdministrator()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $anotherUser = factory(User::class)->create();
        $product = factory(Product::class)->create(['user_id' => $anotherUser->id]);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.products.show', ['id'=> $product->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $product->id,
            'user_id' => $product->user_id,
            'name' => $product->name,
            'description' => $product->description,
            'category' => $product->category,
            'unit_price' => $product->unit_price,
            'size' => $product->size
        ]);
    }

    public function testShowActionWhenResourceNotFound()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        Passport::actingAs($user);
        $token = $user->apiToken();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.products.index').'/'.mt_rand(100, 1000), [], $headers);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return void
     */
    public function testDestroyActionWhenUserIsAuthorized()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $anotherUser = factory(User::class)->create();
        $product = factory(Product::class)->create(['user_id' => $anotherUser->id]);

        $this->assertEquals(Product::count(), 1);
        $products_count = Product::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.products.destroy', ['id'=> $product->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(Product::count(), $products_count - 1);
    }

    /**
     * @return void
     */
    public function testDestroyActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $anotherUser = factory(User::class)->create();
        $product = factory(Product::class)->create(['user_id' => $anotherUser->id]);

        $this->assertEquals(Product::count(), 1);
        $products_count = Product::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.products.destroy', ['id'=> $product->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Product::count(), $products_count);
    }

    /**
     * @return void
     */
    public function testUpdateActionWhenUserIsAuthorized()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $anotherUser = factory(User::class)->create();
        $product = factory(Product::class)->create(['user_id' => $anotherUser->id, 'name' => 'Product 1']);
        $this->assertEquals($product->user_id, $anotherUser->id);
        $this->assertEquals($product->name, 'Product 1');

        $body = [
            'user_id' => $anotherUser->id,
            'name' => 'Product 2',
            'description' => 'Product 1',
            'category' => 'Product Category',
            'unit_price' => 100,
            'size' => ''
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.products.update', ['id'=> $product->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson($body);
    }

    /**
    * @return void
    */
    public function testUpdateActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $anotherUser = factory(User::class)->create();
        $product = factory(Product::class)->create(['user_id' => $anotherUser->id, 'name' => 'Product 1']);
        $this->assertEquals($product->user_id, $anotherUser->id);
        $this->assertEquals($product->name, 'Product 1');

        $body = [
            'user_id' => $product->user_id,
            'name' => 'Product 2',
            'description' => 'Product 1',
            'category' => 'Product Category',
            'unit_price' => 100,
            'size' => 'large'
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.products.update', ['id'=> $product->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
    * @return void
    */
    public function testDatatablesActionWhenUserIsAuthorized()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $plans = factory(Product::class, 5)->create(['user_id' => $user->id]);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.plans.datatables'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
    * @return void
    */
    public function testDatatablesActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.plans.datatables'), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
