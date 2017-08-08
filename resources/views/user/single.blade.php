@extends('layout.main')

@section('header')
    <h2 class='ui left aligned header inverted'>Details</h2>
    @if(Auth::check())
        @if(Auth::user()->id == $user->id)
            <script>setNav('myprofile')</script>
        @else
            <script>setNav('user')</script>
        @endif        
    @endif
    <script src='{{asset('js/posts.js')}}'></script>
    <script src='{{asset('js/user.js')}}'></script>
@endsection

@section('content')
    {{csrf_field()}}
    <div class="ui two column stackable grid">
        <div class="column">
                <img src="{{$user->profileimage()->getURL()}}" alt="" class="ui rounded large image profile-image">
        </div>
        <div class="column">
            <div class="ui huge top attached header">Das ist {{$user->username}}</div>
            <div class="ui bottom attached segment">
                <div class="ui header">Letzte Aktivitäten</div>
                <div class="ui feed">
                    @foreach($user->activities(3) as $activity)
                        @include('activity.overview')
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user())
        @if(Auth::user()->id == $user->id)
            <div class="ui segment">
                <h4 class="ui horizontal divider header">Aktionen</h4>
                <div class="ui buttons">
                    <a href='/logout' class="ui red labeled icon button">
                        <i class="log out icon"></i>
                        Abmelden
                    </a>
                    <a href='/users/{{$user->id}}/edit' class="ui blue right labeled icon button">
                        <i class="edit icon"></i>
                        Profil bearbeiten
                    </a>
                </div>
            </div>
        @endif
    @endif

    <div class="ui segment">
        <h4 class="ui horizontal divider header">Allgemein</h4>
        <div class="ui animated selection list">
            <div class="item">
                <div class="ui red label">
                    <i class="user icon"></i>
                    {{$user->username}}
                </div>
                Benutzername
            </div>
            @if($user->first_name != null || $user->last_name != null)
                <div class="item">
                    <div class="ui violet label">
                        {{$user->first_name}} {{$user->last_name}}
                    </div>
                    Vor- und Nachname
                </div>
            @endif
            <div class="item">
                <div class="ui red label">
                    {{$user->created_at->formatLocalized('%A %d %B %Y')}}
                </div>
                Beigetreten
            </div>
        </div>
    </div>

    <div class="ui segment">
        <h4 class="ui horizontal divider header">Statistiken</h4>
        <div class="ui divided animated selection list">
            <div class="item">
                <div class="ui blue label">
                    {{count($user->posts)}}
                </div>
                Posts veröffentlicht
            </div>
            <div class="item">
                <div class="ui violet label">
                    {{count($user->comments)}}
                </div>
                Kommentare geschrieben
            </div>
            <div class="item">
                Findet
                <div class="ui brown label">
                    {{count($user->shits)}}
                </div>
                Posts scheiße
            </div>
        </div>
    </div>

    @if(count($user->images))
        <div class="ui segment">
        <h4 class="ui horizontal divider header">Bildergalerie</h4>
        <div class="ui horizontal list">
            @foreach($user->images()->orderBy('updated_at', 'DESC')->get() as $image)
                <div class="item">
                    @include('layout.postimage')
                </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <h4 class="ui horizontal divider header">Posts von diesem Benutzer</h4>
    @if(count($user->posts))
        @foreach($user->posts()->orderBy('updated_at', 'DESC')->get() as $post)
            @include('layout.postoverview')
        @endforeach
    @else
        <div class="ui inverted red segment">
            @if($user->authenticate(false))
               Es sieht so aus, als hättest du noch nicht gepostet. Hole es gleich nach, indem du deinen ersten Post schreibst!
            @else
                Dieser Benutzer hat noch nichts gepostet.
            @endif
        </div>
    @endif
    

@endsection
