@extends('layouts.app')

@section('title', 'Calendar - FMA')
@section('hero.icon', 'calendar')
@section('hero.title', 'Calendar')
@section('hero.content', 'Airdates calendar')

@inject('Carbon', 'Carbon\Carbon')
@section('content')
<section class="section">
    <div class="container is-{{ $overview_container }}">
        <div class="calendar">
            <div class="calendar-side">
                <div>
                    <p>{{ $today->year }}</p>
                    <h2>
                        <span>{{ $today->format('D') }},</span>
                        <span>{{ $today->format('M d') }}</span>
                    </h2>
                </div>
            </div>
            <div class="calendar-body">
                <div class="calendar-heading">
                    <div class="calendar-row">
                        <div class="month">{{ $today->format('F') }}</div>
                    </div>
                    <div class="calendar-row">
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>
                        <div>Sun</div>
                    </div>
                </div>
                <div class="calendar-data">
                    @foreach($weeks as $week)
                    <div class="calendar-row">
                        @foreach($week as $day => $date)
                        <a href="?date={{ $date->toDateString() }}" class="calendar-col {{ $date->toDateString() == $today->toDateString() ? 'is-active' : '' }} {{ $date->month == $today->month ? '' : 'is-inactive' }}">
                            <h3>{{ $date->day }} <small>{{ $date->format('M') }}</small></h3>                   
                            @if (isset($meta[$day]))
                            <ul class="counts">
                                <li class="series">{{ $meta[$day]['series'] }} {{ $meta[$day]['series'] > 1 ? 'series' : 'serie' }}</li>
                                @if ($meta[$day]['premiers'] > 0)
                                <li class="premiers">{{ $meta[$day]['premiers'] }} {{ $meta[$day]['premiers'] > 1 ? 'premiers' : 'premier' }}</li>
                                @endif
                                @if ($meta[$day]['returning'] > 0)
                                <li class="returning">{{ $meta[$day]['returning'] }} returning</li>
                                @endif
                                @if ($meta[$day]['season_finale'] > 0)
                                <li class="finale">{{ $meta[$day]['season_finale'] }} {{ $meta[$day]['season_finale'] > 1 ? 'season finales' : 'season finale' }}</li>
                                @endif
                                @if ($meta[$day]['tracking'] > 0)
                                <li class="tracking">{{ $meta[$day]['tracking'] }} tracked</li>
                                @endif
                            </ul>
                            @endif
                        </a>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@if (isset($meta[$today->toDateString()]))
<section class="section">
    <div class="container is-{{ $overview_container }}">
        @foreach($meta[$today->toDateString()]['episodes'] as $episode)
        <div class="episode-card">
            <div class="card-background">
                <img src="{{ asset('img/fanart.png') }}" data-src="{{ $episode->serie->fanart }}" alt=""/>
            </div>
            <div class="card-left">
                <img src="{{ asset('img/poster.png') }}" data-src="{{ $episode->serie->poster }}" alt=""/>
            </div>           
            <div class="card-body">
                <div class="heading">
                    <h3 class="title">{{ $episode->name }}</h3>
                    <p class="subtitle">{{ $episode->serie->name }} | <span class="icon text is-danger"><i class="fa fa-heart"></i></span> {{ $episode->serie->rating }}%</p>
                </div>
                <div class="content">
                    <p>{{ str_limit($episode->overview, 250) }}</p>
                    <a class="button is-white is-outlined is-medium" href="{{ $episode->url }}">View</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
@endsection
