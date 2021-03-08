@extends('layouts.app')
@section('title', "Landing Page")
@section('content')
  <landing-page :maxreplayid="{{ getMaxReplayID() }}" :maxgameversion="'{{ getMaxGameVersion() }}'" :maxgamedate="'{{ getMaxGameDate() }}'"></landing-page>
@endsection
@section('scripts')
@endsection
