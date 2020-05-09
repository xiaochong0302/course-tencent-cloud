{%- macro model_info(value) %}
    {% if value == 'vod' %}
        <span class="layui-badge layui-bg-green">点播</span>
    {% elseif value == 'live' %}
        <span class="layui-badge layui-bg-blue">直播</span>
    {% elseif value == 'read' %}
        <span class="layui-badge layui-bg-black">图文</span>
    {% endif %}
{%- endmacro %}

{%- macro level_info(value) %}
    难度：<span class="layui-badge layui-bg-gray">
    {% if value == 'entry' %}
        入门
    {% elseif value == 'junior' %}
        初级
    {% elseif value == 'medium' %}
        中级
    {% elseif value == 'senior' %}
        高级
    {% endif %}
    </span>
{%- endmacro %}

{%- macro category_info(category) %}
    {% if category %}
        分类：<a class="layui-badge layui-bg-gray" href="{{ url({'for':'admin.course.list'},{'category_id':category.id}) }}">{{ category.name }}</a>
    {% endif %}
{%- endmacro %}

{%- macro teacher_info(teacher) %}
    {% if teacher %}
        讲师：<a class="layui-badge layui-bg-gray" href="{{ url({'for':'admin.course.list'},{'teacher_id':teacher.id}) }}">{{ teacher.name }}</a>
    {% endif %}
{%- endmacro %}

<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a><cite>课程管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.course.search'}) }}">
            <i class="layui-icon layui-icon-search"></i>搜索课程
        </a>
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.course.add'}) }}">
            <i class="layui-icon layui-icon-add-1"></i>添加课程
        </a>
    </div>
</div>

<table class="kg-table layui-table layui-form">
    <colgroup>
        <col width="50%">
        <col>
        <col>
        <col>
        <col>
        <col width="10%">
    </colgroup>
    <thead>
    <tr>
        <th>课程</th>
        <th>课时数</th>
        <th>用户数</th>
        <th>价格</th>
        <th>发布</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>
                <p>标题：<a href="{{ url({'for':'admin.course.chapters','id':item.id}) }}">{{ item.title }}</a> {{ model_info(item.model) }}</p>
                <p>{{ category_info(item.category) }}&nbsp;&nbsp;{{ teacher_info(item.teacher) }}&nbsp;&nbsp;{{ level_info(item.level) }}</p>
            </td>
            <td>
                <a href="{{ url({'for':'admin.course.chapters','id':item.id}) }}">
                    <span class="layui-badge layui-bg-green">{{ item.lesson_count }}</span>
                </a>
            </td>
            <td>
                <a href="{{ url({'for':'admin.student.list'},{'course_id':item.id}) }}">
                    <span class="layui-badge layui-bg-green">{{ item.user_count }}</span>
                </a>
            </td>
            <td>
                <p>市场：￥{{ item.market_price }}</p>
                <p>会员：￥{{ item.vip_price }}</p>
            </td>
            <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ url({'for':'admin.course.update','id':item.id}) }}" {% if item.published == 1 %}checked{% endif %}></td>
            <td align="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                    <ul>
                        <li><a href="{{ url({'for':'admin.course.edit','id':item.id}) }}">编辑课程</a></li>
                        {% if item.deleted == 0 %}
                            <li><a href="javascript:" class="kg-delete" data-url="{{ url({'for':'admin.course.delete','id':item.id}) }}">删除课程</a></li>
                        {% else %}
                            <li><a href="javascript:" class="kg-restore" data-url="{{ url({'for':'admin.course.restore','id':item.id}) }}">还原课程</a></li>
                        {% endif %}
                        <hr>
                        <li><a href="{{ url({'for':'admin.course.chapters','id':item.id}) }}">章节管理</a></li>
                        <li><a href="{{ url({'for':'admin.student.list'},{'course_id':item.id}) }}">学员管理</a></li>
                        <hr>
                        <li><a href="{{ url({'for':'admin.review.list'}) }}?course_id={{ item.id }}">评价管理</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ partial('partials/pager') }}