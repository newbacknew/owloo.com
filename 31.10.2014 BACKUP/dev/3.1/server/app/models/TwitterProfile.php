<?php

class TwitterProfile extends Eloquent {

	protected $connection = 'owloo_twitter';

	protected $table = 'owloo_user_master';

	protected $primaryKey = 'owloo_user_id';
}