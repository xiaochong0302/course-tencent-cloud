{% extends 'templates/layer.volt' %}

{% block content %}

    <div class="answer-tips">
        <h3 class="suggest-text">适合作为回答的</h3>
        <ul class="suggest-list">
            <li><i class="layui-icon layui-icon-ok-circle"></i> 经过验证的有效解决办法</li>
            <li><i class="layui-icon layui-icon-ok-circle"></i> 自己的经验指引，对解决问题有帮助</li>
            <li><i class="layui-icon layui-icon-ok-circle"></i> 遵循 Markdown 语法排版，表达语义正确</li>
        </ul>
        <h3 class="not-suggest-text">不该作为回答的</h3>
        <ul class="not-suggest-list">
            <li><i class="layui-icon layui-icon-close-fill"></i> 询问内容细节或回复楼层</li>
            <li><i class="layui-icon layui-icon-close-fill"></i> 与题目无关的内容</li>
            <li><i class="layui-icon layui-icon-close-fill"></i> “赞” “顶” “同问” 等毫无意义的内容</li>
        </ul>
    </div>

{% endblock %}