<?php

namespace Avorg;

class Session {
    /** @var Php $php */
    private $php;

    /** @var WordPress $wp */
    private $wp;

    public function __construct(Php $php, WordPress $wp)
    {
        $this->php = $php;
        $this->wp = $wp;
    }

    public function __get($name)
    {
        return $this->__isset($name) ? $_SESSION[$name] : null;
    }

    public function __isset($name)
    {
        return array_key_exists($name, $_SESSION);
    }

    public function registerCallbacks()
    {
        $this->wp->add_action("init", [$this, "init"], 1);
    }

    public function init()
    {
        $this->php->initSession();
    }

    public function loadData($data)
    {
        if (is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }

        $_SESSION = array_merge($_SESSION ?? [], $data ?? []);
    }
}