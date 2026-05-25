<footer class="flex-shrink-0 bg-brand text-accent mt-5 relative">
    <div class="h-0.5 w-full bg-gradient-to-r from-transparent via-accent/30 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-4 py-4 md:py-6">
        <div class="flex flex-col md:grid md:grid-cols-4 gap-5 md:gap-8">
            <div class="md:col-span-1 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                    <img src="{{ asset(config('association.logo')) }}" alt="Logo" class="h-10 md:h-12" loading="lazy">
                    <h3 class="text-sm md:text-lg font-bold mb-0" style="color: #F2EB85;">{{ config('association.name_short') }}</h3>
                </div>
                <p class="text-xs md:text-sm text-accent/70 leading-relaxed hidden md:block">
                    {{ config('association.description') }}
                </p>
                <a href="{{ route('contato') }}"
                   class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold uppercase tracking-wider
                          bg-accent/10 text-accent no-underline hover:bg-accent hover:text-brand transition-all duration-300">
                    Fale Conosco
                    <x-icon name="arrow-right" class="w-3 h-3" />
                </a>
            </div>

            <div class="md:col-span-1">
                <h4 class="text-xs font-bold mb-3 md:mb-4 uppercase tracking-widest text-accent/60 text-center md:text-left">Navega&ccedil;&atilde;o</h4>
                <div class="flex flex-wrap justify-center md:flex-col gap-x-5 gap-y-2.5 md:gap-y-2.5 text-sm list-none">
                    <a href="{{ route('home') }}" class="text-accent/70 no-underline hover:text-accent transition-colors py-1 inline-block">Home</a>
                    <a href="{{ route('sobrenos') }}" class="text-accent/70 no-underline hover:text-accent transition-colors py-1 inline-block">Sobre</a>
                    <a href="{{ route('produtos') }}" class="text-accent/70 no-underline hover:text-accent transition-colors py-1 inline-block">Produtos</a>
                    <a href="{{ route('evento') }}" class="text-accent/70 no-underline hover:text-accent transition-colors py-1 inline-block">Eventos</a>
                    <a href="{{ route('contato') }}" class="text-accent/70 no-underline hover:text-accent transition-colors py-1 inline-block">Contato</a>
                </div>
            </div>

            <div class="md:col-span-1">
                <h4 class="text-xs font-bold mb-3 md:mb-4 uppercase tracking-widest text-accent/60 text-center md:text-left">Contato</h4>
                <ul class="space-y-2.5 md:space-y-3 text-sm text-accent/70 list-none p-0 m-0 text-center md:text-left">
                    <li class="flex items-center justify-center md:justify-start gap-2 md:gap-3">
                        <x-icon name="map-marker" class="w-3 h-3 text-accent/60 shrink-0" />
                        <a href="https://www.google.com/maps?q={{ config('association.latitude') }},{{ config('association.longitude') }}"
                           target="_blank" class="text-accent/70 no-underline hover:text-accent transition-colors inline-block align-middle">
                            {{ config('association.address') }}
                        </a>
                    </li>
                    <li class="flex items-center justify-center md:justify-start gap-2 md:gap-3">
                        <x-icon name="envelope" class="w-3 h-3 text-accent/60 shrink-0" />
                        <a href="mailto:{{ config('association.email') }}" class="text-accent/70 no-underline hover:text-accent transition-colors inline-block align-middle">
                            {{ config('association.email') }}
                        </a>
                    </li>
                    <li class="flex items-center justify-center md:justify-start gap-2 md:gap-3">
                        <x-icon name="whatsapp" class="w-3 h-3 text-accent/60 shrink-0" />
                        <a href="https://wa.me/{{ config('association.whatsapp') }}" target="_blank"
                           class="text-accent/70 no-underline hover:text-accent transition-colors">
                            {{ preg_replace('/(\d{2})(\d{1})(\d{4})(\d{4})/', '($1) $2 $3-$4', config('association.whatsapp')) }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="md:col-span-1 text-center md:text-left">
                <h4 class="text-xs font-bold mb-3 md:mb-4 uppercase tracking-widest text-accent/60">Redes</h4>
                <div class="flex gap-3 justify-center md:justify-start mb-3">
                    <a href="{{ config('association.facebook') }}" target="_blank"
                       class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-lg md:rounded-xl bg-accent/10 text-accent/80 no-underline
                              hover:bg-[#1877F2] hover:text-white transition-all duration-200"
                       aria-label="Facebook">
                        <x-icon name="facebook" class="w-4 h-4" />
                    </a>
                    <a href="{{ config('association.instagram') }}" target="_blank"
                       class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-lg md:rounded-xl bg-accent/10 text-accent/80 no-underline
                              hover:bg-gradient-to-br hover:from-[#f09433] hover:via-[#e6683c] hover:to-[#bc2a8d] hover:text-white transition-all duration-200"
                       aria-label="Instagram">
                        <x-icon name="instagram" class="w-4 h-4" />
                    </a>
                    <a href="https://wa.me/{{ config('association.whatsapp') }}" target="_blank"
                       class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-lg md:rounded-xl bg-accent/10 text-accent/80 no-underline
                              hover:bg-[#25D366] hover:text-white transition-all duration-200"
                       aria-label="WhatsApp">
                        <x-icon name="whatsapp" class="w-4 h-4" />
                    </a>
                </div>
                <div class="text-xs text-accent/50 leading-relaxed hidden md:block">
                    <p class="mb-0">Seg a Sex: 08-12 | 14-18</p>
                </div>
            </div>
        </div>

        <div class="my-3 md:my-6">
            <hr class="border-accent/10">
        </div>

        <div class="flex items-center justify-between gap-2 text-xs text-accent/50">
            <span>&copy; {{ date('Y') }} {{ config('association.name_short') }}</span>
            <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-accent/10 text-accent/60
                           hover:bg-accent hover:text-brand transition-all duration-300 cursor-pointer border-0"
                    aria-label="Voltar ao topo">
                <x-icon name="arrow-up" class="w-3 h-3" />
            </button>
        </div>
    </div>
</footer>
