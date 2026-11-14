<section class="section-sm">
    <div class="container">
        <div class="cta-band reveal">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h2 class="mb-2">{{ $site->cta_title }}</h2>
                    <p class="mb-0">{{ $site->cta_text }}</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ $site->get('cta_button_link') ?: route('site.contact', ['subject' => 'Membership']) }}" class="btn btn-accent btn-lg me-2 mb-2">{{ $site->get('cta_button_text', 'Join Us') }}</a>
                    <a href="{{ route('site.contact') }}" class="btn btn-ghost-light btn-lg mb-2">Contact us</a>
                </div>
            </div>
        </div>
    </div>
</section>
