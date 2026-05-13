<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Product;

class ProductTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Product::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'user_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'description'));
        $this->assertTrue(Schema::hasColumn($tableName, 'category'));
        $this->assertTrue(Schema::hasColumn($tableName, 'unit_price'));
        $this->assertTrue(Schema::hasColumn($tableName, 'size'));
        $this->assertTrue(Schema::hasColumn($tableName, 'weight'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 10);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Product::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'user_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'name'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'description'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'category'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'unit_price'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'size'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'weight'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
