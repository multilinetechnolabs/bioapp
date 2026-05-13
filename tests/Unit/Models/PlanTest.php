<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Plan;

class PlanTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Plan::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'description'));
        $this->assertTrue(Schema::hasColumn($tableName, 'category'));
        $this->assertTrue(Schema::hasColumn($tableName, 'price'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 7);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Plan::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'name'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'description'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'category'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'price'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
