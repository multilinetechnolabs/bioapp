<?php

namespace Tests\Feature\Api\V1\Activity;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\Activity\Category as ActivityCategory;
use App\Models\User;

class CategoryControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexActionAsRegularUser()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();

        $activity_categories = factory(ActivityCategory::class, 5)->create();
        $this->assertEquals(ActivityCategory::count(), $activity_categories->count());
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.activity_categories.index'), [], $headers);

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

        $activity_categories = factory(ActivityCategory::class, 5)->create();
        $this->assertEquals(ActivityCategory::count(), $activity_categories->count());
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.activity_categories.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => ['id', 'name',
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

        $this->assertEquals(ActivityCategory::count(), 0);
        $activityCategoriesCount = ActivityCategory::count();
        
        $body = [
            'name' => 'ActivityCategory 1',
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.activity_categories.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(ActivityCategory::count(), $activityCategoriesCount);
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

        $this->assertEquals(ActivityCategory::count(), 0);
        $activityCategoriesCount = ActivityCategory::count();

        $body = [
            'name' => 'ActivityCategory 1',
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.activity_categories.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'name' => 'ActivityCategory 1',
        ]);
        $this->assertEquals(ActivityCategory::count(), $activityCategoriesCount + 1);
    }

    /**
     * @return void
     */
    public function testShowActionAsRegularUser()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $activityCategory = factory(ActivityCategory::class)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.activity_categories.show', ['id'=> $activityCategory->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'name' => $activityCategory->name
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
        
        $activityCategory = factory(ActivityCategory::class)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.activity_categories.show', ['id'=> $activityCategory->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'name' => $activityCategory->name
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

        $response = $this->json('get', URL::route('api.v1.activity_categories.index').'/'.mt_rand(100, 1000), [], $headers);

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
        
        $activityCategory = factory(ActivityCategory::class)->create();

        $this->assertEquals(ActivityCategory::count(), 1);
        $activityCategoriesCount = ActivityCategory::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.activity_categories.destroy', ['id'=> $activityCategory->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(ActivityCategory::count(), $activityCategoriesCount - 1);
    }

    /**
     * @return void
     */
    public function testDestroyActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $activityCategory = factory(ActivityCategory::class)->create();

        $this->assertEquals(ActivityCategory::count(), 1);
        $activityCategoriesCount = ActivityCategory::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.activity_categories.destroy', ['id'=> $activityCategory->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(ActivityCategory::count(), $activityCategoriesCount);
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
        
        $activityCategory = factory(ActivityCategory::class)->create(['name' => 'ActivityCategory 1']);
        $this->assertEquals($activityCategory->name, 'ActivityCategory 1');

        $body = [
            'name' => 'ActivityCategory New'
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.activity_categories.update', ['id'=> $activityCategory->id]), $body, $headers);

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
        
        $activityCategory = factory(ActivityCategory::class)->create(['name' => 'ActivityCategory 1']);
        $this->assertEquals($activityCategory->name, 'ActivityCategory 1');

        $body = [
            'user_id' => $activityCategory->user_id,
            'name' => 'ActivityCategory New'
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.activity_categories.update', ['id'=> $activityCategory->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
