<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use HaloWaypoint\Utils;

class PlaylistCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:playlist_update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates the playlist database.';

	/**
	 * Create a new command instance.
	 *
	 * @return \PlaylistCommand
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		echo "\n .:. Playlist Updater .:. \n";

		if ($this->argument('force') == "true")
		{
			echo "Force update detected! Grabbing from 343. \n";
		}

		if (Utils::updatePlaylists($this->argument('force')))
		{
			echo "Playlist update was complete!\n";
		}
		else
		{
			echo "Playlist update failed!\n";
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('force', InputArgument::OPTIONAL, 'Forces recache from 343 servers', false)
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}
