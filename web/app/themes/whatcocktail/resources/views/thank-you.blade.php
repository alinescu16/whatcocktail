{{-- 
  Template Name: Thank You 
--}}

@extends('layouts.app')

@section('content')
<section class="container content-center py-12">
	<h1 class="montserrat-700-bold text-5xl text-center mb-6">Thank you!</h1>
	<p class="montserrat-400-light text-md text-center">You will shortly be redirected to the <a class="underline underline-offset-4 text-amber-500" href="{{ home_url('/') }}">homepage</a>.</p>
</section>
@endsection