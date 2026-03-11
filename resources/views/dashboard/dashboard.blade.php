@extends('layouts.app')

@section('content')
    @if(Auth::guard('admin')->check())
        @include('dashboard.partials.admin')
    @elseif(Auth::guard('sale')->check())
        @include('dashboard.partials.sale')
    @elseif(Auth::guard('developer')->check())
        @include('dashboard.partials.developer')
    @else
        <!-- Fallback or normal user view -->
        <div class="page">
            <div class="page-header">
                <h1 class="page-title">Welcome</h1>
                <p class="page-desc">You are logged in.</p>
            </div>
        </div>
    @endif
@endsection
