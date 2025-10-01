<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SiteSettings
{
    private static $settings = null;

    /**
     * Get all site settings with caching
     */
    public static function getAll()
    {
        if (self::$settings === null) {
            self::$settings = Cache::remember('site_settings', 3600, function () {
                return DB::table('site_settings')->pluck('setting_value', 'setting_key')->toArray();
            });
        }
        
        return self::$settings;
    }

    /**
     * Get a specific setting value
     */
    public static function get($key, $default = null)
    {
        $settings = self::getAll();
        return $settings[$key] ?? $default;
    }

    /**
     * Get site name
     */
    public static function getSiteName()
    {
        return self::get('site_name', 'PLN GPS Center');
    }

    /**
     * Get site description
     */
    public static function getSiteDescription()
    {
        return self::get('site_description', 'PLN GPS Center Management System');
    }

    /**
     * Get footer text
     */
    public static function getFooterText()
    {
        return self::get('footer_text', '© ' . date('Y') . ' PLN GPS Center. Design & Develop by Pixeleyez');
    }

    /**
     * Get logo URL
     */
    public static function getLogoUrl()
    {
        $logoPath = self::get('logo_path');
        return $logoPath ? asset($logoPath) : asset('assets/images/logo-dark.png');
    }

    /**
     * Get logo icon URL (for collapsed sidebar)
     */
    public static function getLogoIconUrl()
    {
        $logoIconPath = self::get('logo_icon_path');
        return $logoIconPath ? asset($logoIconPath) : asset('assets/images/logo-dark.png');
    }

    /**
     * Get favicon URL
     */
    public static function getFaviconUrl()
    {
        $faviconPath = self::get('favicon_path');
        // Default to rounded SVG that masks corners like ThemeForest style
        return $faviconPath ? asset($faviconPath) : asset('assets/images/favicon-rounded.svg');
    }

    /**
     * Get logo dimensions (now flexible with CSS max-width/height)
     */
    public static function getLogoDimensions()
    {
        return [
            'max_width' => '150px',
            'max_height' => '150px',
            'width' => 'auto',
            'height' => 'auto'
        ];
    }

    /**
     * Get default theme
     */
    public static function getDefaultTheme()
    {
        return self::get('default_theme', 'light');
    }

    /**
     * Get timezone
     */
    public static function getTimezone()
    {
        return self::get('timezone', 'Asia/Jakarta');
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        Cache::forget('site_settings');
        self::$settings = null;
    }
}
