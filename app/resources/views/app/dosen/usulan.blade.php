@extends('layouts.app')

@section('title', $title)

@section('content')
    @livewire($livewire, ['categoryId' => $categoryId])
@endsection



