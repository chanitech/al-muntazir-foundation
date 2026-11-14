@extends('adminlte::page')

@section('title', 'Site Settings')

@section('content_header')
    <x-admin.page-header title="Site Settings" subtitle="Your organisation's name, logo, colours, contact details and the main texts of the website.">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-external-link-alt mr-1"></i> View website</a>
    </x-admin.page-header>
@stop

@php
    $v = fn (string $key) => $values[$key] ?? null;
    $img = fn (string $key) => \App\Support\Media::url($values[$key] ?? null);
    $tabs = [
        'general' => ['General', 'fa-info-circle'],
        'branding' => ['Logo & Colours', 'fa-palette'],
        'contact' => ['Contact & Social', 'fa-address-book'],
        'about' => ['About Us', 'fa-users'],
        'homepage' => ['Homepage', 'fa-home'],
    ];
    $activeTab = array_key_exists(request('tab'), $tabs) ? request('tab') : 'general';
@endphp

@section('content')
    <x-admin.flash />

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="active_tab" id="active_tab" value="{{ $activeTab }}">

        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="nav flex-column nav-pills settings-tabs" role="tablist">
                            @foreach ($tabs as $key => [$label, $icon])
                                <a class="nav-link {{ $activeTab === $key ? 'active' : '' }}" data-toggle="pill" href="#tab-{{ $key }}"
                                   role="tab" data-tab="{{ $key }}"><i class="fas {{ $icon }}"></i>{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> Save settings</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content">

                    {{-- General --}}
                    <div class="tab-pane fade {{ $activeTab === 'general' ? 'show active' : '' }}" id="tab-general" role="tabpanel">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">General</h3></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8"><x-admin.input name="site_name" label="Organisation name" :value="$v('site_name')" required /></div>
                                    <div class="col-md-4"><x-admin.input name="site_short_name" label="Short name" :value="$v('site_short_name')" placeholder="e.g. AYFC" /></div>
                                </div>
                                <x-admin.input name="tagline" label="Tagline" :value="$v('tagline')" help="A short slogan shown under the name." />
                                <div class="row">
                                    <div class="col-md-8"><x-admin.input name="motto" label="Motto" :value="$v('motto')" /></div>
                                    <div class="col-md-4"><x-admin.input name="motto_source" label="Motto source" :value="$v('motto_source')" placeholder="e.g. Qur'an 3:139" /></div>
                                </div>
                                <x-admin.textarea name="footer_about" label="Footer description" :value="$v('footer_about')" rows="2"
                                    help="A sentence about the organisation shown in the website footer." />
                                <x-admin.textarea name="meta_description" label="Search engine description" :value="$v('meta_description')" rows="2" maxlength="300"
                                    help="Shown by Google and when the website is shared on WhatsApp / Facebook. About 150 characters." />
                            </div>
                        </div>
                    </div>

                    {{-- Branding --}}
                    <div class="tab-pane fade {{ $activeTab === 'branding' ? 'show active' : '' }}" id="tab-branding" role="tabpanel">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Logo &amp; Colours</h3></div>
                            <div class="card-body">
                                <x-admin.image name="logo" label="Logo" :current="$img('logo') ?? asset('images/logo.jpg')" :removable="(bool) $v('logo')"
                                    help="Square logo, PNG or JPG. Without an upload, the current AYFC logo is used." />
                                <x-admin.image name="favicon" label="Browser tab icon (favicon)" :current="$img('favicon')" accept="image/png,image/x-icon,image/jpeg"
                                    help="Optional. Square PNG or ICO, at least 64 × 64 px. The logo is used if empty." />

                                <hr>
                                <p class="text-muted">Website colours. The defaults match the AYFC logo.</p>
                                <div class="row">
                                    @foreach (['primary_color' => 'Main colour', 'accent_color' => 'Highlight colour', 'dark_color' => 'Dark colour (header & footer)'] as $key => $label)
                                        <div class="col-md-4">
                                            <div class="form-group color-field">
                                                <label for="f_{{ $key }}">{{ $label }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <input type="color" class="form-control" value="{{ old($key, $v($key)) }}"
                                                               oninput="document.getElementById('f_{{ $key }}').value = this.value" aria-label="{{ $label }} picker">
                                                    </div>
                                                    <input type="text" name="{{ $key }}" id="f_{{ $key }}" value="{{ old($key, $v($key)) }}"
                                                           class="form-control @error($key) is-invalid @enderror" pattern="#[0-9a-fA-F]{6}" required
                                                           oninput="if (/^#[0-9a-fA-F]{6}$/.test(this.value)) this.parentElement.querySelector('[type=color]').value = this.value">
                                                </div>
                                                @error($key) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="reset-colours"
                                        data-defaults='@json(['primary_color' => config('website.defaults.primary_color'), 'accent_color' => config('website.defaults.accent_color'), 'dark_color' => config('website.defaults.dark_color')])'>
                                    <i class="fas fa-undo mr-1"></i> Restore logo colours
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Contact & social --}}
                    <div class="tab-pane fade {{ $activeTab === 'contact' ? 'show active' : '' }}" id="tab-contact" role="tabpanel">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Contact details</h3></div>
                            <div class="card-body">
                                <p class="text-muted">Shown in the website header, footer and Contact page. Leave a field empty to hide it.</p>
                                <div class="row">
                                    <div class="col-md-6"><x-admin.input name="contact_email" label="E-mail" type="email" :value="$v('contact_email')" placeholder="info@ayfc.or.tz" /></div>
                                    <div class="col-md-6"><x-admin.input name="contact_phone" label="Phone" :value="$v('contact_phone')" placeholder="+255 …" /></div>
                                    <div class="col-md-6"><x-admin.input name="contact_whatsapp" label="WhatsApp number" :value="$v('contact_whatsapp')" placeholder="+255 …"
                                        help="Adds a WhatsApp chat button. Include the country code." /></div>
                                    <div class="col-md-6"><x-admin.input name="office_hours" label="Office hours" :value="$v('office_hours')" placeholder="Mon – Fri, 9:00 AM – 5:00 PM" /></div>
                                </div>
                                <x-admin.textarea name="address" label="Address" :value="$v('address')" rows="2" />
                                <x-admin.input name="map_embed_url" label="Google Maps embed link" :value="$v('map_embed_url')"
                                    placeholder="https://www.google.com/maps/embed?pb=…"
                                    help="In Google Maps: Share → Embed a map → copy only the link inside src=&quot;…&quot;." />
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Social media</h3></div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach (['facebook_url' => 'Facebook', 'instagram_url' => 'Instagram', 'x_url' => 'X (Twitter)', 'youtube_url' => 'YouTube', 'tiktok_url' => 'TikTok', 'linkedin_url' => 'LinkedIn'] as $key => $label)
                                        <div class="col-md-6">
                                            <x-admin.input :name="$key" :label="$label" type="url" :value="$v($key)" placeholder="https://…" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- About --}}
                    <div class="tab-pane fade {{ $activeTab === 'about' ? 'show active' : '' }}" id="tab-about" role="tabpanel">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">About us</h3></div>
                            <div class="card-body">
                                <x-admin.input name="about_title" label="Heading" :value="$v('about_title')" />
                                <x-admin.textarea name="about_text" label="Who we are" :value="$v('about_text')" rows="7"
                                    help="Leave an empty line between paragraphs." />
                                <x-admin.image name="about_image" label="About photo" :current="$img('about_image')"
                                    help="A group photo or activity photo. Shown next to the About text." />
                                <div class="row">
                                    <div class="col-md-6"><x-admin.textarea name="mission" label="Mission" :value="$v('mission')" rows="4" /></div>
                                    <div class="col-md-6"><x-admin.textarea name="vision" label="Vision" :value="$v('vision')" rows="4" /></div>
                                </div>
                                <x-admin.textarea name="core_values" label="Core values" :value="$v('core_values')" rows="5"
                                    help="One value per line. You may add a description after a colon, e.g. “Service: we give back to our community”." />
                            </div>
                        </div>
                    </div>

                    {{-- Homepage --}}
                    <div class="tab-pane fade {{ $activeTab === 'homepage' ? 'show active' : '' }}" id="tab-homepage" role="tabpanel">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Top banner</h3></div>
                            <div class="card-body">
                                <div class="callout callout-info py-2">
                                    The homepage shows your <a href="{{ route('admin.sliders.index') }}">slider</a>. These texts are used only when there are no active slides.
                                </div>
                                <x-admin.input name="hero_title" label="Banner heading" :value="$v('hero_title')" />
                                <x-admin.textarea name="hero_subtitle" label="Banner text" :value="$v('hero_subtitle')" rows="2" />
                                <x-admin.image name="hero_image" label="Banner background" :current="$img('hero_image')" help="Optional wide photo." />
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Section headings</h3></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6"><x-admin.input name="programs_title" label="Programs heading" :value="$v('programs_title')" /></div>
                                    <div class="col-md-6"><x-admin.input name="programs_intro" label="Programs intro" :value="$v('programs_intro')" /></div>
                                    <div class="col-md-4"><x-admin.input name="stats_title" label="Impact heading" :value="$v('stats_title')" /></div>
                                    <div class="col-md-4"><x-admin.input name="events_title" label="Events heading" :value="$v('events_title')" /></div>
                                    <div class="col-md-4"><x-admin.input name="news_title" label="News heading" :value="$v('news_title')" /></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Call to action (bottom of homepage)</h3></div>
                            <div class="card-body">
                                <x-admin.input name="cta_title" label="Heading" :value="$v('cta_title')" />
                                <x-admin.textarea name="cta_text" label="Text" :value="$v('cta_text')" rows="2" />
                                <div class="row">
                                    <div class="col-md-5"><x-admin.input name="cta_button_text" label="Button text" :value="$v('cta_button_text')" /></div>
                                    <div class="col-md-7"><x-admin.input name="cta_button_link" label="Button link" :value="$v('cta_button_link')"
                                        placeholder="/contact?subject=Membership" help="Default: the Contact page with “Membership” selected." /></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
@stop

@push('js')
    <script>
        $(function () {
            // Remember which tab is open so the page returns to it after saving.
            $('.settings-tabs a[data-toggle="pill"]').on('shown.bs.tab', function () {
                $('#active_tab').val($(this).data('tab'));
            });

            // After a validation error, open the first tab that contains an error.
            var $error = $('.tab-pane .is-invalid').first();
            if ($error.length) {
                $('.settings-tabs a[href="#' + $error.closest('.tab-pane').attr('id') + '"]').tab('show');
            }

            $('#reset-colours').on('click', function () {
                $.each($(this).data('defaults'), function (key, value) {
                    var $text = $('#f_' + key);
                    $text.val(value).trigger('input');
                    $text.closest('.input-group').find('[type=color]').val(value);
                });
            });
        });
    </script>
@endpush
