<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<link href="<?php echo CSS_PATH?>dialog.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>dialog.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css"/>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto 20px"></div>

<div class="pad-lr-10">
<form name="myform"  action="" method="GET">
<input type="hidden" value="go3c" name="m">
<input type="hidden" value="tvuser" name="c">
<input type="hidden" value="install" name="a">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
		   设备类型：<select name="device_type">
           		 <option value=''>选择</option>
				<option value='101' <?php if($device_type=='101') echo 'selected';?>>apple tv</option>
				<option value='102' <?php if($device_type=='102') echo 'selected';?>>atv(Android stb)</option>
				<option value='103' <?php if($device_type=='103') echo 'selected';?>>ltv(Linux stb)</option>
				<option value='201' <?php if($device_type=='201') echo 'selected';?>>ipad</option>
				<option value='202' <?php if($device_type=='202') echo 'selected';?>>apad</option>
				<option value='203' <?php if($device_type=='203') echo 'selected';?>>winpad</option>
				<option value='301' <?php if($device_type=='301') echo 'selected';?>>iphone</option>
				<option value='302' <?php if($device_type=='302') echo 'selected';?>>aphone</option>
				<option value='303' <?php if($device_type=='303') echo 'selected';?>>win8 phone</option>
				<option value='401' <?php if($device_type=='401') echo 'selected';?>>pc web</option>
				<option value='402' <?php if($device_type=='402') echo 'selected';?>>pc client</option>
				<option value='403' <?php if($device_type=='403') echo 'selected';?>>win8 pc</option>
			</select>&nbsp;
		    开始日期：<input type="text" value="<?php echo $starttime;?>" name="starttime" id="starttime" size="10" class="date" readonly>	
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "starttime",
				trigger    : "starttime",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script> &nbsp;
			结束日期：<input type="text" value="<?php echo $endtime;?>" name="endtime" id="endtime" size="10" class="date" readonly>	
			<script type="text/javascript">
				Calendar.setup({
				weekNumbers: true,
				inputField : "endtime",
				trigger    : "endtime",
				dateFormat: "%Y-%m-%d",
				showTime: false,
				minuteStep: 1,
				onSelect   : function() {this.hide();}
				});
			</script> &nbsp;
			显示：<input name="perpage" type="text" value="<?php echo $perpage;?>" class="input-text" size="3" />个
			<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
		</div>
		</td>
		</tr>
    </tbody>
</table>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
			<th width="15" align="center">设备编号</th>
			<th width="15" align="center">设备类型</th>
			<th width="10" align="center">客户端版本</th>
			<th width='5' align="center">ip</th>
			<th width='10' align="center">区域</th>
			<th width='5' align="center">操作时间</th>
            </tr>
        </thead>
    <tbody>
    <?php if(is_array($data)){foreach($data as $insyall){?>   
	<tr>
	<td align="center"><?php echo $insyall['device_code']?></td>
	<td align="center"><?php echo devicetype($insyall['device_type'])?></td>
	<td align="center"><?php echo $insyall['client_version']?></td>
	<td align="center"><?php echo $insyall['ip']?></td>
	<td align="center"><?php echo $insyall['area']?></td>
	<td align="center"><?php echo $insyall['operation_time']?></td>
	</tr>
	<?php }} ?>
	</tbody>
    </table>
	</div>
	<div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>

<script type="text/javascript" src="<?php echo JS_PATH?>jquery.highcharts.js"></script>
		<script type="text/javascript">

var cate = '<?php echo $dataforchart?>';
cate = $.parseJSON(cate);
//console.log(cate);
var dateArr = new Array();
var appletvArr = new Array();
var atvArr = new Array();
var ltvArr = new Array();
var ipadArr = new Array();
var apadArr = new Array();
var winpadArr = new Array();
var iphoneArr = new Array();
var aphoneArr = new Array();
var win8phoneArr = new Array();
var pcwebArr = new Array();
var pcclientArr = new Array();
var win8pcArr = new Array();
for (var key in cate) {
	dateArr.push(key);
	//tv
	if (cate[key].appletv != undefined)
		appletvArr.push(cate[key].appletv);
	else 
		appletvArr.push(0);
	if (cate[key].atv != undefined)
		atvArr.push(cate[key].atv);
	else 
		atvArr.push(0);
	if (cate[key].ltv != undefined)
		ltvArr.push(cate[key].ltv);
	else 
		ltvArr.push(0);
	//pad
	if (cate[key].ipad != undefined)
		ipadArr.push(cate[key].ipad);
	else 
		ipadArr.push(0);
	if (cate[key].apad != undefined)
		apadArr.push(cate[key].apad);
	else 
		apadArr.push(0);
	if (cate[key].winpad != undefined)
		winpadArr.push(cate[key].winpad);
	else 
		winpadArr.push(0);
	//phone
	if (cate[key].iphone != undefined)
		iphoneArr.push(cate[key].iphone);
	else 
		iphoneArr.push(0);
	if (cate[key].aphone != undefined)
		aphoneArr.push(cate[key].aphone);
	else 
		aphoneArr.push(0);
	if (cate[key].win8phone != undefined)
		win8phoneArr.push(cate[key].win8phone);
	else 
		win8phoneArr.push(0);
	//web
	if (cate[key].pcweb != undefined)
		pcwebArr.push(cate[key].pcweb);
	else 
		pcwebArr.push(0);
	if (cate[key].pcclient != undefined)
		pcclientArr.push(cate[key].pcclient);
	else 
		pcclientArr.push(0);
	if (cate[key].win8pc != undefined)
		win8pcArr.push(cate[key].win8pc);
	else 
		win8pcArr.push(0);
}  
//console.log(pcwebArr);

$(function () {
        $('#container').highcharts({
            title: {
                text: '安装卸载统计',
                x: -20 //center
            },
            subtitle: {
                text: '来源：bigtv.com.cn',
                x: -20
            },
            xAxis: {
                categories: dateArr
            },
            yAxis: {
                title: {
                    text: '次数 (次)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '次'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'apple tv',
                data: appletvArr
            }, {
                name: 'atv',
                data: atvArr
            }, {
                name: 'ltv',
                data: ltvArr
            }, {
                name: 'ipad',
                data: ipadArr
            }, {
                name: 'apad',
                data: apadArr
            }, {
                name: 'winpad',
                data: winpadArr
            }, {
                name: 'iphone',
                data: iphoneArr
            }, {
                name: 'aphone',
                data: aphoneArr
            }, {
                name: 'win8 phone',
                data: win8phoneArr
            }, {
                name: 'pc web',
                data: pcwebArr
            }, {
                name: 'pc client',
                data: pcclientArr
            }, {
                name: 'win8 pc',
                data: win8pcArr
            }]
        });
    });
    

		</script>
<script src="<?php echo JS_PATH?>highcharts.js"></script>
<script src="<?php echo JS_PATH?>exporting.js"></script>

</body>
</html>
