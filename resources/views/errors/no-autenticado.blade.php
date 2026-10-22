@extends('layout.master')

<div class="custom-bg text-dark">
    <div class="d-flex align-items-center justify-content-center min-vh-100 px-2">
        <div class="text-center">
            <h1 class="display-1 fw-bold">419</h1>
            
            <p class="fw-medium my-4" style="font-size: 16px">Lo sentimos, necesitamos que inicies sesión en CANDY para acceder a esta página.</p>

            <img src="{{ asset('img/419.svg') }}" alt="error" class="img-fluid mb-4 block" width="300">

            <div>
                <a href="/litoapps"
                class="btn btn-info fw-semibold rounded-pill px-4 py-2 custom-btn">
                    Ir a Litoapps
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    * {
        color: #0f0f0f;
    }

    .wrapper {
        display: none;
    }
</style>