{ajaxheader modname="Dashboard" filename="dragsort.js" noscriptaculous=true effects=true}

{assign var='isAdmin' value='false'}
{assign var='isModerator' value='false'}
{checkpermission component='Dashboard::' instance='::' level='ACCESS_ADMIN' assign='isAdmin'}
{checkpermission component='Dashboard::' instance='::' level='ACCESS_MODERATE' assign='isModerator'}

<div id="z-dashboardwidgetlist">
<table class="z-dashboardwidgettable">
    <tr>
    <td>
        <div id="widgetsdef">
            {foreach item='userWidget' from=$userWidgets}
                {assign var="position" value=$userWidget.position}
                {assign var="id" value=$userWidget.userWidgetId}
	        <!-- Present only the default widgets -->
	        {if $userWidget->getDefWidget() eq 1}
		    <!-- The user is admin and can sort the default widgets -->
		    {if $isAdmin}
                    <div id="widget_{$id}" class="z-dashboardwidgetcontainer draggable" style="width:{math equation='100/x' x=$modvars.Dashboard.widgets_per_row format='%.0d'}%;">
                        {img modname='Dashboard' src='mouse.png' __alt='Drag to sort' __title='Drag to sort' id="dragicon`$id`" class='z-dragicon'}
			{if $userWidget->getUrl()}
                            <h3>{$userWidget->getTitle()}</h3>
                            <a href="{$userWidget->getUrl()}"></a><br/>
                        {elseif $userWidget->getContent() neq null}
                            <h3>{$userWidget->getTitle()}</h3>
			    {$userWidget->getContent()}
                        {/if}
		    </div>
		    {elseif $isModerator}
                    <div id="widget_{$id}" class="z-dashboardwidgetcontainer" style="width:{math equation='100/x' x=$modvars.Dashboard.widgets_per_row format='%.0d'}%;">
                        {if $userWidget->getUrl()}
                            <h3>{$userWidget->getTitle()}</h3>
                            <a href="{$userWidget->getUrl()}"></a><br/>
                        {elseif $userWidget->getContent() neq null}
                            <h3>{$userWidget->getTitle()}</h3>
                            {$userWidget->getContent()}
                        {/if}
                    </div>
		    {else}
		    <div id="widget_{$id}" class="z-dashboardwidgetcontainer" style="width:{math equation='100/x' x=$modvars.Dashboard.widgets_per_row format='%.0d'}%;">
                        {if $userWidget->getUrl()}
                            <a href="{$userWidget->getUrl()}"></a>
                            <h3>{$userWidget->getTitle()}</h3>
                        {elseif $userWidget->getContent() neq null}
                            <h3>{$userWidget->getTitle()}</h3>
                            {$userWidget->getContent()}
                        {/if}
                    </div>
		    {/if}
	        {/if}
            {/foreach}
        </div>
    </td>
    </tr>
    <tr>
    <td>
    <div id="widgets">
        {foreach item='userWidget' from=$userWidgets}
            {assign var="position" value=$userWidget.position}
            {assign var="id" value=$userWidget.userWidgetId}
            <!-- Present only the default widgets -->
            {if $userWidget->getDefWidget() eq 0}
                <div id="widget_{$id}" class="z-dashboardwidgetcontainer draggable" style="width:{math equation='100/x' x=$modvars.Dashboard.widgets_per_row format='%.0d'}%;">
                    {img modname='Dashboard' src='mouse.png' __alt='Drag to sort' __title='Drag to sort' id="dragicon`$id`" class='z-dragicon'}
		    {if $userWidget->getUrl()}
                        <h3>{$userWidget->getTitle()}</h3>
                        <a href="{$userWidget->getUrl()}"></a><br/>
                    {elseif $userWidget->getContent() neq null}
                        <h3>{$userWidget->getTitle()}</h3>
			{$userWidget->getContent()}
                    {/if}
                </div>
            {/if}
        {/foreach}
    </div>
    </td>
    </tr>
</table>
</div>
