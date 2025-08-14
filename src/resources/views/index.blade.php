@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('livewire')
@livewireStyles
@endsection

@section('search')

@if (isset($mylist))
<livewire:search :mylist="$mylist" :search="$search" />
@else
<livewire:search :search="$search" />
@endif

@endsection

@section('navigation')
@include('components.navigation')
@endsection

@section('content')
<nav class="menu-container">
    <livewire:menu :mylist="$mylist" :search="$search" />
</nav>

<livewire:grid :mylist="$mylist" :search="$search" />

@livewireScripts

@endsection