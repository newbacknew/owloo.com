<?php

class TwitterController extends BaseController {

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
				if (!Cache::has('totalWorldTwitterProfiles'))
				{
					$data = TwitterProfile::whereOwlooUserStatus('1')->count();
					Cache::put('totalWorldTwitterProfiles', $data, 1440);
				}
				return Cache::get('totalWorldTwitterProfiles');
			break;
			case 'hispanic':
				if (!Cache::has('totalHispanicTwitterProfiles'))
				{
					$data = TwitterProfile::whereOwlooUserStatus('1')->count();
					Cache::put('totalHispanicTwitterProfiles', $data, 1440);
				}
				return Cache::get('totalHispanicTwitterProfiles');
			break;
		}
		return 'Invalid method';
	}

}