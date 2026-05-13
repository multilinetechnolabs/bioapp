<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\MediaPlaylist;

class MediaPlaylistTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = MediaPlaylist::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'media_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'playlist_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 5);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = MediaPlaylist::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'media_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'playlist_id'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
