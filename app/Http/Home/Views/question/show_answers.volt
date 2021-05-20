<div class="layui-tab layui-tab-brief search-tab">
    <ul class="layui-tab-title">
        <li class="layui-this" data-url="{{ answer_list_url }}?sort=popular">热门回答</li>
        <li data-url="{{ answer_list_url }}?sort=latest">最新回答</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <div id="answer-list" data-url="{{ answer_list_url }}?sort=popular"></div>
        </div>
    </div>
</div>