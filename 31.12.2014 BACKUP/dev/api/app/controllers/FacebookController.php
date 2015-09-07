<?php

class FacebookController extends BaseController {

	public function getTotalUsers()
	{

		return FacebookPage::whereActive('1')->count();

	}

}
