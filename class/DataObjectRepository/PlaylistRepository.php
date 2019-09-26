<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObjectRepository;
use Exception;

if (!defined('ABSPATH')) exit;

class PlaylistRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Playlist";

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getDataObjects($search = null, $start = null)
	{
		$rawObjects = $this->api->getPlaylists($search, $start);

		return $this->makeDataObjects($rawObjects);
	}

	public function getPlaylist($playlistId)
	{
		$rawObject = $this->api->getPlaylist($playlistId);

		return $this->makeDataObject($rawObject);
	}
}