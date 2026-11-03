{!! view_render_event('bagisto.shop.layout.footer.before') !!}

<!--
    The category repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
-->
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')
@inject('categoryRepository', 'Webkul\Category\Repositories\CategoryRepository')

<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
    $channel = core()->getCurrentChannel();

    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);

    $footerCategories = $categoryRepository->getVisibleCategoryTree($channel->root_category_id);

    /**
     * Footer link columns have no titles in the admin, so name them by position.
     */
    $footerColumnTitles = ['Company', 'Help & Policies', 'More Links', 'Resources'];

    $storeName = core()->getConfigData('sales.shipping.origin.store_name') ?: config('app.name');

    $storeAddress = implode(', ', array_filter([
        core()->getConfigData('sales.shipping.origin.address'),
        core()->getConfigData('sales.shipping.origin.city'),
        core()->getConfigData('sales.shipping.origin.zipcode'),
    ]));

    $storePhone = preg_replace('/\s+/', '', (string) core()->getConfigData('sales.shipping.origin.contact'));

    $storePhoneLink = preg_match('/^\d{10}$/', $storePhone) ? '+91'.$storePhone : $storePhone;

    $storePhoneLabel = preg_match('/^\d{10}$/', $storePhone)
        ? '+91 '.substr($storePhone, 0, 5).' '.substr($storePhone, 5)
        : $storePhone;

    $storeEmail = core()->getConfigData('emails.configure.email_settings.contact_email');

    $storeGstin = core()->getConfigData('sales.shipping.origin.vat_number');
@endphp

<footer class="me-footer">
    <div class="me-footer__inner">
        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

        <!-- News Letter subscription -->
        @if (core()->getConfigData('customer.settings.newsletter.subscription'))
            <div class="me-footer__newsletter">
                <div class="me-footer__newsletter-text">
                    <p
                        class="me-footer__newsletter-title"
                        role="heading"
                        aria-level="2"
                    >
                        @lang('shop::app.components.layouts.footer.newsletter-text')
                    </p>

                    <p class="me-footer__newsletter-subtitle">
                        @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                    </p>
                </div>

                <x-shop::form
                    :action="route('shop.subscription.store')"
                    class="me-footer__form"
                >
                    <div class="me-footer__form-row">
                        <div class="me-footer__field">
                            <x-shop::form.control-group.control
                                type="email"
                                class="me-footer__input"
                                name="email"
                                rules="required|email"
                                label="Email"
                                :aria-label="trans('shop::app.components.layouts.footer.email')"
                                placeholder="email@example.com"
                            />

                            <x-shop::form.control-group.error control-name="email" />
                        </div>

                        <button
                            type="submit"
                            class="me-footer__button"
                        >
                            @lang('shop::app.components.layouts.footer.subscribe')
                        </button>
                    </div>
                </x-shop::form>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}

        <div class="me-footer__grid">
            <!-- Brand and contact details -->
            <div class="me-footer__brand">
                <a
                    href="{{ route('shop.home.index') }}"
                    class="me-footer__logo"
                    aria-label="{{ $storeName }}"
                >
                    <img
                        src="{{ $channel->logo_url ?? bagisto_asset('images/logo.svg') }}"
                        alt="{{ $storeName }}"
                        width="140"
                        height="40"
                        loading="lazy"
                    >
                </a>

                <p class="me-footer__about">
                    Office, school, cleaning and pantry supplies for offices, schools and institutions, delivered to your door.
                </p>

                <ul class="me-footer__contact">
                    @if ($storeAddress)
                        <li>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg>

                            <span>{{ $storeAddress }}</span>
                        </li>
                    @endif

                    @if ($storePhone)
                        <li>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg>

                            <a href="tel:{{ $storePhoneLink }}">{{ $storePhoneLabel }}</a>
                        </li>
                    @endif

                    @if ($storeEmail)
                        <li>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>

                            <a href="mailto:{{ $storeEmail }}">{{ $storeEmail }}</a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Shop categories -->
            @if ($footerCategories->isNotEmpty())
                <nav
                    class="me-footer__col me-footer__col--shop"
                    aria-label="Shop"
                    v-pre
                >
                    <h2 class="me-footer__heading">Shop</h2>

                    <ul class="me-footer__links">
                        @foreach ($footerCategories as $footerCategory)
                            <li>
                                <a href="{{ $footerCategory->url }}">{{ $footerCategory->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            <!-- Footer links from Admin > Settings > Themes > Footer Links -->
            @if ($customization?->options)
                @foreach (array_values($customization->options) as $index => $footerLinkSection)
                    @php
                        usort($footerLinkSection, function ($a, $b) {
                            return $a['sort_order'] - $b['sort_order'];
                        });
                    @endphp

                    <nav
                        class="me-footer__col"
                        aria-label="{{ $footerColumnTitles[$index] ?? 'Links' }}"
                        v-pre
                    >
                        <h2 class="me-footer__heading">{{ $footerColumnTitles[$index] ?? 'Links' }}</h2>

                        <ul class="me-footer__links">
                            @foreach ($footerLinkSection as $link)
                                <li>
                                    <a href="{{ $link['url'] }}">{{ $link['title'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @endforeach
            @endif
        </div>
    </div>

    <div class="me-footer__bottom">
        <div class="me-footer__inner me-footer__bottom-inner">
            {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}

            <p>
                @lang('shop::app.components.layouts.footer.footer-text', ['current_year'=> date('Y') ])
            </p>

            @if ($storeGstin)
                <p>GSTIN: {{ $storeGstin }}</p>
            @endif

            {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
        </div>
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
