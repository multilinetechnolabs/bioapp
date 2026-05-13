<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Payment;

class PaymentTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Payment::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'resource_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'resource_type'));
        $this->assertTrue(Schema::hasColumn($tableName, 'user_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'amount'));
        $this->assertTrue(Schema::hasColumn($tableName, 'date_paid'));
        $this->assertTrue(Schema::hasColumn($tableName, 'description'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 9);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Payment::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'resource_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'resource_type'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'user_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'amount'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'date_paid'), 'date');
        $this->assertEquals(Schema::getColumnType($tableName, 'description'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
