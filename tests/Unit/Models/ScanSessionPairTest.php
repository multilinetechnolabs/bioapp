<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\ScanSessionPair;

class ScanSessionPairTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = ScanSessionPair::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'scan_session_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'pair_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'user_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 6);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = ScanSessionPair::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'scan_session_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'pair_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'user_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
