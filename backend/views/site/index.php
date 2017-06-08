<?php
?>
<div class="form-group form-group-lg captcha field-userform-verifycode required has-error">
    <label class="control-label" for="userform-verifycode">验证</label>
    <div class="row">
        <div class="col-md-5 col-xs-5">
            <input type="text" class="form-control" name="UserForm[verifyCode]">
        </div>
        <div class="col-md-3 col-xs-4 mr20">
            <img id="imgVerifyCode" onclick="javascript:changeVerifyCode();" alt="验证码">
        </div>
        <div class="col-md-3 col-xs-3">
            <a style="cursor:pointer;vertical-align: middle;line-height: 40px;" onclick="alert(1)">换一张</a>
        </div>
    </div>

    <p class="help-block help-block-error"><?php echo ($_POST && isset($model->errors['verifyCode'][0])) ? $model->errors['verifyCode'][0] : ''; ?></p>
</div>
<?php
$js = <<<JS
         $(function(){                   //当页面加载的时候
        changeVerifyCode();         //刷新或者重新加载一个验证码
        
        //清除输入框里面的数据
        $("#clear_form").click(function(){
        $("input").val('');
        });
         function changeVerifyCode() {
        $.ajax({
        url: "/site/captcha?refresh",
        dataType: 'json',
        cache: false,
        success: function(data) {
        $("#imgVerifyCode").attr('src', data['url']);
        }
        });
        }
        });
        
        //更改或者重新加载验证码
       

JS;
$this->registerJs($js);
?>
