<?php

namespace Avorg;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use function defined;
use function get_option;
use GuzzleHttp\Client;

if (!defined('ABSPATH')) exit;

class AvorgApi
{
    /** @var Client $newApiClient */
    private $newApiClient;

    /** @var Client $oldApiClient */
    private $oldApiClient;

    public function __construct()
    {
        $this->newApiClient = new Client([
            'base_uri' => 'https://api.audioverse.org/',
            'headers' => ['Authorization' => 'Bearer ' . get_option("avorgApiKey")]
        ]);

        $this->oldApiClient = new Client([
            'base_uri' => 'https://api2.audioverse.org/',
            'auth' => [get_option("avorgApiUser"), get_option("avorgApiPass")]
        ]);
    }

    /**
     * @param $email
     * @param $password
     * @return mixed
     * @throws Exception
     */
    public function logIn($email, $password)
    {
        return $this->postNew("auth/login", [
            'email' => $email,
            'password' => $password
        ])->data;
    }

    /**
     * @param $catalogId
     * @param $userId
     * @param $sessionToken
     * @param string $catalog
     * @throws Exception
     */
    public function addFavorite($catalogId, $userId, $sessionToken, $catalog = 'recording')
    {
        $this->postOld("favorite", [
            "userId" => $userId,
            "sessionToken" => $sessionToken,
            "catalogId" => $catalogId,
            "catalog" => $catalog
        ]);
    }

    /**
     * @param $userId
     * @param $sessionToken
     * @throws Exception
     */
    public function getFavorites($userId, $sessionToken)
    {
        /* TODO: Stop passing userId && sessionToken via query string */
        $this->getOld("favorite", [
            "userId" => $userId,
            "sessionToken" => $sessionToken,
        ]);
    }

    public function unFavorite($catalogId, $userId, $sessionToken, $catalog = 'recording')
    {

    }

    /**
     * @param $favoriteId
     * @param $userId
     * @param $sessionToken
     * @throws Exception
     */
    public function deleteFavorite($favoriteId, $userId, $sessionToken)
    {
        /* TODO: Stop passing userId && sessionToken via query string */
        $this->deleteOld('favorite', [
            "id[]" => $favoriteId,
            "userId" => $userId,
            "sessionToken" => $sessionToken
        ]);
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function getOneSeries($id)
    {
        if (!is_numeric($id)) return false;

        $response = $this->getResult("series/$id");

        return $response[0]->series;
    }

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
    public function getAllSeries($search = null, $start = null)
    {
        $endpoint = "series?search=$search&start=$start";

        return array_map(function ($item) {
            return $item->series;
        }, $this->getResult($endpoint));
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function getSponsor($id)
    {
        if (!is_numeric($id)) return false;

        $response = $this->getResult("sponsors/$id");

        return $response[0]->sponsors;
    }

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
    public function getSponsors($search = null, $start = null)
    {
        $endpoint = "sponsors?search=$search&start=$start";

        return array_map(function ($item) {
            return $item->sponsors;
        }, $this->getResult($endpoint));
    }

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
    public function getConferences($search = null, $start = null)
    {
        $endpoint = "conferences?search=$search&start=$start";

        return array_map(function ($item) {
            return $item->conferences;
        }, $this->getResult($endpoint));
    }

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
    public function getStories($search = null, $start = null)
    {
        $endpoint = "audiobooks?story=1&search=$search&start=$start";

        return array_map(function ($item) {
            return $item->audiobooks;
        }, $this->getResult($endpoint));
    }

    /**
     * @param $bibleId
     * @param $bookId
     * @param $testamentId
     * @return array
     * @throws Exception
     */
    public function getBibleChapters($bibleId, $bookId, $testamentId)
    {
        return (array)$this->getResult(
            "audiobibles/books/$bookId?volume=$bibleId&testament=$testamentId"
        );
    }

    /**
     * @param $bible_id
     * @return array
     * @throws Exception
     */
    public function getBibleBooks($bible_id)
    {
        return (array)$this->getResult("audiobibles/$bible_id");
    }

    /**
     * @param null $search
     * @return array
     * @throws Exception
     */
    public function getBibles($search = null)
    {
        return (array)$this->getResult("audiobibles");
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function getTopic($id)
    {
        if (!is_numeric($id)) return false;

        $response = $this->getResult("topics/$id");

        return $response[0]->topics;
    }

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
    public function getTopics($search = null, $start = null)
    {
        $endpoint = "topics?search=$search&start=$start";

        return array_map(function ($item) {
            return $item->topics;
        }, $this->getResult($endpoint));
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function getBook($id)
    {
        if (!is_numeric($id)) return false;

        $response = $this->getResult("audiobooks/$id");

        return $response[0]->audiobooks;
    }

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
    public function getBooks($search = null, $start = null)
    {
        $endpoint = "audiobooks?search=$search&start=$start";

        return array_map(function ($item) {
            return $item->audiobooks;
        }, $this->getResult($endpoint));
    }

    /**
     * @param null $search
     * @param null $start
     * @return array|object
     * @throws Exception
     */
    public function getPlaylists($search = null, $start = null)
    {
        return $this->getResult("playlist?search=$search&start=$start");
    }

    /**
     * @param $userId
     * @param $sessionToken
     * @param null $search
     * @param null $start
     * @return mixed
     * @throws Exception
     */
    public function getPlaylistsByUser($userId, $sessionToken, $search = null, $start = null)
    {
        return $this->getResult("playlist?userId=$userId&sessionToken=$sessionToken&search=$search&start=$start");
    }

    /**
     * @param $id
     * @return array|bool|object
     * @throws Exception
     */
    public function getPlaylist($id)
    {
        if (!is_numeric($id)) return false;

        return $this->getResult("playlist/$id");
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function getPresenter($id)
    {
        if (!is_numeric($id)) return false;

        $response = $this->getResult("presenters/{$id}");

        return $response[0]->presenters;
    }

    /**
     * @param null $search
     * @param null $start
     * @return mixed
     * @throws Exception
     */
    public function getPresenters($search = null, $start = null)
    {
        $endpoint = "presenters?search=$search&start=$start";

        return array_map(function ($item) {
            return $item->presenters;
        }, $this->getResult($endpoint));
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function getRecording($id)
    {
        if (!is_numeric($id)) return false;

        $response = $this->getResult("recordings/{$id}");

        return $response[0]->recordings;
    }

    /**
     * @param string $list
     * @return array
     * @throws Exception
     */
    public function getRecordings($list = "")
    {
        $endpoint = trim("recordings/$list", "/");

        return $this->getRecordingsResponse($endpoint);
    }

    /**
     * @param $topicId
     * @return array
     * @throws Exception
     */
    public function getTopicRecordings($topicId)
    {
        return $this->getRecordingsResponse("recordings/topic/$topicId");
    }

    /**
     * @param $presenterId
     * @return bool|array
     * @throws Exception
     */
    public function getPresenterRecordings($presenterId)
    {
        if (!is_numeric($presenterId)) return false;

        return $this->getRecordingsResponse("recordings/presenter/$presenterId");
    }

    /**
     * @param $sponsorId
     * @return bool|null
     * @throws Exception
     */
    public function getSponsorRecordings($sponsorId)
    {
        if (!is_numeric($sponsorId)) return false;

        return $this->getRecordingsResponse("recordings/sponsor/$sponsorId");
    }

    /**
     * @param $conferenceId
     * @return bool|array
     * @throws Exception
     */
    public function getConferenceRecordings($conferenceId)
    {
        if (!is_numeric($conferenceId)) return false;

        return $this->getRecordingsResponse("recordings/conference/$conferenceId");
    }

    /**
     * @param $bookId
     * @return bool|array
     * @throws Exception
     */
    public function getBookRecordings($bookId)
    {
        if (!is_numeric($bookId)) return false;

        return $this->getRecordingsResponse("recordings/audiobook/$bookId");
    }

    /**
     * @param $seriesId
     * @return bool|array
     * @throws Exception
     */
    public function getSeriesRecordings($seriesId)
    {
        if (!is_numeric($seriesId)) return false;

        return $this->getRecordingsResponse("recordings/series/$seriesId");
    }

    /**
     * @param $endpoint
     * @return null
     * @throws Exception
     */
    private function getRecordingsResponse($endpoint)
    {
        return array_map(function ($entry) {
            return $entry->recordings;
        }, $this->getResult($endpoint));
    }

    /**
     * @param $endpoint
     * @return mixed
     * @throws Exception
     */
    private function getResult($endpoint)
    {
        return $this->getOld($endpoint)->result;
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return mixed|ResponseInterface
     * @throws Exception
     */
    private function getOld(string $endpoint, array $data = [])
    {
        return $this->handle($this->oldApiClient, 'GET', $endpoint, [
            'query' => $data
        ]);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    private function postOld(string $endpoint, array $data = [])
    {
        return $this->handle($this->oldApiClient, 'POST', $endpoint, [
            'form_params' => $data
        ]);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    private function deleteOld(string $endpoint, array $data = [])
    {
        return $this->handle($this->oldApiClient, 'DELETE', $endpoint, [
            'query' => $data
        ]);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    private function postNew(string $endpoint, array $data = [])
    {
        return $this->handle($this->newApiClient, 'POST', $endpoint, [
            'form_params' => $data
        ]);
    }

    /**
     * @param Client $client
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    private function handle(
        Client $client,
        string $method,
        string $endpoint,
        array $options = []
    )
    {
        if (defined('AVORG_TESTS_RUNNING') && AVORG_TESTS_RUNNING) {
            throw new Exception("Unmocked API method called");
        }

        try {
            $response = $client->request($method, $endpoint, $options);

            return json_decode($response->getBody());
        } catch (GuzzleException $e) {
            throw new Exception("Failed to get response from url $endpoint");
        }
    }
}