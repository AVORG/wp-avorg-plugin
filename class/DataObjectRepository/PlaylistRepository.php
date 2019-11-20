<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObject;
use Avorg\DataObjectRepository;
use Exception;
use ReflectionException;

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

    /**
     * @param $playlistId
     * @return DataObject
     * @throws ReflectionException
     */
    public function getPlaylist($playlistId)
	{
		$rawObject = $this->api->getPlaylist($playlistId);

		return $this->makeDataObject($rawObject);
	}

    /**
     * @param $userId
     * @param $sessionToken
     * @param null $search
     * @param null $start
     * @return array
     */
    public function getPlaylistsByUser($userId, $sessionToken, $search = null, $start = null)
    {
        $rawObjects = $this->api->getPlaylistsByUser($userId, $sessionToken, $search, $start);

        return $this->makeDataObjects($rawObjects);
    }
}