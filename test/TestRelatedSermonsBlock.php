<?php

use Avorg\Block\RelatedSermons;

final class TestRelatedSermonsBlock extends Avorg\TestCase
{
	/** @var RelatedSermons $block */
	protected $block;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->block = $this->factory->secure("Avorg\\Block\\RelatedSermons");
	}

	public function testInitRegistersScript()
	{
		$this->block->init();

		$this->mockWordPress->assertMethodCalledWith("wp_register_script",
			'avorg-block-relatedSermons',
			AVORG_BASE_URL . '/script/block-relatedSermons.js',
			['wp-blocks', 'wp-element']);
	}

	public function testInitRegistersBlockType()
	{
		$this->block->init();

		$this->mockWordPress->assertMethodCalledWith("register_block_type",
			'avorg/block-relatedsermons', [
				'editor_script' => 'avorg-block-relatedSermons'
			]);
	}
}