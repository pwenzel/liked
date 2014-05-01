<?php namespace Liked;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Entry;
use FastFeed\Factory;

class ImportPandora extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:pandora';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import bookmarked songs from Pandora';

	/**
	 * Create a new command instance.
	 *
	 * @return void
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

		$feed = 'http://feeds.pandora.com/feeds/people/'. $_SERVER['PANDORA_USERNAME'] .'/favorites.xml?max=10';
		$this->info("Loading feed $feed");

		$fastFeed = Factory::create();
		$fastFeed->addFeed('pandora_favorites', $feed);

		// TODO: Cache 
		$items = $fastFeed->fetch('pandora_favorites');

		foreach ($items as $item) {
			
			$entry = Entry::firstOrCreate( array('url' => $item->getId(), 'title' => $item->getName() ) );

			if($entry) {
				$this->info('Imported ' . $item->getId());
			} else {
				$this->error('Failed to import ' . $item->getId());
			}

		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	// protected function getArguments()
	// {
	// 	return array(
	// 		array('example', InputArgument::REQUIRED, 'An example argument.'),
	// 	);
	// }

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	// protected function getOptions()
	// {
	// 	return array(
	// 		array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
	// 	);
	// }

}
