<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: continuation_wrapper.proto

namespace Com\Google\Protos\Youtube\Api\Innertube;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>com.google.protos.youtube.api.innertube.ContinuationWrapper</code>
 */
class ContinuationWrapper extends \Google\Protobuf\Internal\Message
{
    /**
     * internal id for browse continuation wrapper
     *
     * Generated from protobuf field <code>optional .com.google.protos.youtube.api.innertube.BrowseContinuationWrapper browse_continuation = 80226972;</code>
     */
    protected $browse_continuation = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Com\Google\Protos\Youtube\Api\Innertube\BrowseContinuationWrapper $browse_continuation
     *           internal id for browse continuation wrapper
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\ContinuationWrapper::initOnce();
        parent::__construct($data);
    }

    /**
     * internal id for browse continuation wrapper
     *
     * Generated from protobuf field <code>optional .com.google.protos.youtube.api.innertube.BrowseContinuationWrapper browse_continuation = 80226972;</code>
     * @return \Com\Google\Protos\Youtube\Api\Innertube\BrowseContinuationWrapper|null
     */
    public function getBrowseContinuation()
    {
        return $this->browse_continuation;
    }

    public function hasBrowseContinuation()
    {
        return isset($this->browse_continuation);
    }

    public function clearBrowseContinuation()
    {
        unset($this->browse_continuation);
    }

    /**
     * internal id for browse continuation wrapper
     *
     * Generated from protobuf field <code>optional .com.google.protos.youtube.api.innertube.BrowseContinuationWrapper browse_continuation = 80226972;</code>
     * @param \Com\Google\Protos\Youtube\Api\Innertube\BrowseContinuationWrapper $var
     * @return $this
     */
    public function setBrowseContinuation($var)
    {
        GPBUtil::checkMessage($var, \Com\Google\Protos\Youtube\Api\Innertube\BrowseContinuationWrapper::class);
        $this->browse_continuation = $var;

        return $this;
    }

}

