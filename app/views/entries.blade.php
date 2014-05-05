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
		
		<blockquote>
			{{ $entry->description or 'No Excerpt' }}
		</blockquote>

	</article>

	@endforeach

</section>

@stop