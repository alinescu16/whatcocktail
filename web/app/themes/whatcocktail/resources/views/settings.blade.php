{{-- 
  Template Name: Settings
--}}

@extends('layouts.app')

@section('content')
<section>
	<h1>Settings</h1>

	<h3>Prefferences</h3>
	<label>Subscribe to “Cocktail of the week Newsletter</label>
	<label>Subscribe to “Recipe of the month” Newsletter</label>
	<label>Get e-mail notifications of new cocktails and app features</label>


	<h3>Personal Data</h3>
	<p>Delete all your data including all your saved details (IP Address, number of accesses, date of access, saved cocktails, deleted cocktails, requests made).</p>
	<small>Warning! This action is irreversible and can not be undone. We trully remove everything from our databases, logs and your local storage. Even from your computer we might delete some stuff.</small>

	<label>Confirm that you understand by clicking this checkbox first. (I joked about deleting stuff from your computer, I don’t have access to your computer)</label>

	<button id="delete" class="">Delete</button>
</section>
@endsection