<?php
namespace Rehike\Controller\ajax;

use Rehike\Network;
use Rehike\Util\RichShelfUtils;
use Rehike\Util\Base64Url;
use Rehike\Model\Browse\InnertubeBrowseConverter;
use Com\Youtube\Innertube\Helpers\VideosContinuationWrapper;

use function Rehike\Async\async;

/**
 * Controller for browse AJAX requests.
 * 
 * @author Aubrey Pankow <aubyomori@gmail.com>
 * @author Taniko Yamamoto <kirasicecreamm@gmail.com>
 * @author The Rehike Maintainers
 */
return new class extends \Rehike\Controller\core\AjaxController
{
    public $template = "ajax/browse";

    public function onGet(&$yt, $request)
    {
        return $this->onPost($yt, $request);
    }

    public function onPost(&$yt, $request)
    {
        return async(function() use (&$yt, $request)
        {
            if (!isset($request->params->continuation)) self::error();
            $continuation = $request->params->continuation;

            $contWrapper = new VideosContinuationWrapper();
            $contWrapper->mergeFromString(Base64Url::decode($continuation));

            $list = false;
            $wrap = false;
            if ($contWrapper->getContinuation() != "")
            {
                $continuation = $contWrapper->getContinuation();
                $list = $contWrapper->getList();
                $wrap = $contWrapper->getWrapInGrid();
            }

            $response = yield Network::innertubeRequest(
                action: "browse",
                body: [
                    "continuation" => $continuation
                ]
            
            );
            $ytdata = $response->getJson();

            if (isset($ytdata->onResponseReceivedActions))
            {
                foreach ($ytdata->onResponseReceivedActions as $action)
                {
                    if (isset($action->appendContinuationItemsAction))
                    {
                        foreach ($action->appendContinuationItemsAction->continuationItems as &$item)
                        {
                            switch (true)
                            {
                                case isset($item->continuationItemRenderer):
                                    if (!$list && !$wrap)
                                    {
                                        $yt->page->continuation = $item->continuationItemRenderer->continuationEndpoint->continuationCommand->token;
                                    }
                                    else
                                    {
                                        $nContWrapper = new VideosContinuationWrapper();
                                        $nContWrapper->setContinuation($yt->page->continuation = $item->continuationItemRenderer->continuationEndpoint->continuationCommand->token);
                                        $nContWrapper->setList($list);
                                        $nContWrapper->setWrapInGrid($wrap);
                                        $yt->page->continuation = Base64Url::encode($nContWrapper->serializeToString());
                                    }
                                    break;
                                case isset($item->richItemRenderer):
                                    $item = RichShelfUtils::reformatShelfItem($item, $list);
                                    break;
                                case isset($item->richSectionRenderer->content->richShelfRenderer):
                                    $item = RichShelfUtils::reformatShelf($item, $list);
                                    break;
                            }
                        }
                        $yt->page->items = $action->appendContinuationItemsAction->continuationItems;
                    }
                }
            }
            else
            {
                self::error();
            }

            $yt->page->items =
            InnertubeBrowseConverter::generalLockupConverter(
                $yt->page->items,
                [
                    "listView" => $list,
                    "channelRendererUnbrandedSubscribeButton" => true
                ]
            );

            if ($wrap)
            {
                $yt->page->items = [
                    (object) [
                        "shelfRenderer" => (object) [
                            "content" => (object) [
                                "gridRenderer" => (object) [
                                    "contents" => $yt->page->items
                                ]
                            ]
                        ]
                    ]
                ];
            }
    
            $yt->page->target = $request->params->target_id;
            $yt->page->response = $ytdata;
        });
    }
};