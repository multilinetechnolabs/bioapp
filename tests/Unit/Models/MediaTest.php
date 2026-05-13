<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Media;

class MediaTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Media::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'user_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'file_name'));
        $this->assertTrue(Schema::hasColumn($tableName, 's3_name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'description'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 7);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Media::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'user_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'file_name'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 's3_name'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'description'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
