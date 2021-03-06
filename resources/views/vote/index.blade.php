@extends('layouts.app')

@section('title','Votes')

@section('content')

    @foreach($votes as $vote)
        <div class="post-card">
        <div class="card vertical post-card-content">
            <div class="card-action">
                <div class="row post-card-heading no-margin">
                    <div class="col l8 subheade hide-on-med-and-down" align="left"><h6>{{$vote->created_at}}</h6></div>
                    @if (strtotime($vote->ended_at) - strtotime('now') >= 0)
                    	<div class="col l4 right"><a href="{{url('/vote/'.$vote->id)}}">Vote Now!</a></div>
                    @endif
					@if (strtotime($vote->ended_at) - strtotime('now') < 0)
						<div class="col l4 right"><a href="#">Vote Not Available Now</a></div>
					@endif
                </div>
            </div>
            <div class="post-user-profile">
                <div class="card-image"><img class="circle" src="{{ url($vote->getAuthor->avatar) }}" /></div>
                <div class="post-header-container">
                    <h5 class="header post-header">{{$vote->title}}</h5>
                </div>
            </div>
            <div class="card-stacked">
                <div class="card-content display-all">
                    <!--Tags. Limit to 3 per post and their length-->
                    <div class="tag-container">
                        <button class="tag-btn btn-flat waves-effect waves-light">结束时间：{{$vote->ended_at}}</button>
                        <button class="tag-btn btn-flat waves-effect waves-light">投票人数：{{count($vote->votedUserIds())}}</button>
                    </div>
                    <h6 class="subheader post-header hide-on-large-only">{{$vote->created_at}}</h6>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection
