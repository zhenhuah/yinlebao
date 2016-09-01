<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'go3c');
?>
<form name="myform"  action="" method="GET">
<input type="hidden" value="content" name="m">
<input type="hidden" value="content" name="c">
<input type="hidden" value="subeditdo" name="a">
<input type="hidden" value="<?php echo $limitInfo['id'];?>" name="id">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<div class="addContent" style="background:#FFF; width:100%;">
<div class="col-1">
<div class="content pad-6">
	<table width="100%" cellspacing="0" class="table_form">
		<tbody>	
		<tr>
		  <th width="80">字母编号ID:</th>
		  <td><?php echo $limitInfo['id'];?></td>
		</tr>
		<tr>
		  <th width="80">字幕语言:</th>
		  <td>		
		  <select name="language" id="language">
		  <option value="0">请选择</option>	
		  <option <?php if ($limitInfo['language'] == '中文') echo 'selected'; ?> value="中文">中文</option>
		  <option <?php if ($limitInfo['language'] == '英文') echo 'selected'; ?> value="英文">英文</option>	  	
		  </select>&nbsp;<span id="ad_position_error" style="display:none"><font color="red">请选择语言类型</font> 
		</td>
		</tr>
		<tr>
		  <th width="90">字幕类型 </th>
		  <td>
			<select name="format" id="format">
		 	<option value="0">请选择</option>	
		  	<option <?php if ($limitInfo['format'] == 'srt') echo 'selected'; ?> value="srt">srt</option>
		  	<option <?php if ($limitInfo['format'] == 'txt') echo 'selected'; ?> value="txt">txt</option>
		  	</select>
		  </td>
		</tr>
		<tr>
		  <th width="90">视频来源类型 </th>
		  <td>
			<select name="source" id="source">
		 	<option value="0">请选择</option>	
		  	<option <?php if ($limitInfo['source'] == 'pptv') echo 'selected'; ?> value="pptv">pptv</option>
		  	<option <?php if ($limitInfo['source'] == 'qq') echo 'selected'; ?> value="qq">qq</option>
		  	</select>
		  </td>
		</tr>
		<tr>
		<tr id="view_ImgText" >
		  <th width="90"><font color="red" id="data_2_1"  class="data">*</font> 文件链接 </th>
		  <td>
		  <input type="hidden" name="imgType" id="ImgText" value="ImgText">
		  <input type="hidden" value="<?php echo TASK_IMG_PATH;?>" name="webSiteUrl" id="webSiteUrl" >
		  <input type="text" value="<?php echo $limitInfo['url'];?>" name="url" id="url" size="25" >
		  </td>
		</tr>
			<tr>
			<th width="90">文件上传</th>
		 	 <td>
			<span id="content">
			<img id="loading" src="<?php echo APP_PATH;?>statics/images/loading.gif" style="display:none;">
			<input  type="file" size="1" name="fileToUpload" id="fileToUpload" class="input" onchange="checkUpload(this);">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();" style="display:none;">Upload</button>
			</span></br>
			请输入一个文件链接或上传一个本地文件!
			<span id="ad_imgUrl_error" style="display:none;"><font color="red">请选择上传图片</font></span>
		  &nbsp;&nbsp;<span id="ad_imgUrl_error" style="display:none;"><font color="red">请设置图片地址</font></span>
		  </td>
		</tr>
		<tr id="view_ImgFile" style="display:none;">
		  <th width="90"><font color="red" id="data_2_1"  class="data" style="display:none">*</font>图片 链接</th>
		  <td>
			<input type="file" value="" name="ad_imgUrlFile" id="ad_imgUrlFile" size="25" >
		  &nbsp;&nbsp;<span id="ad_imgUrlFile_error" style="display:none;"><font color="red">请选择上传图片或输入地址</font></span>
		  </td>
		</tr>
		<tr>
		<th width="90">文件检查</th>
		  <td>
		  <a href="###" style="display:none;" id="viewImgUrl"><font color="blue">浏览</font></a>
		  &nbsp;&nbsp;<input type="checkbox" id="regtype" name="regtype" style="display:none;"/><font color="red">*</font>有效
		  <span id="ad_regtype_error" style="display:none;"><font color="red">请勾选确认图片正确!</font></span>
		  </td>
		</tr>
		<tr>
		  <th width="90">视频时长</th>
		  <td>
		  <input type="text" value="<?php echo $limitInfo['run_time'];?>" name="run_time" id="run_time" size="25" >
		  </td>
		</tr>
		</tbody>
	</table>
	</div>
</div>      
</div>
<div class="bk10"></div>
<div  style="float:right">	
	<input type="submit" class="button" value="保存" name="dosubmit">
</div> 
</form>
</body>
</html>
