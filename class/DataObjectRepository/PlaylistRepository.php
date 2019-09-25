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

	public function getPlaylist($playlistId)
	{
		$rawObject = $this->api->getPlaylist($playlistId);

		return $this->makeDataObject($rawObject);
	}

    public function getDataObjects()
    {
        // TODO: Implement getDataObjects() method.
    }
}