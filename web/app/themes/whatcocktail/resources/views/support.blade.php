{{-- 
  Template Name: Support 
--}}

@extends('layouts.app')

@section('content')
	<section class="container content-center py-12">
			<p class="max-w-screen-sm mx-auto montserrat-600-bold text-md text-center mb-14 text-slate-500">WhatCocktails will remain free. Forever.</small>
			<h1 class="max-w-screen-sm mx-auto montserrat-700-bold text-5xl text-center mb-20 text-slate-700">Support WhatCocktail</h1>
			<p class="max-w-screen-sm mx-auto montserrat-400 text-xl text-center mb-9 text-slate-600">I will not invest over my possibilities to keep this running. I hope you get to use it with ease, find it usefull and maybe even give some feedback or proposals for improvement. </p>
			<p class="montserrat-400 text-xl text-center mb-12 text-slate-600">Why not, I hope that you will sustain the project. All donations are appreaciated and much oblijed. Thank you in advance!</p> 

		@include('partials.content-donate', ['dark' => false])

	</section>
@endsection