{% if pager.total_pages > 1 %}
    <div class="pager">
        <div class="layui-box layui-laypage layui-laypage-default">
            <a href="javascript:" data-target="{{ pager.target }}" data-url="{{ pager.first }}">首页</a>
            <a href="javascript:" data-target="{{ pager.target }}" data-url="{{ pager.previous }}">上页</a>
            <a href="javascript:" data-target="{{ pager.target }}" data-url="{{ pager.next }}">下页</a>
            <a href="javascript:" data-target="{{ pager.target }}" data-url="{{ pager.last }}">尾页</a>
        </div>
    </div>
{% endif %}