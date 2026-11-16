{{-- "Forgot password" page in the AdminLTE style (no Vite build needed) --}}
@extends('adminlte::auth.passwords.email')

@section('password_email_url', route('password.email'))
