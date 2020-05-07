<!DOCTYPE html>
<html lang="zh-Hans-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>视频直播</title>
    <script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.2.js"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div id="player"></div>
</body>
</html>

<script>

    var flvPullUrls = '{{ flv_pull_urls|json_encode }}';
    var flv = JSON.parse(flvPullUrls);
    var config = {
        flv: flv.od,
        flv_sd: flv.sd,
        flv_hd: flv.hd,
        live: true,
        h5_flv: true,
        autoplay: true,
        clarity: 'hd',
        width: 720,
        height: 405
    };

    var player = new TcPlayer('player', config);

</script>
