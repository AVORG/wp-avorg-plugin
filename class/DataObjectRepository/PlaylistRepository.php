<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObjectRepository;
use Exception;

if (!defined('ABSPATH')) exit;

class PlaylistRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Playlist";

	/**
	 * @throws Exception
	 */
	public function getPlaylists()
	{
		$rawObjects = $this->api->getPlaylists();

		return $this->makeDataObjects($rawObjects);
	}
}