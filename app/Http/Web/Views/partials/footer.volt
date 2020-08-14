<div class="layui-main">
    <div class="left">
        <div class="bottom-nav">
            {% for nav in navs.bottom %}
                <a href="{{ nav.url }}" target="{{ nav.target }}">{{ nav.name }}</a>
            {% endfor %}
        </div>
        <div class="copyright">
            {% if site.copyright %}
                <span>&copy; {{ site.copyright }}</span>
            {% endif %}
            <a href="{{ app_info.link }}" title="{{ app_info.name }}">Powered by {{ app_info.alias }} {{ app_info.version }}</a>
            {% if site.icp_sn %}
                <a href="{{ site.icp_link }}">{{ site.icp_sn }}</a>
            {% endif %}
            {% if site.police_sn %}
                <a href="{{ site.police_link }}">{{ site.police_sn }}</a>
            {% endif %}
        </div>
    </div>
    <div class="right"></div>
</div>