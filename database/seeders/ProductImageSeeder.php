<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }

    /**
     * Get predefined image groups.
     * Each array represents images for one product (different angles/views).
     */
    public static function getImageGroups(): array
    {
        return [
            // product 1
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/huy-phan-KFcqUsn0_Z0-unsplash_nk0hxp?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/phillip-goldsberry-fZuleEfeA1Q-unsplash_ltzftq?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/natalie-malotova-Lh1gOvyMq-I-unsplash_akgvzs?_a=BAMAAAfm0',
            ],
            // product 10
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/costa-live-ERtuYB5ZG2Q-unsplash_jfo8bd?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/costa-live-ERtuYB5ZG2Q-unsplash_jfo8bd?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/costa-live-en8fNXIPkZ0-unsplash_mjwrsk?_a=BAMAAAfm0',
            ],
            // product 11
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/julia-mayo-5j5ej4VXa04-unsplash_lwedg9?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/lisa-anna-GuxAoWzz2mU-unsplash_i7s2fv?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/julia-mayo-dLlg3m-2e6g-unsplash_wa45dx?_a=BAMAAAfm0',
            ],
            // product 12
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/karthick-gislen--pSrzbG43w8-unsplash_xplsxr?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/adrian-swancar-G12opPvmqP0-unsplash_mqg1d8?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/abdelrahman-bayomi-XA_6uwWgLQ4-unsplash_buvhex?_a=BAMAAAfm0',
            ],
            // product 13
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/mary-skrynnikova-WuT1vTTzBcg-unsplash_lpfyao?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/mary-skrynnikova-PuG-jpI_dqI-unsplash_ejqwj7?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/mary-skrynnikova-K6N2AwexUsc-unsplash_qcl3p5?_a=BAMAAAfm0',
            ],
            // product 14
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/costa-live-mFzMqLRqMIM-unsplash_dpoxc5?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/costa-live-k9z-PMFolfo-unsplash_yv78wb?_a=BAMAAAfm0',
            ],
            // product 15
            // [

            // ],
            // product 2
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/daniil-silantev-1P6AnKDw6S8-unsplash_lmcr57?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/daniil-silantev-wLaus9bLmGQ-unsplash_z5u0by?_a=BAMAAAfm0',
            ],
            // product 3
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/photo-1762803841187-519b5fdf2109_vihsyg?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/photo-1762803841091-c5327f7aed37_kcx65o?_a=BAMAAAfm0',
            ],
            // product 4
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/costa-live-bR-Z4-6aI3M-unsplash_igcets?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/costa-live-NaR0fz6aUOs-unsplash_cisjou?_a=BAMAAAfm0',
            ],
            // product 5
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/syahmi-syahir-whzQn65SIPg-unsplash_l0nacp?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/menna-ahmed-crkAggG6UQw-unsplash_gkd2fq?_a=BAMAAAfm0',
            ],
            // product 6
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/jason-leung-3SejSK_A9cE-unsplash_zragbh?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/mk-s-P8Jq5auYoAw-unsplash_obpk4n?_a=BAMAAAfm0',
            ],
            // product 7
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/adrien-olichon-_C0u-d857BY-unsplash_bll1cn?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/agata-create-drbP8A14AIY-unsplash_gfr2vs?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/adrien-olichon-_C0u-d857BY-unsplash_jmz2yy?_a=BAMAAAfm0',
            ],
            // product 8
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/matus-gocman-4PSqRScfEf0-unsplash_gedaln?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/matus-gocman-xOrvkr1xWS8-unsplash_uu4yi1?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/matus-gocman-qQvX0_Ar2cU-unsplash_tgeuco?_a=BAMAAAfm0',
            ],
            // product 9
            [
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/xavier-foucrier-u8e9lcJ7gfk-unsplash_bpckwg?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/xavier-foucrier-u8e9lcJ7gfk-unsplash_bpckwg?_a=BAMAAAfm0',
                'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/xavier-foucrier-dD0gdjq27l8-unsplash_qxm50u?_a=BAMAAAfm0',
            ],
        ];
    }
}
