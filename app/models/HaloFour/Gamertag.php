<?php

namespace HaloFour;

use Jenssegers\Mongodb\Model as Eloquent;

class Gamertag extends Eloquent {

	protected $collection = "h4_gamertags";

	public $timestamps = false;

	protected $guarded = ['0x00', '0x15', '0x16', '0x18', '0x2a', '0x3d', '0x3e', '0x3f', '0x40', '0x41'];


	public function get0x1dAttribute($value)
	{
		return self::unpack_msg($value);
	}

	public function get0x25Attribute($value)
	{
		return self::unpack_msg($value);
	}

	public function get0x27Attribute($value)
	{
		return self::unpack_msg($value);
	}

	public function set0x1dAttribute($value)
	{
		return self::pack_msg($value);
	}

	public function set0x25Attribute($value)
	{
		return self::pack_msg($value);
	}

	public function set0x27Attribute($value)
	{
		return self::pack_msg($value);
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
}