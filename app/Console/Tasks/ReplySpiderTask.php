<?php

namespace App\Console\Tasks;

use App\Models\Reply as ReplyModel;
use App\Models\Thread as ThreadModel;
use App\Models\User as UserModel;
use Phalcon\Cli\Task;
use QL\QueryList;

class ReplySpiderTask extends Task
{

    public function mainAction()
    {
        $threads = ThreadModel::query()
            ->columns(['id'])
            ->where('id > 59429')
            ->orderBy('id ASC')
            ->execute();

        $ql = $this->getRules();

        foreach ($threads as $thread) {
            $this->handleList($ql, $thread->id);
            sleep(5);
        }
    }

    protected function getRules()
    {
        $ql = QueryList::getInstance()->rules([
            'thread_content' => ['div.qa-disscus', 'html'],
            'user_link' => ['div.qa-comment-author > a', 'href'],
            'user_img' => ['div.qa-comment-author > a > img', 'src'],
            'user_name' => ['span.qa-comment-nick', 'text'],
            'reply_id' => ['div.qa-comment', 'data-cid'],
            'reply_content' => ['div.qa-comment-c > div.rich-text', 'html'],
            'reply_time' => ['span.qa-comment-time', 'text'],
        ]);

        return $ql;
    }

    protected function handleList($ql, $threadId)
    {

        $thread = ThreadModel::findFirst($threadId);

        $first = true;

        foreach (range(1, 10) as $page) {

            $url = "https://www.imooc.com/qadetail/{$threadId}?page={$page}";

            echo "============== Thread {$threadId}, Page {$page} =================" . PHP_EOL;

            $data = $ql->get($url)->query()->getData();

            if ($data->count() == 0) {
                break;
            }

            foreach ($data->all() as $item) {

                if ($first) {
                    $threadContent = $this->getThreadContent($item['thread_content']);
                    if ($threadContent) {
                        $thread->update(['content' => $threadContent]);
                    }
                    $first = false;
                }

                $userData = [
                    'id' => $this->getUserId($item['user_link']),
                    'name' => $this->getUserName($item['user_name']),
                    'avatar' => $item['user_img'],
                ];

                $user = UserModel::findFirst($userData['id']);

                if (!$user) {
                    $user = new UserModel();
                    $user->create($userData);
                }

                $replyData = [
                    'thread_id' => $threadId,
                    'author_id' => $user->id,
                    'id' => $item['reply_id'],
                    'content' => $this->getReplyContent($item['reply_content']),
                    'created_at' => $this->getReplyTime($item['reply_time']),
                ];

                $reply = ReplyModel::findFirst($replyData['id']);

                if (!$reply && $replyData['content']) {
                    $reply = new ReplyModel();
                    $reply->create($replyData);
                }
            }
        }

        $ql->destruct();
    }

    protected function getUserId($userLink)
    {
        $result = str_replace(['/u/', '/bbs'], '', $userLink);

        return trim($result);
    }

    protected function getUserName($userName)
    {
        $result = mb_substr($userName, 0, 30);

        return $result;
    }

    protected function getThreadContent($content)
    {
        $content = str_replace('&nbsp;&nbsp;', '&nbsp;', $content);
        if (mb_strlen($content) > 3000) {
            return false;
        }
        $result = mb_substr($content, 0, 3000);
        return $result;
    }

    protected function getReplyContent($content)
    {
        $content = str_replace('&nbsp;&nbsp;', '&nbsp;', $content);
        if (mb_strlen($content) > 1500) {
            return false;
        }
        $result = mb_substr($content, 0, 1500);
        return $result;
    }

    protected function getReplyTime($time)
    {
        $date = $this->filter->sanitize($time, ['trim', 'string']);

        if (strpos($date, '天')) {
            $days = str_replace(['天前'], '', $date);
            $days = intval($days);
            $result = strtotime("-{$days} days");
        } else {
            $result = strtotime(trim($date));
        }

        return $result;
    }

}
