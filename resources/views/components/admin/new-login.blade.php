{{-- Login details of a newly created member account, shown only once --}}
@if (session('new_login'))
    <div class="alert alert-info">
        <h5 class="mb-2"><i class="fas fa-key mr-1"></i> Login details for the new member</h5>
        <p class="mb-2">Give these details to the member. They are shown only once. The member can change the password under <em>Account Settings → Change Password</em>.</p>
        <dl class="row mb-0">
            <dt class="col-sm-3">Login e-mail</dt>
            <dd class="col-sm-9"><code class="bg-white text-dark px-2 py-1 rounded">{{ session('new_login.email') }}</code></dd>
            <dt class="col-sm-3">Temporary password</dt>
            <dd class="col-sm-9"><code class="bg-white text-dark px-2 py-1 rounded" style="font-size: 1.1rem; user-select: all">{{ session('new_login.password') }}</code></dd>
        </dl>
    </div>
@endif
