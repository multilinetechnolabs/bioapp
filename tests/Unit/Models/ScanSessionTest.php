<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\ScanSession;

class ScanSessionTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = ScanSession::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'client_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'user_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'scan_type'));
        $this->assertTrue(Schema::hasColumn($tableName, 'date_started'));
        $this->assertTrue(Schema::hasColumn($tableName, 'date_ended'));
        $this->assertTrue(Schema::hasColumn($tableName, 'cost_type'));
        $this->assertTrue(Schema::hasColumn($tableName, 'cost'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 10);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = ScanSession::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'client_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'user_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'scan_type'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'date_started'), 'date');
        $this->assertEquals(Schema::getColumnType($tableName, 'date_ended'), 'date');
        $this->assertEquals(Schema::getColumnType($tableName, 'cost_type'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'cost'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
