<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Inquiry;

class InquiryTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Inquiry::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'name'));
        $this->assertTrue(Schema::hasColumn($tableName, 'email'));
        $this->assertTrue(Schema::hasColumn($tableName, 'mode'));
        $this->assertTrue(Schema::hasColumn($tableName, 'phone_no'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 7);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Inquiry::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'name'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'email'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'mode'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'phone_no'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
