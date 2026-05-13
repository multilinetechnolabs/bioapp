<input type="hidden" id="post_author" name="post_author" value="{{ !empty($post->post_author) ? $post->post_author : Auth::user()->id }}"></input>
<div class="form-group">
    <label for="post_title">Title</label>
    <input type="text" class="form-control" id="post_title" name="post_title" aria-describedby="post_title" placeholder="" required="true" value="{{ $post->post_title }}">
</div>
<div class="form-group">
    <label for="post_slug">Slug</label>
    <input type="text" class="form-control" id="post_slug" name="post_slug" aria-describedby="post_slug" placeholder="" required="true" value="{{ $post->post_slug }}">
</div>
<div class="form-group">
    <label for="post_excerpt">Excerpt</label>
    <input type="text" class="form-control" id="post_excerpt" name="post_excerpt" aria-describedby="post_excerpt" placeholder="" value="{{ $post->post_excerpt }}">
</div>
<hr>
<!-- <div class="row">
    <div class="col">
        <label class="form-check-label" for="post_featured_img">Post Featured Image</label>
        <input type="text" class="form-control" id="post_featured_img" name="post_featured_img" placeholder="">
    </div>
    <div class="col">
        <label class="form-check-label" for="post_featured_img_id">Post Featured Image ID</label>
        <input type="number" class="form-control" id="post_featured_img" name="post_featured_img_id" placeholder="">
    </div>
</div>
<hr> -->
<div class="form-group">
    <label for="seo_title">SEO Title</label>
    <input type="text" class="form-control" id="seo_title" name="seo_title" aria-describedby="seo_title" placeholder="" value="{{ $post->seo_title }}">
</div>
<div class="form-group">
    <label for="seo_meta_description">SEO Meta Description</label>
    <input type="text" class="form-control" id="seo_meta_description" name="seo_meta_description" aria-describedby="seo_meta_description" placeholder="" value="{{ $post->seo_meta_description }}">
</div>
<div class="form-group">
    <label for="seo_meta_keywords">SEO Meta Keywords</label>
    <input type="text" class="form-control" id="seo_meta_keywords" name="seo_meta_keywords" aria-describedby="seo_meta_keywords" placeholder="" value="{{ $post->seo_meta_keywords }}">
    <small id="seo_meta_keywords_help" class="form-text text-muted">Seperate keywords with comma.</small>
</div>
<hr>
<label>Publish Date</label>
<input type="date" class="form-control" id="published_at" name="published_at"  value="{{ !empty($post->published_at) ? \Carbon\Carbon::parse($post->published_at)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}">
<br>
<div class="form-group">
    <label for="post_content">Content</label>
    <textarea class="form-control" id="post_content" name="post_content" type="text" placeholder="">{{ $post->post_content }}</textarea>
</div>
