<?php

namespace App\Services\Frontend;

use App\Models\Berita;
use App\Models\Post\Post;
use App\Models\Post\PostKategori;

class NewsService
{
    public static function getMetaData()
    {
        return [
            'title' => 'Berita - Portal Perpus',
            'description' => 'Berita terkini dari Portal Perpus',
            'keywords' => 'berita, news, portal perpus'
        ];
    }

    public static function getNews($limit = 10)
    {
        return Berita::where('status_berita', 'published')
            ->orderBy('tanggal_berita', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($berita) {
                return [
                    'title' => $berita->judul_berita,
                    'slug' => $berita->slug_berita,
                    'content' => strip_tags($berita->isi_berita),
                    'excerpt' => substr(strip_tags($berita->isi_berita), 0, 200) . '...',
                    'date' => $berita->tanggal_berita,
                    'timestamp' => $berita->tanggal_berita ? \Carbon\Carbon::parse($berita->tanggal_berita)->diffForHumans() : '',
                    'url' => route('frontend.news.show', ['newsId' => $berita->slug_berita]),
                    'image' => publicMedia($berita->filename_berita, 'berita'),
                    'meta_description' => $berita->meta_desc_berita,
                    'meta_keywords' => $berita->meta_keyword_berita,
                ];
            });
    }


    public static function getPost($slug)
    {
        $berita = Berita::where('slug_berita', $slug)
            ->where('status_berita', 'published')
            ->first();

        if (!$berita) {
            return null;
        }

        return [
            'id' => $berita->berita_id,
            'title' => $berita->judul_berita,
            'slug' => $berita->slug_berita,
            'content' => $berita->isi_berita,
            'date' => $berita->tanggal_berita,
            'timestamp' => $berita->tanggal_berita ? \Carbon\Carbon::parse($berita->tanggal_berita)->diffForHumans() : '',
            'image' => publicMedia($berita->filename_berita, 'berita'),
            'meta_description' => $berita->meta_desc_berita,
            'meta_keywords' => $berita->meta_keyword_berita,
            'author' => $berita->author ? $berita->author->name : 'Admin',
        ];
    }

    public static function getAchievements($limit = 5)
    {
        return Post::where('status_post', 'published')
            ->whereHas('kategori', function ($query) {
                $query->where('kode_kategori', 'prestasi');
            })
            ->orderBy('tanggal_post', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($post) {
                return [
                    'title' => $post->judul_post,
                    'slug' => $post->slug_post,
                    'date' => $post->tanggal_post,
                    'image' => publicMedia($post->filename_post, 'artikel'),
                ];
            });
    }

    public static function getAnnouncements($limit = 5)
    {
        return Post::where('status_post', 'published')
            ->whereHas('kategori', function ($query) {
                $query->where('kode_kategori', 'pengumuman');
            })
            ->orderBy('tanggal_post', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($post) {
                return [
                    'title' => $post->judul_post,
                    'slug' => $post->slug_post,
                    'date' => $post->tanggal_post,
                ];
            });
    }

    public static function getResearch($limit = 5)
    {
        return Post::where('status_post', 'published')
            ->whereHas('kategori', function ($query) {
                $query->where('kode_kategori', 'penelitian');
            })
            ->orderBy('tanggal_post', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($post) {
                return [
                    'title' => $post->judul_post,
                    'slug' => $post->slug_post,
                    'date' => $post->tanggal_post,
                    'image' => publicMedia($post->filename_post, 'artikel'),
                ];
            });
    }

    public static function getBestResearch($limit = 3)
    {
        return self::getResearch($limit);
    }

    public static function getAgenda($limit = 5)
    {
        // Return empty collection for now since Agenda model is not available
        return collect([]);
    }

    public static function getCategories()
    {
        return PostKategori::all()->map(function ($kategori) {
            return [
                'id' => $kategori->postkategori_id,
                'name' => $kategori->nama_kategori,
                'slug' => $kategori->kode_kategori,
            ];
        });
    }
}
