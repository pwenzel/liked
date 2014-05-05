@extends('layouts.master')

@section('sidebar')
    @parent

<!--     <p>This is appended to the master sidebar.</p> -->
@stop

@section('content')

<section class="row">

	@foreach ($entries as $entry)
	    
	<article class="large-12 columns">

		<h3>
			<a href="{{ $entry->url }}">
			  	{{ $entry->title }}
		  	</a>
	  	</h3>

	  	<div class="row">

	  	@if($entry->image)
	  		<div class="large-2 columns">
	  			<a class="th" href="{{ $entry->image }}">
				  <img src="{{ $entry->image }}">
				</a>
	  		</div>
	  	@endif
	  		
	  		<div class="large-10 columns">
	  			<p>
					{{ $entry->description or 'No Excerpt' }}
				</p>
	  		</div>

	  	</div>

	</article>

	@endforeach

</section>

@stop