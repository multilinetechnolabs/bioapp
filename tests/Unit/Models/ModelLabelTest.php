<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\ModelLabel;

class ModelLabelTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = ModelLabel::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'pair_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'scan_type'));
        $this->assertTrue(Schema::hasColumn($tableName, 'target'));
        $this->assertTrue(Schema::hasColumn($tableName, 'label'));
        $this->assertTrue(Schema::hasColumn($tableName, 'point_x'));
        $this->assertTrue(Schema::hasColumn($tableName, 'point_y'));
        $this->assertTrue(Schema::hasColumn($tableName, 'point_z'));
        $this->assertTrue(Schema::hasColumn($tableName, 'label_x'));
        $this->assertTrue(Schema::hasColumn($tableName, 'label_y'));
        $this->assertTrue(Schema::hasColumn($tableName, 'label_z'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 13);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = ModelLabel::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'pair_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'scan_type'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'target'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'label'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'point_x'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'point_y'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'point_z'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'label_x'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'label_y'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'label_z'), 'float');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
