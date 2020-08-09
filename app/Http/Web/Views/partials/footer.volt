<div class="layui-main">
    <div class="left">
        <div class="bottom-nav">
            {% for nav in navs.bottom %}
                <a href="{{ nav.url }}" target="{{ nav.target }}">{{ nav.name }}</a>
            {% endfor %}
        </div>
        <div class="copyright">
            <span>{{ settings.copyright }}</span>
            {% if settings.icp_sn %}
                <a href="{{ settings.icp_link }}">{{ settings.icp_sn }}</a>
            {% endif %}
            {% if settings.police_sn %}
                <a href="{{ settings.police_link }}">{{ settings.police_sn }}</a>
            {% endif %}
        </div>
    </div>
    <div class="right"></div>
</div>