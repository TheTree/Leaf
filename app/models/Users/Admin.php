<?php namespace Users;

use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;
use Jenssegers\Mongodb\Eloquent\SoftDeletingTrait;
use Jenssegers\Mongodb\Model as Eloquent;

class Admin extends Eloquent implements UserInterface, RemindableInterface {
	use SoftDeletingTrait;

	protected static $rules = [
		'email' => 'required',
		'password' => 'required|min:4'
	];

	protected $collection = "admins";

	protected $guarded = ['_id'];

	protected $hidden = ['password'];

	public function getReminderEmail()
	{
		return $this->Email;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
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
