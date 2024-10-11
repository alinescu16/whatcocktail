<section class="donate @if($dark) bg-slate-900 @endif py-12 ">
	<div class="donate-header">
		<p class="montserrat-600-bold text-md text-center mb-5 text-slate-500 @if($dark) text-white @endif">Each coffe actually feeds servers, API contribution and possibly some of it goes into the tattoo jar.</p>
		<h3 class="donation-title text-3xl text-center mb-9 @if($dark) text-white @endif text-slate-700">5 $. Fair, a Latte somewhere nice. TY!</h3>
	</div>

	<div class="donate-body">
		<form class="" id="" action="">
			<div class="form-body content-center text-center">
				<button aria-checked="true" class="bg-white @if($dark) bg-slate-100 @endif hover:bg-orange-400 hover:shadow-xl hover:tex-white taria-checked:shadow-xl aria-checked:text-white aria-checked:bg-orange-500 py-2 px-10 me-8 montserrat-400-bold">5$</button>
				<button class="bg-white @if($dark) bg-slate-100 @endif hover:bg-orange-400 hover:shadow-xl hover:text-white aria-checked:text-white aria-checked:bg-orange-400 py-2 px-10 me-8 montserrat-400-bold">10$</button>
				<button class="bg-white @if($dark) bg-slate-100 @endif hover:bg-orange-400 hover:shadow-xl hover:text-white aria-checked:text-white aria-checked:bg-orange-400 py-2 px-10 me-8 montserrat-400-bold">25$</button>
				<button class="bg-white @if($dark) bg-slate-100 @endif hover:bg-orange-400 hover:shadow-xl hover:text-white aria-checked:text-white aria-checked:bg-orange-400 py-2 px-10 me-8 montserrat-400-bold">50$</button>
				<input type="text" name="" id="" class="@if($dark) bg-slate-100 @endif py-2 px-10 focus:shadow-inner outline-none" placeholder="Or... Your Custom Ammount..." />
			</div>

			<button type="submit" id="submit" class="submit montserat-400-light mx-auto mt-12 flex text-lg hover:shadow-xl text-white bg-orange-500 py-2 px-10" aria-labeled-by="checkout-help">To Checkout <svg xmlns="http://www.w3.org/2000/svg" class="text-sm fill-white w-3 inline-block ms-3" viewBox="0 0 320 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg></button>
			<p id="checkout-help" class="mx-auto w-max pr-16 @if($dark) text-white @endif text-slate-700">Powered by Stripe™</p>
		</form>
	</div>
</section>