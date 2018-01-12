@extends('layouts.app')


@section('content')


<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>  
<link href="http://demo.expertphp.in/css/jquery.ui.autocomplete.css" rel="stylesheet">
<script src="http://demo.expertphp.in/js/jquery.js"></script>
<script src="http://demo.expertphp.in/js/jquery-ui.min.js"></script>
<script src="http://demo.expertphp.in/js/jquery-ui.js"></script>
<script src="http://demo.expertphp.in/js/jquery-ui.min.js"></script>
<script src="https://code.jquery.com/jquery-3.1.0.js"></script>
<script src="https://code.jquery.com/jquery-ui.min.jss"></script>
<script src="https://code.jquery.com/jquery-ui.js"></script>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css"/>
<div class="container">

    <h1>Laravel 5 Autocomplete using Bootstrap Typeahead JS</h1>   

    <input class="cid form-control" style="margin:0px auto;width:300px;" type="text" id="cid" name='term' data-action="{{ route('autocomplete') }}">

</div>

<script>
    $('.cid').each(function() {
        var $this = $(this);
        var src = $this.data('action');

        $this.autocomplete({
            source: src,
            minLength: 2,
            select: function(event, ui) {
                $('.cid').val(ui.item.value);
                $('.cid').val(ui.item.id);
            }
        });
    });

</script> 

@endsection