@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-11">
            @livewire($component, [
                'proposal_id' => $proposal_id,
                'id_laporan' => $id_laporan,
            ])
        </div>
    </div>
@endsection



