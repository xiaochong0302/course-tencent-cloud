{{ partial('course/expiry_macro') }}

<table class="kg-table layui-table">
    <colgroup>
        <col>
        <col>
        <col>
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>标题</th>
        <th>课时数</th>
        <th>有效期</th>
        <th>价格</th>
    </tr>
    </thead>
    <tbody>
    {% for item in courses %}
        <tr>
            <td>{{ item.title }}</td>
            <td><span class="layui-badge layui-bg-gray">{{ item.lesson_count }}</span></td>
            <td>{{ study_expiry_info(item.study_expiry) }}</td>
            <td>
                <p>市场价：{{ '￥%0.2f'|format(item.market_price) }}</p>
                <p>会员价：{{ '￥%0.2f'|format(item.vip_price) }}</p>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

<br>

<div class="kg-price-guiding">
    建议市场价：<span class="layui-badge layui-bg-red">￥{{ guiding_price.market_price }}</span>
    &nbsp;&nbsp;
    建议会员价：<span class="layui-badge layui-bg-red">￥{{ guiding_price.vip_price }}</span>
</div>
<br>