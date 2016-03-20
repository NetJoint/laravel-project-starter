(function () {

    var URL = window.UEDITOR_HOME_URL || getUEBasePath();

    window.UEDITOR_CONFIG = {
        UEDITOR_HOME_URL: URL

                // 服务器统一请求接口路径
        , serverUrl: "/api/ueditor"

                //工具栏上的所有的功能按钮和下拉框，可以在new编辑器的实例时选择自己需要的从新定义
                /*, toolbars: [[
                 'fullscreen', 'source', '|', 'undo', 'redo', '|',
                 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                 'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                 'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                 'directionalityltr', 'directionalityrtl', 'indent', '|',
                 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                 'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                 'simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
                 'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
                 'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
                 'print', 'preview', 'searchreplace', 'help', 'drafts'
                 ]]*/
        , toolbars: [[
                'undo', 'redo', '|',
                //文本
                'bold', 'italic', 'underline', '|', 'paragraph', 'fontfamily', 'fontsize', 'forecolor', 'backgroundcolor', 'insertorderedlist', 'insertunorderedlist', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                //链接和图片
                'link', 'unlink', 'insertimage', 'attachment', '|',
                //表格
                'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', '|'
                        , 'source','fullscreen'
            ]]
                //,theme: 'default'
                //,themePath: URL + "themes/"
        , zIndex: 9000     //编辑器层级的基数,默认是900

                //如果自定义，最好给p标签如下的行高，要不输入中文时，会有跳动感
                //,initialStyle:'p{line-height:1em}'//编辑器层级的基数,可以用来改变字体等

                //,initialFrameWidth:1000  //初始化编辑器宽度,默认1000
        , initialFrameHeight: 200  //初始化编辑器高度,默认320

                //启用自动保存
        ,enableAutoSave: false
                //自动保存间隔时间， 单位ms
        , saveInterval: 600000

                //是否启用元素路径，默认是显示
        , elementPathEnabled: false
                //wordCount
                //,wordCount:true          //是否开启字数统计
        , maximumWords: 100000       //允许的最大字符数        
                //,wordCountMsg:''   //当前已输入 {#count} 个字符，您还可以输入{#leave} 个字符
                //,wordOverFlowMsg:''    //<span style="color:red;">你输入的字符个数已经超出最大允许值，服务器可能会拒绝保存！</span>

                // 是否自动长高,默认true
        , autoHeightEnabled: false
        
        // 远程图片本地化,默认true
        ,catchRemoteImageEnable: false
        ,catcherActionName: "catchimage"
        ,catcherFieldName: "url"

                /* 上传图片配置项 */
        , "imageActionName": "uploadimage", /* 执行上传图片的action名称 */
        "imageFieldName": "file", /* 提交的图片表单名称 */
        "imageMaxSize": 20480000, /* 上传大小限制，单位B */
        "imageAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
        "imageCompressEnable": true, /* 是否压缩图片,默认是true */
        "imageCompressBorder": 1200, /* 图片压缩最长边限制 */
        "imageInsertAlign": "none", /* 插入的图片浮动方式 */
        "imageUrlPrefix": "", /* 图片访问路径前缀 */
        "imagePathFormat": "", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
        /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
        /* {time} 会替换成时间戳 */
        /* {yyyy} 会替换成四位年份 */
        /* {yy} 会替换成两位年份 */
        /* {mm} 会替换成两位月份 */
        /* {dd} 会替换成两位日期 */
        /* {hh} 会替换成两位小时 */
        /* {ii} 会替换成两位分钟 */
        /* {ss} 会替换成两位秒 */
        /* 非法字符 \ : * ? " < > | */
        /* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */

        /* 上传视频配置 */
        "videoActionName": "uploadvideo", /* 执行上传视频的action名称 */
        "videoFieldName": "upfile", /* 提交的视频表单名称 */
        "videoPathFormat": "/ueditor/php/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "videoUrlPrefix": "", /* 视频访问路径前缀 */
        "videoMaxSize": 102400000, /* 上传大小限制，单位B，默认100MB */
        "videoAllowFiles": [
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

        /* 上传文件配置 */
        "fileActionName": "uploadfile", /* controller里,执行上传视频的action名称 */
        "fileFieldName": "file", /* 提交的文件表单名称 */
        "filePathFormat": "", /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "fileUrlPrefix": "", /* 文件访问路径前缀 */
        "fileMaxSize": 51200000, /* 上传大小限制，单位B，默认50MB */
        "fileAllowFiles": [
            ".png", ".jpg", ".jpeg", ".gif", ".bmp",
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
            ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
            ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
        ], /* 上传文件格式显示 */

        /* 列出指定目录下的图片 */
        "imageManagerActionName": "listimage", /* 执行图片管理的action名称 */
        "imageManagerListPath": "", /* 指定要列出图片的目录 */
        "imageManagerListSize": 20, /* 每次列出文件数量 */
        "imageManagerUrlPrefix": "", /* 图片访问路径前缀 */
        "imageManagerInsertAlign": "none", /* 插入的图片浮动方式 */
        "imageManagerAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */

        /* 列出指定目录下的文件 */
        "fileManagerActionName": "listfile", /* 执行文件管理的action名称 */
        "fileManagerListPath": "", /* 指定要列出文件的目录 */
        "fileManagerUrlPrefix": "", /* 文件访问路径前缀 */
        "fileManagerListSize": 20, /* 每次列出文件数量 */
        "fileManagerAllowFiles": [
            ".png", ".jpg", ".jpeg", ".gif", ".bmp",
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
            ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
            ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
        ] /* 列出的文件类型 */

    };

    function getUEBasePath(docUrl, confUrl) {

        return getBasePath(docUrl || self.document.URL || self.location.href, confUrl || getConfigFilePath());

    }

    function getConfigFilePath() {

        var configPath = document.getElementsByTagName('script');

        return configPath[ configPath.length - 1 ].src;

    }

    function getBasePath(docUrl, confUrl) {

        var basePath = confUrl;


        if (/^(\/|\\\\)/.test(confUrl)) {

            basePath = /^.+?\w(\/|\\\\)/.exec(docUrl)[0] + confUrl.replace(/^(\/|\\\\)/, '');

        } else if (!/^[a-z]+:/i.test(confUrl)) {

            docUrl = docUrl.split("#")[0].split("?")[0].replace(/[^\\\/]+$/, '');

            basePath = docUrl + "" + confUrl;

        }

        return optimizationPath(basePath);

    }

    function optimizationPath(path) {

        var protocol = /^[a-z]+:\/\//.exec(path)[ 0 ],
                tmp = null,
                res = [];

        path = path.replace(protocol, "").split("?")[0].split("#")[0];

        path = path.replace(/\\/g, '/').split(/\//);

        path[ path.length - 1 ] = "";

        while (path.length) {

            if ((tmp = path.shift()) === "..") {
                res.pop();
            } else if (tmp !== ".") {
                res.push(tmp);
            }

        }

        return protocol + res.join("/");

    }

    window.UE = {
        getUEBasePath: getUEBasePath
    };

})();
