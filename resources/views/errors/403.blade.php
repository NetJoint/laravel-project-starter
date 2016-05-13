<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="renderer" content="webkit">
        <title>403</title>
        <link href="/lib/bootstrap2-sui/css/bootstrap2-sui.css" rel="stylesheet">
        <script src="/lib/jquery/jquery-1.12.1.min.js"></script>
    </head>
    <body>
        <div style="width:100%;text-align:center;display: table" id="box">
            <div style="vertical-align: middle;display: table-cell">
                <div class="msg msg-large msg-error">
                    <div class="msg-con">
                        @if($exception->getMessage())
                            {{$exception->getMessage()}}
                        @else
                            对不起，您无权访问该页面
                        @endif                        
                        <p>
                            您可以返回 <a href="javascript:window.history.go(-1)">上一页</a> 或 <a href="/">首页</a>
                        </p>
                    </div>
                    <s class="msg-icon"></s>
                </div>
            </div>
        </div>
        <script>
            $(function () {
                $('#box').height($(window).height()*0.9);
            });
        </script>
    </body>
</html>