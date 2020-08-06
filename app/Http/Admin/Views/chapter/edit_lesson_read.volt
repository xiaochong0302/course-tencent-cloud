<div id="editor"></div>

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.chapter.content','id':chapter.id}) }}">

    <div class="layui-form-item">
        <textarea class="layui-hide" name="content">{{ read.content }}</textarea>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="chapter_id" value="{{ chapter.id }}">
        </div>
    </div>

</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vditor/dist/index.css"/>

<script src="https://cdn.jsdelivr.net/npm/vditor/dist/index.min.js" defer></script>

<script>
    layui.use(['jquery'], function () {

        var $ = layui.jquery;
        var $content = $('textarea[name=content]');
        var vditor = new Vditor('editor', {
            minHeight: 420,
            outline: true,
            tab: "    ",
            resize: {
                enable: true
            },
            cache: {
                enable: false
            },
            preview: {
                markdown: {
                    chinesePunct: true
                }
            },
            counter: {
                enable: true,
                max: 60000
            },
            upload: {
                url: '/admin/upload/img/editor',
                max: 10 * 1024 * 1024,
                accept: 'image/*',
                headers: {
                    'X-Csrf-Token': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (editor, responseText) {
                    console.log(editor, responseText);
                    var json = JSON.parse(responseText);
                    var img = '![](' + json.data.src + ')';
                    vditor.insertValue(img);
                }
            },
            value: $content.val()
        });

        $('.kg-submit').on('click', function () {
            $content.val(vditor.getValue());
        });
    });
</script>