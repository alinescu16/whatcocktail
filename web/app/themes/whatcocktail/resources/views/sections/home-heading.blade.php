<section class="pt-20 pb-32 bg-cover bg-bottom bg-fixed relative" style="background-image: url('{{ $featured_image }}')"> <!-- Photo by Magda Ehlers: https://www.pexels.com/photo/person-preparing-rum-drink-1189261/ -->
	<div class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-50 z-40 pointer-events-none"></div>
	<div class="container mx-auto z-50 relative">
		<p class="montserrat-600-bold text-md text-center mb-6 text-white">WhatCocktai.io</p>
		<h2 class="montserrat-700-bold text-5xl text-center mb-20 text-white">Search for a cocktail by</h2>

		<form class="" action="" id="">
			<div class="form-header form-buttons text-center mb-6">
				<button aria-checked="true" class="rounded bg-white/75 hover:text-white hover:bg-orange-400 aria-checked:shadow-xl aria-checked:text-white aria-checked:bg-orange-500 py-2 px-10 me-8 montserrat-400-bold inline text-slate-600">NAME</button>
				<button aria-checked="false" class="rounded bg-white/75 hover:text-white hover:bg-orange-400 aria-checked:shadow-xl aria-checked:text-white aria-checked:bg-orange-500 py-2 px-10 me-8 montserrat-400-bold inline text-slate-600">INGREDIENTS</button>
				<p class="inline montserrat-600-bold text-md text-center me-6 text-white">or... </p>
				<button aria-checked="false" class="rounded bg-white/75 hover:text-white hover:bg-orange-400 aria-checked:shadow-xl aria-checked:text-white aria-checked:bg-orange-500 py-2 px-10 me-8 montserrat-400-bold inline text-slate-600">GET A RANDOM COCKTAIL</button>
			</div>

			<div class="form-input text-center relative w-max mx-auto">
				<input type="text" name="cocktail" aria-description="cocktail-details-input" placeholder="Cocktail's name" class="bg-white/75 py-2 px-10 outline-none rounded-full text-slate-600"/>
				<button type="submit" id="submit" class="rounded-full submit text-white bg-orange-500 py-1 px-3 absolute right-1 top-1 hover:shadow-xl"><svg class="text-sm fill-white w-3 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg></button>
			</div>
		</form>
	</div>
</section>