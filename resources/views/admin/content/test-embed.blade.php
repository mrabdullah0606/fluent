@extends('admin.master.master')

@section('title', 'Support Chat')

@section('content')
    <div style="height: calc(100vh - 100px);">
        <iframe 
            src="https://app.chatwoot.com/app/accounts/131300/dashboard"
            style="width: 100%; height: 100%; border: none;"
            allowfullscreen>
        </iframe>
    </div>
@endsection
