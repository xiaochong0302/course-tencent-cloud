<script>

    window.user = {
        id: '{{ auth_user.id }}',
        name: '{{ auth_user.name }}',
        avatar: '{{ auth_user.avatar }}',
        locked: '{{ auth_user.locked }}',
        vip: '{{ auth_user.vip }}'
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
        cs: {
            enabled: '{{ im_info.cs.enabled }}'
        },
        ws: {
            connect_url: '{{ im_info.ws.connect_url }}',
            ping_interval: '{{ im_info.ws.ping_interval }}'
        }
    };

</script>