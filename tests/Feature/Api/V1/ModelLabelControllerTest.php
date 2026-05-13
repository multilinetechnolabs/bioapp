<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\ModelLabel;
use App\Models\Pair;
use App\Models\User;

class ModelLabelControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexAction()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $token = $user->apiToken();

        factory(ModelLabel::class, 5)->create();
        $this->assertEquals(ModelLabel::count(), 5);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.modelLabels.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => ['id', 'target', 'pair_id', 'label', 'point_x', 'point_y', 'point_z', 'label_x',
                    'label_y', 'label_z', 'scan_type',
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

        $this->assertEquals(ModelLabel::count(), 0);
        $modelLabels_count = ModelLabel::count();
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'pair_id' => factory(Pair::class)->create()->id,
            'scan_type' => 'body_scan',
            'target' => 'male',
            'point_x' => 1.11,
            'point_y' => 2.22,
            'point_z' => 3.33
        ];

        $response = $this->json('post', URL::route('api.v1.modelLabels.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(ModelLabel::count(), $modelLabels_count);
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

        $this->assertEquals(ModelLabel::count(), 0);
        $modelLabels_count = ModelLabel::count();
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $pair = factory(Pair::class)->create();

        $body = [
            'pair_id' => $pair->id,
            'scan_type' => 'body_scan',
            'target' => 'male',
            'point_x' => 1.11,
            'point_y' => 2.22,
            'point_z' => 3.33
        ];

        $response = $this->json('post', URL::route('api.v1.modelLabels.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson($body);
        $this->assertEquals(ModelLabel::count(), $modelLabels_count + 1);
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
        
        $modelLabel = factory(ModelLabel::class)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.modelLabels.show', ['id'=> $modelLabel->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $modelLabel->id,
            'pair_id' => $modelLabel->pair->id
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

        $response = $this->json('get', URL::route('api.v1.modelLabels.index').'/'.mt_rand(100, 1000), [], $headers);

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
        
        $modelLabel = factory(ModelLabel::class)->create();

        $this->assertEquals(ModelLabel::count(), 1);
        $modelLabels_count = ModelLabel::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.modelLabels.destroy', ['id'=> $modelLabel->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(ModelLabel::count(), $modelLabels_count - 1);
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
        
        $modelLabel = factory(ModelLabel::class)->create();

        $this->assertEquals(ModelLabel::count(), 1);
        $modelLabels_count = ModelLabel::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.modelLabels.destroy', ['id'=> $modelLabel->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(ModelLabel::count(), $modelLabels_count);
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
        
        $modelLabel = factory(ModelLabel::class)->create(['target' => 'male']);
        $this->assertEquals($modelLabel->target, 'male');

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'pair_id' => $modelLabel->pair_id,
            'scan_type' => $modelLabel->scan_type,
            'target' => 'female',
            'point_x' => $modelLabel->point_x,
            'point_y' => $modelLabel->point_y,
            'point_z' => $modelLabel->point_z
        ];

        $response = $this->json('put', URL::route('api.v1.modelLabels.update', ['id'=> $modelLabel->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $modelLabel->id,
            'pair_id' => $modelLabel->pair_id,
            'scan_type' => $modelLabel->scan_type,
            'target' => 'female',
            'point_x' => $modelLabel->point_x,
            'point_y' => $modelLabel->point_y,
            'point_z' => $modelLabel->point_z

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
        
        $modelLabel = factory(ModelLabel::class)->create(['target' => 'male']);
        $this->assertEquals($modelLabel->target, 'male');

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $body = [
            'pair_id' => $modelLabel->pair_id,
            'scan_type' => $modelLabel->scan_type,
            'target' => 'female',
            'point_x' => $modelLabel->point_x,
            'point_y' => $modelLabel->point_y,
            'point_z' => $modelLabel->point_z
        ];

        $response = $this->json('put', URL::route('api.v1.modelLabels.update', ['id'=> $modelLabel->id]), $body, $headers);

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
        
        $modelLabels = factory(ModelLabel::class, 5)->create();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.modelLabels.datatables'), [], $headers);

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

        $response = $this->json('get', URL::route('api.v1.modelLabels.datatables'), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
