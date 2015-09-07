<?php

class FacebookPage extends Eloquent {

	protected $table = 'facebook_page';

	protected $primaryKey = 'id_page';

	public function location() {

		return $this->hasOne('Country', 'location', 'id_country');

	}

	public function firstLocalFansCountry() {

		return $this->hasOne('Country', 'first_local_fans_country', 'id_country');

	}

}