<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\Media;
use App\Models\User;

class MediaControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexAction()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $token = $user->apiToken();

        factory(Media::class, 3)->create(['user_id' => $user->id]);
        $this->assertEquals(Media::count(), 3);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.medias.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => ['id', 'user_id', 'file_name', 's3_name', 'description',
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

        $this->assertEquals(Media::count(), 0);
        $medias_count = Media::count();
        
        $body = [
            'user_id' => $user->id,
            'file_name' => str_random(50),
            's3_name' => str_random(50),
            'description' => str_random(50)
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.medias.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'user_id' => $user->id
        ]);
        $this->assertEquals(Media::count(), $medias_count + 1);
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
        
        $media = factory(Media::class)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.medias.show', ['id'=> $media->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $media->id,
            'user_id' => $media->user_id,
            'file_name' => $media->file_name,
            's3_name' => $media->s3_name
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

        $response = $this->json('get', URL::route('api.v1.medias.index').'/'.mt_rand(100, 1000), [], $headers);

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
        
        $media = factory(Media::class)->create(['user_id' => $user->id]);

        $this->assertEquals(Media::count(), 1);
        $medias_count = Media::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.medias.destroy', ['id'=> $media->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(Media::count(), $medias_count - 1);
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
        $media = factory(Media::class)->create(['user_id' => $anotherUser->id]);

        $this->assertEquals(Media::count(), 1);
        $medias_count = Media::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.medias.destroy', ['id'=> $media->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Media::count(), $medias_count);
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
        
        $media = factory(Media::class)->create(['user_id' => $user->id]);
        $this->assertEquals($media->user_id, $user->id);

        $description = str_random(100);
        $body = [
            'user_id' => $media->user_id,
            'file_name' => $media->file_name,
            's3_name' => $media->s3_name,
            'description' => $description
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.medias.update', ['id'=> $media->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $media->id,
            'user_id' => $media->user_id,
            'file_name' => $media->file_name,
            's3_name' => $media->s3_name,
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
        $media = factory(Media::class)->create(['user_id' => $anotherUser->id]);
        $this->assertEquals($media->user_id, $anotherUser->id);

        $description = str_random(100);
        $body = [
            'user_id' => $media->user_id,
            'file_name' => $media->file_name,
            's3_name' => $media->s3_name,
            'description' => $description
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.medias.update', ['id'=> $media->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
