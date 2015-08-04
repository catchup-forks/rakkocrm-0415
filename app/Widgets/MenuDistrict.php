<?php

namespace App\Widgets;

use Caffeinated\Widgets\Widget;

use App\Modules\Menus\Http\Models\Menu as LMenu;
use App\Modules\Menus\Http\Models\Menulink;

use App;
use Cache;
use Config;
use DB;
use Menu;
use Session;
use Theme;


class MenuDistrict extends Widget
{


	public function handle()
	{

		$activeTheme = Theme::getActive();
		$districts = Cache::get('district', null);
//dd($districts);

		if ($districts == null) {
			$districts = Cache::rememberForever('districts', function() {
				$main_menu_id = LMenu::where('name', '=', 'district')->pluck('id');
//				$links = Menulink::where('menu_id', '=', $main_menu_id)->orderBy('position')->get();
				$links = DB::table('menulinks')
					->leftJoin('menulink_translations', 'menulinks.id', '=', 'menulink_translations.menulink_id')
					->where('menu_id', '=', $main_menu_id)
					->where('status', '=', 1, 'AND')
					->get();
				$links = new \Illuminate\Support\Collection($links);
//dd($links);

				return $links;
			});
		}


		if (count($districts)) {
		Menu::handler('district')->hydrate(function()
			{

// 			$main_menu_id = LMenu::where('name', '=', 'district')->pluck('id');
// 			return Menulink::where('menu_id', '=', $main_menu_id)->orderBy('position')->get();
			$districts = Cache::get('districts');
//dd($schools);
			return $districts;

			},
			function($children, $item)
			{
				$children->add($item->translate(App::getLocale())->url, $item->translate(App::getLocale())->title, Menu::items($item->as));
			});

			return Theme::View($activeTheme . '::' . 'widgets.district_menu');
		}
	}


}