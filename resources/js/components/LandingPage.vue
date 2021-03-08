<template>
  <div class="container">
    <div>
      <div class="container">
        <div class="deathwing_logo">
          <img src="/images/logo/full_deathwing.png" />
        </div>
        <div class="byline">
          <h3>Heroes of the Storm statistics and comparison</h3>
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
                <p class="card-text">See Global Statistics.</p>
                <a href="/Global/Hero" class="btn btn-secondary">See Global Statistics</a>
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
                <p class="card-text">See all of your stats in one place: See data for individual maps or heroes played, match history and comparisons all from within a streamlined profile.</p>
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
          <p>Heroes Profile uses data from Heroes Profile and HotsApi.  HotsApi is an open Heroes of the Storm replay database with user uploaded replay data.
            Currently, Heroes Profile has pulled {{ maxreplayid }} replays up to and including data from patch {{ maxgameversion }} and date/time <span class="date-format-2">{{ maxgamedate }}</span>
            and incorporated them into our dataset.</p>
          </div>
        </div>
      </div>
    </div>

  </template>

  <script>
  export default {
    name: 'LandingPage',
    props: {
      maxreplayid: Number,
      maxgameversion: String,
      maxgamedate: String,
    },
    mounted() {
      console.log('Component mounted.')
    }
  }
  </script>
