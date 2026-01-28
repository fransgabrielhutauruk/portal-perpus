<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Log;

/**
 * Safe Data Service
 *
 * Service untuk handling data dengan aman dan fallback values
 *
 * @author wahyudibinsaid
 */
class SafeDataService
{
    /**
     * Safely get nested object/array value with fallback
     *
     * @param mixed $data
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($data, string $key, $default = null)
    {
        return data_get($data, $key, $default);
    }

    /**
     * Safely get object property with fallback
     *
     * @param object|null $object
     * @param string $property
     * @param mixed $default
     * @return mixed
     */
    public static function getProperty($object, string $property, $default = null)
    {
        if (!is_object($object)) {
            return $default;
        }

        return property_exists($object, $property) ? $object->{$property} : $default;
    }

    /**
     * Safely get array value with fallback
     *
     * @param array|null $array
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public static function getArrayValue($array, $key, $default = null)
    {
        if (!is_array($array)) {
            return $default;
        }

        return array_key_exists($key, $array) ? $array[$key] : $default;
    }

    /**
     * Ensure data is object, return empty object if not
     *
     * @param mixed $data
     * @return object
     */
    public static function ensureObject($data): object
    {
        return is_object($data) ? $data : new \stdClass();
    }

    /**
     * Ensure data is array, return empty array if not
     *
     * @param mixed $data
     * @return array
     */
    public static function ensureArray($data): array
    {
        return is_array($data) ? $data : [];
    }

    /**
     * Safely execute service method with fallback
     *
     * Supports:
     * - callable
     * - 'Class::method' string
     * - [ClassOrObject, 'method'] array
     *
     * @param callable|string|array $callback
     * @param mixed $fallback
     * @return mixed
     */
    public static function safeExecute($callback, $fallback = null)
    {
        try {
            // normalize supported callback types
            if (is_callable($callback)) {
                $result = call_user_func($callback);
            } elseif (is_string($callback) && strpos($callback, '::') !== false) {
                [$class, $method] = explode('::', $callback, 2);
                if (class_exists($class) && method_exists($class, $method)) {
                    $result = call_user_func([$class, $method]);
                } else {
                    return $fallback;
                }
            } elseif (is_array($callback) && count($callback) === 2) {
                [$classOrObject, $method] = $callback;
                if ((is_string($classOrObject) && class_exists($classOrObject) && method_exists($classOrObject, $method)) || (is_object($classOrObject) && method_exists($classOrObject, $method))) {
                    $result = call_user_func($callback);
                } else {
                    return $fallback;
                }
            } else {
                // unsupported callback type
                return $fallback;
            }

            return $result !== null ? $result : $fallback;
        } catch (\Throwable $e) {
            Log::warning('SafeDataService: Service method failed', [
                'error'    => $e->getMessage(),
                'callback' => is_string($callback) ? $callback : (is_array($callback) ? json_encode($callback) : (is_object($callback) ? get_class($callback) : gettype($callback))),
                'trace'    => $e->getTraceAsString()
            ]);

            return $fallback;
        }
    }

    /**
     * Create safe data structure with fallbacks
     *
     * @param array $structure
     * @return object
     */
    public static function createSafeStructure(array $structure): object
    {
        $result = new \stdClass();

        foreach ($structure as $key => $value) {
            if (is_array($value)) {
                $result->{$key} = self::createSafeStructure($value);
            } else {
                $result->{$key} = $value;
            }
        }

        return $result;
    }

    /**
     * Get safe fallback data for history page
     *
     * @return object
     */
    public static function getMetaDataFallbacks(): object
    {
        return self::createSafeStructure([
            'title'       => '',
            'description' => '',
            'keywords'    => '',
        ]);
    }

    /**
     * Get safe fallback data for history page
     *
     * @return object
     */
    public static function getPageConfigFallbacks(): object
    {
        return self::createSafeStructure([
            'background_image' => '',
            'seo'              => [
                'title'                      => '',
                'description'                => '',
                'keywords'                   => '',
                'canonical'                  => '',
                'og_image'                   => '',
                'og_type'                    => '',
                'structured_data'            => '',
                'breadcrumb_structured_data' => ''
            ]
        ]);
    }

    /**
     * Get safe fallback data for landing page
     *
     * @return object
     */
    public static function getLandingFallbacks(): object
    {
        return self::createSafeStructure([
            'hero'             => [
                'content'         => [
                    'titles'   => [['Politeknik Caltex Riau']],
                    'subtitle' => 'Selamat Datang di Politeknik Caltex Riau'
                ],
                'media'           => ['type' => 'video'],
                'processed_media' => []
            ],
            'statistics'       => [],
            'jurusan_list'     => [],
            'pmb_data'         => [
                'content'    => [
                    'title'       => 'Informasi PMB',
                    'description' => 'Informasi penerimaan mahasiswa baru.'
                ],
                'highlights' => [],
                'actions'    => ['primary' => ['url' => '#', 'text' => 'Info Lebih Lanjut']]
            ],
            'infografis_image' => '', // Added fallback for infografis image
            'sdg'              => [
                'content' => ['title' => 'SDG'],
                'images'  => ['main' => ['src' => '', 'alt' => 'SDG']],
                'goals'   => []
            ],
            'partnership'      => [
                'content'    => ['title' => 'Partnership'],
                'statistics' => [],
                'partners'   => ['institutions' => [], 'instances' => [], 'industries' => []]
            ],
            'site_identity'    => [
                'identity'      => ['tagline' => 'Politeknik Caltex Riau'],
                'contact'       => [
                    'address'     => ['full' => '', 'maps_url' => '#'],
                    'phone'       => ['main' => '', 'mobile' => '', 'formatted_main' => '', 'formatted_mobile' => ''],
                    'description' => 'Politeknik Caltex Riau berlokasi strategis di kawasan pendidikan Kota Pekanbaru.'
                ],
                'social_media'  => [],
                'menus'         => ['services' => [], 'academic' => []],
                'copyright'     => ['full_text' => '© Politeknik Caltex Riau'],
                'hero_cta'      => [
                    ['text' => 'Profil PCR', 'url' => '#', 'class' => 'btn-default'],
                    ['text' => 'Daftar Sekarang', 'url' => '#', 'class' => 'btn-default']
                ],
                'hints_section' => [
                    'title'    => 'Petunjuk',
                    'subtitle' => 'Ingin <span>mudah menemukan</span> lokasi Politeknik Caltex Riau?',
                    'intro'    => 'Politeknik Caltex Riau memiliki lokasi yang strategis dan mudah diakses. Berikut adalah beberapa petunjuk untuk membantu Anda menemukan kampus kami dengan mudah.'
                ]
            ],
            /**
             * Added by DZB for tinta-kammpus section
             */
            'articles'         => [
                'content'      => ['title' => 'Artikel', 'subtitle' => '', 'description' => ''],
                'highlighted'  => [
                    [
                        'title'     => '',
                        'timestamp' => '',
                        'url'       => '',
                        'images'    => [
                            'main' => [
                                'src' => '',
                                'alt' => ''
                            ]
                        ]
                    ]
                ],
                'newest'       => [
                    'title'     => '',
                    'timestamp' => '',
                    'url'       => '',
                    'images'    => [
                        'main' => [
                            'src' => '',
                            'alt' => ''
                        ]
                    ]
                ],
                'achievements' => [
                    'title'     => '',
                    'timestamp' => '',
                    'url'       => '',
                    'images'    => [
                        'main' => [
                            'src' => '',
                            'alt' => ''
                        ]
                    ]
                ],
                'researches'   => [
                    'title'     => '',
                    'timestamp' => '',
                    'url'       => '',
                    'images'    => [
                        'main' => [
                            'src' => '',
                            'alt' => ''
                        ]
                    ]
                ]
            ],
        ]);
    }

    /**
     * Get safe fallback data for berita/news page
     *
     * @return object
     */
    public static function getBeritaFallbacks(): object
    {
        return self::createSafeStructure([
            'header'       => '',
            'title'        => '',
            'subtitle'     => '',

            'newest'  => [
                [
                    'title'     => '',
                    'excerpt'   => '',
                    'timestamp' => '',
                    'url'       => '',
                    'images'    => [
                        'main' => [
                            'src' => '',
                            'alt' => ''
                        ]
                    ],
                ]
            ],
        ]);
    }

    /**
     * Get safe fallback data for berita show/detail page
     *
     * @return object
     */
    public static function getBeritaShowFallbacks(): object
    {
        return self::createSafeStructure([
            'header'       => '',
            'title'        => '',
            'url'         => '',
            'content'      => [
                'body'            => '',
                'timestamp'        => '',
                'author'           => '',
            ],
        ]);
    }

    /**
     * Get safe fallback data for Usulan Buku page
     *
     * @return object
     */
    public static function getUsulanBukuFallbacks(): object
    {
        return self::createSafeStructure([
            'header'      => 'Usulan Buku',
            'title'       => 'Form Usulan Pengadaan Buku',
            'subtitle'    => 'Layanan Perpustakaan',
            'description' => 'Silahkan isi form berikut untuk mengusulkan pengadaan buku baru.',
            'prodi_list'  => [],
            'form'        => [
                'action_url' => route('frontend.req.buku.send'),
            ]
        ]);
    }

    public static function getUsulanModulFallbacks(): object
    {
        return self::createSafeStructure([
            'header'      => 'Usulan Modul',
            'title'       => 'Form Usulan Modul',
            'subtitle'    => 'Layanan Perpustakaan',
            'description' => 'Silahkan isi form berikut untuk mengusulkan modul baru.',
            'prodi_list'  => [],
            'form'        => [
                'action_url' => route('frontend.req.modul.send'),
            ]
        ]);
    }

    public static function getBebasPustakaFallbacks(): object
    {
        return self::createSafeStructure([
            'header'      => 'Bebas Pustaka',
            'title'       => 'Form Permohonan Bebas Pustaka',
            'subtitle'    => 'Layanan Perpustakaan',
            'description' => 'Silahkan isi form berikut untuk request kartu bebas pustaka.',
            'prodi_list'  => [],
            'form'        => [
                'action_url' => route('frontend.bebaspustaka.sendForm'),
            ]
        ]);
    }

    public static function getTurnitinFallbacks(): object
    {
        return self::createSafeStructure([
            'header'      => 'Cek Turnitin',
            'title'       => 'Form Pengajuan Cek Turnitin',
            'subtitle'    => 'Layanan Perpustakaan',
            'description' => 'Silahkan isi form berikut untuk mengajukan dokumen cek turnitin.',
            'prodi_list'  => [],
            'form'        => [
                'action_url' => route('frontend.req.turnitin.send'),
            ]
        ]);
    }
}
