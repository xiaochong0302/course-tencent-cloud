{% if pager.total_pages > 1 %}
    <div class="kg-pager">
        <div class="layui-box layui-laypage layui-laypage-default">
            <a href="{{ pager.first }}">首页</a>
            <a href="{{ pager.previous }}">上页</a>
            <a href="{{ pager.next }}">下页</a>
            <a href="{{ pager.last }}">尾页</a>
        </div>
    </div>
{% endif %}