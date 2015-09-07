<?php

class InstagramController extends BaseController {

	/*
	|
	| Total Profiles
	|
	*/
	public function getTotalProfile($idiom)
	{
		switch ($idiom)
		{
			case 'world':
				if (!Cache::has('totalWorldInstagramProfiles'))
				{
					$data = InstagramProfile::whereActive('1')->count();
					Cache::put('totalWorldInstagramProfiles', $data, 1440);
				}
				return Cache::get('totalWorldInstagramProfiles');
			break;
		}
		return 'Invalid method';
	}


}