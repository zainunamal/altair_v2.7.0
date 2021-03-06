<?php

    function curl_html($url,$out) {
        $cmd = "curl '$url' -o $out";
        exec($cmd);
    }

    for($i=0;$i<2;$i++) {

        $server = 'http://altair_html_rtl.local/';
        $app_v = json_decode(file_get_contents('./package.json'),true);
        $app_version = $app_v['version'];

        if($i==0) {
            $release = "&release=1";
            $folder = '../../_release/'.$app_version.'/demo/_demo_site_rtl/html/';
        } else {
            $release = '';
            $folder = '../../_release/'.$app_version.'/altair_v'.$app_version.'/admin_rtl/html/dist/';
        }

        if (is_dir($folder)) {
            array_map('unlink', glob($folder."*.html"));
        }

        curl_html($server."login.php?generate".$release,$folder."login.html");
        curl_html($server."error_404.php?generate".$release,$folder."error_404.html");
        curl_html($server."error_500.php?generate".$release,$folder."error_500.html");

        $files = glob(dirname(__FILE__).'/../../php/views/*.php', GLOB_BRACE);
        foreach($files as $file) {
            $file = str_replace(dirname(__FILE__).'/../../php/views/', '', $file);
            $file = str_replace('.php', '', $file);
            curl_html($server."index.php?generate".$release."&page=".$file, $folder.$file.".html");
        }
    }