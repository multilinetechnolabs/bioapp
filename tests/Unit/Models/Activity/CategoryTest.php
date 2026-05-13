<?php

namespace Tests\Unit\Models\Activity;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Activity\Category;

class CategoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Category::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 4);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Category::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'name'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
