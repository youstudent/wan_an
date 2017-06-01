<h2 style="color: #3C8DBC">统计中心</h2>
<div class="cf well form-search" style="height: 68px;">
    <form method="get" class="form-horizontal" action="searchs">
        <div class="fl">
            <div class="btn-group">
                <input name="start" class="form-control" onclick="WdatePicker()" value="" placeholder="开始时间" type="text">
            </div>
            <div class="btn-group">
                <input name="end" class="form-control" onclick="WdatePicker()" value="" placeholder="结束时间" type="text">
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">查询</button>
            </div>
        </div>
    </form>
</div>
<table class="table table-hover table-bordered table-striped" >
  <thead>
    <tr class="danger" >
      <th>总业绩</th>
      <th>分享收益</th>
      <th>绩效收益</th>
      <th>财务总支出</th>
      <th>结余</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?=$count?></td>
      <td>44454</td>
      <td>1000</td>
      <td><?=$c?></td>
      <td>-525</td>
    </tr>
  </tbody>
</table>