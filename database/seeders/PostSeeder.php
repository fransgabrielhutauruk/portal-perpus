<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                // "post_id" => 1,
                "postkategori_id" => 1,
                "level" => "main-site",
                "level_id" => null,
                "judul_post" => "Pentingnya Bekal Komunikasi Berbahasa Inggris di Era Masyarakat Ekonomi Asia",
                "isi_post" => "<p style=\"text-align:justify\">Siang itu (06\/01), Politeknik Caltex Riau (PCR) kedatangan tamu spesial. Para mahasiswa PCR menyambut kehadiran pembicara khusus dari<\/p>\r\n\r\n<div style=\"page-break-after: always\"><span style=\"display:none\">&nbsp;<\/span><\/div>\r\n\r\n<p style=\"text-align:justify\">pensiunan PT Chevron Pacific Indonesia, Ricky Aman. Beliau adalah pembicara yang telah lama memberikan banyak kuliah umum di dalam maupun luar negeri. Kali ini, beliau membawakan tema &ldquo;Pentingnya Komunikasi Berbahasa Inggris di Era Masyarakat Ekonomi Asia&rdquo; di auditorium kampus PCR. Dihadiri ratusan mahasiswa PCR, kuliah umum diadakan mulai pukul 14.00 siang.<\/p>\r\n\r\n<p style=\"text-align:justify\">Menurut Ricky, sebagai seorang mahasiswa diperlukan kesadaran untuk mulai berkomunikasi menggunakan bahasa inggris di tiap kegiatan seperti kampus, lingkungan rumah dan juga media sosial. Menurutnya di era perdagangan bebas yang notabene adalah bentuk integrasi negara-negara di kawasan Asia Tenggara dalam bidang ekonomi.<\/p>\r\n\r\n<p style=\"text-align:justify\">&ldquo;Akan munculnya suatu sistem perdagangan bebas di kawasan Asia Tenggara yang memungkinkan barang, jasa maupun tenaga profesional dari negara tetangga masuk ke Indonesia. Dan untuk menghadapi hal tersebut diperlukan Sumber Daya Manusia (SDM) yang siap memenangkan persaingan global tersebut,&rdquo; paparnya.<\/p>\r\n\r\n<p style=\"text-align:justify\">Tambah Ricky, sebagai calon lulusan yang akan bersaing di dunia kerja, mahasiswa PCR harus siap untuk bisa menjadi SDM yang handal terutama dalam hal berkomunikasi. Dalam hal ini peranan bahasa Inggris sangat diperlukan baik dalam menguasai komunikasi berbasis teknologi maupun dalam berkomunikasi secara langsung.<\/p>\r\n",
                "tanggal_post" => "2016-01-05T17:00:00.000Z",
                "user_id_author" => 1,
                "status_post" => "published",
                "meta_desc_post" => "Politeknik Caltex Riau (PCR) kedatangan tamu spesial dari pensiunan PT Chevron Pacific Indonesia Ricky Aman sebagai pembicara khusus",
                "meta_keyword_post" => "PCR, Politeknik, Caltex, Riau, Politeknik Caltex,Ricky Aman,Chevron Pacific Indonesia",
                "slug_post" => "pentingnya-bekal-komunikasi-berbahasa-inggris-di-era-masyarakat-ekonomi-asia",
                //"media_id_post" => 1,
                "created_by" => "DEV",
                "updated_by" => null,
                "deleted_by" => null,
                "created_at" => "2025-08-21T11:17:54.167Z",
                "updated_at" => "2025-08-21T11:17:54.167Z",
                "deleted_at" => null
            ],
            [
                // "post_id" => 2,
                "postkategori_id" => 1,
                "level" => "main-site",
                "level_id" => null,
                "judul_post" => "Politeknik Caltex Riau : Dalam Membangun Budaya dan Karakter ",
                "isi_post" => "<p style=\"text-align:justify\">Disadari atau tidak, setiap organisasi memiliki&nbsp;<em>values<\/em>&nbsp;yang berbeda-beda. Bahkan perusahaan yang sama di lokasi yang berbeda akan memiliki<\/p>\r\n\r\n<div style=\"page-break-after: always\"><span style=\"display:none\">&nbsp;<\/span><\/div>\r\n\r\n<p style=\"text-align:justify\"><em>values<\/em> yang sedikit berbeda. Banyak faktor yang mempengaruhi terbentuknya nilai-nilai dan budaya kerja suatu organisasi. Untuk organisasi yang menyadari pentingnya&nbsp;<em>corporate values<\/em><em> maka akan<\/em>&nbsp;membentuk budaya organisasi sejak awal organisasi tersebut dibangun.<\/p>\r\n\r\n<p style=\"text-align:justify\">Banyak yang tidak menyadari bahwa konstruksi organisasi yang kokoh ditopang karena budaya kerjanya, bukan bangunannya. Membangun gedung adalah bagian termudah dari membangun sebuah institusi jika dana yang akan digunakan tersedia. Bab tersulitnya adalah bagaimana membangun budaya institusi yang efektif sehingga roda organisasi bisa berjalan dengan baik.<\/p>\r\n\r\n<p style=\"text-align:justify\">Para pendiri Politeknik Caltex Riau sedari awal memang sudah mencanangkan bahwa Politeknik Caltex Riau tidaklah berorientasi untuk menjadi ITB kedua atau ITS kedua, maupun Politeknik terkenal lainnya di Indonesia. Kampus ini dibangun untuk menjadi Politeknik Caltex Riau dengan corak dan warna sendiri yang berbeda dengan kampus manapun yang ada. Untuk menghasilkan corak yang berbeda, Dosen yang direkrut juga berasal dari berbagai Perguruan Tinggi, tidak didominasi hanya dari satu Perguruan Tinggi. Itu juga sebabnya PT Caltex Pacific Indonesia tidak menyerahkan proses pembangunan Politeknik Caltex Riau kepada Perguruan Tinggi manapun. Para pendiri PCR ingin agar <em>corporate values<\/em> yang ada di PT CPI bisa diimplementasikan di Politeknik Caltex Riau. Oleh karena itu tim pengembangan PCR hanya diisi oleh para profesional yang juga pegawai PT CPI.<\/p>\r\n\r\n<p style=\"text-align:justify\">Budaya dan nilai-nilai yang dibangun Politeknik Caltex Riau sangat unik dan berbeda dari yang kebanyakan berlaku di berbagai kampus di Indonesia. Salah satu contohnya adalah sejak awal Politeknik Caltex Riau menetapkan waktu kerja <em>full-time<\/em> untuk seluruh pegawai termasuk Dosen. Dosen wajib berada di Kampus minimal 40 jam\/minggu, baik mengajar maupun tidak mengajar. Dengan demikian maka di luar waktu mengajar dosen bisa mengembangkan diri, mengembangkan materia pengajaran, membantu mengembangkan institusi, atau membimbing mahasiswa.<\/p>\r\n\r\n<p style=\"text-align:justify\">Seiring waktu setelah proses akademik berjalan dengan baik, perlahan-lahan aturan-aturan terkait kepegawaian disusun dan disahkan. Sebenarnya aturan yang disusun tersebut sebagian besar sudah diimplementasikan hanya saja belum didokumentasikan. Peraturan dan budaya kerja yang disusun merupakan kombinasi dari <em>corporate values<\/em> PT CPI yang disesuaikan dengan pola kerja dan aturan yang berlaku di dunia akademik. Hal ini yang menyebabkan Politeknik Caltex Riau menjadi unik.<\/p>\r\n\r\n<p style=\"text-align:justify\">Salah satu budaya akademik yang terbentuk dari proses yang memang telah dirancang adalah kedisiplinan. Saat ini masyarakat melihat Politeknik Caltex Riau identik dengan kampus &ldquo;disiplin&rdquo;, tidak hanya mahasiswanya namun juga dosennya. Mahasiswa, Dosen dan Pegawai lainnya harus masuk jam 07 tepat, tidak boleh telat. Disiplin diterapkan tidak hanya dalam hal kehadiran, namun juga dalam hal penampilan. Mahasiswa, Dosen dan Pegawai lainnya harus berpenampilan rapi dan sopan.<\/p>\r\n\r\n<p style=\"text-align:justify\">Selain disiplin, <em>value<\/em> lain yang sangat dibanggakan adalah <em>integrity<\/em> yang meliputi keterbukaan dan kejujuran. Tidak ada pungutan liar di Kampus. Banyak mahasiswa PCR yang menerima beasiswa dari berbagai instansi dan tidak ada potongan apapun yang dikenakan kepada mahasiswa. PCR tidak akan mengajarkan proses-proses yang bersifat koruptif kepada mahasiswanya. Seluruh proses administrasi dan keuangan dilakukan secara terbuka dan transparan. Bahkan laporan keuangan Yayasan Politeknik Chevron Riau setiap tahun di audit oleh Kantor Akuntan Publik dan hasilnya dipublikasikan di berbagai media.<\/p>\r\n\r\n<p style=\"text-align:justify\">Satu lagi <em>value<\/em> yang dibangun di Politeknik Caltex Riau adalah <em>dignity<\/em> atau boleh disebut juga kehormatan dan kebanggaan terhadap institusi. Kebanggaan bisa muncul dihati seseorang karena memang ada hal yang bisa dibanggakan. Jika tidak, maka yang muncul hanya <em>fanatisme<\/em> bukan <em>dignity<\/em>. Oleh karena itu Politeknik Caltex Riau berusaha untuk terus menciptakan kebanggaan di hati seluruh masyarakat kampus. Kebanggaan dimunculkan dengan cara memperindah kampus, menjaga standar proses akademik yang tinggi, dan tentunya dengan berbagai prestasi yang diraih. Tentu saja kebanggaan yang dibangun harus terjaga agar tidak berubah menjadi kesombongan dan keangkuhan.<\/p>\r\n\r\n<p style=\"text-align:justify\">Mahasiswa, dosen, staf dan seluruh komponen sivitas akademika Politeknik Caltex Riau berasal dari berbagai daerah dan suku yang tentunya memiliki pola pikir dan budaya yang berbeda-beda. Meski demikian, semua orang yang berasal dari berbagai suku dan daerah tersebut harus ikut dan mematuhi budaya yang ada di Politeknik Caltex Riau manakala berada di dalam lingkungan kampus. Membangun budaya bukanlah perkara mudah. Diperlukan komitmen, konsistensi dan ketegasan. Larangan merokok di lingkungan kampus sudah diterapkan sejak awal PCR beroperasi sehingga lebih mudah membentuknya. Mengubah kebiasaan itu memang tidak mudah. Hal yang sama pernah dilakukan PCR saat menetapkan PCR sebagai kampus tertib lalu lintas. Untuk menegakan aturan tersebut tidak cukup hanya diserahkan kepada pelaksana dilapangan. Pimpinan harus muncul di depan untuk menunjukan keseriusan dan ketegasan akan aturan tersebut. Oleh karena pada saat awal aturan ditetapkan tahun 2008, Direktur dan Pembantu Direktur secara rutin hadir dilapangan untuk memberi dukungan bagi para satpam dalam menegakan aturan. Walaupun awalnya mendapat tantangan, namun Alhamdulilah aturan tersebut akhirnya dapat ditegakkan dan menjadi budaya baru di lingkungan kampus.<\/p>\r\n\r\n<p style=\"text-align:justify\">Begitulah sekelumit cerita bagaimana Politeknik Caltex Riau membentuk nilai-nilai dan norma-norma yang berlaku di lingkungan kampus. Semua yang direncanakan bisa berjalan baik karena adanya komitmen dan konsistensi dari para Pimpinan serta kesadaran yang kuat untuk selalu lebih baik yang ditunjukan para dosen, mahasiswa dan seluruh komponen sivitas akademika. Karakter sebuah organisasi bukan hanya dibentuk oleh kualitas gedungnya, namun lebih karena budaya kerjanya. Begitu juga dengan mahasiswa, karakter mahasiswa tidak hanya dibentuk oleh ilmu dan keterampilannya, namun juga dari sikap dan mental yang kuat.<\/p>\r\n\r\n<p style=\"text-align:justify\">Selamat Menyambut Ulang Tahun yang Ke-15 buat Politeknik Caltex Riau. Banyak sekali nikmat Allah S.W.T yang sudah kita peroleh selama ini, dan tidak sedikit cobaan yang sudah kita lalui. Semoga usia 15 tahun ini membuat kita semakin kuat untuk terus maju. Aamin.<\/p>\r\n\r\n<p style=\"text-align:justify\">&nbsp;<\/p>\r\n\r\n<p style=\"text-align:justify\"><strong>Direktur Politeknik Caltex Riau<\/strong><\/p>\r\n\r\n<p style=\"text-align:justify\"><strong>Dr. Hendriko, S.T.,M.Eng<\/strong><\/p>\r\n",
                "tanggal_post" => "2016-01-10T17:00:00.000Z",
                "user_id_author" => 1,
                "status_post" => "published",
                "meta_desc_post" => "Sekelumit cerita bagaimana Politeknik Caltex Riau membentuk nilai-nilai dan norma-norma yang berlaku di lingkungan kampus.",
                "meta_keyword_post" => "PCR, Politeknik, Caltex, Riau, Politeknik Caltex",
                "slug_post" => "politeknik-caltex-riau-:-dalam-membangun-budaya-dan-karakter-",
                //"media_id_post" => 1,
                "created_by" => "DEV",
                "updated_by" => null,
                "deleted_by" => null,
                "created_at" => "2025-08-21T11:17:54.167Z",
                "updated_at" => "2025-08-21T11:17:54.167Z",
                "deleted_at" => null
            ],
            [
                // "post_id" => 3,
                "postkategori_id" => 2,
                "level" => "main-site",
                "level_id" => null,
                "judul_post" => "192 Mahasiswa PCR Terima Beasiswa Bidik Misi Pemerintah Provinsi Riau",
                "isi_post" => "<p style=\"text-align:justify\">Sebanyak 192 mahasiswa Politeknik Caltex Riau (PCR) menerima beasiswa bidik misi dari pemerintah Provinsi Riau. Serah terima beasiswa dilaksanakan di<\/p>\r\n\r\n<div style=\"page-break-after: always\"><span style=\"display:none\">&nbsp;<\/span><\/div>\r\n\r\n<p style=\"text-align:justify\">auditorium gedung serba guna PCR, Senin (18\/01). Diserakan langsung oleh Kepala Dinas Pendidikan Riau, Dr. H. Kamsol, M.Si kepada mahasiswa penerima beasiswa, acara dihadiri juga oleh orang tua seluruh penerima beasiswa.<\/p>\r\n\r\n<p style=\"text-align:justify\">Menurut Direktur PCR, Dr. Hendriko, PCR sudah tahun kedua menerima beasiswa bidik misi pemerintah Provinsi Riau. Pada tahun pertama yakni 2014 lalu, penerima beasiswa tersebut berjumlah 47 orang. Dan di tahun 2015 ditambah lagi menjadi 145 orang. Keseluruhan penerima terdiri dari mahasiswa di seluruh program studi angkatan 2013 hingga 2015.<\/p>\r\n\r\n<p style=\"text-align:justify\">&ldquo;Alhamdulilah PCR diberikan kepercayaan untuk bisa menyalurkan beasiswa bidik misi pemprov. Pada tahun pertama beasiswa yang diberikan menanggung biaya pembangunan dan biaya spp. Untuk tahun kedua pemprov membantu biaya spp selama satu tahun,&rdquo; jelasnya.<\/p>\r\n\r\n<p style=\"text-align:justify\">Senada dengan itu, Ketua Yayasan Politeknik Chevron Riau (YPCR), Robinar Djadjadisastra, menyatakan adanya beasiswa bidik misi riau ini menjadi pelengkap beasiswa yang sudah ada sebelumnya di PCR. &ldquo;Bagi PCR, beasiswa ini melengkapi beasiswa yang sudah ada di PCR. Karena sebelumnya PCR memiliki beasiswa YPCR dan bidik misi dari Dirjen Pendidikan Tinggi dan beasiswa pemprov ini menjadi pelengkap beasiswa yang ada di PCR,&rdquo; paparnya.<\/p>\r\n\r\n<p style=\"text-align:justify\"><strong>Perkenalkan Program Studi Baru &nbsp;<\/strong><\/p>\r\n\r\n<p style=\"text-align:justify\">Dalam kesempatan kali ini, PCR juga memperkenalkan dua program studi baru yakni DIV Teknik Mesin dan DIV Teknik Listrik. Kedua program studi ini akan mulai menerima mahasiswa baru tahun ajaran 2016 &ndash; 2017. Diharapkan kedua program studi baru ini menjawab kebutuhan industri akan tenaga kerja diploma dengan kemampuan teknis setara S1.<\/p>\r\n\r\n<p style=\"text-align:justify\">&ldquo;Bersyukur di akhir tahun 2015 lalu, dua program studi DIV mendapatkan izin resmi dari DIKTI untuk bisa mulai beroperasi. Izin tersebut berdasarkan nomor 22\/KPT\/I\/2015 oleh karena itu, bagi yang tertarik bisa langsung mendaftar melalui jalur Penjaringan Siswa Unggul Daerah (PSUD) maupun ujian masuk,&rdquo; jelasnya.<\/p>\r\n",
                "tanggal_post" => "2016-01-17T17:00:00.000Z",
                "user_id_author" => 1,
                "status_post" => "published",
                "meta_desc_post" => "Sebanyak 192 mahasiswa Politeknik Caltex Riau (PCR) menerima beasiswa bidik misi dari pemerintah Provinsi Riau untuk tahun 2016",
                "meta_keyword_post" => "PCR, Politeknik, Caltex, Riau, beasiswa, Politeknik Caltex,bidik misi",
                "slug_post" => "192-mahasiswa-pcr-terima-beasiswa-bidik-misi-pemerintah-provinsi-riau",
                //"media_id_post" => 1,
                "created_by" => "DEV",
                "updated_by" => null,
                "deleted_by" => null,
                "created_at" => "2025-08-21T11:17:54.167Z",
                "updated_at" => "2025-08-21T11:17:54.167Z",
                "deleted_at" => null
            ],
            [
                // "post_id" => 4,
                "postkategori_id" => 1,
                "level" => "main-site",
                "level_id" => null,
                "judul_post" => "Permadhis PCR Akan Selenggarakan Donor Darah di Hari Cinta Kasih",
                "isi_post" => "<p style=\"text-align:justify\"><span style=\"font-family:arial,sans-serif; font-size:9.5pt\">Dalam rangka memeringati hari Metta yaitu hari cinta kasih, Persatuan Mahasiswa Budhis (Permadhis) Politeknik Caltex Riau (PCR) akan mengadakan <\/span><\/p>\r\n\r\n<div style=\"page-break-after: always\"><span style=\"display:none\">&nbsp;<\/span><\/div>\r\n\r\n<p style=\"text-align:justify\"><span style=\"font-family:arial,sans-serif; font-size:9.5pt\">kegiatan donor darah hari Minggu mendatang, 24 Januari 2016. Menurut Pembina Permadhis, Jefry, acara tersebut akan diadakan di Kampus PCR dari pukul 07.30 pagi. <\/span><\/p>\r\n\r\n<p style=\"text-align:justify\"><span style=\"font-family:arial,sans-serif; font-size:9.5pt\">&ldquo;Kegiatan donor darah ini diharapkan bisa memberikan banyak manfaat bagi sesama. Karena di hari cinta kasih seharusnya kita memberikan sesuatu yang bermanfaat bagi banyak orang. Layaknya sekantong darah yang Anda sumbang dapat menyelamatkan hingga 3 nyawa,&rdquo; jelasnya. <\/span><\/p>\r\n\r\n<p style=\"text-align:justify\"><span style=\"font-family:arial,sans-serif; font-size:9.5pt\">Untuk kegiatan ini, Permadhis bekerjasama dengan Palang Merah Indonesia (PMI) Kota Pekanbaru. Seluruh kantong darah yang dihasilkan akan langsung disalurkan melalui PMI. Ditargetkan ratusan pendonor akan berpartisipasi dalam acara ini. <\/span><\/p>\r\n\r\n<p style=\"text-align:justify\"><span style=\"font-family:arial,sans-serif; font-size:9.5pt\">&ldquo;Karena donor darah sebenarnya juga menyehatkan bagi sang pendonor. Rajin mendonor darah akan mengurangi resiko untuk terkena berbagai macam penyakit, menurunkan level zat besi dalam darah dan mencegah penuaan dini,&rdquo; paparnya. &nbsp;<\/span><\/p>\r\n\r\n<p style=\"text-align:justify\"><span style=\"font-family:arial,sans-serif; font-size:9.5pt\">Untuk pendaftaran bisa langsung menghubungi panitia acara di kontak 0822-8437-9360 atas nama Elisa Silviana.&nbsp;<\/span><\/p>\r\n",
                "tanggal_post" => "2016-01-19T17:00:00.000Z",
                "user_id_author" => 1,
                "status_post" => "published",
                "meta_desc_post" => "Memeringati hari Metta yaitu hari cinta kasih, Persatuan Mahasiswa Budhis (Permadhis) Politeknik Caltex Riau (PCR) akan mengadakan kegiatan donor darah",
                "meta_keyword_post" => "PCR,Politeknik, Caltex, Riau,Permadhis,donor darah,hari Metta",
                "slug_post" => "permadhis-pcr-akan-selenggarakan-donor-darah-di-hari-cinta-kasih",
                //"media_id_post" => 1,
                "created_by" => "DEV",
                "updated_by" => null,
                "deleted_by" => null,
                "created_at" => "2025-08-21T11:17:54.167Z",
                "updated_at" => "2025-08-21T11:17:54.167Z",
                "deleted_at" => null
            ],
            [
                // "post_id" => 5,
                "postkategori_id" => 1,
                "level" => "main-site",
                "level_id" => null,
                "judul_post" => "PUSKOM PCR Akan Adakan Pelatihan Pengelolaan Blog",
                "isi_post" => "<p style=\"text-align:justify\">Dalam rangka meningkatkan dan menyalurkan hobi menulis mahasiswa Politeknik Caltex Riau (PCR), Departemen Pusat Komputer (PUSKOM) PCR akan melaksanakan<\/p>\r\n\r\n<div style=\"page-break-after: always\"><span style=\"display:none\">&nbsp;<\/span><\/div>\r\n\r\n<p style=\"text-align:justify\">kegiatan pelatihan blog 9 Februari mendatang di kampus PCR. Blog dipilih karena menjadi sarana yang sangat tepat untuk menyangkan seluruh ide pikiran secara luas.<\/p>\r\n\r\n<p style=\"text-align:justify\">Hal itu dipaparkan oleh staf PUSKOM PCR, Shandy Setyo Nugroho, menurutnya menuangkan ide dalam bentuk tulisan lebih bisa dibaca oleh masyarakat luas dan bisa menjadi forum diskusi publik. &ldquo;Media blog yang diangkat di sini adalah blog PCR sendiri yang berbasis free dan open-source content management system (CMS) &ndash; wordpress. Selain itu pelatihan akan ditekankan kepada bagaimana pengelolaan blog seharusnya,&rdquo; paparnya.<\/p>\r\n\r\n<p style=\"text-align:justify\">Para peserta yang berminat bisa melakukan pendaftaran mulai tanggal 18 Januari hingga 5 Februari 2016. Melalui google form di alamat <u>bit.ly\/Blogpcr2016<\/u>, setap peserta yang mendaftar tidak akan dipungut biaya apapun. Selain itu, setiap mahasiswa yang mengikuti pelatihan ini akan mendapatkan sertifikat, nilai transkrip akademik kemahasiswaan, snack dan domain blog mahasiswa.<\/p>\r\n\r\n<p style=\"text-align:justify\">Informasi lebih lanjut bisa menghubungi Muthaminna di 082284922245 atau <a href=\"mailto:inna@pcr.ac.id\">inna@pcr.ac.id<\/a>.<\/p>\r\n",
                "tanggal_post" => "2016-01-21T17:00:00.000Z",
                "user_id_author" => 1,
                "status_post" => "published",
                "meta_desc_post" => "Departemen Pusat Komputer (PUSKOM) PCR akan melaksanakan kegiatan pelatihan Blog di kampus PCR untuk meningkatkan dan menyalurkan hobi menulis mahasiswa",
                "meta_keyword_post" => "PCR, Politeknik, Caltex, Riau, PUSKOM PCR, Politeknik Caltex,Blog,Pelatihan",
                "slug_post" => "puskom-pcr-akan-adakan-pelatihan-pengelolaan-blog",
                //"media_id_post" => 1,
                "created_by" => "DEV",
                "updated_by" => null,
                "deleted_by" => null,
                "created_at" => "2025-08-21T11:17:54.167Z",
                "updated_at" => "2025-08-21T11:17:54.167Z",
                "deleted_at" => null
            ],
        ];
        DB::table('post')->insert($data);
    }
}
