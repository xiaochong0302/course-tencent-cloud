<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

use App\Models\Answer as AnswerModel;
use App\Models\Article as ArticleModel;
use App\Models\Course as CourseModel;
use App\Models\Help as HelpModel;
use App\Models\Page as PageModel;
use App\Models\PointGift as PointGiftModel;
use App\Models\Question as QuestionModel;
use League\CommonMark\GithubFlavoredMarkdownConverter;
use Phalcon\Mvc\Model\Resultset;

class V202207291145 extends Migration
{

    /**
     * @var GithubFlavoredMarkdownConverter
     */
    protected $markdownConverter;

    public function run()
    {
        $this->initMarkdownConverter();
        $this->courseMarkdownToHtml();
        $this->articleMarkdownToHtml();
        $this->questionMarkdownToHtml();
        $this->answerMarkdownToHtml();
        $this->pageMarkdownToHtml();
        $this->helpMarkdownToHtml();
        $this->pointGiftMarkdownToHtml();
    }

    protected function initMarkdownConverter()
    {
        $this->markdownConverter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
    }

    protected function articleMarkdownToHtml()
    {
        /**
         * @var $articles Resultset|ArticleModel[]
         */
        $articles = ArticleModel::query()->execute();

        if ($articles->count() == 0) return;

        foreach ($articles as $article) {
            $content = $this->markdownConverter->convertToHtml($article->content);
            $article->content = $content;
            $article->update();
        }
    }

    protected function courseMarkdownToHtml()
    {
        /**
         * @var $courses Resultset|CourseModel[]
         */
        $courses = CourseModel::query()->execute();

        if ($courses->count() == 0) return;

        foreach ($courses as $course) {
            $details = $this->markdownConverter->convertToHtml($course->details);
            $course->details = $details;
            $course->update();
        }
    }

    protected function questionMarkdownToHtml()
    {
        /**
         * @var $questions Resultset|QuestionModel[]
         */
        $questions = QuestionModel::query()->execute();

        if ($questions->count() == 0) return;

        foreach ($questions as $question) {
            $content = $this->markdownConverter->convertToHtml($question->content);
            $question->content = $content;
            $question->update();
        }
    }

    protected function answerMarkdownToHtml()
    {
        /**
         * @var $answers Resultset|AnswerModel[]
         */
        $answers = AnswerModel::query()->execute();

        if ($answers->count() == 0) return;

        foreach ($answers as $answer) {
            $content = $this->markdownConverter->convertToHtml($answer->content);
            $answer->content = $content;
            $answer->update();
        }
    }

    protected function pageMarkdownToHtml()
    {
        /**
         * @var $pages Resultset|PageModel[]
         */
        $pages = PageModel::query()->execute();

        if ($pages->count() == 0) return;

        foreach ($pages as $page) {
            $content = $this->markdownConverter->convertToHtml($page->content);
            $page->content = $content;
            $page->update();
        }
    }

    protected function helpMarkdownToHtml()
    {
        /**
         * @var $helps Resultset|HelpModel[]
         */
        $helps = HelpModel::query()->execute();

        if ($helps->count() == 0) return;

        foreach ($helps as $help) {
            $content = $this->markdownConverter->convertToHtml($help->content);
            $help->content = $content;
            $help->update();
        }
    }

    protected function pointGiftMarkdownToHtml()
    {
        /**
         * @var $gifts Resultset|PointGiftModel[]
         */
        $gifts = PointGiftModel::query()
            ->where('type = :type:', ['type' => PointGiftModel::TYPE_GOODS])
            ->execute();

        if ($gifts->count() == 0) return;

        foreach ($gifts as $gift) {
            $details = $this->markdownConverter->convertToHtml($gift->details);
            $gift->details = $details;
            $gift->update();
        }
    }

}