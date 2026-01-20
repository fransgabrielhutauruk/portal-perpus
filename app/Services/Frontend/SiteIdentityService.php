<?php

namespace App\Services\Frontend;

/**
 * Site Identity Service
 * 
 * Service untuk mengelola identitas situs, kontak, dan informasi institusi
 * 
 * @author wahyudibinsaid
 */
class SiteIdentityService
{
    /**
     * Get site identity information
     * 
     * @return object
     */
    public static function getSiteIdentity(): object
    {
        return (object) [
            'name'             => 'Politeknik Caltex Riau',
            'short_name'       => 'PCR',
            'tagline'          => 'Membangun Literasi <br><span>Mencerdaskan Generasi</span>',
            'description'      => 'Politeknik Caltex Riau adalah institusi pendidikan vokasi terkemuka yang menghasilkan lulusan berkualitas dan siap bersaing di tingkat global.',
            'established_year' => 2001, // Tahun berdiri
            'current_year'     => date('Y'),
            'logo_path'        => asset('theme/frontend/images/logo-portal.webp'),
            'hero_settings'    => [
                'show_default_slide' => true, // Control default slide visibility
            ]
        ];
    }

    /**
     * Get contact information
     * 
     * @return object
     */
    public static function getContactInfo(): object
    {
        return (object) [
            'address' => [
                'full'          => 'Jl. Umban Sari No.1, Umban Sari, Kec. Rumbai, Kota Pekanbaru, Riau 28265',
                'street'        => 'Jl. Umban Sari No.1',
                'district'      => 'Umban Sari',
                'subdistrict'   => 'Kec. Rumbai',
                'city'          => 'Kota Pekanbaru',
                'province'      => 'Riau',
                'postal_code'   => '28265',
                'maps_url'      => 'https://maps.app.goo.gl/ogvGHr1UXYKRGsjN7',
                // full iframe embed url for use in front-end map iframe
                'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1488.38854983942!2d101.42593720683222!3d0.570365708891641!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5ab67086f2e89%3A0x65a24264fec306bb!2sPoliteknik%20Caltex%20Riau!5e0!3m2!1sen!2sid!4v1748221520927!5m2!1sen!2sid'
            ],
            'phone'   => [
                'main'              => '(0761) - 53939',
                'mobile'            => '0811 758 0101',
                'academic_phone'    => '0761 53939',
                'cooperation_phone' => '0811 757 4101'
            ],
            'email'   => 'pustaka@pcr.ac.id',
        ];
    }

    /**
     * Get social media links
     * 
     * @return array
     */
    public static function getSocialMedia(): array
    {
        return [
            [
                'platform' => 'instagram',
                'name'     => 'Instagram',
                'url'      => 'https://www.instagram.com/perpustakaan.pcr/',
                'icon'     => 'fa-brands fa-instagram',
                'username' => '@politeknikcaltexriau'
            ],
            [
                'platform' => 'facebook',
                'name'     => 'Facebook',
                'url'      => 'https://www.facebook.com/profile.php?id=61582700355202',
                'icon'     => 'fa-brands fa-facebook-f',
                'username' => 'Politeknik.Caltex.Riau'
            ]
        ];
    }

    /**
     * Get service menu links
     * 
     * @return array
     */
    public static function getServiceMenu(): array
    {
        return [
            [
                'title'    => 'OPAC',
                'url'      => 'https://opac.lib.pcr.ac.id',
                'external' => true
            ],
            [
                'title'    => 'Repository',
                'url'      => 'https://repository.lib.pcr.ac.id',
                'external' => true
            ],
            [
                'title'    => 'ISBN',
                'url'      => 'https://isbn.lib.pcr.ac.id',
                'external' => true
            ]
        ];
    }

    /**
     * Get form menu links
     * 
     * @return array
     */
    public static function getFormMenu(): array
    {
        return [
            [
                'title'    => 'Usulan Koleksi Buku',
                'url'      => '/layanan/req-buku',
                'external' => false
            ],
            [
                'title'    => 'Kebutuhan Modul Semester',
                'url'      => '/layanan/req-modul',
                'external' => false
            ],
            [
                'title'    => 'Cek Turnitin',
                'url'      => '/layanan/req-turnitin',
                'external' => false
            ],
            [
                'title'    => 'Surat Bebas Pustaka',
                'url'      => '/layanan/req-bebas-pustaka',
                'external' => false
            ]
        ];
    }

    /**
     * Get copyright information
     * 
     * @return object
     */
    public static function getCopyright(): object
    {
        $identity = self::getSiteIdentity();

        return (object) [
            'year'      => $identity->current_year,
            'text'      => "{$identity->current_year} &copy; {$identity->name}. Dikembangkan oleh BSTI PCR",
            'developer' => 'BSTI PCR',
            'full_text' => "{$identity->current_year} &copy; {$identity->name}. Dikembangkan oleh BSTI PCR"
        ];
    }

    /**
     * Get complete site identity data for footer
     * 
     * @return object
     */
    public static function getFooterData(): object
    {
        return (object) [
            'identity'     => self::getSiteIdentity(),
            'contact'      => self::getContactInfo(),
            'social_media' => self::getSocialMedia(),
            'menus'        => (object) [
                'services' => self::getServiceMenu(),
                'forms'    => self::getFormMenu()
            ],
            'copyright'    => self::getCopyright()
        ];
    }



    /**
     * Get site meta information for SEO
     * 
     * @return object
     */
    public static function getSiteMeta(): object
    {
        $identity = self::getSiteIdentity();
        $contact  = self::getContactInfo();

        return (object) [
            'title'          => $identity->name,
            'description'    => $identity->description,
            'keywords'       => 'politeknik, caltex, riau, pendidikan, vokasi, teknologi',
            'author'         => $identity->name,
            'og_title'       => $identity->name,
            'og_description' => $identity->description,
            'og_image'       => data_get($identity, 'logo_path'),
            'twitter_card'   => 'summary_large_image',
            'canonical_url'  => url('/'),
            'contact_email'  => $contact->email['main'],
            'contact_phone'  => $contact->phone['formatted_main']
        ];
    }
}
