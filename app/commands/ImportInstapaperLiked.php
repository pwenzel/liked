<?php namespace Liked;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Entry;
use SimpleXmlElement;
use DateTime;

class ImportInstapaperLiked extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:instapaper';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import liked articles from Instapaper';

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
	 * 
	 * @link http://stackoverflow.com/questions/14383782/parsing-rss-xml-in-php-with-namespaces
	 * @link http://blog.sherifmansour.com/?p=302
	 * @link http://www.sitepoint.com/simplexml-and-namespaces/
	 */
	public function fire()
	{

		$max = filter_var($this->option('max'), FILTER_VALIDATE_INT);

		$feed = $_SERVER['INSTAPAPER_STARRED_RSS_URL'];
		$this->info("Loading feed $feed");

		$xml = \Cache::remember($feed, 60, function() use ($feed)
		{
		    $this->info("Uncached.");
			return file_get_contents($feed);
		});

		$xml = new SimpleXmlElement($xml);
		$items = $xml->channel->item;

		foreach ($items as $item) {

			$entry = Entry::firstOrCreate( array(
				'url' => $item->link,
			));

			// $meta = \Cache::remember('embedly_'.$feed, 60, function() use ($item)
			// {
			//     $embedly = new \Embedly\Embedly(array(
			// 	    'key' => $_SERVER['EMBEDLY_API_KEY'],
			// 	    'user_agent' => 'Mozilla/5.0 (compatible; liked/1.0)'
			// 	));
				
			// 	return $embedly->extract(array(
			// 	    'urls' => array($item->url)
			// 	));
			// });

			$entry->title = $item->title;
			$entry->guid = $item->guid;
			$entry->pubdate = DateTime::createFromFormat(DateTime::RSS, $item->pubDate);
			$entry->description = $item->description;

			if($entry->save()) {
				$this->info('Imported ' . $entry->url);
			} else {
				$this->error('Failed to import ' . $item->link);
			}

		}

	}

}
