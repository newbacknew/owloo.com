<?php

class OwlooController extends BaseController {

	/*
	|
	| Total Users:
	|
	*/
	public function getTotalUsers()
	{
		if (!Cache::has('totalOwlooUsers'))
		{
			$data = OwlooUser::count();
			Cache::put('totalOwlooUsers', $data, 1440);
		}
		return Cache::get('totalOwlooUsers');
	}

}