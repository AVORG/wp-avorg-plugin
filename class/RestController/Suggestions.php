<?php

namespace Avorg\RestController;

use Avorg\Database;
use Avorg\RestController;
use Avorg\WordPress;
use WP_REST_Request;
use function defined;

if (!defined('ABSPATH')) exit;

class Suggestions extends RestController
{
    protected $route = '/suggestions';

    /** @var Database $database */
    private $database;

    public function __construct(Database $database, WordPress $wp)
    {
        parent::__construct($wp);

        $this->database = $database;
    }

    public function handleGet(WP_REST_Request $request)
    {
        $rows = $this->database->searchWeights($request['term']);

        if (!$rows) return [];

        $groups = $this->getTrimmedGroups($rows);
        $items = array_merge(...$groups);

        return $this->sortByRelevance($items);
    }

    /**
     * @param $rows
     * @return array
     */
    private function getTrimmedGroups($rows): array
    {
        $types = array_unique(array_column($rows, "type"));

        return array_map(function ($type) use ($rows) {
            $rowsOfType = $this->filterByType($rows, $type);
            $sortedRows = $this->sortByRelevance($rowsOfType);

            return array_slice($sortedRows, 0, 5);
        }, $types);
    }

    /**
     * @param $rows
     * @param $type
     * @return array
     */
    private function filterByType($rows, $type): array
    {
        return array_filter($rows, function ($row) use ($type) {
            return $row['type'] === $type;
        });
    }

    /**
     * @param array $array
     * @return array
     */
    private function sortByRelevance(array $array)
    {
        $relevance = array_column($array, "relevance");
        array_multisort($relevance, SORT_DESC, $array);

        return $array;
    }
}