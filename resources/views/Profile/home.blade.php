@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="container center">
    <h1 class="display-1">Zemill</h1>
</div>
  <div class="container center">
      <ul class="nav nav-tabs">
          <li class="nav-item">
              <a href="/Profile" class="nav-link active">Profile</a>
          </li>
          <li class="nav-item">
              <a href="/Profile/FriendsAndFoes" class="nav-link">Friends and Foes</a>
          </li>
          <li class="nav-item">
              <a href="Profile/Heroes/All" class="nav-link">Heroes</a>
          </li>
          <li class="nav-item">
              <a href="#" class="nav-link">Matchups</a>
          </li>
          <li class="nav-item">
              <a href="#" class="nav-link">Roles</a>
          </li>
          <li class="nav-item">
              <a href="#" class="nav-link">Maps</a>
          </li>
          <li class="nav-item">
              <a href="#" class="nav-link">Talents</a>
          </li>
          <li class="nav-item">
              <a href="#" class="nav-link">MMR Breakdown</a>
          </li>
          <li class="nav-item">
              <a href="#" class="nav-link">Match History</a>
          </li>
      </ul>
  </div>

  @include('filters.profile')

  <div class="container">
    <div class="card">
      <label id='wins'>Wins </label>
      <label id='losses'>Losses </label>
    </div>
  </div>


@endsection

@section('scripts')
  <script>
  $(document).ready(function() {
    var formData = $('#basic_search').serializeArray();


    parameters =
    {
      'page' : @json($page),
      'data' : formData,
      'blizz_id' : 67280,
      'region' : 1,
      'game_type' : "",
      'season' : "",
    }

    $.ajax({
      url: @json($inputUrl),
      data: parameters,
      //type: "POST",
      success: function(results){
        $("#wins").append(results[0].wins);
        $("#losses").append(results[0].losses);
        console.log(results);
      }
    });


  });
  </script>


@endsection
