<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Schema;

use Carbon\Carbon;

use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = User::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'username'));
        $this->assertTrue(Schema::hasColumn($tableName, 'email'));
        $this->assertTrue(Schema::hasColumn($tableName, 'password'));
        $this->assertTrue(Schema::hasColumn($tableName, 'business'));
        $this->assertTrue(Schema::hasColumn($tableName, 'location'));
        $this->assertTrue(Schema::hasColumn($tableName, 'privacy'));
        $this->assertTrue(Schema::hasColumn($tableName, 'remember_token'));
        $this->assertTrue(Schema::hasColumn($tableName, 'company_name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'first_name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'last_name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'phone_no'));
        $this->assertTrue(Schema::hasColumn($tableName, 'fax_no'));
        $this->assertTrue(Schema::hasColumn($tableName, 'alternate_email'));
        $this->assertTrue(Schema::hasColumn($tableName, 'billing_title'));
        $this->assertTrue(Schema::hasColumn($tableName, 'address'));
        $this->assertTrue(Schema::hasColumn($tableName, 'logo'));
        $this->assertTrue(Schema::hasColumn($tableName, 'country'));
        $this->assertTrue(Schema::hasColumn($tableName, 'zip'));
        $this->assertTrue(Schema::hasColumn($tableName, 'age'));
        $this->assertTrue(Schema::hasColumn($tableName, 'profile_picture'));
        $this->assertTrue(Schema::hasColumn($tableName, 'email_verified_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 25);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = User::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'name'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'email'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'password'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'business'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'location'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'privacy'), 'boolean');
        $this->assertEquals(Schema::getColumnType($tableName, 'remember_token'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'company_name'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'first_name'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'last_name'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'phone_no'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'fax_no'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'alternate_email'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'billing_title'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'address'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'logo'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'country'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'zip'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'age'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'profile_picture'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'email_verified_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }

    /**
     * @return void
     */
    public function testHasSubscriptionAfterCreated()
    {
        $user = factory(User::class)->create();

        $this->assertEquals($user->subscriptions->count(), 1);

        $subscription = $user->subscriptions->first();
        $starts_at = Carbon::parse($subscription->starts_at);
        $ends_at = Carbon::parse($subscription->ends_at);

        $this->assertNotEquals($subscription->starts_at, $subscription->ends_at);
        $this->assertEquals($subscription->user_id, $user->id);
        $this->assertEquals(Carbon::parse($subscription->ends_at)->diffInDays(Carbon::parse($subscription->starts_at)), 15);
    }

    // /**
    //  * @return void
    //  */
    // public function testHasNoSubscriptionAfterCreated()
    // {
    //     for ($k = 0 ; $k < 50; $k++) {
    //         factory(User::class)->create();
    //     }

    //     $user = factory(User::class)->create();

    //     $this->assertEquals($user->subscriptions->count(), 0);
    // }
}
