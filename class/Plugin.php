<?php

namespace Avorg;

use function defined;

if (!defined('ABSPATH')) exit;

class Plugin
{
    /** @var Database $database */
    private $database;

    /** @var Renderer $renderer */
    private $renderer;

    /** @var WordPress $wp */
    private $wp;

    /** @var array $dependencies */
    private $dependencies;

    public function __construct(
        AdminPanel $adminPanel,
        AjaxActionFactory $ajaxActionFactory,
        BlockFactory $blockFactory,
        Database $database,
        PlaceholderContent $contentBits,
        Localization $localization,
        PageFactory $pageFactory,
        Pwa $pwa,
        Renderer $renderer,
        RestControllerFactory $restControllerFactory,
        Router $router,
        Session $session,
        ScriptFactory $scriptFactory,
        WordPress $WordPress
    )
    {
        $this->database = $database;
        $this->renderer = $renderer;
        $this->wp = $WordPress;

        $this->dependencies = func_get_args();
    }

    public function registerCallbacks()
    {
        $this->wp->add_action("admin_notices", [$this, "renderAdminNotices"]);
        $this->wp->add_action("init", [$this, "init"]);
        $this->wp->register_activation_hook(AVORG_PLUGIN_FILE, [$this, "activate"]);

        $this->registerDependencyCallbacks();
    }

    private function registerDependencyCallbacks()
    {
        array_walk($this->dependencies, function ($dependency) {
            if (method_exists($dependency, 'registerCallbacks')) {
                call_user_func([$dependency, 'registerCallbacks']);
            }
        });
    }

    public function renderAdminNotices()
    {
        $this->wp->settings_errors();

        $this->outputUnsetOptionError(
            ["permalink_structure"],
            "AVORG Warning: Permalinks turned off!",
            "/wp-admin/options-permalink.php"
        );

        $this->outputUnsetOptionError(
            ["avorgApiUser", "avorgApiPass"],
            "AVORG Warning: Missing API credentials!",
            "/wp-admin/admin.php?page=avorg"
        );

        if (!$this->wp->is_plugin_active("pwa/pwa.php")) {
            $this->renderer->renderNotice("error",
                "AVORG Warning: PWA plugin not active!", "/wp-admin/plugins.php");
        }
    }

    /**
     * @param $optionNames
     * @param $message
     * @param null $url
     */
    private function outputUnsetOptionError($optionNames, $message, $url = null)
    {
        $values = array_map([$this->wp, "get_option"], $optionNames);

        if (in_array(False, $values)) {
            $this->renderer->renderNotice("error", $message, $url);
        }
    }

    public function activate()
    {
        $this->database->updateDatabase();
    }

    public function init()
    {
        $this->enqueuePluginStyles();
        $this->enqueueVideoJsStyles();
    }

    private function enqueuePluginStyles()
    {
        $this->wp->wp_enqueue_style("avorgStyle", AVORG_BASE_URL . "/style/style.css");
        $this->wp->wp_enqueue_style("avorgEditorStyle", AVORG_BASE_URL . "/style/editor.css");
    }

    private function enqueueVideoJsStyles()
    {
        $this->wp->wp_enqueue_style(
            "avorgVideoJsStyle",
            "//vjs.zencdn.net/7.0/video-js.min.css");
    }
}
