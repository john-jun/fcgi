<?php
declare(strict_types=1);

namespace Air\FCgi;

/**
 * Class FastCGI
 * @package Air\FCgi
 */
class FastCGI
{
    //header length
    public const HEADER_LEN = 8;

    //unpack 解包参数
    public const HEADER_FORMAT = 'Cversion/Ctype/nrequestId/ncontentLength/CpaddingLength/Creserved';

    //max content length
    public const MAX_CONTENT_LENGTH = 65535;

    //version default 1
    public const VERSION_1 = 1;

    //begin message
    public const BEGIN_REQUEST = 1;

    //abort message
    public const ABORT_REQUEST = 2;

    //end message
    public const END_REQUEST = 3;

    //params message
    public const PARAMS = 4;

    //stdin message
    public const STDIN = 5;

    //stdout message
    public const STDOUT = 6;

    //stderr message
    public const STDERR = 7;

    //data message
    public const DATA = 8;

    //get values message
    public const GET_VALUES = 9;

    //get values result message
    public const GET_VALUES_RESULT = 10;

    //unknown message
    public const UNKNOWN_TYPE = 11;

    //request id default
    public const DEFAULT_REQUEST_ID = 1;

    //keep conn
    public const KEEP_CONN = 1;

    //响应器
    public const RESPONDER = 1;

    //认证器
    public const AUTHORIZER = 2;

    //过滤器
    public const FILTER = 3;

    //请求正常完成
    public const REQUEST_COMPLETE = 0;

    //服务器不支持并发处理，请求已被拒绝
    public const CANT_MPX_CONN = 1;

    //服务器耗尽了资源或达到限制，请求已被拒绝
    public const OVERLOADED = 2;

    //不支持指定的role，请求已被拒绝
    public const UNKNOWN_ROLE = 3;
}
