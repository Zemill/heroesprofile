@extends('layouts.app')
@section('title', $title)


@section('content')
  <leaderboard-page
    :columns="{{  json_encode($columns) }}"
    :inputurl="'{{ $inputUrl }}'" 
    >
  </leaderboard-page>
@endsection

@section('scripts')
@endsection
