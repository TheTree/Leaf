<?php namespace Users;

use Jenssegers\Mongodb\Model as Eloquent;

class Admins extends Eloquent {

	protected $collection = "admins";

	protected $softDelete = false;

	protected $guarded = ['_id'];

}
