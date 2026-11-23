{{-- resources/views/app/dosen/usulan/index.blade.php --}}
@extends('layouts.app')

@section('title', $title)

@section('content')
    {{-- Memanggil komponen Livewire (Daftar Usulan atau Undangan) --}}
    @livewire($livewire, ['categoryId' => $categoryId ?? null])
@endsection



