<div class="navbar navbar-inverse navbar-static-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="{{config('app.url')}}">{{sitename()}}</a>
            <ul class="nav pull-right">                
                @if($user = Sentinel::getUser())
                <li><a href="javascript::viod(0)">欢迎您：{{$user->name}}</a></li>
                <li><a href="/logout">退出登录</a></li>
                @else
                <li><a href="/register">注册</a></li>
                <li><a href="/login">登 录</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>