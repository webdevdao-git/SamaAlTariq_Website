@extends('layouts.admin')
@section('title', 'Projects')
@section('icon', 'file-plus')
@section('heading', 'Add Projects')
@section('subheading', 'Create and manage projects.')

@section('content')
    <div class="rounded-2xl border border-portal-ink/10 bg-white p-10 text-center">
        <p class="text-ink-muted">The create form is next in the build.</p>
        <a href="{{ route('admin.dashboard') }}" class="mt-4 inline-block text-portal hover:underline">Back to dashboard</a>
    </div>
@endsection
