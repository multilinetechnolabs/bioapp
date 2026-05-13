<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Order;

class OrderTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Order::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'user_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'product_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'description'));
        $this->assertTrue(Schema::hasColumn($tableName, 'quantity'));
        $this->assertTrue(Schema::hasColumn($tableName, 'shipping_address'));
        $this->assertTrue(Schema::hasColumn($tableName, 'shipping_zip'));
        $this->assertTrue(Schema::hasColumn($tableName, 'shipping_service'));
        $this->assertTrue(Schema::hasColumn($tableName, 'will_shipping'));
        $this->assertTrue(Schema::hasColumn($tableName, 'shipping_day_set'));
        $this->assertTrue(Schema::hasColumn($tableName, 'shipping_rate'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 13);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Order::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'bigint');
        $this->assertEquals(Schema::getColumnType($tableName, 'user_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'product_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'description'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'quantity'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'shipping_address'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'shipping_zip'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'shipping_service'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'will_shipping'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'shipping_day_set'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'shipping_rate'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
