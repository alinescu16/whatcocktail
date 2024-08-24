{{-- 
  Template Name: Home 
--}}

@extends('layouts.app')

@section('content')
    @include('sections.home-heading')
    @include('sections.what-is-cocktail')
    @include('sections.popular')
@endsection
