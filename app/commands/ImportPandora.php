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

		$feed = 'http://feeds.pandora.com/feeds/people/'. $_SERVER['PANDORA_USERNAME'] .'/favorites.xml?max=';
		$this->info("Loading feed $feed");

		$fastFeed = Factory::create();
		$fastFeed->addFeed('pandora_favorites', $feed);

		$items = \Cache::remember($feed, 60, function() use ($fastFeed)
		{
		    return $fastFeed->fetch('pandora_favorites');
		});

		foreach ($items as $item) {

			$entry = Entry::firstOrCreate( array(
				'url' => $item->getId(),
			));

			$meta = \Cache::remember('embedly_'.$feed, 60, function() use ($entry)
			{
			    $embedly = new \Embedly\Embedly(array(
				    'key' => $_SERVER['EMBEDLY_API_KEY'],
				    'user_agent' => 'Mozilla/5.0 (compatible; liked/1.0)'
				));
				
				return $embedly->extract(array(
				    'urls' => array($entry->url)
				));
			});

			$entry->title = $item->getName();
			$entry->pubdate = $item->getDate();
			$entry->favicon = $meta[0]->favicon_url;

			if($entry->save()) {
				$this->info('Imported ' . $entry->url);
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
