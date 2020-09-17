{% extends 'templates/main.volt' %}

{% block content %}

    {% if chapter.status == 'active' %}
        {{ partial('live/live_active') }}
    {% elseif chapter.status == 'inactive' %}
        {{ partial('live/live_inactive') }}
    {% elseif chapter.status =='forbid' %}
        {{ partial('live/live_forbid') }}
    {% endif %}

{% endblock %}

{% block include_js %}

    {% if chapter.status == 'active' %}
        {{ js_include('https://imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.3.js', false) }}
        {{ js_include('home/js/chapter.live.player.js') }}
        {{ js_include('home/js/chapter.live.chat.js') }}
        {{ js_include('home/js/chapter.action.js') }}
        {{ js_include('home/js/course.share.js') }}
    {% endif %}

{% endblock %}