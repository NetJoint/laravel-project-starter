<div class="navbar navbar-inverse navbar-static-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="{{config('app.url')}}">{{sitename()}}</a>
            <ul class="nav pull-right">                
                @if($user = Sentinel::getUser())
                <li><a href="/home">欢迎您：{{$user->name}}</a></li>
                    @if($user->hasRole('admin'))
                    <li><a href="/manage">管理中心</a></li>
                    @endif                    
                <li><a href="/logout">退出登录</a></li>
                @else
                <li><a href="/register">注 册</a></li>
                <li><a href="/login">登 录</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>