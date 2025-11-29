<?php

namespace Database\Factories;

use App\Enums\InspirationCategoryEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inspiration>
 */
class InspirationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $images = [
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/spacejoy-NpF_OYE301E-unsplash_khj6qv?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/prydumano-design-iL7p_66Cv00-unsplash_c7ptyi?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/spacejoy-c0JoR_-2x3E-unsplash_ydoyyg?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/juliana-uribbe-xNe9_YF-RgY-unsplash_wemvnu?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/juliana-uribbe-W985UllJIlk-unsplash_d3cden?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/prydumano-design-xHWD7LGdy0g-unsplash_cnvsem?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/darshan-patel-DfzzpBRZCT0-unsplash_h1i5qr?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/gian-paolo-aliatis-hQvLc8KDN6A-unsplash_lfdqkc?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/prydumano-design-VWhfT5eRA0U-unsplash_yivl0c?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/juliana-uribbe-FvUEnfD4228-unsplash_zcfzho?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/obegi-home-IDAsplMgG7A-unsplash_t7gmhk?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/collov-home-design-xJ14RuLV9zI-unsplash_u7icl2?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/angela-bailey-tuJtzghMuEw-unsplash_nf09xd?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/gian-paolo-aliatis--AfflhhVVuA-unsplash_rqqld6?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/faisal-waheed-yMhBYpf64sM-unsplash_vpora9?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/bayu-syaits-X73n9dX0PYA-unsplash_qwooyk?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/collov-home-design-5a7URgkummE-unsplash_gobzkx?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/collov-home-design-MopQfWaJFiw-unsplash_eaakay?_a=BAMAAAfm0',
            'https://res.cloudinary.com/dgmddarj4/image/upload/f_auto/q_auto/bayu-syaits-QaaP5RONuRI-unsplash_slp7l9?_a=BAMAAAfm0',
        ];

        shuffle($images);

        return [
            'title'         => $this->faker->sentence(3),
            'image_url'     => $this->faker->randomElement($images),
            'category'      => $this->faker->randomElement(InspirationCategoryEnum::cases())->value,
            'display_order' => $this->faker->numberBetween(0, 100),
            'height'        => $this->faker->randomElement(['short', 'medium', 'tall']),
            'is_active'     => true,
        ];
    }
}
