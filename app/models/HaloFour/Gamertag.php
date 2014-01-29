<?php

namespace HaloFour;

use Jenssegers\Mongodb\Model as Eloquent;

class Gamertag extends Eloquent {

	protected $collection = "h4_gamertags";

	public $timestamps = false;

	protected $guarded = ['_id', 'SeoGamertag', 'Date', 'Month', 'Year'];

	public function get0x1dAttribute($value)
	{
		return self::unpack_msg($value);
	}

	public function setSpecializationAttribute($value)
	{
		$this->attributes['Specialization'] = $this->getCurrentSpecialization($value, "Name");
		$this->attributes['SpecializationLevel'] = $this->getCurrentSpecialization($value, "Level");
	}

	public function setKDRatioAttribute($value)
	{
		$this->attributes['KDRatio'] = floatval($value);
		//$this->attributes['KADRatio'] = round()
	}


	/**
	 * Decodes utf8 strings from MongoDB and unpacks back into an array from msgpack
	 *
	 * @param $value
	 * @return mixed
	 */
	private function unpack_msg($value)
	{
		return msgpack_unpack(utf8_decode($value));
	}

	/**
	 * Encodes an array into UTF8, then msgpack's it.
	 *
	 * @param $value
	 * @return mixed
	 */
	private function pack_msg($value)
	{
		return msgpack_pack(utf8_encode($value));
	}

	private function getCurrentSpecialization($data, $type = "Name")
	{
		foreach($data as $spec)
		{
			if ($spec->IsCurrent === true)
			{
				return $spec->$type;
			}
		}

		return "None";
	}
}