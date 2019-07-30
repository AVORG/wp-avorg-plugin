<?php


use Avorg\BlockRepository;

final class TestBlockRepository extends Avorg\TestCase
{
	/** @var BlockRepository $repository */
	protected $repository;

	public function setUp()
	{
		parent::setUp();

		$this->repository = $this->factory->secure("Avorg\\BlockRepository");
	}

	public function testInitRegistersTypeScriptLoader()
	{
		$this->repository->registerBlocks();

		$this->mockWordPress->assertMethodCalledWith("wp_register_script",
			'avorg_scripts',
			AVORG_BASE_URL . 'script/ts_loader.js',
			['wp-blocks', 'wp-element', 'system-js']);
	}

	public function testRegistersSystemJs()
	{
		$this->repository->registerBlocks();

		$this->mockWordPress->assertMethodCalledWith('wp_register_script',
			'system-js', AVORG_BASE_URL . "/node_modules/systemjs/dist/system.js");
	}

	public function testEnqueuesTypescriptLoader()
	{
		$this->repository->registerBlocks();

		$this->mockWordPress->assertMethodCalledWith('wp_enqueue_script', 'avorg_scripts');
	}

	public function testGetsBlockIndices()
	{
		$this->repository->registerBlocks();

		$this->mockFilesystem->assertMethodCalledWith('getMatchingPathsRecursive',
			'component/block', '/index\.js/');
	}

	public function testLocalizesLoaderScript()
	{
		$this->mockFilesystem->setReturnValue('getMatchingPathsRecursive', [
			AVORG_BASE_PATH . 'component/block/layer/name/index.js'
		]);

		$this->repository->registerBlocks();

		$this->mockWordPress->assertMethodCalledWith('wp_localize_script',
			'avorg_scripts', 'avorg_scripts', [
				'urls' => [AVORG_BASE_URL . 'component/block/layer/name/index.js']
			]);
	}
}