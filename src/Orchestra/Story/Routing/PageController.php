<?php namespace Orchestra\Story\Routing;

use Illuminate\Support\Facades\View;
use Orchestra\Story\Model\Content;

class PageController extends ContentController {

	/**
	 * Return the response, this method allow each content type to be group 
	 * via different set of view.
	 *
	 * @access protected
	 * @param  \Orchestra\Story\Model\Content   $page
	 * @param  integer                          $id
	 * @param  string                           $slug
	 * @return Response
	 */
	protected function getResponse($page, $id, $slug)
	{
		if ( ! View::exists($view = "orchestra/story::pages.{$slug}"))
		{
			$view = 'orchestra/story::page';
		}

		return View::make($view, compact('page'));
	}

	/**
	 * Get the requested page/content from Model.
	 *
	 * @access protected
	 * @param  integer  $id
	 * @param  string   $slug
	 * @return \Orchestra\Story\Model\Content
	 */
	protected function getRequestedContent($id, $slug)
	{
		switch (true)
		{
			case isset($id) and ! is_null($id) :
				return Content::page()->publish()->where('id', $id)->firstOrFail();
				break;
			case isset($slug) and ! is_null($slug) :
				return Content::page()->publish()->where('slug', "_page_/{$slug}")->firstOrFail();
				break;
			default :
				return App::abort(404);
		}
	}
}
