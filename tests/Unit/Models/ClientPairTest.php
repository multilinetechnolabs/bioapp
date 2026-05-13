<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\ClientPair;

class ClientPairTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = ClientPair::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'client_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'pair_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 5);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = ClientPair::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'client_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'pair_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
