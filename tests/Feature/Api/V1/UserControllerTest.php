<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\User;

class UserControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexActionAsRegularUser()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();

        $users = factory(User::class, 5)->create();
        $this->assertEquals(User::count(), $users->count() + 1);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.users.index'), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
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

        $users = factory(User::class, 5)->create();
        $this->assertEquals(User::count(), $users->count() + 1);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.users.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'email', 'business', 'location', 'privacy', 'company_name', 'first_name',
                    'last_name', 'phone_no', 'fax_no', 'alternate_email', 'billing_title', 'address', 'country', 'zip',
                    'age', 'logo', 'profile_picture', 'created_at', 'updated_at', 'deletable', 'editable'],
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

        $this->assertEquals(User::count(), 1);
        $users_count = User::count();
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'name' => 'User 1',
            'email' => 'testuser1@gmail.com',
            'password' => bcrypt('vBSTh1RDGTU'),
            'first_name' => 'User 1',
            'last_name' => 'User 1',
            'privacy' => false,
            'alternate_email' => 'testuser0@gmail.com'
        ];

        $response = $this->json('post', URL::route('api.v1.users.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(User::count(), $users_count);
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

        $this->assertEquals(User::count(), 1);
        $users_count = User::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'name' => 'User 1',
            'email' => 'testuser1@gmail.com',
            'password' => bcrypt('vBSTh1RDGTU'),
            'first_name' => 'User 1',
            'last_name' => 'User 1',
            'privacy' => false,
            'alternate_email' => 'testuser0@gmail.com'
        ];

        $response = $this->json('post', URL::route('api.v1.users.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(User::count(), $users_count);
    }

    /**
     * @return void
     */
    public function testShowActionAsRegularUser()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $user = factory(User::class)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.users.show', ['id'=> $user->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
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

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.users.me'), [], $headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $user->id,
        ]);

        $response = $this->json('get', URL::route('api.v1.users.show', ['id'=> $anotherUser->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $anotherUser->id,
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

        $response = $this->json('get', URL::route('api.v1.users.index').'/'.mt_rand(100, 1000), [], $headers);

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
        $anotherUser->assignRole($role);
        $this->assertTrue($anotherUser->hasRole($role->name));

        $this->assertEquals(User::count(), 2);
        $users_count = User::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $anotherUser = factory(User::class)->create();

        $this->assertEquals(User::count(), 3);
        $users_count = User::count();

        $response = $this->json('delete', URL::route('api.v1.users.destroy', ['id'=> $anotherUser->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(User::count(), $users_count - 1);
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

        $this->assertEquals(User::count(), 2);
        $users_count = User::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.users.destroy', ['id'=> $anotherUser->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(User::count(), $users_count);

        $role = Role::create(['name' => 'administrator']);
        $user->assignRole($role);
        $this->assertTrue($user->hasRole($role->name));

        $response = $this->json('delete', URL::route('api.v1.users.destroy', ['id'=> $user->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(User::count(), $users_count);

        $anotherUser->assignRole($role);
        $this->assertTrue($anotherUser->hasRole($role->name));

        $response = $this->json('delete', URL::route('api.v1.users.destroy', ['id'=> $anotherUser->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(User::count(), $users_count);
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
        
        $user = factory(User::class)->create(['name' => 'User 1', 'privacy' => true]);
        $this->assertEquals($user->name, 'User 1');
        $this->assertEquals($user->privacy, true);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'name' => 'User 1 New',
            'email' => 'user1@email.com',
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'privacy' => false,
            'alternate_email' => $user->alternate_email
        ];

        $response = $this->json('put', URL::route('api.v1.users.me'), $body, $headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson($body);

        $anotherUser = factory(User::class)->create(['name' => 'Another User 1', 'privacy' => true]);

        $body = [
            'name' => $anotherUser->name.' New',
            'email' => $anotherUser->email,
            'first_name' => $anotherUser->first_name,
            'last_name' => $anotherUser->last_name,
            'privacy' => false,
            'alternate_email' => $anotherUser->alternate_email
        ];

        $response = $this->json('put', URL::route('api.v1.users.update', ['id'=> $anotherUser->id]), $body, $headers);
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
        
        $user = factory(User::class)->create(['name' => 'User 1', 'privacy' => true]);
        $this->assertEquals($user->name, 'User 1');
        $this->assertEquals($user->privacy, true);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'name' => 'User 1 New',
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'privacy' => false,
            'alternate_email' => $user->alternate_email
        ];

        $response = $this->json('put', URL::route('api.v1.users.update', ['id'=> $user->id]), $body, $headers);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $anotherUser = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);
        $anotherUser->assignRole($role);
        $this->assertTrue($anotherUser->hasRole($role->name));

        $response = $this->json('put', URL::route('api.v1.users.update', ['id'=> $anotherUser->id]), $body, $headers);
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
        
        $users = factory(User::class, 5)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.users.datatables'), [], $headers);

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

        $response = $this->json('get', URL::route('api.v1.users.datatables'), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
