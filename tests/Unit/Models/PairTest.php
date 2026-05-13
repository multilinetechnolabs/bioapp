<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Pair;

class PairTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Pair::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'scan_type'));
        $this->assertTrue(Schema::hasColumn($tableName, 'ref_no'));
        $this->assertTrue(Schema::hasColumn($tableName, 'name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'radical'));
        $this->assertTrue(Schema::hasColumn($tableName, 'origins'));
        $this->assertTrue(Schema::hasColumn($tableName, 'symptoms'));
        $this->assertTrue(Schema::hasColumn($tableName, 'paths'));
        $this->assertTrue(Schema::hasColumn($tableName, 'alternative_routes'));
        $this->assertTrue(Schema::hasColumn($tableName, 'guided_ref_no'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 12);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Pair::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'scan_type'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'ref_no'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'name'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'radical'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'origins'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'symptoms'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'paths'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'alternative_routes'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'guided_ref_no'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
