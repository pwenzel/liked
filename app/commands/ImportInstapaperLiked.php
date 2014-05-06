<?php namespace Liked;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Entry;
use SimpleXmlElement;
use DateTime;
use Requests;


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

			$api = 'https://readability.com/api/content/v1/parser?url=' . $item->link . '&token=' . $_SERVER['READABILITY_PARSE_API_KEY'];

			$meta = \Cache::rememberForever($api, function() use ($item, $api)
			{
			    $this->info("Uncached request to $api");
	
			    $response = Requests::get($api, array(), array('timeout' => 20)); // Define a timeout of 20 seconds

				if($response->success) {
					return json_decode($response->body);
				} else {
					return false;
				}

			});

			$entry->title = $item->title;
			$entry->guid = $item->guid;
			$entry->liked_date = DateTime::createFromFormat(DateTime::RSS, $item->pubDate);

			// Readabilty Excerpt
			$entry->description = (empty($meta->excerpt)) ? null : $meta->excerpt;

			// Readability Article Date
			$entry->date_published = (empty($meta->date_published)) ? null : DateTime::createFromFormat('Y-m-d g:i:s', $meta->date_published);

			// Readability Image
			$entry->image = (empty($meta->lead_image_url)) ? null : $meta->lead_image_url;


			if($entry->save()) {
				$this->info('Imported ' . $entry->url);
			} else {
				$this->error('Failed to import ' . $item->link);
			}

		}

	}

}
