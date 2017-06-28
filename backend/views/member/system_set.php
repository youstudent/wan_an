<button id="reset">清空数据</button>
<?php
$this->title = '高级操作';

$this->registerJs("
    $('#reset').on('click', function(){
        $.get('http://admin.wantu3.cn/member/sys-rest', function(){
            alert('你的数据已经都没有了,并且不可回滚,你你清楚这是个危险操作，你还是执行了他。');
        })
    })
");

$this->registerJs("alert('这是一个危险的操作，你应该明白你在做什么!');");
?>