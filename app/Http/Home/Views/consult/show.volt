{% extends 'templates/layer.volt' %}

{% block content %}

    {% set consult.answer = consult.answer ? consult.answer : '请耐心等待回复吧' %}

    <div class="consult-info">
        {% if consult.course.id is defined %}
            <div class="item">
                <div class="label">课程：</div>
                <div class="title">{{ consult.course.title }}</div>
            </div>
        {% endif %}
        {% if consult.chapter.id is defined %}
            <div class="item">
                <div class="label">章节：</div>
                <div class="title">{{ consult.chapter.title }}</div>
            </div>
        {% endif %}
        <div class="item">
            <div class="label">咨询：</div>
            <div class="content">{{ consult.question }}</div>
        </div>
        <div class="item">
            <div class="label">回复：</div>
            <div class="content">{{ consult.answer }}</div>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.iframeAuto(index);
    </script>

{% endblock %}