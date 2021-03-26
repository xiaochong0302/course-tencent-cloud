{% extends 'templates/layer.volt' %}

{% block content %}

    <table class="layui-table mt0">
        <colgroup>
            <col>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>课程标题</th>
            <th>销售价格</th>
            <th>购买人次</th>
        </tr>
        </thead>
        <tbody>
        {% for item in courses %}
            {% set course_url = url({'for':'home.course.show','id':item.id}) %}
            <tr>
                <td><a href="{{ course_url }}" target="_blank">{{ item.title }}</a></td>
                <td class="red">{{ '￥%0.2f'|format(item.market_price) }}</td>
                <td>{{ item.user_count }}</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

{% endblock %}