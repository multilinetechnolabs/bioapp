<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Activity;

class ActivityTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Activity::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'user_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'category'));
        $this->assertTrue(Schema::hasColumn($tableName, 'title'));
        $this->assertTrue(Schema::hasColumn($tableName, 'content'));
        $this->assertTrue(Schema::hasColumn($tableName, 'date_published'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 8);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Activity::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'user_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'category'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'title'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'content'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'date_published'), 'date');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
