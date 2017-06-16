<button id="reset">清空数据</button>
<?php
$this->title = '高级操作';

$this->registerJs("
    $('#reset').on('click', function(){
        $.get('http://admin.wantu3.cn/member/sys-rest', function(){
            alert('操作成功');
        })
    })
");

?>