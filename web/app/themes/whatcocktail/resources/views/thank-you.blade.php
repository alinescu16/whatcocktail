{{-- 
  Template Name: Thank You 
--}}

@extends('layouts.app')

@section('content')
<section>
	<h1>Thank you!</h1>
	<p>You will shortly be redirected to the <a href="{{ home_url('/') }}">homepage</a>.</p>
</section>
@endsection