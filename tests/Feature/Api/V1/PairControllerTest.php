<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\Pair;
use App\Models\User;

class PairControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexAction()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $token = $user->apiToken();

        factory(Pair::class)->create(['name' => 'Pair 1']);
        factory(Pair::class)->create(['name' => 'Pair 2']);
        factory(Pair::class)->create(['name' => 'Pair 3']);
        $this->assertEquals(Pair::count(), 3);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.pairs.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            ['name' => 'Pair 1'],
            ['name' => 'Pair 2'],
            ['name' => 'Pair 3']
        ]);
        $response->assertJsonStructure([
            '*' => ['id',  'name', 'radical', 'origins', 'symptoms', 'paths', 'alternative_routes',
                    'scan_type', 'ref_no', 'guided_ref_no',
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

        $this->assertEquals(Pair::count(), 0);
        $pairs_count = Pair::count();
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'name' => 'New Pair',
            'scan_type' => 'Body Scan',
            'radical' => 'Radical 1',
            'origins' => 'Origins 1',
            'symptoms' => 'Symptoms 1',
            'paths' => 'Paths 1',
            'alternative_routes' => 'Alternative Routes 1',
            'ref_no' => '100001',
            'guided_ref_no' => '100011'
        ];

        $response = $this->json('post', URL::route('api.v1.pairs.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Pair::count(), $pairs_count);
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

        $this->assertEquals(Pair::count(), 0);
        $pairs_count = Pair::count();
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'name' => 'New Pair',
            'scan_type' => 'Body Scan',
            'radical' => 'Radical 1',
            'origins' => 'Origins 1',
            'symptoms' => 'Symptoms 1',
            'paths' => 'Paths 1',
            'alternative_routes' => 'Alternative Routes 1',
            'ref_no' => '100001',
            'guided_ref_no' => '100011'
        ];

        $response = $this->json('post', URL::route('api.v1.pairs.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson($body);
        $this->assertEquals(Pair::count(), $pairs_count + 1);
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
        
        $pair = factory(Pair::class)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.pairs.show', ['id'=> $pair->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $pair->id,
            'name' => $pair->name
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

        $response = $this->json('get', URL::route('api.v1.pairs.index').'/'.mt_rand(100, 1000), [], $headers);

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
        
        $pair = factory(Pair::class)->create();

        $this->assertEquals(Pair::count(), 1);
        $pairs_count = Pair::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.pairs.destroy', ['id'=> $pair->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(Pair::count(), $pairs_count - 1);
    }

    /**
     * @return void
     */
    public function testDestroyActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $pair = factory(Pair::class)->create();

        $this->assertEquals(Pair::count(), 1);
        $pairs_count = Pair::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.pairs.destroy', ['id'=> $pair->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Pair::count(), $pairs_count);
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
        
        $pair = factory(Pair::class)->create(['name' => 'Old Name']);
        $this->assertEquals($pair->name, 'Old Name');

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'name' => 'New Pair Name',
            'scan_type' => $pair->scan_type,
            'radical' => $pair->radical,
            'origins' => $pair->origins,
            'symptoms' => $pair->symptoms,
            'paths' => $pair->paths,
            'alternative_routes' => $pair->alternative_routes,
            'ref_no' => $pair->ref_no,
            'guided_ref_no' => $pair->guided_ref_no

        ];

        $response = $this->json('put', URL::route('api.v1.pairs.update', ['id'=> $pair->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $pair->id,
            'name' => 'New Pair Name',
            'scan_type' => $pair->scan_type,
            'radical' => $pair->radical,
            'origins' => $pair->origins,
            'symptoms' => $pair->symptoms,
            'paths' => $pair->paths,
            'alternative_routes' => $pair->alternative_routes,
            'ref_no' => $pair->ref_no,
            'guided_ref_no' => $pair->guided_ref_no

        ]);
    }

    /**
    * @return void
    */
    public function testUpdateActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();
        $role = Role::create(['name' => 'administrator']);

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $pair = factory(Pair::class)->create(['name' => 'Old Name']);
        $this->assertEquals($pair->name, 'Old Name');

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'name' => 'New Pair Name',
            'scan_type' => $pair->scan_type,
            'radical' => $pair->radical,
            'origins' => $pair->origins,
            'symptoms' => $pair->symptoms,
            'paths' => $pair->paths,
            'alternative_routes' => $pair->alternative_routes,
            'ref_no' => $pair->ref_no,
            'guided_ref_no' => $pair->guided_ref_no

        ];

        $response = $this->json('put', URL::route('api.v1.pairs.update', ['id'=> $pair->id]), $body, $headers);

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
        
        $pairs = factory(Pair::class, 5)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.pairs.datatables'), [], $headers);

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

        $response = $this->json('get', URL::route('api.v1.pairs.datatables'), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
