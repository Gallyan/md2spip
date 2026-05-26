@php
    $locale = \App\Support\LocaleUrls::locale();
    $currentUrl = \App\Support\LocaleUrls::current();
    $faqs = (array) __('messages.seo.faqs');
    $howToSteps = (array) __('messages.seo.how_to_steps');
    $features = (array) __('messages.seo.features');
@endphp
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@graph": [
        {
            "@@type": "WebSite",
            "@@id": "{{ url('/') }}/#website",
            "name": "{{ config('app.name') }}",
            "url": "{{ url('/') }}",
            "description": "{{ __('messages.meta.description_short') }}",
            "inLanguage": ["fr", "en"],
            "publisher": { "@@id": "{{ url('/') }}/#person" }
        },
        {
            "@@type": "Person",
            "@@id": "{{ url('/') }}/#person",
            "name": "{{ config('legal.editor_name') }}",
            "url": "{{ config('legal.social.website', 'https://www.orsal.fr') }}",
            "jobTitle": "Software Engineer",
            "knowsAbout": ["Laravel development", "web development", "open source", "Markdown", "SPIP CMS"]@if(collect(config('legal.social'))->filter()->isNotEmpty())
            ,"sameAs": {!! json_encode(collect(config('legal.social'))->filter()->values()) !!}
            @endif
        },
        {
            "@@type": ["WebApplication", "SoftwareApplication"],
            "@@id": "{{ url('/') }}/#application",
            "name": "{{ config('app.name') }}",
            "alternateName": "markdown2spip",
            "description": "{{ __('messages.seo.app_description') }}",
            "url": "{{ $currentUrl }}",
            "applicationCategory": "UtilitiesApplication",
            "applicationSubCategory": "Text conversion tool",
            "operatingSystem": "Any",
            "browserRequirements": "Requires JavaScript",
            "inLanguage": "{{ $locale }}",
            "image": "{{ asset('og-image.png') }}",
            "screenshot": {
                "@@type": "ImageObject",
                "url": "{{ asset('og-image.png') }}",
                "width": 1320,
                "height": 755,
                "caption": "{{ __('messages.meta.image_alt') }}"
            },
            "keywords": "{{ __('messages.seo.app_keywords') }}",
            "dateCreated": "2026-01-01",
            "datePublished": "2026-01-15",
            "isAccessibleForFree": true,
            "license": "https://www.gnu.org/licenses/gpl-3.0",
            "isBasedOn": {
                "@@type": "SoftwareSourceCode",
                "codeRepository": "https://github.com/Gallyan/md2spip",
                "programmingLanguage": ["PHP", "JavaScript"],
                "license": "https://www.gnu.org/licenses/gpl-3.0"
            },
            "offers": {
                "@@type": "Offer",
                "price": "0",
                "priceCurrency": "EUR"
            },
            "featureList": {!! json_encode($features, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!},
            "potentialAction": {
                "@@type": "CreateAction",
                "target": {
                    "@@type": "EntryPoint",
                    "urlTemplate": "{{ url('/') }}",
                    "actionPlatform": [
                        "http://schema.org/DesktopWebPlatform",
                        "http://schema.org/MobileWebPlatform"
                    ]
                }
            },
            "author": { "@@id": "{{ url('/') }}/#person" },
            "creator": { "@@id": "{{ url('/') }}/#person" },
            "publisher": { "@@id": "{{ url('/') }}/#person" }
        },
        {
            "@@type": "FAQPage",
            "@@id": "{{ url('/') }}/#faq",
            "inLanguage": "{{ $locale }}",
            "mainEntity": [
                @foreach ($faqs as $faq)
                {
                    "@@type": "Question",
                    "name": {!! json_encode($faq['q'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!},
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": {!! json_encode($faq['a'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!}
                    }
                }@if(! $loop->last),@endif
                @endforeach
            ]
        },
        {
            "@@type": "HowTo",
            "@@id": "{{ url('/') }}/#howto",
            "inLanguage": "{{ $locale }}",
            "name": {!! json_encode(__('messages.seo.how_to_name'), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!},
            "description": {!! json_encode(__('messages.seo.how_to_description'), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!},
            "totalTime": "PT10S",
            "step": [
                @foreach ($howToSteps as $i => $step)
                {
                    "@@type": "HowToStep",
                    "position": {{ $i + 1 }},
                    "name": {!! json_encode($step['name'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!},
                    "text": {!! json_encode($step['text'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!}
                }@if(! $loop->last),@endif
                @endforeach
            ]
        }
    ]
}
</script>
