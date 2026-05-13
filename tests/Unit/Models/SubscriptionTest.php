<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Subscription;

class SubsriptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Subscription::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'plan_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'user_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'ends_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'starts_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 7);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Subscription::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'plan_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'user_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'ends_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'starts_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
