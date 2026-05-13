<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\PostPostCategory;

class PostPostCategoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = PostPostCategory::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'post_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'post_category_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 5);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = PostPostCategory::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'bigint');
        $this->assertEquals(Schema::getColumnType($tableName, 'post_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'post_category_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
