<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\GroupDiscussion;

class GroupDiscussionTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = GroupDiscussion::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'group_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'discussion'));
        $this->assertTrue(Schema::hasColumn($tableName, 'dis_type'));
        $this->assertTrue(Schema::hasColumn($tableName, 'dis_order'));
        $this->assertTrue(Schema::hasColumn($tableName, 'dis_status'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_by'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_by'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 10);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = GroupDiscussion::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'group_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'discussion'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'dis_type'), 'boolean');
        $this->assertEquals(Schema::getColumnType($tableName, 'dis_order'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'dis_status'), 'boolean');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_by'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_by'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
