{% extends 'templates/layer.volt' %}

{% block content %}

    <div>
        <table class="layui-table">
            <colgroup>
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>标题</th>
                <th>学员数</th>
                <th>价格</th>
            </tr>
            </thead>
            <tbody>
            {% for item in courses %}
                {% set course_url = url({'for':'home.course.show','id':item.id}) %}
                <tr>
                    <td><a href="{{ course_url }}" target="_blank">{{ item.title }}</a></td>
                    <td>{{ item.user_count }}</td>
                    <td class="red">{{ '￥%0.2f'|format(item.market_price) }}</td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>

{% endblock %}