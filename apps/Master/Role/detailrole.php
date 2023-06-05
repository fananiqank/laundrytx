<?php
if($_GET['d'] == 'in'){
	require( '../../../funlibs.php' );
	$con=new Database;
	session_start();

	$jenis = 'edit';
} else {
	$jenis = 'simpan';
}
	
	$selrole = $con->select("m_role","*","role_id = '$_GET[p]'");
	foreach ($selrole as $role) {}

?>
<input class="form-control" name="jenis" id="jenis" value="<?=$jenis?>" type="hidden" />
<input class="form-control" name="kode" id="kode" value="<?=$_GET[p]?>" type="hidden" />
<div class="form-group">
	<label class="col-sm-3 control-label">Role Name<span class="required">*</span></label>
	<div class="col-sm-9">
		<input type="text" name="rolename" id="rolename" class="form-control" value="<?=$role[name]?>" onkeypress="return allvalscript(event)" required/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-3 control-label"> Choose role<span class="required">*</span></label>
	<div class="col-sm-9">
		<font size="2px">
			<div class="pre-scrollable">
			<table class="table" width="100%" style="height: 30px;">
                <?php
					$query2=$con->select("m_menus","*","has_child = 1 and status = 1");
					foreach($query2 as $sel2){	
							?> 
				<tr>
					<td  colspan="2" align="center"><b><?=$sel2['name'];?></b>
						<input id="id_menu[]" name="id_menu[]" value="<?=$sel2[menu_id]?>" type="hidden"/>
					</td>
                </tr>
				<tbody>
				<?php 
					$query4=$con->select("m_menus","*","has_child = 0 and object_id = '$sel2[menu_id]' and status = 1","menu_id");
					//echo "select * from m_menus where has_child = 0 and object_id = '$sel2[menu_id]' and status = 1";
					foreach($query4 as $sel4){	
						
				?>
				<tr>
					<td align="center">
				       	<input type="checkbox" class="control-primary" name="hak[]" id="hak[]" value="<?=$sel4[menu_id]?>"
				<?php 	
						$sat=$con->select("m_role a JOIN role_menus b on a.role_id=b.role_id","*","a.role_id='$role[role_id]'");
						foreach($sat as $val){
	
							if($sel4['menu_id'] == $val['menu_id']){echo "checked";}
						} 
				?>		>
					</td>		
					<td><?=$sel4['name'];?></td>
				</tr>
				<?php 	}
						}
					 
				?>
				</tbody>
			</table>
		</div>
	</font>
	</div>
</div>	
<div class="form-group">


</div> 