<?php namespace Users;

use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;
use Jenssegers\Mongodb\Model as Eloquent;

class Admin extends Eloquent implements UserInterface, RemindableInterface {

	protected static $rules = [
		'email' => 'required',
		'password' => 'required|min:4'
	];

	protected $collection = "admins";

	protected $softDelete = false;

	protected $guarded = ['_id'];

	protected $hidden = ['password'];

	public function getReminderEmail()
	{
		return $this->Email;
	}

	public function getAuthIdentifier()
	{
		return $this->_id;
	}

	public function getAuthPassword()
	{
		return $this->password;
	}

	public static function check($data)
	{
		$validator = Validator::make($data, static::$rules);

		if ($validator->fails())
		{
			return $validator;
		}
		else
		{
			return true;
		}
	}

}
