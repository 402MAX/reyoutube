<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: request/browse_request_params.proto

namespace GPBMetadata\Request;

class BrowseRequestParams
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            '
�
#request/browse_request_params.protocom.youtube.innertube.request"�
BrowseRequestParams
page (H �
tab (	H�J
sort (27.com.youtube.innertube.request.BrowseRequestParams.SortH�J
flow (27.com.youtube.innertube.request.BrowseRequestParams.FlowH�S
	live_view (2;.com.youtube.innertube.request.BrowseRequestParams.LiveViewH�
shelf_id (H�"N
Sort
DEFAULT_SORT 
MOST_POPULAR

OLDEST

NEWEST

LATEST",
Flow
DEFAULT_FLOW 
GRID
LIST"K
LiveView
DEFAULT_LIVEVIEW 
ALL
LIVE
UPCOMING
PASTB
_pageB
_tabB
_sortB
_flowB

_live_viewB
	_shelf_idJJJbproto3'
        , true);

        static::$is_initialized = true;
    }
}

