{% extends 'templates/main.volt' %}

{% block content %}

    <table class="kg-table layui-table">
        <tr>
            <th>国家</th>
            <th>省份</th>
            <th>城市</th>
            <th>运营商</th>
        </tr>
        <tr>
            <td>{{ region.country }}</td>
            <td>{{ region.province }}</td>
            <td>{{ region.city }}</td>
            <td>{{ region.isp }}</td>
        </tr>
    </table>

{% endblock %}