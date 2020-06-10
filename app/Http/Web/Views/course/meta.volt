<div class="cover">
    <img src="{{ course.cover }}" alt="{{ course.title|e }}">
</div>

<div class="info">
    {% if course.model == 'vod' %}
        <p>课程时长 <span>{{ course.attrs.duration|total_duration }}</span></p>
    {% elseif course.model == 'live' %}
        <p>直播时间 <span>{{ course.attrs.start_date }} ~ {{ course.attrs.end_date }}</span></p>
    {% endif %}
    {% if course.market_price > 0 %}
        <p>
            学习期限 <span class="expiry">{{ course.study_expiry }}个月</span>
            退款期限 <span class="expiry">{{ course.refund_expiry }}天</span>
        </p>
    {% endif %}
    <p>
        {% if course.market_price > 0 %}
            市场价格 <span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>
        {% else %}
            市场价格 <span class="free">免费</span>
        {% endif %}
        {% if course.vip_price > 0 %}
            会员价格 <span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span>
        {% else %}
            会员价格 <span class="free">免费</span>
        {% endif %}
    </p>
    <p>
        难度 <span>{{ level_info(course.level) }}</span>
        课时 <span>{{ course.lesson_count }}</span>
        学员 <span>{{ course.user_count }}</span>
        收藏 <span>{{ course.favorite_count }}</span>
        评分 <span>{{ course.rating }}</span>
    </p>
    <div class="share">
        <a class="favorite" href="javascript:"><i class="layui-icon layui-icon-heart"></i></a>
        <a class="mobile" href="javascript:"><i class="layui-icon layui-icon-cellphone"></i></a>
        <a class="weibo" href="javascript:"><i class="layui-icon layui-icon-login-weibo"></i></a>
        <a class="wechat" href="javascript:"><i class="layui-icon layui-icon-login-wechat"></i></a>
        <a class="qq" href="javascript:"><i class="layui-icon layui-icon-login-qq"></i></a>
    </div>
</div>