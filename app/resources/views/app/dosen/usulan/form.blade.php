@extends('layouts.app')

@section('title', $title)

@section('content')
    <div>
        @livewire($livewire, [
            'categoryId' => $categoryId,
            'id_usulan' => $id_usulan,
        ])
    </div>
@endsection
