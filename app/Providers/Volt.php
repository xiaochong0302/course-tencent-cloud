<?php

namespace App\Providers;

use Phalcon\Mvc\View\Engine\Volt as PhalconVolt;

class Volt extends Provider
{

    protected $serviceName = 'volt';

    public function register()
    {
        $this->di->setShared('volt', function ($view, $di) {

            $volt = new PhalconVolt($view, $di);

            $volt->setOptions([
                'compiledPath' => cache_path() . '/volt/',
                'compiledSeparator' => '_',
            ]);

            $compiler = $volt->getCompiler();

            $compiler->addFunction('css_link', function ($resolvedArgs) {
                return 'kg_css_link(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('js_include', function ($resolvedArgs) {
                return 'kg_js_include(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('substr', function ($resolvedArgs) {
                return 'kg_substr(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('can', function ($resolvedArgs) {
                return 'kg_can(' . $resolvedArgs . ')';
            });

            $compiler->addFunction('img_url', function ($resolvedArgs) {
                return 'kg_img_url(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('array_object', function ($resolvedArgs) {
                return 'kg_array_object(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('object_array', function ($resolvedArgs) {
                return 'kg_object_array(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('play_duration', function ($resolvedArgs) {
                return 'kg_play_duration(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('total_duration', function ($resolvedArgs) {
                return 'kg_total_duration(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('human_number', function ($resolvedArgs) {
                return 'kg_human_number(' . $resolvedArgs . ')';
            });

            return $volt;
        });
    }

}