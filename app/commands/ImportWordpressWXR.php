<?php namespace Liked;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Entry;
use SimpleXmlElement;
use DateTime;
use Requests;

class ImportWordpressWXR extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:wordpress';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import links from Wordpress WXR file';

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
	 */
	public function fire()
	{

		$file = $this->option('file');
		
		if(file_exists($file)) {
			$this->info("Loading file: $file");
		} else {
			$this->error("Failed to load $file");
		}
		

		$xml = new SimpleXmlElement(file_get_contents($file));
		$items = $xml->channel->item;

		foreach ($items as $item) {

			$entry = Entry::firstOrNew( array(
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
			$entry->description = $item->description;

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

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('file', null, InputOption::VALUE_REQUIRED, 'File to import.', null),
		);
	}

}
