{{-- "Confirm your password" page in the AdminLTE style (no Vite build needed) --}}
@extends('adminlte::auth.passwords.confirm')

@section('password_reset_url', route('password.request'))
@section('dashboard_url', route('dashboard'))
