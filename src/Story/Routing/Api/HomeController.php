<?php namespace Orchestra\Story\Routing\Api;

use Orchestra\Story\Model\Content;
use Orchestra\Support\Facades\Acl;
use Orchestra\Support\Facades\Meta;

class HomeController extends EditorController
{
    /**
     * Define filters for current controller.
     *
     * @return void
     */
    protected function setupFilters()
    {
        //
    }

    /**
     * Show Dashboard.
     *
     * @return mixed
     */
    public function getIndex()
    {
        $acl = Acl::make('orchestra/story');

        if ($acl->can('create post') or $acl->can('manage post')) {
            return $this->write();
        }

        return view('orchestra/story::api.home');
    }

    /**
     * Write a post.
     *
     * @return mixed
     */
    protected function write()
    {
        Meta::set('title', 'Write a Post');

        $content = new Content;
        $content->setAttribute('type', Content::POST);
        $content->setAttribute('format', $this->editorFormat);

        return view('orchestra/story::api.editor', [
            'content' => $content,
            'url'     => resources('storycms.posts'),
            'method'  => 'POST',
        ]);
    }
}
