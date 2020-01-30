<?php

namespace App\Builders;

class SlideList extends Builder
{

    public function handleSlides($slides)
    {
        $imgBaseUrl = kg_img_base_url();

        foreach ($slides as $key => $slide) {
            $slides[$key]['cover'] = $imgBaseUrl . $slide['cover'];
        }

        return $slides;
    }

}
