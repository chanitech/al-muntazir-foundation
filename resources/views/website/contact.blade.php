@extends('website.layouts.app')

@section('title', 'Contact Us')

@section('content')
    @include('website.partials.page-hero', ['title' => 'Contact Us', 'subtitle' => 'Questions, ideas or want to join? We would love to hear from you.'])

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <span class="eyebrow">Get in touch</span>
                    <h2 class="mb-3">We're here to help</h2>
                    <p class="text-muted mb-4">Send us a message using the form and a member of our team will get back to you as soon as possible.</p>

                    <div class="row g-3">
                        @if ($site->contact_phone)
                            <div class="col-sm-6 col-lg-12">
                                <div class="contact-tile">
                                    <div class="icon"><i class="bi bi-telephone"></i></div>
                                    <div><h3>Call us</h3><a href="tel:{{ preg_replace('/[^0-9+]/', '', $site->contact_phone) }}">{{ $site->contact_phone }}</a></div>
                                </div>
                            </div>
                        @endif
                        @if ($site->whatsappUrl())
                            <div class="col-sm-6 col-lg-12">
                                <div class="contact-tile">
                                    <div class="icon" style="background: rgba(37, 211, 102, .12); color: #1da851"><i class="bi bi-whatsapp"></i></div>
                                    <div><h3>WhatsApp</h3><a href="{{ $site->whatsappUrl() }}" target="_blank" rel="noopener">{{ $site->contact_whatsapp }}</a></div>
                                </div>
                            </div>
                        @endif
                        @if ($site->contact_email)
                            <div class="col-sm-6 col-lg-12">
                                <div class="contact-tile">
                                    <div class="icon"><i class="bi bi-envelope"></i></div>
                                    <div><h3>E-mail</h3><a href="mailto:{{ $site->contact_email }}">{{ $site->contact_email }}</a></div>
                                </div>
                            </div>
                        @endif
                        @if ($site->address)
                            <div class="col-sm-6 col-lg-12">
                                <div class="contact-tile">
                                    <div class="icon"><i class="bi bi-geo-alt"></i></div>
                                    <div><h3>Visit us</h3><p>{!! nl2br(e($site->address)) !!}</p></div>
                                </div>
                            </div>
                        @endif
                        @if ($site->office_hours)
                            <div class="col-sm-6 col-lg-12">
                                <div class="contact-tile">
                                    <div class="icon"><i class="bi bi-clock"></i></div>
                                    <div><h3>Office hours</h3><p>{{ $site->office_hours }}</p></div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if (count($site->socialLinks()))
                        <div class="mt-4">
                            <p class="fw-semibold mb-2">Follow us</p>
                            <div class="social-soft">@include('website.partials.social-links')</div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-7">
                    <div class="contact-card">
                        @if (session('success'))
                            <div class="alert alert-success-soft d-flex gap-2 align-items-start mb-4" role="status">
                                <i class="bi bi-check-circle-fill fs-5"></i><div>{{ session('success') }}</div>
                            </div>
                        @endif

                        <h3 class="mb-4">Send us a message</h3>
                        <form method="POST" action="{{ route('site.contact.store') }}" novalidate>
                            @csrf
                            {{-- Spam trap: hidden from people, filled in by bots --}}
                            <div class="hp-field" aria-hidden="true">
                                <label for="website">Leave this field empty</label>
                                <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Your name *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="120"
                                           class="form-control @error('name') is-invalid @enderror" autocomplete="name">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-mail *</label>
                                    <input type="email" name="email" id="email" value="{{ $errors->newsletter->any() ? '' : old('email') }}" required
                                           class="form-control @error('email') is-invalid @enderror" autocomplete="email">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone / WhatsApp</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" maxlength="40"
                                           class="form-control @error('phone') is-invalid @enderror" autocomplete="tel">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Topic</label>
                                    <select name="subject" id="subject" class="form-select @error('subject') is-invalid @enderror">
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject }}" @selected(old('subject', $selectedSubject ?? 'General enquiry') === $subject)>{{ $subject }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea name="message" id="message" rows="6" required minlength="10" maxlength="5000"
                                              class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 d-flex flex-wrap align-items-center gap-3">
                                    <button type="submit" class="btn btn-brand btn-lg"><i class="bi bi-send me-2"></i>Send message</button>
                                    <small class="text-muted">Fields marked * are required.</small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($site->map_embed_url)
        <section class="pb-5">
            <div class="container">
                <div class="map-embed">
                    <iframe src="{{ $site->map_embed_url }}" title="Map showing our location" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>
            </div>
        </section>
    @endif
@endsection
