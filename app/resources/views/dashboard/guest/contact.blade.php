@extends('layouts.guest_master')



@section('content')
<main class="bg-background text-on-background font-body-md overflow-x-hidden">

    <!-- Breadcrumb Header -->
    <section class="relative h-[300px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-primary academic-overlay leaf-pattern"></div>
        <div class="relative z-10 text-center text-on-primary px-4">
            <h1 class="font-display-lg text-display-lg mb-4">Contact Us</h1>
            <nav class="flex justify-center items-center gap-2 font-label-sm text-label-sm uppercase tracking-wider">
                <a href="{{ route('welcome.guest') }}" class="hover:text-secondary-fixed transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-tertiary-fixed">Contact</span>
            </nav>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="relative">
        <!-- Full Width Map -->
        <div class="w-full h-[600px] bg-surface-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.366440268481!2d99.14652231534431!3d2.383215998263155!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x302e00fdad2d7341%3A0xf59ef99973d1d43e!2sInstitut%20Teknologi%20Del!5e0!3m2!1sen!2sid!4v1698765432100!5m2!1sen!2sid"
                class="w-full h-full border-0"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <!-- Get Connected Floating Form -->
        <div class="max-w-container-max mx-auto px-4 md:px-section-padding-desktop -mt-32 md:-mt-48 pb-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">

                <!-- Contact Form -->
                <div class="lg:col-span-8 bg-white p-8 md:p-12 rounded-xl shadow-md border border-outline-variant">
                    <div class="mb-8">
                        <h2 class="font-headline-lg text-headline-lg text-primary mb-2">Get Connected</h2>
                        <p class="text-on-surface-variant font-body-md">Ada pertanyaan seputar kolaborasi penelitian atau pengabdian masyarakat? Hubungi kami.</p>
                    </div>

                    <form action="javascript:void(0)" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <div id="message" class="{{ session('success') ? 'block' : 'hidden' }} md:col-span-2 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 font-body-md">
                            {{ session('success') }}
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-label-md text-on-surface">Full Name</label>
                            <input type="text" name="name" placeholder="John Doe" required
                                   class="w-full bg-white border border-outline-variant rounded-lg p-3 focus:border-secondary focus:ring-1 focus:ring-secondary transition-all outline-none"/>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-label-md text-on-surface">Email Address</label>
                            <input type="email" name="email" placeholder="john@del.ac.id" required
                                   class="w-full bg-white border border-outline-variant rounded-lg p-3 focus:border-secondary focus:ring-1 focus:ring-secondary transition-all outline-none"/>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-label-md text-on-surface">Preferred Service</label>
                            <select name="service"
                                    class="w-full bg-white border border-outline-variant rounded-lg p-3 focus:border-secondary focus:ring-1 focus:ring-secondary transition-all outline-none">
                                <option value="">Pilih Layanan</option>
                                <option value="research">Research Collaboration</option>
                                <option value="community">Community Service</option>
                                <option value="journal">Journal &amp; Publications</option>
                                <option value="innovation">Innovation &amp; Incubation</option>
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-label-md text-on-surface">Phone Number</label>
                            <input type="tel" name="phone" placeholder="+62 ..."
                                   class="w-full bg-white border border-outline-variant rounded-lg p-3 focus:border-secondary focus:ring-1 focus:ring-secondary transition-all outline-none"/>
                        </div>

                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="font-label-md text-label-md text-on-surface">Message</label>
                            <textarea name="comment" rows="5" placeholder="How can we help you?"
                                      class="w-full bg-white border border-outline-variant rounded-lg p-3 focus:border-secondary focus:ring-1 focus:ring-secondary transition-all outline-none"></textarea>
                            <span class="font-label-sm text-outline">*Data Anda aman dalam sistem kami.</span>
                        </div>

                        <div class="md:col-span-2">
                            <button type="submit"
                                    class="bg-primary text-on-primary px-8 py-4 rounded-lg font-label-md text-lg hover:bg-secondary transition-all flex items-center justify-center gap-2 shadow-sm group">
                                Send Message
                                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">send</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Sidebar: Contact Info + Hours -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-primary-container text-on-primary p-8 rounded-xl shadow-md border border-outline">
                        <h3 class="font-headline-md text-headline-md mb-6">Contact Information</h3>
                        <ul class="space-y-6">
                            <li class="flex items-start gap-4">
                                <span class="material-symbols-outlined text-secondary-fixed mt-1">location_on</span>
                                <div>
                                    <p class="font-label-md text-on-primary-container">Campus Address</p>
                                    <p class="font-body-md opacity-90">Jl. Sisingamangaraja, Sitoluama, Laguboti, Toba, Sumatera Utara, 22381</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <span class="material-symbols-outlined text-secondary-fixed mt-1">mail</span>
                                <div>
                                    <p class="font-label-md text-on-primary-container">Email Us</p>
                                    <p class="font-body-md opacity-90">lppm@del.ac.id</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <span class="material-symbols-outlined text-secondary-fixed mt-1">call</span>
                                <div>
                                    <p class="font-label-md text-on-primary-container">Call Center</p>
                                    <p class="font-body-md opacity-90">+62 632 331234</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-surface-container p-8 rounded-xl border border-outline-variant">
                        <h3 class="font-headline-md text-headline-md text-primary mb-4">Working Hours</h3>
                        <div class="space-y-2 text-on-surface-variant font-body-md">
                            <div class="flex justify-between"><span>Sen - Jum</span><span>08:00 - 17:00</span></div>
                            <div class="flex justify-between"><span>Sabtu</span><span>08:00 - 12:00</span></div>
                            <div class="flex justify-between text-error font-medium"><span>Minggu</span><span>Tutup</span></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="bg-surface-container py-20 relative overflow-hidden">
        <div class="max-w-container-max mx-auto px-4 md:px-section-padding-desktop relative z-10">
            <div class="bg-white p-10 md:p-16 rounded-2xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-8 border border-outline-variant">
                <div class="md:w-1/2">
                    <span class="bg-tertiary text-on-tertiary px-3 py-1 rounded text-label-sm font-label-sm uppercase mb-4 inline-block">Stay Updated</span>
                    <h2 class="font-headline-lg text-headline-lg text-primary mb-4">Subscribe Our Newsletter</h2>
                    <p class="text-on-surface-variant font-body-md">Dapatkan berita riset terbaru, notifikasi publikasi, dan update pengabdian masyarakat langsung di inbox Anda.</p>
                </div>
                <div class="md:w-1/2 w-full">
                    <form action="#" class="flex flex-col sm:flex-row gap-0 shadow-sm rounded-lg overflow-hidden border border-outline">
                        <input type="email" placeholder="Your academic email..."
                               class="flex-grow p-4 bg-white border-0 focus:ring-0 outline-none text-on-surface font-body-md"/>
                        <button type="submit"
                                class="bg-primary text-on-primary px-8 py-4 font-label-md whitespace-nowrap hover:bg-secondary transition-colors">
                            Subscribe Now
                        </button>
                    </form>
                    <p class="text-label-sm text-on-surface-variant mt-4 px-1">Dengan berlangganan, Anda menyetujui kebijakan privasi data akademik kami.</p>
                </div>
            </div>
        </div>
    </section>

</main>

@push('scripts')
<script>
    // Micro-interactions for form focus
    document.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.style.transform = 'scale(1.01)';
            input.parentElement.style.transition = 'transform 0.2s ease-out';
        });
        input.addEventListener('blur', () => {
            input.parentElement.style.transform = 'scale(1)';
        });
    });
</script>
@endpush
@endsection



