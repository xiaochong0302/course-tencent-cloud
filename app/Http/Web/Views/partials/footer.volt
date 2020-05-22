<div class="layui-main">
    <div class="left">
        <div class="bottom-nav">
            {% for nav in site_navs.bottom %}
                <a href="{{ nav.url }}" target="{{ nav.target }}">{{ nav.name }}</a>
            {% endfor %}
        </div>
        <div class="copyright">
            <span>{{ site_settings.copyright }}</span>
            {% if site_settings.icp_sn %}
                <a href="{{ site_settings.icp_link }}">{{ site_settings.icp_sn }}</a>
            {% endif %}
            {% if site_settings.police_sn %}
                <a href="{{ site_settings.police_link }}">{{ site_settings.police_sn }}</a>
            {% endif %}
        </div>
    </div>
    <div class="right"></div>
</div>