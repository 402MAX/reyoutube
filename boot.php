<?php
use YukisCoffee\CoffeeRequest\CoffeeRequest;
CoffeeRequest::setResolve([
    Rehike\Util\Nameserver\Nameserver::get("www.youtube.com", "1.1.1.1", 443)
        ->serialize()
]);

use Rehike\ControllerV2\Core as ControllerV2;
use Rehike\TemplateManager;

TemplateManager::registerGlobalState($yt);

// Pass resource constants to the templater
TemplateManager::addGlobal("ytConstants", $ytConstants);
TemplateManager::addGlobal("PIXEL", $ytConstants->pixelGif);

// Load general i18n files
use Rehike\i18n;
i18n::setDefaultLanguage("en");
i18n::newNamespace("main/regex")->registerFromFolder("i18n/regex");
i18n::newNamespace("main/misc")->registerFromFolder("i18n/misc");
i18n::newNamespace("main/guide")->registerFromFolder("i18n/guide");

// i18n for templates
$msgs = i18n::newNamespace("main/global")->registerFromFolder("i18n/global");
$yt->msgs = $msgs->getStrings()[$msgs->getLanguage()];

// Controller V2 init
ControllerV2::registerStateVariable($yt);
ControllerV2::setRedirectHandler(require "includes/spf_redirect_handler.php");

// Player init
use \Rehike\Player\PlayerCore;
PlayerCore::configure([
    "cacheMaxTime" => 18000, // 5 hours in seconds
    "cacheDestDir" => "cache",
    "cacheDestName" => "player_cache" // .json
]);

// Parse user preferences as stored by the YouTube application.
if (isset($_COOKIE["PREF"])) {
    $PREF = explode("&", $_COOKIE["PREF"]);
    $yt->PREF = (object) [];
    for ($i = 0; $i < count($PREF); $i++) {
        $option = explode("=", $PREF[$i]);
        $title = $option[0];
        if (isset($option[1]))
        {
            $yt->PREF->$title = $option[1];
        }
    }
} else {
    $yt->PREF = (object) [
        "f5" => "20030"
    ];
}

// Import all template functions
foreach (glob("includes/template_functions/*.php") as $file) include $file;