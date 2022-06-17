<?php
$resources = [];
/***
 * $resources[$role][$module] = [
 *      $controller => [$actions]
 *  ]
 */
$resources['guest']['admin'] = [
    'public' => '*'
];

$resources['moderator']['admin'] = [
    'index'     => '*',//key is controller name and value are lists of actions in the controller;
    'test'      => '*',
    'moderation'=> '*',
    'public'    => '*',
    'report'    => '*',
    'session'   => '*',
    'slide'     => '*',
    'tag'       => '*',
    'upload'    => '*',
    'page'      => ['list','add','create','edit','update','restore'],
    'review'    => ['search','list',' edit','update','moderate','restore'],
    'question'  => ['category','search','list','add','edit','show','create','update','restore','moderate','report'],
    'article'   => ['category','search','list','add','edit','show','create','update','restore','moderate','report']
];

$resources['admin']['admin'] = [
    'article' => '*',
    'answer'  => '*',
    'audit' => '*',
    'category' => '*',
    'chapter' => '*',
    'comment' => '*',
    'consult' => '*',
    'course' => '*',
    'flashSale' => '*',
    'help' => '*',
    'im-group' => '*',
    'im-groupUser' => '*',
    'nav' => '*',
    'order' => '*',
    'package' => '*',
    'pointGift' => '*',
    'pointGiftRedeem' => '*',
    'pointHistory' => '*',
    'refund' => '*',
    'resource' => '*',
    'sole' => '*',
    'stat' => '*',
    'student' => '*',
    'topic' => '*',
    'trade' => '*',
    'user'  => '*',
];

$resources['developer']['admin'] = [
    'setting'=> '*',
    'util'   => '*',
];

return $resources;
