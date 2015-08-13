<?php

namespace App\Widgets;

use Caffeinated\Widgets\Widget;

use App\Modules\Himawari\Http\Models\Content as Content;

use App;
use Config;
use Menu;
use Session;
use Theme;


class Banner extends Widget
{


	public function handle()
	{

		$activeTheme = Theme::getActive();

		Menu::handler('banner')->hydrate(function()
			{

			$pages = Content::InPrint()->IsBanner()->orderBy('order')->get();
			return $pages;

			},
			function($children, $item)
			{
				if($item->depth < 1) {
					$children->add($item->slug, $item->translate(Config::get('app.locale'))->title, Menu::items($item->as));
				}
			});

		return Theme::View($activeTheme . '::' . 'widgets.banner');
	}


}