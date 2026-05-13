<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

use URL;

use App\Models\Payment;
use App\Models\User;

class PaymentControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testIndexActionAsRegularUser()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();

        factory(Payment::class)->create(['user_id' => $user->id, 'amount' => 101]);
        factory(Payment::class)->create(['user_id' => $user->id, 'amount' => 102]);
        factory(Payment::class)->create(['user_id' => $user->id, 'amount' => 103]);
        $this->assertEquals(Payment::count(), 3);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.payments.index'), [], $headers);

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

        factory(Payment::class)->create(['user_id' => $user->id, 'amount' => 101]);
        factory(Payment::class)->create(['user_id' => $user->id, 'amount' => 102]);
        factory(Payment::class)->create(['user_id' => $user->id, 'amount' => 103]);
        $this->assertEquals(Payment::count(), 3);
        
        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.payments.index'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            ['user_id' => $user->id, 'amount' => 101],
            ['user_id' => $user->id, 'amount' => 102],
            ['user_id' => $user->id, 'amount' => 103]
        ]);
        $response->assertJsonStructure([
            '*' => ['id', 'user_id', 'amount', 'date_paid',
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

        $this->assertEquals(Payment::count(), 0);
        $payments_count = Payment::count();
        
        $body = [
            'amount' => 100,
            'date_paid' => \Carbon\Carbon::now()->toDateString()
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.payments.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Payment::count(), $payments_count);
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

        $this->assertEquals(Payment::count(), 0);
        $payments_count = Payment::count();
        
        $anotherUser = factory(User::class)->create();

        $body = [
            'user_id' => $anotherUser->id,
            'amount' => 100,
            'date_paid' => \Carbon\Carbon::now()->toDateString()
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('post', URL::route('api.v1.payments.store'), $body, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'user_id' => $anotherUser->id,
            'amount' => 100,
            'date_paid' => \Carbon\Carbon::now()->toDateString()
        ]);
        $this->assertEquals(Payment::count(), $payments_count + 1);
    }

    /**
     * @return void
     */
    public function testShowActionAsRegularUser()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $payment = factory(Payment::class)->create(['user_id' => $user->id]);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.payments.show', ['id'=> $payment->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $payment->id,
            'user_id' => $payment->user_id,
            'amount' => $payment->amount,
            'date_paid' => $payment->date_paid
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
        
        $anotherUser = factory(User::class)->create();
        $payment = factory(Payment::class)->create(['user_id' => $anotherUser->id]);

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('get', URL::route('api.v1.payments.show', ['id'=> $payment->id]), [], $headers);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $payment->id,
            'user_id' => $payment->user_id,
            'amount' => $payment->amount,
            'date_paid' => $payment->date_paid
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

        $response = $this->json('get', URL::route('api.v1.payments.index').'/'.mt_rand(100, 1000), [], $headers);

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
        
        $payment = factory(Payment::class)->create(['user_id' => $user->id]);

        $this->assertEquals(Payment::count(), 1);
        $payments_count = Payment::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.payments.destroy', ['id'=> $payment->id]), [], $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals(Payment::count(), $payments_count - 1);
    }

    /**
     * @return void
     */
    public function testDestroyActionWhenUserIsUnauthorized()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $token = $user->apiToken();
        
        $payment = factory(Payment::class)->create(['user_id' => $user->id]);

        $this->assertEquals(Payment::count(), 1);
        $payments_count = Payment::count();

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('delete', URL::route('api.v1.payments.destroy', ['id'=> $payment->id]), [], $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(Payment::count(), $payments_count);
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
        
        $payment = factory(Payment::class)->create(['user_id' => $user->id, 'amount' => 100]);
        $this->assertEquals($payment->user_id, $user->id);
        $this->assertEquals($payment->amount, 100);

        $body = [
            'user_id' => $payment->user_id,
            'amount' => 1000,
            'date_paid' => $payment->date_paid
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.payments.update', ['id'=> $payment->id]), $body, $headers);

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
        
        $payment = factory(Payment::class)->create(['user_id' => $user->id, 'amount' => 100]);
        $this->assertEquals($payment->user_id, $user->id);
        $this->assertEquals($payment->amount, 100);

        $body = [
            'user_id' => $payment->user_id,
            'amount' => 1000,
            'date_paid' => $payment->date_paid
        ];

        $headers = [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];

        $response = $this->json('put', URL::route('api.v1.payments.update', ['id'=> $payment->id]), $body, $headers);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
