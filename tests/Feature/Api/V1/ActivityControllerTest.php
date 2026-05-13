<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\Activity;
use App\Models\Activity\Category as ActivityCategory;
use App\Models\User;

class ActivityControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexAction()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $token = $user->apiToken();

        factory(Activity::class, 5)->create();
        $this->assertEquals(Activity::count(), 5);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.activities.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => ['id',  'user_id', 'category', 'title', 'content', 'date_published',
                    'created_at', 'updated_at', 'deletable', 'editable'],
        ]);
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

        $this->assertEquals(Activity::count(), 0);
        $activities_count = Activity::count();
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'category' => factory(ActivityCategory::class)->create()->name,
            'title' => str_random(20),
            'content' => str_random(20),
            'date_published' => Carbon::now()->format('Y-m-d')
        ];

        $response = $this->json('post', URL::route('api.v1.activities.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson($body);
        $this->assertEquals(Activity::count(), $activities_count + 1);
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
        
        $activity = factory(Activity::class)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.activities.show', ['id'=> $activity->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $activity->id,
        ]);
    }

    /**
    * @return void
    */
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

        $response = $this->json('get', URL::route('api.v1.activities.index').'/'.mt_rand(100, 1000), [], $headers);

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
        
        $activity = factory(Activity::class)->create();

        $this->assertEquals(Activity::count(), 1);
        $activities_count = Activity::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.activities.destroy', ['id'=> $activity->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(Activity::count(), $activities_count - 1);
    }

    /**
     * @return void
     */
    public function testDestroyActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $activity = factory(Activity::class)->create();

        $this->assertEquals(Activity::count(), 1);
        $activities_count = Activity::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.activities.destroy', ['id'=> $activity->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Activity::count(), $activities_count);
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
        
        $activity_category = factory(ActivityCategory::class)->create();
        $activity = factory(Activity::class)->create(['user_id' => $user->id, 'category' => $activity_category->name]);
        $this->assertEquals($activity->category, $activity_category->name);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $activity_category_new = factory(ActivityCategory::class)->create();
        $body = [
            'user_id' => $activity->user_id,
            'category' => $activity_category_new->name,
            'title' => 'test',
            'content' => $activity->content,
            'date_published' => $activity->date_published
        ];

        $response = $this->json('put', URL::route('api.v1.activities.update', ['id'=> $activity->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'user_id' => $activity->user_id,
            'category' => $activity_category_new->name,
            'title' => 'test',
            'content' => $activity->content,
            'date_published' => $activity->date_published
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
        
        $activity_category = factory(ActivityCategory::class)->create();
        $activity = factory(Activity::class)->create(['category' => $activity_category->name]);
        $this->assertEquals($activity->category, $activity_category->name);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $activity_category_new = factory(ActivityCategory::class)->create();
        $body = [
            'user_id' => $activity->user_id,
            'category' => $activity_category_new->name,
            'title' => $activity->title,
            'content' => $activity->content,
            'date_published' => $activity->date_published
        ];

        $response = $this->json('put', URL::route('api.v1.activities.update', ['id'=> $activity->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
