{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md8">
                {{ partial('index/main_global_stat') }}
                {{ partial('index/main_mod_stat') }}
                {{ partial('index/main_report_stat') }}
                {{ partial('index/main_app_trend') }}
            </div>
            <div class="layui-col-md4">
                {{ partial('index/main_today_stat') }}
                {{ partial('index/main_app_info') }}
                {{ partial('index/main_server_info') }}
                {{ partial('index/main_team_info') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_css %}

    <style>
        .kg-body {
            padding: 15px 0;
            background: #f2f2f2;
        }
    </style>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'layer', 'helper'], function () {

            var $ = layui.jquery;
            var helper = layui.helper;

            var $appTrend = $('#app-trend');
            helper.ajaxLoadHtml($appTrend.data('url'), $appTrend.attr('id'));

        });

    </script>

{% endblock %}