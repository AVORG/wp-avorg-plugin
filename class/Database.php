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
        $current = "1.0.5";
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
    id VARCHAR(32) NOT NULL,
    category text NOT NULL,
    url text NOT NULL,
    title text NOT NULL,
    weight float DEFAULT '1' NOT NULL,
    FULLTEXT KEY (url,title),
    PRIMARY KEY  (id)
);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $result = dbDelta($schema);

        # Warning: dbDelta sometimes reports success even when it has failed.
        Logger::log(json_encode($result));
    }

    public function incrementWeight($url, $title)
    {
        $table = $this->wpdb->prefix . 'avorg_weights';
        $id = sha1("$url:$title");
        $query = $this->wpdb->prepare(
            "UPDATE %s SET weight = weight+1 WHERE id = %s",
            $table, $id);

        return $this->wpdb->query($query);
    }

    public function decayWeights()
    {
        $table = $this->wpdb->prefix . 'avorg_weights';
        $query = $this->wpdb->prepare(
            "UPDATE %s SET weight = weight*.9",
            $table);

        return $this->wpdb->query($query);
    }

    public function getWeightRow($url, $title)
    {
        $table = $this->wpdb->prefix . 'avorg_weights';
        $id = sha1("$url:$title");
        $query = $this->wpdb->prepare(
            "SELECT * FROM %s WHERE id = %s",
            $table, $id);

        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function getWeightRows()
    {
        $table = $this->wpdb->prefix . 'avorg_weights';
        $query = $this->wpdb->prepare("SELECT * FROM %s", $table);

        return $this->wpdb->get_results($query);
    }

    public function searchWeightRows($term)
    {
        $table = $this->wpdb->prefix . 'avorg_weights';
        $q = <<<QUERY
SELECT *, MATCH (title, url) AGAINST (%s) AS relevance FROM %s WHERE MATCH (title, url) AGAINST (%s) ORDER BY relevance DESC
QUERY;
        $query = $this->wpdb->prepare($q, $table);

        return $this->wpdb->get_results($query);
    }

    public function insertWeightRow($url, $title, $category)
    {
        $table = $this->wpdb->prefix . 'avorg_weights';

        return $this->wpdb->insert($table, [
            "id" => sha1("$url:$title"),
            "category" => $category,
            "url" => $url,
            "title" => $title
        ]);
    }
}