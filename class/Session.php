<?php

namespace Avorg;

/**
 * @property mixed|null userId
 * @property mixed|null sessionToken
 */
class Session
{
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
        return isset($_SESSION)
            && is_array($_SESSION)
            && array_key_exists($name, $_SESSION);
    }

    public function registerCallbacks()
    {
        $this->wp->add_action("init", [$this, "init"], 1);
    }

    public function init()
    {
        $this->php->initSession();

//        session_unset();

//        var_dump($this->userId, $this->sessionToken);
    }

    public function loadData($data)
    {
        if (is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }

        $_SESSION = array_merge($_SESSION ?? [], $data ?? []);
    }
}