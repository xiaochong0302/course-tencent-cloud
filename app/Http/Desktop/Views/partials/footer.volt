<div class="layui-main">
    <div class="left">
        <div class="bottom-nav">
            {% for nav in navs.bottom %}
                <a href="{{ nav.url }}" target="{{ nav.target }}">{{ nav.name }}</a>
            {% endfor %}
        </div>
        <div class="copyright">
            {% if site_info.copyright %}
                <span>&copy; {{ site_info.copyright }}</span>
            {% endif %}
            <a href="{{ app_info.link }}" title="{{ app_info.name }}">Powered by {{ app_info.alias }} {{ app_info.version }}</a>
            {% if site_info.icp_sn %}
                <a href="{{ site_info.icp_link }}">{{ site_info.icp_sn }}</a>
            {% endif %}
            {% if site_info.police_sn %}
                <a href="{{ site_info.police_link }}">{{ site_info.police_sn }}</a>
            {% endif %}
        </div>
    </div>
    <div class="right"></div>
</div>