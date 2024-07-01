{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro connect_provider(item) %}
        {% if item.provider == 1 %}
            <i class="layui-icon layui-icon-login-qq login-qq"></i>
        {% elseif item.provider == 2 %}
            <i class="layui-icon layui-icon-login-wechat login-wechat"></i>
        {% elseif item.provider == 3 %}
            <i class="layui-icon layui-icon-login-weibo login-weibo"></i>
        {% endif %}
    {%- endmacro %}

    {%- macro connect_user(item) %}
        {% if item.open_avatar %}
            <span class="open-avatar"><img src="{{ item.open_avatar }}" alt="{{ item.open_name }}"></span>
        {% endif %}
        <span class="open-name">{{ item.open_name }}</span>
    {%- endmacro %}

    {% set edit_pwd_url = url({'for':'home.uc.account'},{'type':'password'}) %}
    {% set edit_phone_url = url({'for':'home.uc.account'},{'type':'phone'}) %}
    {% set edit_email_url = url({'for':'home.uc.account'},{'type':'email'}) %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                {% if oauth_provider.wechat.enabled == 1 %}
                    <div class="section">
                        <div class="my-nav">
                            <span class="title">关注订阅</span>
                        </div>
                        <div class="wechat-scan-box">
                            {% if wechat_oa_connected == 0 %}
                                <div class="qrcode"></div>
                                <div class="tips">关注官方公众号，订阅系统重要通知</div>
                            {% else %}
                                <div class="tips">你已经关注官方公众号</div>
                            {% endif %}
                        </div>
                    </div>
                {% endif %}
                <div class="section">
                    <div class="my-nav">
                        <span class="title">账号安全</span>
                    </div>
                    <div class="security-item-list">
                        <div class="security-item">
                            <div class="info">
                                <span class="icon"><i class="layui-icon layui-icon-password"></i></span>
                                <span class="title">登录密码</span>
                                <span class="summary">经常更改密码有助于保护您的帐号安全</span>
                            </div>
                            <div class="action">
                                <a class="layui-btn layui-btn-sm btn-edit-pwd" href="{{ edit_pwd_url }}">修改</a>
                            </div>
                        </div>
                        <div class="security-item">
                            <div class="info">
                                <span class="icon"><i class="layui-icon layui-icon-cellphone"></i></span>
                                <span class="title">手机绑定</span>
                                {% if account.phone %}
                                    <span class="summary">已绑定手机：{{ account.phone|anonymous }}</span>
                                {% else %}
                                    <span class="summary">可用于登录和重置密码</span>
                                {% endif %}
                            </div>
                            <div class="action">
                                <a class="layui-btn layui-btn-sm btn-edit-phone" href="{{ edit_phone_url }}">绑定</a>
                            </div>
                        </div>
                        <div class="security-item">
                            <div class="info">
                                <span class="icon"><i class="layui-icon layui-icon-email"></i></span>
                                <span class="title">邮箱绑定</span>
                                {% if account.email %}
                                    <span class="summary">已绑定邮箱：{{ account.email|anonymous }}</span>
                                {% else %}
                                    <span class="summary">可用于登录和重置密码</span>
                                {% endif %}
                            </div>
                            <div class="action">
                                <a class="layui-btn layui-btn-sm btn-edit-email" href="{{ edit_email_url }}">绑定</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="my-nav">
                        <span class="title">开放登录</span>
                    </div>
                    {% if connects|length > 0 %}
                        <div class="connect-tips">已经绑定的第三方帐号</div>
                        <div class="connect-list">
                            <table class="layui-table" lay-skin="line">
                                <tr>
                                    <td>提供方</td>
                                    <td>用户信息</td>
                                    <td>最后登录</td>
                                    <td width="15%">操作</td>
                                </tr>
                                {% for connect in connects %}
                                    {% set url = url({'for':'home.uc.unconnect','id':connect.id}) %}
                                    {% set time = connect.update_time > 0 ? connect.update_time : connect.create_time %}
                                    <tr>
                                        <td>{{ connect_provider(connect) }}</td>
                                        <td>{{ connect_user(connect) }}</td>
                                        <td>{{ date('Y-m-d H:i',time) }}</td>
                                        <td><a class="layui-btn layui-btn-danger layui-btn-sm kg-delete" href="javascript:" data-url="{{ url }}" data-tips="确定要解除绑定吗？">解绑</a></td>
                                    </tr>
                                {% endfor %}
                            </table>
                        </div>
                    {% endif %}
                    <div class="connect-tips">支持绑定的第三方帐号</div>
                    <div class="oauth-list">
                        {% if oauth_provider.qq.enabled == 1 %}
                            <a class="layui-icon layui-icon-login-qq login-qq" href="{{ url({'for':'home.oauth.qq'}) }}"></a>
                        {% endif %}
                        {% if oauth_provider.weixin.enabled == 1 %}
                            <a class="layui-icon layui-icon-login-wechat login-wechat" href="{{ url({'for':'home.oauth.weixin'}) }}"></a>
                        {% endif %}
                        {% if oauth_provider.weibo.enabled == 1 %}
                            <a class="layui-icon layui-icon-login-weibo login-weibo" href="{{ url({'for':'home.oauth.weibo'}) }}"></a>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {% if oauth_provider.wechat.enabled == 1 and wechat_oa_connected == 0 %}
        {{ js_include('home/js/wechat.oa.subscribe.js') }}
    {% endif %}

{% endblock %}