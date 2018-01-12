@extends('layouts.app')

@section('styles')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ URL::to('/') }}/css/select2.css">
    
    <script src="{{ URL::to('/') }}/js/select2.js"></script>

@endsection


@section('content')
    <h1>Laravel 5 Autocomplete using Bootstrap Typeahead JS</h1>   
    <h1 id='teste'></h1>
        <select multiple name="cid[]" id="cid" class="cid form-control"></select>
        <input type="submit" action="" id="submit" value="">
    <ul>
        @foreach($cids as $cid)
            <li>{{ $cid['id']}}</li>
            <ul>
                <li> {{ $cid['text']}} </li>
            </ul>
        @endforeach
    </ul>
</div>

<script type="text/javascript">
    var selected = $("#cid").val();
    $.get("{{ route('search') }}", selected);

    $(document).ready(function() {
        $('#submit').on('click', function () {
            $("#teste").append($("#cid").val());
            return false;
        });

        // Configs id #cid to use select 2
        $("#cid").select2({
            placeholder: "Selecione o(s) CID(s)",
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('autocomplete') }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.id.toString().concat(" - ",item.text),
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    });
</script>

@endsection