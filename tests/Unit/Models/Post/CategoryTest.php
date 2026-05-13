<?php

namespace Tests\Unit\Models\Post;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Post\Category;

class CategoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Category::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'title'));
        $this->assertTrue(Schema::hasColumn($tableName, 'slug'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 5);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Category::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'bigint');
        $this->assertEquals(Schema::getColumnType($tableName, 'title'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'slug'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
