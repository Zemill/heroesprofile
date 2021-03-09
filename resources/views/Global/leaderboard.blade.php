@extends('layouts.app')
@section('title', $title)


@section('content')
  <global-search-form
    :majorpatches="{{ json_encode(getMajorPatches()) }}"
    :minorpatches="{{ json_encode(getMinorPatches()) }}"
    :gametypes="{{ json_encode(getGameTypesForFilters()) }}"
    :heroes="{{ json_encode(getHeroesForFilters()) }}"
    :leaguetiers="{{ json_encode(getRankNames()) }}"
    :herolevels="{{ json_encode(getHeroLevelsForFilters()) }}"
    :roles="{{ json_encode(array_values(getHeroRoles())) }}"
    :gamemaps="{{ json_encode(getFilterMaps()) }}"


  ></global-search-form>

  <leaderboard-page
    :fields="{{  json_encode($columns) }}"
    :inputurl="'{{ $inputUrl }}'"
  >
  </leaderboard-page>
@endsection

@section('scripts')
@endsection
