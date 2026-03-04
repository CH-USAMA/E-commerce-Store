@extends('layouts.branch')

@section('title', 'Access Restricted')

@section('content')
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="display-1 text-muted mb-4"><i class="bi bi-shop-window"></i></div>
                <h2 class="fw-bold">No Store Assigned</h2>
                <p class="text-muted lead">You are logged in as a Branch Manager, but you have not been assigned to a
                    specific store yet. Please contact the Super Admin to assign you to a branch.</p>
                <div class="mt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-jabulani btn-lg px-5">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection