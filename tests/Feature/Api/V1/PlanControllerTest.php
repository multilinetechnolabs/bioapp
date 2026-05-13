<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\Plan;
use App\Models\User;

class PlanControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexAction()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $token = $user->apiToken();

        $plans_count = Plan::count();
        factory(Plan::class)->create(['name' => 'Plan 1']);
        factory(Plan::class)->create(['name' => 'Plan 2']);
        factory(Plan::class)->create(['name' => 'Plan 3']);
        $this->assertEquals(Plan::count(), $plans_count + 3);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.plans.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            ['name' => Plan::first()->name],
            ['name' => 'Plan 1'],
            ['name' => 'Plan 2'],
            ['name' => 'Plan 3']
        ]);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'description', 'category', 'price',
                    'created_at', 'updated_at', 'deletable', 'editable'],
        ]);
    }

    /**
     * @return void
     */
    public function testStoreActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $token = $user->apiToken();

        $plans_count = Plan::count();
        $this->assertEquals(Plan::count(), $plans_count);
        
        $body = [
            'name' => 'Plan 1',
            'description' => 'Plan 1',
            'category' => 'Plan 1',
            'price' => 14.99
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.plans.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Plan::count(), $plans_count);
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

        $plans_count = Plan::count();
        $this->assertEquals(Plan::count(), $plans_count);
        
        $body = [
            'name' => 'New Plan',
            'description' => 'New Plan Description',
            'category' => 'New Plan Category',
            'price' => mt_rand(20, 100)
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.plans.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'name' => 'New Plan'
        ]);
        $this->assertEquals(Plan::count(), $plans_count + 1);
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
        
        $plan = factory(Plan::class)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.plans.show', ['id'=> $plan->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $plan->id,
            'name' => $plan->name
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

        $response = $this->json('get', URL::route('api.v1.plans.index').'/'.mt_rand(100, 1000), [], $headers);

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
        
        $plans_count = Plan::count();
        $plan = factory(Plan::class)->create();
        $plans_count = $plans_count + 1;
        $this->assertEquals(Plan::count(), $plans_count);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.plans.destroy', ['id'=> $plan->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(Plan::count(), $plans_count - 1);
    }

    /**
     * @return void
     */
    public function testDestroyActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $plans_count = Plan::count();
        $plan = factory(Plan::class)->create();
        $plans_count = $plans_count + 1;
        $this->assertEquals(Plan::count(), $plans_count);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.plans.destroy', ['id'=> $plan->id]), [], $headers);
        
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Plan::count(), $plans_count);
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
        
        $plan = factory(Plan::class)->create(['name' => 'Old Name']);
        $this->assertEquals($plan->name, 'Old Name');

        $body = [
            'name' => 'New Name',
            'description' => $plan->description,
            'category' => $plan->category,
            'price' => $plan->price
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.plans.update', ['id'=> $plan->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $plan->id,
            'name' => 'New Name',
            'description' => $plan->description,
            'category' => $plan->category,
            'price' => $plan->price
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
        
        $plan = factory(Plan::class)->create(['name' => 'Old Name']);
        $this->assertEquals($plan->name, 'Old Name');

        $body = [
            'name' => 'New Name',
            'description' => $plan->description,
            'category' => $plan->category,
            'price' => $plan->price
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.plans.update', ['id'=> $plan->id]), $body, $headers);

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
        
        $plans = factory(Plan::class, 5)->create();

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
