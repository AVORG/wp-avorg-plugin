<?php

namespace Avorg;


use Exception;

if (!defined('ABSPATH')) exit;

class Book implements iJsonEncodable
{
	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	/** @var Router $router */
	private $router;

	private $data;

	public function __construct(RecordingRepository $recordingRepository, Router $router)
	{
		$this->recordingRepository = $recordingRepository;
		$this->router = $router;
	}

	/**
	 * @param mixed $data
	 * @return Book
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function __isset($name)
	{
		return isset($this->data->$name);
	}

	public function __get($name)
	{
		if (!$this->__isset($name)) return null;

		return $this->data->$name;
	}

	public function getUrl()
	{
		return $this->router->buildUrl("Avorg\Page\Book\Detail", [
			"entity_id" => $this->data->id,
			"slug" => $this->router->formatStringForUrl($this->data->title) . ".html"
		]);
	}

	private function getId()
	{
		return intval($this->__get("id"));
	}

	/**
	 * @return false|string
	 * @throws Exception
	 */
	public function toJson()
	{
		return json_encode([
			"recordings" => $this->getDataRecordings()
		]);
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	private function getDataRecordings()
	{
		return array_map(function (Recording $recording) {
			return $recording->toData();
		}, $this->getRecordings());
	}

	/**
	 * @throws Exception
	 */
	public function getRecordings()
	{
		return $this->recordingRepository->getBookRecordings($this->getId());
	}
}