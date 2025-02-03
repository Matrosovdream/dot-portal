@extends('web.layouts.app')

@section('content')

    <section class="blog pt-30 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-post-wrapper">

                        @foreach($articles as $article)

                            <article class="single-post-item">
                                @php /*
                                <div class="post-thumbnail-wrapper">
                                    <a href="{{ route('web.articles.show', $article->slug) }}">
                                        <img src="{{ asset('user/assets/img/blog/post_02.jpg') }}" alt="{{ $article->title }}">
                                    </a>
                                </div>
                                */ @endphp
                                <div class="post-content-wrapper">
                                    <ul class="post-meta ul_li">
                                        <li>
                                            <span class="posted-on">
                                                <i class="far fa-calendar-check"></i>
                                                <a href="#!">{{ $article->created_at->format('F j, Y') }}</a>
                                            </span>
                                        </li>
                                    </ul>
                                    <h3 class="post-title border_effect">
                                        <a href="{{ route('web.articles.show', $article->slug) }}">{{ $article->title }}</a>
                                    </h3>
                                    <div class="post-excerpt">
                                        <p>{{ $article->short_description }}</p>
                                    </div>
                                </div>
                            </article>

                            <hr/>

                        @endforeach
                        
                        <!--
                        <div class="pagination_wrap pt-20">
                            <ul>
                                <li><a href="#"><i class="far fa-long-arrow-left"></i></a></li>
                                <li><a href="#" class="current_page">01</a></li>
                                <li><a href="#">02</a></li>
                                <li><a href="#"><i class="fal fa-ellipsis-h"></i></a></li>
                                <li><a href="#">08</a></li>
                                <li><a href="#"><i class="far fa-long-arrow-right"></i></a></li>
                            </ul>
                        </div>
                        -->

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="blog-sidebar">
                        <div class="widget">
                            <h3 class="widget-title">Search</h3>
                            <form class="widget__search" action="#">
                                <input type="text" placeholder="Search your keyword">
                                <button>
                                    <img src="{{ asset('user/assets/img/icon/search.svg') }}" alt="">
                                </button>
                            </form>
                        </div>
                        <div class="widget">
                            <h3 class="widget-title">Categories</h3>
                            <ul class="widget__category list-unstyled">
                                <li><a href="#!"><i class="far fa-arrow-up"></i> Business visa</a></li>
                                <li><a href="#!"><i class="far fa-arrow-up"></i> Tourist visa</a></li>
                                <li><a href="#!"><i class="far fa-arrow-up"></i> Permanent Residency</a></li>
                            </ul>
                        </div>
                        <div class="widget">
                            <h3 class="widget-title">Tags</h3>
                            <div class="tagcloud">
                                <a href="#!">Citizenship</a>
                                <a href="#!">Family</a>
                                <a href="#!">Immigration</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection