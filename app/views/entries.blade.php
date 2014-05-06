@extends('layouts.master')

@section('content')

<section class="row">

	@foreach ($entries as $entry)
	    
	<article class="large-12 columns">

		
		<h4>
			{{ $entry->liked_date->format('l, F d, Y') }}
		</h4>
			
		<h3>
			<a href="{{ $entry->url }}">
			  	{{ $entry->title }}
		  	</a>
	  	</h3>

	  	<p>
			{{ $entry->description or 'No Excerpt' }}
		</p>

	</article>

	@endforeach

</section>

@stop