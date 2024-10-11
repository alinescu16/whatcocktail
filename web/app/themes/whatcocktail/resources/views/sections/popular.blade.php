<section class="container py-24">
	<p class="max-w-screen-sm mx-auto montserrat-600-bold text-md text-center mb-14 text-slate-500">Try some popular cocktails.</small>
	<h2 class="max-w-screen-sm mx-auto montserrat-700-bold text-5xl text-center mb-20 text-slate-700">Popular Cocktails</h2>

	<div class="md:columns-2 columns-1 pt-12 ">
		@foreach ($cocktails as $cocktail)
			<div class="w-full">
				<div class="box box-transparent box-popular-instructions p-12 rounded-lg border-4 border-gray-500">

					@if (isset($cocktail->preparation))
					<div class="box-content content-ingredients">
						<span>Instructions: </span>
						<span>{{ $cocktail->preparation }}</span>
					</div>
					@endif
					
					@if (isset($cocktail->ingredients))
						<div class="box-content content-ingredients">
							<span>Ingredients: </span>

							<span>
								@foreach ($cocktail->ingredients as $index => $ingredient)
									@if (isset($ingredient->ingredient))
										{{ $ingredient->ingredient }}@if($index + 1 < count($cocktail->ingredients)),@endif 
										<!-- Replace count($cocktail->ingredients) with $cocktail->ingredients_count -->
									@endif
								@endforeach
							</span>
						</div>
					@endif
				</div>

				<div class="box box-popular-description">
					<p class="box-content content-cocktail-category montserrat-400 text-left text-amber-500">{{ $cocktail->category }}</p>
					<a href="{{ get_permalink( get_page_by_title( 'Cocktail Details' ) ) . '?cocktail=' . $cocktail->name }}">
						<h2 class="box-content content-cocktail-name max-w-screen-sm me-auto montserrat-700-bold text-5xl mb-20 text-slate-700">{{ $cocktail->name }}</h2>
					</a>
					
					@if (isset($cocktail->estimated_preparation_time))
						<span class="box-content content-cocktail-preparation-time">~ {{ $cocktail->estimated_preparation_time[0] }} - {{ $cocktail->estimated_preparation_time[1] }}</span>
					@endif
				</div>
			</div>
		@endforeach
	</div>
</section>