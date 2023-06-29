<?php

use Rehike\Async\Promise;
use \Rehike\Controller\core\AjaxController;
use \Rehike\Network;
use \Rehike\TemplateFunctions;
use \Rehike\Model\Share\ShareBoxModel;
use \Rehike\Model\Share\ShareEmbedModel;

use function Rehike\Async\async;

return new class extends AjaxController 
{
    private ?string $videoId;
    private ?string $listId;

    public function onGet(&$yt, $request) 
    {
        $action = self::findAction();

        if (is_null($action) || !isset($request->params->video_id)) self::error();

        $this->videoId = $request->params->video_id;
        $this->listId = $request->params->list;

        switch ($action) 
        {
            case "get_share_box":
                self::getShareBox($yt, $request);
                break;
            case "get_embed":
                self::getEmbed($yt, $request);
                break;
        }
    }

    /**
     * Get the share box.
     */
    private function getShareBox(&$yt, $request): Promise/*<void>*/
    {
        return async(function() use (&$yt, $request) {
            $this->template = "ajax/share/get_share_box";
            $priInfo = yield self::videoInfo($this->videoId);
            $yt->page = ShareBoxModel::bake(
                videoId: $this->videoId, 
                title: TemplateFunctions::getText($priInfo->title), 
                listId: $this->listId
            );
        });
    }

    private function getEmbed(&$yt, $request): Promise/*<void>*/
    {
        return async(function() use (&$yt, $request) {
            $this->template = "ajax/share/get_embed";
            $priInfo = yield self::videoInfo($this->videoId);
            $listData = null;

            if ($this->listId) 
            {
                $listData = yield Network::innertubeRequest(
                    action: "browse",
                    body: [
                        "browseId" => "VL" . $this->listId
                    ]
                );
            }

            $yt->page = ShareEmbedModel::bake(
                videoId: $this->videoId, 
                title: TemplateFunctions::getText($priInfo->title), 
                listData: $listData
            );
        });
    }

    protected function videoInfo(string $videoId): Promise/*<object>*/
    {
        return async(function() use ($videoId) {
            $response = yield Network::innertubeRequest(
                action: "next",
                body: [
                    "videoId" => $videoId
                ]
            );
            $ytdata = $response->getJson();
    
            $results = $ytdata->contents->twoColumnWatchNextResults->results->results->contents ?? [];
            for ($i = 0; $i < count($results); $i++) 
            {
                if (isset($results[$i]->videoPrimaryInfoRenderer)) 
                {
                    $priInfo = $results[$i]->videoPrimaryInfoRenderer;
                }
            }
    
            if (!isset($priInfo)) 
            {
                http_response_code(400);
                echo $response;//"{\"errors\":[]}";
                die();
            }
            return $priInfo;
        });
    }
};