<div class="layui-tabs search-tab">
    <ul class="layui-tabs-header">
        <li class="layui-this" data-url="{{ answer_list_url }}?sort=popular">热门回答</li>
        <li data-url="{{ answer_list_url }}?sort=latest">最新回答</li>
    </ul>
    <div class="layui-tabs-body">
        <div class="layui-tabs-item layui-show">
            <div id="answer-list" data-url="{{ answer_list_url }}?sort=popular"></div>
        </div>
    </div>
</div>
