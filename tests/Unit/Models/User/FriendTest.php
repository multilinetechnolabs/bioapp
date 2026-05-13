<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\User\Friend;

class UserFriendTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Friend::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'user_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'friend_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'accepted'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 6);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Friend::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'user_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'friend_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'accepted'), 'boolean');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
