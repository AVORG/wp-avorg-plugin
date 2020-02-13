<?php

namespace Avorg;

use function dbDelta;
use function defined;
use function get_option;
use function update_option;

if (!defined('ABSPATH')) exit;

class Database
{
    private $wpdb;

    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;
    }

    public function updateDatabase()
    {
        $current = "2.1.0";
        $previous = get_option("avorg_schema_version");



        if ($previous != $current) {
            $this->updateSchema();
            update_option("avorg_schema_version", $current);
        }
    }

    public function updateSchema()
    {
        # Warning: dbDelta is very finicky with schema formatting.
        $schema = "CREATE TABLE {$this->wpdb->prefix}avorg_weights (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    entity_id int NOT NULL,
    type text NOT NULL,
    title text NOT NULL,
    url text NOT NULL,
    weight float DEFAULT '1' NOT NULL,
    FULLTEXT KEY (title),
    PRIMARY KEY  (id)
);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $result = dbDelta($schema);

        # Warning: dbDelta sometimes reports success even when it has failed.
        Logger::log(json_encode($result));
    }

    public function incrementOrCreateWeight($entityId, $title, $type, $url)
    {
        return $this->incrementWeight($entityId, $type) ||
            $this->insertWeightRow($entityId, $title, $type, $url);
    }

    public function incrementWeight($entityId, $type)
    {
        $table = $this->wpdb->prefix . 'avorg_weights';
        $query = $this->wpdb->prepare(
            "UPDATE $table SET weight = weight+1 WHERE entity_id = %d AND type = %s",
            $entityId, $type);

        return $this->wpdb->query($query);
    }

    public function decayWeights()
    {
        $table = $this->wpdb->prefix . 'avorg_weights';
        $query = "UPDATE $table SET weight = weight*.9";

        return $this->wpdb->query($query);
    }

    public function getWeightRow($entityId, $type)
    {
        $table = $this->wpdb->prefix . 'avorg_weights';
        $query = $this->wpdb->prepare(
            "SELECT * FROM $table WHERE entity_id = %d AND type = %s",
            $entityId, $type);

        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function getWeightRows()
    {
        $table = $this->wpdb->prefix . 'avorg_weights';
        $query = $this->wpdb->prepare("SELECT * FROM %s", $table);

        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function searchWeights($term)
    {
        $table = $this->wpdb->prefix . 'avorg_weights';
        $q = <<<QUERY
SELECT *, MATCH (title) AGAINST (%s) AS relevance FROM $table WHERE MATCH (title) AGAINST (%s) ORDER BY relevance DESC
QUERY;
        $query = $this->wpdb->prepare($q, $term, $term);

        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function insertWeightRow($entityId, $title, $type, $url)
    {
        $table = $this->wpdb->prefix . 'avorg_weights';

        return $this->wpdb->insert($table, [
            "entity_id" => $entityId,
            "title" => $title,
            "type" => $type,
            "url" => $url,
        ]);
    }
}