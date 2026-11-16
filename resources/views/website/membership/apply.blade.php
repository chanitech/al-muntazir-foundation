@extends('website.layouts.app')

@section('title', 'Become a Member')
@section('meta_description', 'Apply online to become a member of '.$site->site_name.'.')

@section('content')
    @include('website.partials.page-hero', [
        'title' => 'Become a Member',
        'subtitle' => 'Join '.$site->site_name.'. Fill in the form and our team will review your application.',
    ])

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <span class="eyebrow">How it works</span>
                    <h2 class="h3 mb-4">Three simple steps</h2>
                    <ol class="apply-steps">
                        <li>
                            <span class="num">1</span>
                            <div><strong>Send your application</strong><p>Tell us about yourself using this form. It takes about five minutes.</p></div>
                        </li>
                        <li>
                            <span class="num">2</span>
                            <div><strong>We review it</strong><p>Our team checks your details and may contact you if anything is missing.</p></div>
                        </li>
                        <li>
                            <span class="num">3</span>
                            <div><strong>Welcome to the forum</strong><p>Once approved, you receive your membership number.</p></div>
                        </li>
                    </ol>
                    <div class="side-card mt-4">
                        <h3 class="h6 mb-2">Questions?</h3>
                        <p class="text-muted small mb-3">We are happy to help before you apply.</p>
                        <a href="{{ route('site.contact', ['subject' => 'Membership']) }}" class="btn btn-outline-brand btn-sm">Contact us</a>
                    </div>
                </div>

                <div class="col-lg-8">
                    @if (session('application_sent'))
                        <div class="contact-card text-center py-5" role="status">
                            <div class="apply-success-icon"><i class="bi bi-check-lg"></i></div>
                            <h2 class="h3 mb-2">Thank you, your application has been received!</h2>
                            <p class="text-muted mb-4">Our team will review it and contact you. Please keep your reference number.</p>
                            <div class="apply-reference">{{ session('application_sent') }}</div>
                            <div class="mt-4">
                                <a href="{{ route('home') }}" class="btn btn-brand">Back to the homepage</a>
                            </div>
                        </div>
                    @else
                        <div class="contact-card">
                            @if ($errors->any())
                                <div class="alert alert-danger rounded-3" role="alert">
                                    <strong>Please check the form.</strong> Some fields need your attention.
                                </div>
                            @endif

                            <form method="POST" action="{{ route('site.membership.store') }}" novalidate>
                                @csrf
                                <div class="hp-field" aria-hidden="true">
                                    <label for="website">Leave this field empty</label>
                                    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                                </div>

                                <fieldset class="apply-section">
                                    <legend><span>1</span> Personal details</legend>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="first_name" class="form-label">First name *</label>
                                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required maxlength="100" autocomplete="given-name"
                                                   class="form-control @error('first_name') is-invalid @enderror">
                                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="middle_name" class="form-label">Middle name</label>
                                            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" maxlength="100" autocomplete="additional-name"
                                                   class="form-control @error('middle_name') is-invalid @enderror">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="last_name" class="form-label">Last name *</label>
                                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required maxlength="100" autocomplete="family-name"
                                                   class="form-control @error('last_name') is-invalid @enderror">
                                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="date_of_birth" class="form-label">Date of birth</label>
                                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" max="{{ now()->subDay()->format('Y-m-d') }}"
                                                   class="form-control @error('date_of_birth') is-invalid @enderror" autocomplete="bday">
                                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="national_id" class="form-label">National ID (NIDA) number</label>
                                            <input type="text" name="national_id" id="national_id" value="{{ old('national_id') }}" maxlength="50"
                                                   class="form-control @error('national_id') is-invalid @enderror">
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="apply-section">
                                    <legend><span>2</span> Contact &amp; location</legend>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone / WhatsApp *</label>
                                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required maxlength="30" autocomplete="tel" placeholder="+255 …"
                                                   class="form-control @error('phone') is-invalid @enderror">
                                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email" name="email" id="email" value="{{ old('email') }}" maxlength="255" autocomplete="email"
                                                   class="form-control @error('email') is-invalid @enderror">
                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        @if ($regions->isNotEmpty())
                                            <div class="col-md-6">
                                                <label for="current_region" class="form-label">Region</label>
                                                <select name="current_region" id="current_region" class="form-select @error('current_region') is-invalid @enderror">
                                                    <option value="">Select region…</option>
                                                    @foreach ($regions as $region)
                                                        <option value="{{ $region->name }}" data-id="{{ $region->id }}" @selected(old('current_region') === $region->name)>{{ $region->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="current_district" class="form-label">District</label>
                                                <select name="current_district" id="current_district" class="form-select @error('current_district') is-invalid @enderror"
                                                        data-selected="{{ old('current_district') }}" data-url="{{ url('/districts') }}">
                                                    <option value="">Select region first…</option>
                                                </select>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <label for="current_region" class="form-label">Region</label>
                                                <input type="text" name="current_region" id="current_region" value="{{ old('current_region') }}" maxlength="100" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="current_district" class="form-label">District</label>
                                                <input type="text" name="current_district" id="current_district" value="{{ old('current_district') }}" maxlength="100" class="form-control">
                                            </div>
                                        @endif
                                        <div class="col-12">
                                            <label for="current_village" class="form-label">Ward / street / village</label>
                                            <input type="text" name="current_village" id="current_village" value="{{ old('current_village') }}" maxlength="150"
                                                   class="form-control @error('current_village') is-invalid @enderror" autocomplete="address-line1">
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="apply-section">
                                    <legend><span>3</span> About you</legend>
                                    <div class="row g-3">
                                        @if ($membershipTypes->count() > 1)
                                            <div class="col-12">
                                                <label for="membership_type_id" class="form-label">Type of membership</label>
                                                <select name="membership_type_id" id="membership_type_id" class="form-select @error('membership_type_id') is-invalid @enderror">
                                                    <option value="">Not sure, please advise me</option>
                                                    @foreach ($membershipTypes as $type)
                                                        <option value="{{ $type->id }}" @selected((string) old('membership_type_id') === (string) $type->id)>{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="col-md-6">
                                            <label for="education" class="form-label">Education</label>
                                            <input type="text" name="education" id="education" value="{{ old('education') }}" maxlength="255"
                                                   class="form-control" placeholder="e.g. Form Six, Diploma, University student">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="occupation" class="form-label">Occupation</label>
                                            <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" maxlength="255"
                                                   class="form-control" placeholder="e.g. Student, Teacher, Business">
                                        </div>
                                        <div class="col-12">
                                            <label for="motivation" class="form-label">Why would you like to join?</label>
                                            <textarea name="motivation" id="motivation" rows="4" maxlength="2000"
                                                      class="form-control @error('motivation') is-invalid @enderror">{{ old('motivation') }}</textarea>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="form-check mb-4">
                                    <input class="form-check-input @error('consent') is-invalid @enderror" type="checkbox" name="consent" id="consent" value="1" @checked(old('consent'))>
                                    <label class="form-check-label" for="consent">
                                        I confirm that the information above is correct and agree that {{ $site->site_short_name ?: $site->site_name }} may contact me about my application. *
                                    </label>
                                    @error('consent')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <button type="submit" class="btn btn-accent btn-lg"><i class="bi bi-send me-2"></i>Submit application</button>
                                    <small class="text-muted">Fields marked * are required.</small>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Load the districts of the chosen region.
        (function () {
            var region = document.getElementById('current_region');
            var district = document.getElementById('current_district');
            if (!region || !district || district.tagName !== 'SELECT') return;

            function load() {
                var option = region.options[region.selectedIndex];
                var id = option && option.getAttribute('data-id');
                district.innerHTML = '<option value="">' + (id ? 'Loading…' : 'Select region first…') + '</option>';
                if (!id) return;
                fetch(district.getAttribute('data-url') + '/' + encodeURIComponent(id), { headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.json(); })
                    .then(function (items) {
                        var selected = district.getAttribute('data-selected');
                        district.innerHTML = '<option value="">Select district…</option>';
                        items.forEach(function (item) {
                            var o = document.createElement('option');
                            o.value = item.name;
                            o.textContent = item.name;
                            if (item.name === selected) o.selected = true;
                            district.appendChild(o);
                        });
                    })
                    .catch(function () { district.innerHTML = '<option value="">Could not load districts</option>'; });
            }

            region.addEventListener('change', function () { district.setAttribute('data-selected', ''); load(); });
            if (region.value) load();
        })();
    </script>
@endpush
