<div class="layui-main">
    <div class="row nav">
        {% for nav in navs.bottom %}
            <a href="{{ nav.url }}" target="{{ nav.target }}">{{ nav.name }}</a>
        {% endfor %}
    </div>
    <div class="row copyright">
        {% if site_info.copyright %}
            <span>&copy; {{ site_info.copyright }}</span>
        {% endif %}
        <a href="{{ app_info.link }}" title="{{ app_info.name }}" target="_blank">Powered by {{ app_info.alias }} {{ app_info.version }}</a>
        {% if site_info.icp_sn %}
            <a href="{{ site_info.icp_link }}" target="_blank">{{ site_info.icp_sn }}</a>
        {% endif %}
        {% if site_info.police_sn %}
            <a href="{{ site_info.police_link }}" target="_blank">{{ site_info.police_sn }}</a>
        {% endif %}
    </div>
    {% if contact_info.enabled == 1 %}
        <div class="row contact">
            {% if contact_info.qq %}
                {% set link_url = 'https://wpa.qq.com/msgrd?v=3&uin=%s&site=qq&menu=yes'|format(contact_info.qq) %}
                <a class="qq" href="{{ link_url }}" title="客服QQ：{{ contact_info.qq }}"><span class="iconfont icon-qq"></span></a>
            {% endif %}
            {% if contact_info.wechat %}
                <a class="wechat" href="javascript:" title="微信公众号"><span class="iconfont icon-wechat"></span></a>
            {% endif %}
            {% if contact_info.toutiao %}
                <a class="toutiao" href="javascript:" title="头条号"><span class="iconfont icon-toutiao"></span></a>
            {% endif %}
            {% if contact_info.weibo %}
                {% set link_url = 'https://weibo.com/u/%s'|format(contact_info.weibo) %}
                <a class="weibo" href="{{ link_url }}" title="微博主页"><span class="iconfont icon-weibo"></span></a>
            {% endif %}
            {% if contact_info.zhihu %}
                {% set link_url = 'https://www.zhihu.com/people/%s'|format(contact_info.zhihu) %}
                <a class="zhihu" href="{{ link_url }}" title="知乎主页"><span class="iconfont icon-zhihu"></span></a>
            {% endif %}
            {% if contact_info.email %}
                {% set link_url = 'mailto:%s'|format(contact_info.email) %}
                <a class="mail" href="{{ link_url }}" title="联系邮箱：{{ contact_info.email }}"><span class="iconfont icon-mail"></span></a>
            {% endif %}
            {% if contact_info.phone %}
                <a class="phone" href="javascript:" title="联系电话：{{ contact_info.phone }}"><span class="iconfont icon-phone"></span></a>
            {% endif %}
            {% if contact_info.address %}
                {% set link_url = 'https://map.baidu.com/search/%s?querytype=s&wd=%s'|format(contact_info.address,contact_info.address) %}
                <a class="location" href="{{ link_url }}" title="联系地址：{{ contact_info.address }}"><span class="iconfont icon-location"></span></a>
            {% endif %}
        </div>
    {% endif %}
</div>