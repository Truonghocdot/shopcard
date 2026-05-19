@extends('layouts.app')

@section('title', 'Policies & Regulations - Rabby TCG - Graded Cards & Premium Collectibles')
@section('description', 'Read Rabby TCG policies, return guidelines, secure payment options, lifetime authenticity guarantees, and worldwide shipping terms.')

@push('meta')
<meta name="keywords" content="Rabby TCG, graded cards, PSA cards, trading card game, Pokemon cards, YuGiOh, One Piece card game, secure TCG shop">
<meta property="og:title" content="Policies & Regulations - Rabby TCG">
<meta property="og:description" content="Read Rabby TCG policies, return guidelines, secure payment options, and lifetime authenticity guarantees.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/policy') }}">
<meta name="twitter:card" content="summary">
<link rel="canonical" href="{{ url('/policy') }}">
@endpush

@push('schema')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "@id": "{{ url('/') }}#organization",
        "name": "Rabby TCG",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "description": "Rabby TCG is the premier store for authentic graded trading card games, Pokemon cards, and premium collectibles.",
        "sameAs": [
            "https://www.facebook.com"
        ]
    }
</script>

<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [{
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ url('/') }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "Policies & Regulations",
                "item": "{{ url('/policy') }}"
            }
        ]
    }
</script>
@endpush

@section('content')
<div class="policy-page relative overflow-hidden min-h-screen pt-12 pb-20">
    <div class="container mx-auto px-4 relative z-10 max-w-6xl">
        <!-- Hero Section -->
        <div class="text-center mb-16 relative scroll-reveal">
            <div class="inline-block relative mb-6">
                <div class="relative bg-linear-to-r from-primary to-indigo-600 text-white px-12 py-5 font-black text-3xl md:text-6xl rounded-3xl shadow-[0_0_30px_rgba(74,222,128,0.3)] transform hover:scale-105 transition-all">
                    POLICIES & REGULATIONS
                </div>
            </div>
            <p class="text-white font-black tracking-[0.3em] uppercase text-xs md:text-sm mt-4 opacity-80">
                Rabby TCG Premium Security & Authenticity Protocols
            </p>
        </div>

        <!-- Main Content -->
        <div class="space-y-12">
            <!-- 1. Authenticity & Grading Policy -->
            <section class="policy-section scroll-reveal">
                <h2 class="section-title text-white">
                    <span class="material-icons text-primary drop-shadow-[0_0_8px_rgba(74,222,128,0.5)]">verified</span>
                    1. Authenticity & Graded Cards Guarantee
                </h2>

                <div class="content-box glass space-y-6 border-white/10">
                    <div>
                        <h3 class="subsection-title text-white">1.1 Absolute Authenticity</h3>
                        <p class="text-neutral-400 leading-relaxed">
                            At <strong class="text-primary font-black uppercase">Rabby TCG</strong>, we hold authenticity to the highest industry standard. 
                            All listed trading cards (Pokemon, Yu-Gi-Oh!, Weiss Schwarz, One Piece) are guaranteed <span class="text-primary font-black underline decoration-primary/30 underline-offset-4">100% authentic</span>. We do not deal in proxies, counterfeits, or custom cards under any circumstances.
                        </p>
                    </div>

                    <div>
                        <h3 class="subsection-title text-white">1.2 Third-Party Grading (PSA, BGS, CGC)</h3>
                        <p class="text-neutral-400 leading-relaxed">
                            Graded cards offered on our platform are authenticated and encapsulated by world-renowned grading companies such as PSA (Professional Sports Authenticator), BGS (Beckett Grading Services), and CGC. Every certificate number is verified against the official database.
                        </p>
                    </div>

                    <div>
                        <h3 class="subsection-title text-white">1.3 Raw Card Condition</h3>
                        <ul class="list-disc list-inside space-y-3 text-neutral-400 leading-relaxed">
                            <li>All raw cards are carefully inspected under specialized lighting.</li>
                            <li>Condition descriptors (Near Mint, Lightly Played, etc.) strictly adhere to TCGplayer guidelines.</li>
                            <li>High-resolution photos and videos of raw cards are available upon request.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 2. Privacy Policy -->
            <section class="policy-section">
                <h2 class="section-title">
                    <span class="material-icons text-primary drop-shadow-[0_0_8px_rgba(74,222,128,0.5)]">shield</span>
                    2. Privacy & Information Security Policy
                </h2>

                <div class="content-box space-y-6">
                    <div>
                        <h3 class="subsection-title text-white">2.1 Information Collection</h3>
                        <p class="text-neutral-400 leading-relaxed">
                            <strong class="text-primary">Rabby TCG</strong> committed to protecting your personal information. We only collect the minimal required data to secure transactions, such as account name, email, and billing details.
                        </p>
                    </div>

                    <div>
                        <h3 class="subsection-title text-white">2.2 Secure Storage & Encryption</h3>
                        <p class="text-neutral-400 leading-relaxed">
                            All user credentials, wallet balances, and purchase logs are encrypted and securely stored using state-of-the-art database encryption. No credit card or bank credentials are saved on our servers.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 3. Returns & Exchange Policy -->
            <section class="policy-section">
                <h2 class="section-title text-white">
                    <span class="material-icons text-primary drop-shadow-[0_0_8px_rgba(74,222,128,0.5)]">assignment_return</span>
                    3. Returns & Exchange Policy
                </h2>

                <div class="content-box space-y-6 border-white/10">
                    <div>
                        <h3 class="subsection-title text-white">3.1 Eligible Returns</h3>
                        <p class="text-neutral-400 leading-relaxed">
                            Due to market volatility, standard card sales are final. However, we accept returns/exchanges in the following cases:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-neutral-400 leading-relaxed">
                            <li>The received card does not match the purchased description or PSA certificate number.</li>
                            <li>The card was damaged during transit due to improper packaging.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="subsection-title text-white">3.2 Reporting Period</h3>
                        <p class="text-neutral-400 leading-relaxed">
                            Any discrepancies must be reported within <span class="text-primary font-black">24 hours</span> of delivery confirmation. High-quality unboxing video is required to process return claims.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 4. Quick Policy Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-16">
                <!-- Authentic Cards -->
                <div class="policy-card group">
                    <div class="flex gap-6">
                        <div class="policy-icon-box">
                            <span class="material-icons text-3xl">verified_user</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white mb-3 flex items-center gap-2 uppercase tracking-widest leading-tight">
                                100% AUTHENTIC CARDS
                            </h3>
                            <p class="text-neutral-500 leading-relaxed text-xs font-bold">
                                Every single card in our inventory goes through rigorous multi-stage authentications to ensure lifelong guarantee.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Premium Packaging -->
                <div class="policy-card group">
                    <div class="flex gap-6">
                        <div class="policy-icon-box">
                            <span class="material-icons text-3xl">local_shipping</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white mb-3 flex items-center gap-2 uppercase tracking-widest leading-tight">
                                PREMIUM PACKAGING
                            </h3>
                            <p class="text-neutral-500 leading-relaxed text-xs font-bold">
                                We ship raw cards in premium penny sleeves and top-loaders, inside water-resistant bubble mailers to ensure pristine arrival.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Instant Delivery -->
                <div class="policy-card group">
                    <div class="flex gap-6">
                        <div class="policy-icon-box">
                            <span class="material-icons text-3xl">bolt</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white mb-3 flex items-center gap-2 uppercase tracking-widest leading-tight">
                                INSTANT DISPATCH
                            </h3>
                            <p class="text-neutral-500 leading-relaxed text-xs font-bold">
                                Digital codes and order confirmations are dispatched immediately. Graded slabs are securely packaged and shipped within 24 hours.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Dedicated Support -->
                <div class="policy-card group">
                    <div class="flex gap-6">
                        <div class="policy-icon-box">
                            <span class="material-icons text-3xl">support_agent</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white mb-3 flex items-center gap-2 uppercase tracking-widest leading-tight">
                                24/7 DEDICATED SUPPORT
                            </h3>
                            <p class="text-neutral-500 leading-relaxed text-xs font-bold">
                                Our collectors-turned-support team are available 24/7 to help you resolve order inquiries or product conditions.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Section -->
            <div class="glass rounded-3xl border border-white/10 shadow-3xl p-8 relative overflow-hidden mt-16">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <span class="material-icons text-8xl text-primary">hub</span>
                </div>
                <div class="relative z-10">
                    <h2 class="text-2xl font-black text-white mb-6 uppercase tracking-widest">
                        Connect with <span class="text-primary">Rabby TCG</span>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="https://zalo.me/g/wilgna867" target="_blank" class="social-btn zalo">
                            <div class="btn-inner">
                                <span class="font-bold text-sm">ZALO GROUP</span>
                                <span class="text-[10px] opacity-70">Exclusive Coupons & Events</span>
                            </div>
                        </a>
                        <a href="https://discord.gg" target="_blank" class="social-btn discord">
                            <div class="btn-inner">
                                <span class="font-bold text-sm">DISCORD SERVER</span>
                                <span class="text-[10px] opacity-70">Join our TCG community</span>
                            </div>
                        </a>
                        <a href="https://facebook.com" target="_blank" class="social-btn facebook">
                            <div class="btn-inner">
                                <span class="font-bold text-sm">FACEBOOK PAGE</span>
                                <span class="text-[10px] opacity-70">Contact our Admins</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Closing Note -->
            <div class="text-center max-w-4xl mx-auto bg-neutral-950/50 border border-white/10 backdrop-blur-3xl p-12 rounded-[2.5rem] relative overflow-hidden mt-20 group/thanks shadow-3xl">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-linear-to-r from-transparent via-primary to-transparent"></div>

                <div class="relative z-10">
                    <span class="material-icons text-primary text-6xl mb-6 animate-bounce">favorite</span>
                    <h2 class="text-3xl font-black text-white mb-6 uppercase tracking-widest">THANK YOU FOR YOUR TRUST</h2>
                    <div class="max-w-2xl mx-auto">
                        <p class="text-neutral-400 mb-8 italic text-lg leading-relaxed">
                            "Rabby TCG was built by collectors, for collectors. We appreciate your continued support in helping us build the most secure, authentic, and high-end TCG destination."
                        </p>
                    </div>

                    <div class="inline-flex items-center gap-6 text-xs font-black tracking-[0.4em] text-primary uppercase">
                        <span class="w-16 h-0.5 bg-linear-to-r from-transparent to-primary/30"></span>
                        Rabby TCG Protocol
                        <span class="w-16 h-0.5 bg-linear-to-l from-transparent to-primary/30"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .policy-section {
        margin-bottom: 4rem;
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 950;
        color: #fff;
        margin-bottom: 2rem;
        text-transform: uppercase;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .section-title .material-icons {
        font-size: 2.25rem;
    }

    .subsection-title {
        font-size: 1.25rem;
        font-weight: 900;
        color: #fff;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: -0.02em;
    }

    .content-box {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-left: 4px solid #4ade80;
        padding: 2.5rem;
        border-radius: 1.5rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    .policy-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 2rem;
        padding: 40px 30px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .policy-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 60px rgba(74, 222, 128, 0.15);
        border-color: rgba(74, 222, 128, 0.4);
    }

    .policy-icon-box {
        width: 64px;
        height: 64px;
        min-width: 64px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4ade80;
        transition: 0.4s;
    }

    .policy-card:hover .policy-icon-box {
        background: #4ade80;
        color: #fff;
        border-color: #4ade80;
        box-shadow: 0 0 20px rgba(74, 222, 128, 0.4);
    }

    /* Social Buttons */
    .social-btn {
        display: block;
        border-radius: 0.75rem;
        overflow: hidden;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-inner {
        padding: 15px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: 0.3s;
    }

    .social-btn.zalo {
        background: #0068ff;
        color: #fff;
    }

    .social-btn.discord {
        background: #5865f2;
        color: #fff;
    }

    .social-btn.facebook {
        background: #1877f2;
        color: #fff;
    }

    .social-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
        .policy-card {
            padding: 30px 20px;
        }

        .policy-icon-box {
            width: 50px;
            height: 50px;
            min-width: 50px;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .content-box {
            padding: 1.5rem;
        }
    }
</style>
@endsection