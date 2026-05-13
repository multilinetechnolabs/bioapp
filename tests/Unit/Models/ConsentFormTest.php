<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\ConsentForm;

class ConsentFormTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = ConsentForm::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'client_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'date_entered'));
        $this->assertTrue(Schema::hasColumn($tableName, 'file_name'));
        $this->assertTrue(Schema::hasColumn($tableName, 's3_name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'description'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 8);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = ConsentForm::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'client_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'date_entered'), 'date');
        $this->assertEquals(Schema::getColumnType($tableName, 'file_name'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 's3_name'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'description'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
