<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseLesson;
use App\Models\CourseLessonImage;
use App\Models\CourseLessonVideo;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseLessonController extends Controller
{
    public function index(CourseModule $module)
    {
        return view('admin.pages.course.lessons', [
            'module' => $module,
            'lessons' => $module->lessons,
        ]);
    }

    public function create(CourseModule $module)
    {
        return view('admin.pages.course.lesson_form', ['module' => $module, 'lesson' => new CourseLesson()]);
    }

    public function store(Request $request, CourseModule $module)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);
        $data['order'] = $module->lessons()->max('order') + 1;
        $lesson = $module->lessons()->create($data);

        return redirect()->route('admin.course.lesson.edit', $lesson->id)->with('success', 'Lesson created.');
    }

    public function edit(CourseLesson $lesson)
    {
        return view('admin.pages.course.lesson_form', [
            'module' => $lesson->module,
            'lesson' => $lesson,
            'images' => $lesson->images,
            'videos' => $lesson->videos,
        ]);
    }

    public function update(Request $request, CourseLesson $lesson)
    {
        $lesson->update($request->validate([
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'order' => 'nullable|integer',
        ]));

        return back()->with('success', 'Lesson updated.');
    }

    public function destroy(CourseLesson $lesson)
    {
        $module = $lesson->module;
        $lesson->delete();

        return redirect()->route('admin.course.lesson.index', $module->id)->with('success', 'Lesson deleted.');
    }

    public function storeImage(Request $request, CourseLesson $lesson)
    {
        $request->validate([
            'language' => [
                'required',
                'in:en,es,fr',
                Rule::unique('course_lesson_images', 'language')
                    ->where(fn ($query) => $query->where('course_lesson_id', $lesson->id)),
            ],
            'image' => 'required|image|max:5120',
        ], [
            'language.unique' => 'This lesson already has an image for that language — delete the existing one first if you want to replace it.',
        ]);

        $dir = public_path('uploads/course_lessons/' . $lesson->id);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = uniqid() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move($dir, $filename);

        $lesson->images()->create([
            'language' => $request->language,
            'path' => 'uploads/course_lessons/' . $lesson->id . '/' . $filename,
        ]);

        return back()->with('success', 'Image uploaded.');
    }

    public function destroyImage(CourseLessonImage $image)
    {
        $full = public_path($image->path);
        if (file_exists($full)) {
            unlink($full);
        }
        $image->delete();

        return back()->with('success', 'Image removed.');
    }

    public function storeVideo(Request $request, CourseLesson $lesson)
    {
        $request->validate(['url' => 'required|string|max:2000']);

        $lesson->videos()->create([
            'url' => $this->normalizeVideoUrl($request->url),
            'order' => $lesson->videos()->max('order') + 1,
        ]);

        return back()->with('success', 'Video added.');
    }

    public function destroyVideo(CourseLessonVideo $video)
    {
        $video->delete();

        return back()->with('success', 'Video removed.');
    }

    /**
     * Admins may paste the raw link, the full <iframe> embed snippet (e.g. copied
     * straight from Bunny Stream's "Share" panel), or Bunny's standalone "play"
     * page link (the one meant for opening in its own browser tab, not embedding —
     * easy to grab by mistake since it's what opens when you just click a Bunny
     * video link). Normalize all three into a clean embed URL with autoplay
     * forced off, regardless of what the source defaulted to.
     */
    protected function normalizeVideoUrl(string $url): string
    {
        $url = trim($url);

        if (preg_match('/<iframe[^>]*\ssrc=["\']([^"\']+)["\']/i', $url, $matches)) {
            $url = trim($matches[1]);
        }

        if (!str_contains($url, 'mediadelivery.net')) {
            return $url;
        }

        // Bunny's standalone "play" page isn't meant for embedding — the "embed"
        // path is the one designed to sit inside an iframe on another page.
        $url = preg_replace('#(mediadelivery\.net)/play/#i', '$1/embed/', $url);

        $parts = parse_url($url);
        parse_str($parts['query'] ?? '', $query);
        $query['autoplay'] = 'false';

        return $parts['scheme'] . '://' . $parts['host'] . ($parts['path'] ?? '') . '?' . http_build_query($query);
    }
}
