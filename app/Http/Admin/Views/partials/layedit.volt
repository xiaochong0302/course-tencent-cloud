<script>

    layui.use(['jquery', 'layedit'], function () {

        var $ = layui.jquery;
        var layedit = layui.layedit;

        layedit.set({
            uploadImage: {url: '/admin/upload/img/content'}
        });

        var index = layedit.build('kg-layedit');

        $('.kg-submit').on('click', function () {
            layedit.sync(index);
        });
    });

</script>