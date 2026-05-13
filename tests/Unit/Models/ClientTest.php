<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Client;

class ClientTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Client::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'user_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'first_name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'last_name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'email'));
        $this->assertTrue(Schema::hasColumn($tableName, 'address'));
        $this->assertTrue(Schema::hasColumn($tableName, 'phone_no'));
        $this->assertTrue(Schema::hasColumn($tableName, 'date_of_birth'));
        $this->assertTrue(Schema::hasColumn($tableName, 'emergency_contact_person'));
        $this->assertTrue(Schema::hasColumn($tableName, 'emergency_contact_number'));
        $this->assertTrue(Schema::hasColumn($tableName, 'session_cost_type'));
        $this->assertTrue(Schema::hasColumn($tableName, 'session_cost'));
        $this->assertTrue(Schema::hasColumn($tableName, 'session_paid'));
        $this->assertTrue(Schema::hasColumn($tableName, 'gender'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 16);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Client::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'user_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'first_name'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'last_name'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'email'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'address'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'phone_no'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'date_of_birth'), 'date');
        $this->assertEquals(Schema::getColumnType($tableName, 'emergency_contact_person'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'emergency_contact_number'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'session_cost_type'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'session_cost'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'session_paid'), 'boolean');
        $this->assertEquals(Schema::getColumnType($tableName, 'gender'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
