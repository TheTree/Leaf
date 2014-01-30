<?php namespace HaloFour;

use Jenssegers\Mongodb\Model as Eloquent;

class Gamertag extends Eloquent {

	protected $collection = "h4_gamertags";

	protected $softDelete = true;

	protected $guarded = ['_id', 'SeoGamertag', 'Date', 'Month', 'Year', 'Status'];

	public function getTotalMedalStatsAttribute($value)
	{
		return $this->unpackMsg($value);
	}

	public function getTotalSkillStatsAttribute($value)
	{
		return $this->unpackMsg($value);
	}

	public function setSpecializationAttribute($value)
	{
		$this->attributes['Specialization'] = $this->getCurrentSpecialization($value, "Name");
		$this->attributes['SpecializationLevel'] = $this->getCurrentSpecialization($value, "Level");
	}

	public function setTotalGameplayAttribute($value)
	{
		$this->attributes['TotalGameplay'] = $this->adjustDate($value);
	}

	public function setKDRatioAttribute($value)
	{
		$this->attributes['KDRatio'] = floatval($value);
		//$this->attributes['KADRatio'] = round()
	}

	public function setTotalMedalStatsAttribute($value)
	{
		$this->attributes['TotalMedalStats'] = $this->packMsg($value);
	}

	public function setTotalSkillStatsAttribute($value)
	{
		$this->attributes['TotalSkillStats'] = $this->packMsg($this->setCurrentSkills($value));
	}

	public function setEmblemAttribute($value)
	{
		$this->attributes['Emblem'] = substr_replace($value, "", -12);
	}



	/**
	 * Decodes utf8 strings from MongoDB and unpacks back into an array from msgpack
	 *
	 * @param $value
	 * @return mixed
	 */
	private function unpackMsg($value)
	{
		return msgpack_unpack(utf8_decode($value));
	}

	/**
	 * Encodes an array into UTF8, then msgpack's it.
	 *
	 * @param $value
	 * @return mixed
	 */
	private function packMsg($value)
	{
		return utf8_encode(msgpack_pack($value));
	}

	/**
	 * Takes DD.HH.MM.SS (days.hours.minutes.seconds)
	 * and converts to unix timestamp
	 *
	 * @param $value
	 * @return int
	 */
	private function adjustDate($value)
	{
		if (preg_match('/(?P<days>[0-9]*).(?P<hours>[0-9]*):(?P<minutes>[0-9]*):(?P<seconds>[0-9]*)/', $value, $regs))
		{
			return (($regs['days'] * 86400) + ($regs['hours'] * 3600) + ($regs['minutes'] * 60) + $regs['seconds']);
		}

		return 0;
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

	private function setCurrentSkills($skills)
	{
		foreach($skills as $key => $skill)
		{
			unset($skills[$key]->PlaylistName);
			unset($skills[$key]->PlaylistDescription);
			unset($skills[$key]->PlaylistImageUrl);
		}

		return $skills;
	}
}