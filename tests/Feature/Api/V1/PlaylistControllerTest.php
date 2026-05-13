<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\Playlist;
use App\Models\User;

class PlaylistControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexAction()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $token = $user->apiToken();

        factory(Playlist::class, 3)->create(['user_id' => $user->id]);
        $this->assertEquals(Playlist::count(), 3);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.playlists.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => ['id', 'user_id', 'name', 'description',
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

        $this->assertEquals(Playlist::count(), 0);
        $playlists_count = Playlist::count();
        
        $body = [
            'user_id' => $user->id,
            'name' => str_random(50),
            'description' => str_random(50)
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.playlists.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'user_id' => $user->id
        ]);
        $this->assertEquals(Playlist::count(), $playlists_count + 1);
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
        
        $playlist = factory(Playlist::class)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.playlists.show', ['id'=> $playlist->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $playlist->id,
            'user_id' => $playlist->user_id,
            'name' => $playlist->name
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

        $response = $this->json('get', URL::route('api.v1.playlists.index').'/'.mt_rand(100, 1000), [], $headers);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return void
     */
    public function testDestroyActionWhenUserIsAuthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $playlist = factory(Playlist::class)->create(['user_id' => $user->id]);

        $this->assertEquals(Playlist::count(), 1);
        $playlists_count = Playlist::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.playlists.destroy', ['id'=> $playlist->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(Playlist::count(), $playlists_count - 1);
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
        $playlist = factory(Playlist::class)->create(['user_id' => $anotherUser->id]);

        $this->assertEquals(Playlist::count(), 1);
        $playlists_count = Playlist::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.playlists.destroy', ['id'=> $playlist->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Playlist::count(), $playlists_count);
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
        
        $playlist = factory(Playlist::class)->create(['user_id' => $user->id]);
        $this->assertEquals($playlist->user_id, $user->id);

        $description = str_random(100);
        $body = [
            'user_id' => $playlist->user_id,
            'name' => $playlist->name,
            'description' => $description
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.playlists.update', ['id'=> $playlist->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $playlist->id,
            'user_id' => $playlist->user_id,
            'name' => $playlist->name,
            'description' => $description
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
        
        $anotherUser = factory(User::class)->create();
        $playlist = factory(Playlist::class)->create(['user_id' => $anotherUser->id]);
        $this->assertEquals($playlist->user_id, $anotherUser->id);

        $description = str_random(100);
        $body = [
            'user_id' => $playlist->user_id,
            'name' => $playlist->name,
            'description' => $description
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.playlists.update', ['id'=> $playlist->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
