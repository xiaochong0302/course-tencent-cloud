<?php

namespace App\Http\Home\Services;

use App\Models\Danmaku as DanmakuModel;
use App\Repos\Danmaku as DanmakuRepo;
use App\Transformers\DanmakuList as DanmakuListTransformer;
use App\Validators\Danmaku as DanmakuValidator;

class Danmaku extends Service
{

    public function getChapterDanmakus($chapterId, $limit = 1000)
    {
        $danmakuRepo = new DanmakuRepo();

        $params = [
            'chapter_id' => $chapterId,
            'published' => 1,
            'deleted' => 0
        ];

        $pager = $danmakuRepo->paginate($params, 'latest', 1, $limit);

        $result = $this->handleDanmakus($pager);

        return $result;
    }

    public function createDanmaku()
    {
        $body = $this->request->getJsonRawBody();

        $danmaku = new DanmakuModel();

        $validator = new DanmakuValidator();

        $data = [];

        $chapter = $validator->checkChapter($body->id);

        $user = $validator->checkUser($body->author, $body->token);

        $validator->checkPostLimit($user->id);

        $data['text'] = $validator->checkText($body->text);
        $data['color'] = $validator->checkColor($body->color);
        $data['type'] = $validator->checkType($body->type);
        $data['time'] = $validator->checkTime($body->time);
        $data['ip'] = $this->request->getClientAddress();
        $data['course_id'] = $chapter->course_id;
        $data['chapter_id'] = $chapter->id;
        $data['user_id'] = $user->id;

        $danmaku->create($data);

        return $danmaku;
    }

    protected function handleDanmakus($pager)
    {
        if ($pager->total_items == 0) {
            return [];
        }

        $transformer = new DanmakuListTransformer();

        $items = $pager->items->toArray();

        $danmakus = $transformer->handleUsers($items);

        $result = [];

        foreach ($danmakus as $danmaku) {
            $result[] = [
                $danmaku['time'],
                $danmaku['type'],
                $danmaku['color'],
                $danmaku['user']['name'],
                $danmaku['text'] . ' - by ' . $danmaku['user']['name'],
            ];
        }

        return $result;
    }

}
