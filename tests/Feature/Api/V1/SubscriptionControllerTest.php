<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use Carbon\Carbon;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionControllerTest extends TestCase
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

        $subscriptions_count = Subscription::count();
        factory(Subscription::class, 5)->create(['user_id' => $user->id]);
        $this->assertEquals(Subscription::count(), $subscriptions_count + 5);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.subscriptions.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
      
        $response->assertJsonStructure([
            '*' => ['id', 'user_id', 'plan_id', 'starts_at', 'ends_at', 'user', 'plan',
                    'created_at', 'updated_at', 'deletable', 'editable'],
        ]);
    }

    /**
     * @return void
     */
    public function testIndexActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $token = $user->apiToken();

        $subscriptions_count = Subscription::count();
        factory(Subscription::class, 5)->create(['user_id' => $user->id]);
        $this->assertEquals(Subscription::count(), $subscriptions_count + 5);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.subscriptions.index'), [], $headers);

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

        $subscriptions_count = Subscription::count();
        $this->assertEquals(Subscription::count(), $subscriptions_count);
        
        $plan = factory(Plan::class)->create();
        $body = [
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'starts_at' => Carbon::now()->format('Y-m-d'),
            'ends_at' => Carbon::now()->addDays(mt_rand(30, 365))->format('Y-m-d')
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.subscriptions.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Subscription::count(), $subscriptions_count);
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

        $subscriptions_count = Subscription::count();
        $this->assertEquals(Subscription::count(), $subscriptions_count);
        
        $plan = factory(Plan::class)->create();
        $body = [
            'plan_id' => $plan->id,
            'user_id' => $user->id,
            'starts_at' => Carbon::now()->format('Y-m-d'),
            'ends_at' => Carbon::now()->addDays(mt_rand(30, 365))->format('Y-m-d')
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.subscriptions.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson($body);
        $this->assertEquals(Subscription::count(), $subscriptions_count + 1);
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
        
        $subscription = factory(Subscription::class)->create(['user_id' => $user->id]);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.subscriptions.show', ['id'=> $subscription->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $subscription->id,
            'user_id' => $subscription->user_id,
            'plan_id' => $subscription->plan_id
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

        $response = $this->json('get', URL::route('api.v1.subscriptions.index').'/'.mt_rand(100, 1000), [], $headers);

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
        
        $subscriptions_count = Subscription::count();
        $subscription = factory(Subscription::class)->create(['user_id' => $user->id]);
        $subscriptions_count = $subscriptions_count + 1;
        $this->assertEquals(Subscription::count(), $subscriptions_count);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.subscriptions.destroy', ['id'=> $subscription->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(Subscription::count(), $subscriptions_count - 1);
    }

    /**
     * @return void
     */
    public function testDestroyActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $subscriptions_count = Subscription::count();
        $subscription = factory(Subscription::class)->create(['user_id' => $user->id]);
        $subscriptions_count = $subscriptions_count + 1;
        $this->assertEquals(Subscription::count(), $subscriptions_count);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.subscriptions.destroy', ['id'=> $subscription->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Subscription::count(), $subscriptions_count);
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
        
        $subscription = factory(Subscription::class)->create(['user_id' => $user->id]);
        $this->assertEquals($subscription->user_id, $user->id);

        $ends_at = Carbon::now()->addDays(mt_rand(30, 365))->format('Y-m-d');
        $body = [
            'plan_id' => $subscription->plan_id,
            'user_id' => $subscription->user_id,
            'starts_at' => $subscription->starts_at,
            'ends_at' => $ends_at
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.subscriptions.update', ['id'=> $subscription->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $subscription->id,
            'plan_id' => $subscription->plan_id,
            'user_id' => $subscription->user_id,
            'starts_at' => Carbon::now()->format('Y-m-d'),
            'ends_at' => $ends_at
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
        
        $subscription = factory(Subscription::class)->create(['user_id' => $user->id]);
        $this->assertEquals($subscription->user_id, $user->id);

        $ends_at = Carbon::now()->addDays(mt_rand(30, 365))->format('Y-m-d');
        $body = [
            'plan_id' => $subscription->plan_id,
            'user_id' => $subscription->user_id,
            'starts_at' => $subscription->starts_at,
            'ends_at' => $ends_at
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.subscriptions.update', ['id'=> $subscription->id]), $body, $headers);

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
        
        $subscriptions = factory(Subscription::class, 5)->create(['user_id' => $user->id]);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.subscriptions.datatables'), [], $headers);

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

        $response = $this->json('get', URL::route('api.v1.subscriptions.datatables'), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
