<?php namespace Orchestra\Story\Routing\Api;

use Orchestra\Story\Model\Content;
use Orchestra\Support\Facades\Meta;
use Illuminate\Http\RedirectResponse;
use Orchestra\Support\Facades\Messages;

class PostsController extends ContentController
{
    /**
     * Define filters for current controller.
     *
     * @return void
     */
    public function setupFilters()
    {
        parent::setupFilters();

        $this->resource = 'storycms.posts';

        $this->beforeFilter('orchestra.story:create-post', [
            'only' => ['create', 'store'],
        ]);

        $this->beforeFilter('orchestra.story:update-post', [
            'only' => ['edit', 'update'],
        ]);

        $this->beforeFilter('orchestra.story:delete-post', [
            'only' => ['delete', 'destroy'],
        ]);
    }

    /**
     * List all the posts.
     *
     * @return mixed
     */
    public function index()
    {
        $contents = Content::with('author')->latestBy(Content::CREATED_AT)->post()->paginate();
        $type     = 'post';

        Meta::set('title', 'List of Posts');

        return view('orchestra/story::api.index', compact('contents', 'type'));
    }

    /**
     * Write a post.
     *
     * @return mixed
     */
    public function create()
    {
        Meta::set('title', 'Write a Post');

        $content         = new Content;
        $content->type   = Content::POST;
        $content->format = $this->editorFormat;

        return view('orchestra/story::api.editor', [
            'content' => $content,
            'url'     => resources('storycms.posts'),
            'method'  => 'POST',
        ]);
    }

    /**
     * Edit a post.
     *
     * @param  int  $id
     * @return mixed
     */
    public function edit($id = null)
    {
        Meta::set('title', 'Write a Post');

        $content = Content::where('type', 'post')->where('id', $id)->firstOrFail();

        return view('orchestra/story::api.editor', [
            'content' => $content,
            'url'     => resources("storycms.posts/{$content->id}"),
            'method'  => 'PUT',
        ]);
    }

    /**
     * Store a post.
     *
     * @param  \Orchestra\Story\Model\Content  $content
     * @param  array  $input
     * @return mixed
     */
    protected function storeCallback($content, $input)
    {
        $content->save();

        Messages::add('success', 'Post has been created.');

        return new RedirectResponse(resources("storycms.posts/{$content->id}/edit"));
    }

    /**
     * Update a post.
     *
     * @param  \Orchestra\Story\Model\Content  $content
     * @param  array  $input
     * @return mixed
     */
    protected function updateCallback($content, $input)
    {
        $content->save();

        Messages::add('success', 'Post has been updated.');

        return new RedirectResponse(resources("storycms.posts/{$content->id}/edit"));
    }

    /**
     * Delete a post.
     *
     * @param  \Orchestra\Story\Model\Content  $content
     * @return mixed
     */
    protected function destroyCallback($content)
    {
        $content->delete();

        Messages::add('success', 'Post has been deleted.');

        return new RedirectResponse(resources('storycms.posts'));
    }
}
