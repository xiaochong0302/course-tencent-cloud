<script>

    window.user = {
        id: '{{ auth_user.id }}',
        name: '{{ auth_user.name }}',
        avatar: '{{ auth_user.avatar }}',
        locked: '{{ auth_user.locked }}',
        vip: '{{ auth_user.vip }}'
    };

    window.contact = {
        enabled: '{{ contact_info.enabled }}',
        qq: '{{ contact_info.qq }}',
        wechat: '{{ contact_info.wechat }}',
        toutiao: '{{ contact_info.toutiao }}',
        weibo: '{{ contact_info.weibo }}',
        zhihu: '{{ contact_info.zhihu }}',
        phone: '{{ contact_info.phone }}',
        email: '{{ contact_info.email }}',
        address: '{{ contact_info.address }}'
    };

    window.im = {
        main: {
            title: '{{ im_info.main.title }}',
            upload_img_enabled: '{{ im_info.main.upload_img_enabled }}',
            upload_file_enabled: '{{ im_info.main.upload_file_enabled }}',
            tool_audio_enabled: '{{ im_info.main.tool_audio_enabled }}',
            tool_video_enabled: '{{ im_info.main.tool_video_enabled }}',
            msg_max_length: '{{ im_info.main.msg_max_length }}',
        },
        ws: {
            connect_url: '{{ im_info.ws.connect_url }}',
            ping_interval: '{{ im_info.ws.ping_interval }}'
        }
    };

</script>