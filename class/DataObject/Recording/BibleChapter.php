<?php

namespace Avorg\DataObject\Recording;

use Avorg\DataObject;
use function defined;

if (!defined('ABSPATH')) exit;

class BibleChapter extends DataObject\Recording
{
	public function toArray()
	{
		return array_merge(parent::toArray(), [
			"title" => "Chapter " . $this->chapter_id,
			"audioFiles" => $this->getAudioFileArrays()
		]);
	}

	private function getAudioFileArrays()
	{
		$base = 'https://www.audioverse.org/english/download/audiobible';
		$filename = pathinfo($this->path)['filename'];
		$bookId = $this->book_id;
		$chapterId = $this->chapter_id;
		$pieces = explode('_', $filename);
		$dir = end($pieces) . "${bookId}_${chapterId}.mp3";
		$basename = urlencode($this->path);

		return [
			[
				'streamUrl' => "$base/$dir/$basename",
				'type' => 'audio/mp3'
			]
		];
	}

	public function getId()
	{
		return sha1($this->path);
	}
}