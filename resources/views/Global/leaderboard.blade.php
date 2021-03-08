@extends('layouts.app')
@section('title', $title)


@section('content')
  <leaderboard-page></leaderboard-page>

  @if ($filtertype == "leaderboard")
    @include('filters.leaderboard')
  @else
    @include('filters.globals')
  @endif


  <div class="container-fluid">
    <table id="{{ $tableid }}" class="table table-striped table-bordered table-sm " cellspacing="0" width="100%">
      <thead class="thead-dark">
        @if(isset($column_headers))
          <tr>
            @foreach ($column_headers as $column)
              <th data-field="{{$column['key'] }}" id="{{$column['key'] }}">{{ $column['text'] }}</th>
            @endforeach
          </tr>
        @endif
        <tr>
          @foreach ($columns as $column)
            <th data-field="{{$column['key'] }}">{{ $column['text'] }}</th>
          @endforeach
        </tr>
      </thead>
    </table>
    <div id="echodata">
    </div>
  </div>

@endsection

@section('scripts')
@endsection
