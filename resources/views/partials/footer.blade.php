
<!-- Begin Footer -->
<footer class="footer">
    <div class="container-fluid w-100">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-3">
                @php
                    $logoIconPath = DB::table('site_settings')->where('setting_key', 'logo_icon_path')->value('setting_value');
                    $logoIconUrl = $logoIconPath ? asset($logoIconPath) : (($logoPath = DB::table('site_settings')->where('setting_key', 'logo_path')->value('setting_value')) ? asset($logoPath) : asset('assets/images/logo-dark.png'));
                @endphp
                <img src="{{ $logoIconUrl }}" alt="Logo Icon" style="max-width: 32px; max-height: 32px; width: auto; height: auto;" class="footer-logo-icon">
                @php
                    $footerText = class_exists('\App\Helpers\SiteSettings') ? \App\Helpers\SiteSettings::getFooterText() : (DB::table('site_settings')->where('setting_key', 'footer_text')->value('setting_value') ?? '© ' . date('Y') . ' PLN GPS Center. Design & Develop by Pixeleyez');
                @endphp
                <span>{!! $footerText !!}</span>
            </div>
        </div>
    </div>
</footer>
<!-- END Footer -->
 