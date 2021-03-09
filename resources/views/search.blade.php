@extends('layouts.app')
@section('title', "Landing Page")
@section('content')
  <div class="container">
    <div class="deathwing_logo">
      <img src="/images/logo/full_deathwing.png" />
    </div>
    <div class="byline">
      <h3>Heroes of the Storm statistics and comparison</h3>
    </div>
  </div>

  <div class="search-bar-wrapper">
  <div class="search-bar">
    <form class="form-inline my-2 my-lg-0 header-search-form">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-sm btn-primary my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</div>
</div>


  <div class="container-fluid grey-background mb-3">
    <div class="container">
      <div class="card-group">
        <div class="card">
          <div class="card-image">
            <i class="fas fa-users"></i>
          </div>
          <div class="card-header">
            <h3>Global Stats</h3>
          </div>
          <div class="card-body">
            <p class="card-text">See statistics.</p>
            <a href="/Compare" class="btn btn-secondary">See Statistics</a>
          </div>
        </div>
        <div class="card">
          <div class="card-image">
            <i class="fas fa-address-card"></i>
          </div>
          <div class="card-header">
            <h3>Extensive player profile</h3>
          </div>
          <div class="card-body">
            <p class="card-text">See all of your stats in one place: see data for individual maps or heroes played, match history and comparisons all from within a streamlined profile.</p>
            <a href="/search" class="btn btn-secondary">Find your Profile</a>
          </div>
        </div>


        <div class="card">
          <div class="card-image">
            <i class="fas fa-align-left"></i>
          </div>
          <div class="card-header">
            <h3>Variety of Leaderboards</h3>
          </div>
          <div class="card-body">
            <p class="card-text">View leaderboards based on Player, Hero, or Role using Heroes Profile Rating.  Get talent builds, and navigate directly to player's profiles.</p>
            <a href="/Global/Leaderboard" class="btn btn-secondary">View Leaderboards</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid primary-background">
    <div class="container primary-content">
      <p>Heroes Profile uses data from Heroes Profile.  Currently, Heroes Profile has pulled {{ number_format(getMaxReplayID()) }} replays up to and including data from patch {{ getMaxGameVersion() }} and date/time <span class="date-format-2">{{ getMaxGameDate() }}</span>
        and incorporated them into our dataset.</p>
      </div>
    </div>

  @endsection
  @section('scripts')
  @endsection
