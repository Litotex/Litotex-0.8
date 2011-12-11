{include file=$HEADER}
<div id="successDiv" style="display:none;"></div>
<div id="errorDiv" style="display:none;"></div>
<div class="acp_users">
	
	<div class="litotex_acp_tab" id="acp_buildings_menue">
	
		<ul>
			<li>
				<a href="index.php?package=acp_buildings&action=list">
					<span>{#buildings_building_list#}</span>
				</a>
			</li>
			<li>
				<a href="index.php?package=acp_buildings&action=new">
					<span>{#buildings_add_building#}</span> 
				</a>
			</li>
		</ul>
	</div>
</div>

{include file=$FOOTER}