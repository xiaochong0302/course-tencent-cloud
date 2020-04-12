<?php

namespace App\Builders;

class SlideList extends Builder
{

    public function handleSlides($slides)
    {
        $baseUrl = kg_ci_base_url();

        foreach ($slides as $key => $slide) {
            $slides[$key]['cover'] = $baseUrl . $slide['cover'];
        }

        return $slides;
    }

}
