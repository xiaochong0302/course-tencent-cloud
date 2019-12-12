<div class="layui-tab layui-tab-brief">
    <ul class="layui-tab-title kg-tab-title">
        <li class="layui-this">推流配置</li>
        <li>拉流配置</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            {{ partial('config/live_push') }}
        </div>
        <div class="layui-tab-item">
            {{ partial('config/live_pull') }}
        </div>
    </div>
</div>