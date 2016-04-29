<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="renderer" content="webkit">        
        @section('title')
            <title>{{sitename()}}</title>
        @show        
        @section('keywords')
            <meta name="keywords" content="">
        @show
        @section('description')
            <meta name="description" content="">
        @show
        @yield("css")
        <link href="/lib/bootstrap2-sui/css/bootstrap2-sui.css" rel="stylesheet">
        <link href="/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/css/website.css" rel="stylesheet">
        @yield("style")
        <!--[if lte IE 9]>
              <script src="/lib/iehack/html5shiv.min.js"></script>
              <script src="/lib/iehack/respond.min.js"></script>
              <script src="/lib/iehack/placeholder.js"></script>
        <![endif]-->        
        <link rel="shortcut icon" href="/favicon.png" sizes="16x16 32x32">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <script src="/lib/jquery/jquery-1.12.1.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browsehappy">您使用的浏览器<strong>版本过低</strong>，请<a href="http://browsehappy.com/">点此升级您的浏览器</a>以获得更好的体验。</p>
        <![endif]-->
        @yield("body")
        <script src="/lib/bootstrap2-sui/js/bootstrap2-sui.min.js"></script>
        @yield("script")     
    </body>
</html>