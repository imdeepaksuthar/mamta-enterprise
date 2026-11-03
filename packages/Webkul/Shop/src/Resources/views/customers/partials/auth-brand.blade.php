<!--
    Brand panel beside the sign-in, sign-up, forgot and reset password forms.
    Shown on large screens only; styles live in public/css/custom.css (.me-auth).
-->
@php
    $authStorePhone = preg_replace('/\s+/', '', (string) core()->getConfigData('sales.shipping.origin.contact'));

    $authStoreEmail = core()->getConfigData('emails.configure.email_settings.contact_email');
@endphp

<aside class="me-auth__brand">
    <a
        href="{{ route('shop.home.index') }}"
        class="me-auth__brand-logo"
        aria-label="{{ config('app.name') }}"
    >
        <img
            src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
            alt="{{ config('app.name') }}"
            width="140"
            height="40"
        >
    </a>

    <div class="me-auth__brand-body">
        <p class="me-auth__brand-title">
            Office, school, cleaning and pantry supplies in one place.
        </p>

        <ul class="me-auth__brand-list">
            <li>Track your orders and download invoices</li>
            <li>Save addresses for a quicker checkout</li>
            <li>Reorder your regular supplies in a few clicks</li>
            <li>Keep a wishlist of products you buy often</li>
        </ul>
    </div>

    @if ($authStorePhone || $authStoreEmail)
        <p class="me-auth__brand-help">
            Need help?

            @if ($authStorePhone)
                <a href="tel:{{ preg_match('/^\d{10}$/', $authStorePhone) ? '+91'.$authStorePhone : $authStorePhone }}">{{ $authStorePhone }}</a>
            @endif

            @if ($authStorePhone && $authStoreEmail)
                ·
            @endif

            @if ($authStoreEmail)
                <a href="mailto:{{ $authStoreEmail }}">{{ $authStoreEmail }}</a>
            @endif
        </p>
    @endif
</aside>
