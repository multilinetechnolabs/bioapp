<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

use Illuminate\Support\Facades\Schema;

use App\Models\Post;

class PostTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasColumns()
    {
        $tableName = Post::getTableName();

        $this->assertTrue(Schema::hasColumn($tableName, 'id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'post_author'));
        $this->assertTrue(Schema::hasColumn($tableName, 'post_content'));
        $this->assertTrue(Schema::hasColumn($tableName, 'post_title'));
        $this->assertTrue(Schema::hasColumn($tableName, 'post_excerpt'));
        $this->assertTrue(Schema::hasColumn($tableName, 'published_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'comment_status'));
        $this->assertTrue(Schema::hasColumn($tableName, 'post_slug'));
        $this->assertTrue(Schema::hasColumn($tableName, 'post_type'));
        $this->assertTrue(Schema::hasColumn($tableName, 'post_featured_img'));
        $this->assertTrue(Schema::hasColumn($tableName, 'post_featured_md_id'));
        $this->assertTrue(Schema::hasColumn($tableName, 'seo_title'));
        $this->assertTrue(Schema::hasColumn($tableName, 'seo_meta_description'));
        $this->assertTrue(Schema::hasColumn($tableName, 'seo_meta_keywords'));
        $this->assertTrue(Schema::hasColumn($tableName, 'created_at'));
        $this->assertTrue(Schema::hasColumn($tableName, 'updated_at'));

        $this->assertEquals(count(Schema::getColumnListing($tableName)), 16);
    }

    /**
     * @return void
     */
    public function testColumnTypes()
    {
        $tableName = Post::getTableName();

        $this->assertEquals(Schema::getColumnType($tableName, 'id'), 'bigint');
        $this->assertEquals(Schema::getColumnType($tableName, 'post_author'), 'integer');
        $this->assertEquals(Schema::getColumnType($tableName, 'post_content'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'post_title'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'post_excerpt'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'published_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'comment_status'), 'boolean');
        $this->assertEquals(Schema::getColumnType($tableName, 'post_slug'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'post_type'), 'string');
        $this->assertEquals(Schema::getColumnType($tableName, 'post_featured_img'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'post_featured_md_id'), 'bigint');
        $this->assertEquals(Schema::getColumnType($tableName, 'seo_title'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'seo_meta_description'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'seo_meta_keywords'), 'text');
        $this->assertEquals(Schema::getColumnType($tableName, 'created_at'), 'datetime');
        $this->assertEquals(Schema::getColumnType($tableName, 'updated_at'), 'datetime');
    }
}
