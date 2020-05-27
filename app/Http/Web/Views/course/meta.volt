<div class="cover">
    <img src="{{ course.cover }}" alt="{{ course.summary|e }}">
</div>

<div class="info">
    <p class="duration">课程时长：{{ course.attrs.duration|total_duration }}</p>
    <p class="expiry">
        <span class="study-expiry">学习期限：<span class="layui-badge-rim">{{ course.study_expiry }}个月</span></span>
        <span class="refund-expiry">退款期限：<span class="layui-badge-rim">{{ course.refund_expiry }}天</span></span>
    </p>
    <p class="price">
        <span class="market-price">市场价格：<span class="layui-badge-rim">￥{{ course.market_price }}</span></span>
        <span class="vip-price">会员价格：<span class="layui-badge-rim">￥{{ course.vip_price }}</span></span>
    </p>
    <p class="stats">
        <span class="user-count">{{ course.user_count }}次学习</span>
        <span class="review-count">{{ course.review_count }}次评价</span>
        <span class="favorite-count">{{ course.favorite_count }}次收藏</span>
    </p>
</div>