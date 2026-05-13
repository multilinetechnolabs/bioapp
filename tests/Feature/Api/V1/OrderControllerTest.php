<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexActionWhenUserIsAuthorized()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        Passport::actingAs($user);
        $token = $user->apiToken();

        factory(Order::class)->create(['user_id' => $user->id]);
        factory(Order::class)->create(['user_id' => $user->id]);
        factory(Order::class)->create(['user_id' => $user->id]);
        $this->assertEquals(Order::count(), 3);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.orders.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            ['user_id' => $user->id],
            ['user_id' => $user->id],
            ['user_id' => $user->id]
        ]);
        $response->assertJsonStructure([
            '*' => ['id', 'user_id', 'product_id', 'quantity', 'description',
                    'created_at', 'updated_at', 'deletable', 'editable'],
        ]);
    }

    /**
     * @return void
     */
    public function testIndexActionWhenUserIsUnuthorized()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $token = $user->apiToken();

        factory(Order::class)->create(['user_id' => $user->id]);
        factory(Order::class)->create(['user_id' => $user->id]);
        factory(Order::class)->create(['user_id' => $user->id]);
        $this->assertEquals(Order::count(), 3);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.orders.index'), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return void
     */
    public function testStoreActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $token = $user->apiToken();

        $this->assertEquals(Order::count(), 0);
        $orders_count = Order::count();
        
        $product = factory(Product::class)->create();
        $body = [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'description' => 'Order 1',
            'quantity' => 3
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.orders.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Order::count(), $orders_count);
    }

    /**
     * @return void
     */
    public function testStoreActionWhenUserIsAuthorized()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        Passport::actingAs($user);
        $token = $user->apiToken();

        $this->assertEquals(Order::count(), 0);
        $orders_count = Order::count();
        
        $product = factory(Product::class)->create();
        $body = [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'description' => 'Order 1',
            'quantity' => 3,
            'shipping_service' => str_random(20),
            'will_shipping' => str_random(20),
            'shipping_address' => str_random(20),
            'shipping_day_set' => str_random(20),
            'shipping_zip' => \Faker\Provider\Base::randomNumber(6),
            'shipping_rate' => \Faker\Provider\Base::randomFloat(2, 10, 50),
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.orders.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'description' => 'Order 1',
            'quantity' => 3
        ]);
        $this->assertEquals(Order::count(), $orders_count + 1);
    }

    /**
     * @return void
     */
    public function testShowAction()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $order = factory(Order::class)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.orders.show', ['id'=> $order->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $order->id,
            'user_id' => $order->user_id,
            'product_id' => $order->product_id,
            'description' => $order->description,
            'quantity' => $order->quantity
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

        $response = $this->json('get', URL::route('api.v1.orders.index').'/'.mt_rand(100, 1000), [], $headers);

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
        
        $order = factory(Order::class)->create();

        $this->assertEquals(Order::count(), 1);
        $orders_count = Order::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.orders.destroy', ['id'=> $order->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(Order::count(), $orders_count - 1);
    }

    /**
     * @return void
     */
    public function testDestroyActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $order = factory(Order::class)->create();

        $this->assertEquals(Order::count(), 1);
        $orders_count = Order::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.orders.destroy', ['id'=> $order->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Order::count(), $orders_count);
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
        
        $order = factory(Order::class)->create(['description' => 'Order 1']);
        $this->assertEquals($order->description, 'Order 1');

        $body = [
            'user_id' => $order->user_id,
            'product_id' => $order->product_id,
            'description' => 'Order 1 New',
            'quantity' => $order->quantity
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.orders.update', ['id'=> $order->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $order->id,
            'user_id' => $order->user_id,
            'product_id' => $order->product_id,
            'description' => 'Order 1 New',
            'quantity' => $order->quantity
        ]);
    }

    /**
    * @return void
    */
    public function testUpdateActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $order = factory(Order::class)->create(['description' => 'Order 1']);
        $this->assertEquals($order->description, 'Order 1');

        $body = [
            'user_id' => $order->user_id,
            'product_id' => $order->product_id,
            'description' => 'Order 1 New',
            'quantity' => $order->quantity
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.orders.update', ['id'=> $order->id]), $body, $headers);

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
        
        $orders = factory(Order::class, 5)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.orders.datatables'), [], $headers);

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

        $response = $this->json('get', URL::route('api.v1.orders.datatables'), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
