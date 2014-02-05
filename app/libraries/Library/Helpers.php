<?php namespace Library;

use Illuminate\Support\Facades\File;

class Helpers {

	/**
	 * Returns medals and cleaned up places so
	 *
	 * 1,2,3,4 => Gold, Silver, Bronze, 4th
	 *
	 * @param $value
	 * @return string
	 */
	public static function getTrophy($value)
	{
		$trophy_url = '<img title="%1$s" src="' . asset('img/trophies/%2$s') . '" />';
		switch($value)
		{
			case 1:
				return sprintf($trophy_url, $value . "st", 'trophy_gold.png');

			case 2:
				return sprintf($trophy_url, $value . "nd", 'trophy_silver.png');

			case 3:
				return sprintf($trophy_url, $value . "rd", 'trophy_bronze.png');

			default:
				return number_format($value) . "<sup>th</sup>";
		}
	}

	/**
	 * Returns the storage location of graphics, in full filepath
	 *
	 * @param $location
	 * @param $seo
	 * @param bool $create if directories should be created
	 * @return string
	 */
	public static function getStorageLocation($location, $seo, $create = true)
	{
		$path = public_path("uploads" . "/" . $location . "/" . $seo);

		if ($create)
		{
			if (!File::isDirectory($path))
			{
				File::makeDirectory($path, 0777, true);
			}
		}

		return str_finish($path, '/');
	}

	/**
	 * A function for making time periods readable
	 *
	 * @author      Aidan Lister <aidan@php.net>
	 * @version     2.0.1
	 * @link        http://aidanlister.com/2004/04/making-time-periods-readable/
	 * @param       int     number of seconds elapsed
	 * @param       string  which time periods to display
	 * @param       bool    whether to show zero time periods
	 * @return string
	 */
	public static function time_duration($seconds, $use = null, $zeros = false)
	{
		// Define time periods
		$periods = [
			'years' => 31556926,
			'Months' => 2629743,
			'weeks' => 604800,
			'days' => 86400,
			'hours' => 3600,
			'minutes' => 60,
			'seconds' => 1
		];

		// Break into periods
		$seconds = (float)$seconds;
		$segments = [];

		foreach ($periods as $period => $value)
		{
			if ($use && strpos($use, $period[0]) === false)
			{
				continue;
			}

			$count = floor($seconds / $value);

			if ($count == 0 && !$zeros)
			{
				continue;
			}

			$segments[strtolower($period)] = $count;
			$seconds = $seconds % $value;
		}

		// Build the string
		$string = [];
		foreach ($segments as $key => $value)
		{
			$segment_name = substr($key, 0, -1);
			$segment = $value . ' ' . $segment_name;

			if ($value != 1)
			{
				$segment .= 's';
			}
			$string[] = $segment;
		}

		return implode(', ', $string);
	}

	public static function seoFriendlyUrl($val)
	{
		return(str_replace(' ', '-', strtolower($val)));
	}
}
