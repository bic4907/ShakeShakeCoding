<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="9FRd2ASEj9c3YAqq7Wwz_bFRWR1Kfp8VxiqwIu3_Ys0" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic|Noto+Sans&display=swap&subset=korean" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v5.2.0/js/all.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script async src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML"></script>

    {{ Html::style('css/style.css') }}

    <!--[if IE]>
    {{ Html::style('css/style.ie.css') }}
    <![endif]-->

    {{ Html::style('css/loading.css') }}
    {{ Html::style('css/animate.min.css') }}
    {{ Html::style('css/github-markdown.css') }}

    <title>
        @if (View::hasSection('title'))
            @yield('title') - {{ config('app.name') }}
        @else
            {{ config('app.name') }}
        @endif

    </title>
    @if (Auth::check())
    <script>const _UID = '{{ Auth::user()->user_id }}';</script>
    @endif
</head>
<body>

    <div id="global-loading">
        <div class="lds-ripple"><div></div><div></div></div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-expand">
        <div class="container" style="min-width:1300px;">
            <a class="navbar-brand" href="{{ Route('home') }}">
                <img src="/img/nav_logo.png" width="300" class="d-inline-block align-top" alt="">
            </a>


            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div id="main-menu" class="navbar-nav">
                    <a class="nav-item nav-link" href="{{Route('question.list')}}">Question</a>
                </div>
                @if (Route::has('login'))
                <div id="sub-menu" class="navbar-nav ml-auto">
                    @if (Auth::check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user mr-2"></i>{{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="{{ Route('mypage') }}">마이페이지</a>
                                @if(Auth::user()->usertype == App\Enums\UserType::Professor)
                                <a class="dropdown-item" href="{{ Route('mypage.question.list') }}">내 출제물</a>
                                @else
                                <a class="dropdown-item" href="{{ Route('mypage.submission.list') }}">내 제출물</a>
                                @endif
                                <a class="dropdown-item" href="{{ Route('logout') }}">로그아웃</a>
                            </div>
                        </li>
                    @else
                        <a class="nav-item nav-link" href="{{Route('register')}}"><i class="fas fa-user mr-2"></i>Sign In</a>
                        <a class="nav-item nav-link" href="{{Route('login')}}"><i class="fas fa-sign-in-alt mr-2"></i>Log In</a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </nav>


    <header style="opacity:0;">
        @yield('header')
    </header>

    <section style="opacity:0;">
        @yield('content')
    </section>

    <footer class="page-footer font-small pt-5 mt-5">
        <div class="container text-center text-md-left mb-5">
            <div class="row">
                <div class="col-md-5 mt-md-0 mt-3">
                    <img src="/img/footer_logo.png" class="img-fluid">
                    <p class="mt-1 text-center font-weight-light font-italic">A Educational Platform for Starter of Programming</p>
                </div>
                <hr class="clearfix w-100 d-md-none pb-3">
                <div class="col-md-4 mb-md-0 mb-3">
                    <h5 class="font-weight-bold font-italic"><span class="gist-color">C</span>ontact</h5>
                    <p class="text-small font-italic" style="font-size:0.9em;">
                        Sejong University
                    </p>
                    <p>
                        <a href="mailto:webmaster@gist-leaderboard.com">
                            <i class="fas fa-envelope"></i>
                            <span class="font-italic">ShakeShakeCoding@gmail.com</span>
                        </a>
                    </p>
                </div>
                <div class="col-md-3 mb-md-0 mb-3">
                    <h5 class="font-weight-bold font-italic"><span class="gist-color">L</span>inks</h5>
                    <ul id="links" class="list-unstyled">
                        <li>
{{--                            <a href="{{Route('notice')}}">공지사항</a>--}}
                        </li>
                        <li>
{{--                            <a href="{{Route('privacypolicy')}}">개인정보처리방침</a>--}}
                        </li>
                        <li>
{{--                            <a href="{{Route('emailpolicy')}}">이메일무단수집거부</a>--}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-copyright text-center font-weight-normal font-italic py-4" style="color:#738181">
            Copyright (C) 2019 Sejong University. All Rights Reserved
        </div>

    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
    <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

    @if (env('APP_ENV') == 'local')
        <script src="http://{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    @else
        <script src="https://{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    @endif



    {{ Html::script('js/utils.js') }}
    {{ Html::script('js/app.js') }}
    {{ Html::script('js/loading.js') }}
    {{ Html::script('js/md_viewer.js') }}

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
        $('.file-uploader').FileUploader()
        $('.mdviewer').MarkDownViewer()
        //$('.md-viewer').MDViewer()

    </script>
    <script type="text/x-mathjax-config">
      MathJax.Hub.Config({
        tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
      });
    </script>
</body>
</html>
