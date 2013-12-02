{ajaxheader modname="Dashboard" filename="dashboard_user_dashboard.js" noscriptaculous=true effects=true}
{ajaxheader modname="Dashboard" filename="dragsort.js" noscriptaculous=true effects=true}

{assign var='isAdmin' value='false'}
{assign var='isModerator' value='false'}
{checkpermission component='Dashboard::' instance='::' level='ACCESS_ADMIN' assign='isAdmin'}
{checkpermission component='Dashboard::' instance='::' level='ACCESS_MODERATE' assign='isModerator'}
{gt text='Dashboard Widgets' assign='title'}
{pagesetvar name='title' value=$title}
{insert name='csrftoken' assign="token"}

<h2>{$title}</h2>
<div class="z-clearfix" style="height:1000px;">
    <div id="dashboard_available_widgets" class="z-clearfix">
        <form class="z-form" id="availablewidgetform" action="{modurl modname="Dashboard" type="user" func="addWidget"}" method="post" enctype="application/x-www-form-urlencoded">
            <input type="hidden" name="csrftoken" value="{$token}" />
            <label for="dashboard_available_widgets_edit">{gt text="Add widgets"}</label>
            {if $available_checkbox}
            <input id="dashboard_available_widgets_edit" name="available_checkbox" type="checkbox" value="0" onclick="dashboard_add_widgets_onclick(0)" checked/>
            {else}
            <input id="dashboard_available_widgets_edit" name="available_checkbox" type="checkbox" value="1" onclick="dashboard_add_widgets_onclick(1)" />
            {/if}
            <hr>
            <div id="dashboard_available_widgets_container" class="zx-clearfix">
                <h3>Available Widgets</h3>
                {foreach item='widget' from=$widgets}
                <div class="z-dashboardwidgetcontainer" style="width:{math equation='100/x' x=$modvars.Dashboard.available_per_row format='%.0d'}%;">
                    {assign var="module" value=$widget.module}
                    {assign var="icon" value=$widget.icon}
                    {if $icon}
                    {img modname="$module" src="$icon"}<br />
                    {/if}
                    {$widget->getTitle()}<br />
                    {assign var="id" value=$widget.id}
                    {if $isAdmin}
                    <label >{gt text="Default:"}</label>
                    <input id="dashboard_default_widget" name="set_default_widget" type="checkbox" value="1"/>
                    {/if}
                    <span class="z-nowrap z-buttons">
                        <button id="dashboard_add_widget{$widget.id}" class="z-button z-bt-small" name="id" value="{$id}" type="submit">{gt text="Add"}</button>
                    </span>
                </div>
                {/foreach}
            </div>
        </form>
    </div>
    <hr>
    <div id="z-dashboardwidgetlist" class="z-clearfix">
        <!-- The admin users can sort and remove the default widgets -->
        <div id="widgetsdef" class="z-clearfix">
            {foreach item='userWidget' from=$userWidgets}
            {assign var="position" value=$userWidget.position}
            {assign var="id" value=$userWidget.userWidgetId}
            <!-- Present only the default widgets -->		
            {if $userWidget->getDefWidget() eq 1}
            {if $isAdmin}
            <div id="widget_{$id}" class="z-dashboardwidgetcontainer draggable" style="width:{math equation='100/x' x=$modvars.Dashboard.widgets_per_row format='%.0d'}%;">
                {img modname='Dashboard' src='mouse.png' __alt='Drag to sort' __title='Drag to sort' id="dragicon`$id`" class='z-dragicon'}
                {if $userWidget->getUrl()}
                <h3>{$userWidget->getTitle()}</h3>
                <a href="{$userWidget->getUrl()}"></a><br/>
                {elseif $userWidget->getConfContent() neq null}
                <h3>{$userWidget->getTitle()}</h3>
                {$userWidget->getConfContent()}	
                {/if}
                <form class="z-form" id="widgetsform{$userWidget.userWidgetId}" action="{modurl modname="Dashboard" type="user" func="removeWidget"}" method="post" enctype="application/x-www-form-urlencoded">
                    <input type="hidden" name="csrftoken" value="{$token}" />
                    <span class="z-nowrap z-buttons">
                        <button id="dashboard_remove_widget{$userWidget.userWidgetId}" class="z-button z-bt-small" name="id" value="{$id}" type="submit">{gt text="Remove"}</button>
                    </span>
                </form>
            </div>
            {elseif $isModerator}
            <div id="widget_{$id}" class="z-dashboardwidgetcontainer" style="width:{math equation='100/x' x=$modvars.Dashboard.widgets_per_row format='%.0d'}%;">
                {if $userWidget->getUrl()}
                <h3>{$userWidget->getTitle()}</h3>
                <a href="{$userWidget->getUrl()}"></a><br/>
                {elseif $userWidget->getConfContent() neq null}
                <h3>{$userWidget->getTitle()}</h3>
                {$userWidget->getConfContent()}
                {/if}
            </div>
            {else}
            <!-- The user is not admin and cannot sort and remove the default widgets -->
            <div id="widget_{$id}" class="z-dashboardwidgetcontainer" style="width:{math equation='100/x' x=$modvars.Dashboard.widgets_per_row format='%.0d'}%;">
                {if $userWidget->getUrl()}
                <a href="{$userWidget->getUrl()}"></a><br/>
                <h3>{$userWidget->getTitle()}</h3>
                {elseif $userWidget->getConfContent() neq null}
                <h3>{$userWidget->getTitle()}</h3>
                {/if}
            </div>
            {/if}
            {/if}
            {/foreach}
            <br/>
        </div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>

        <!-- Present the non-default widgets for both user groups -->
        <div id="widgets" class="z-clearfix">
            {foreach item='userWidget' from=$userWidgets}
            {assign var="position" value=$userWidget.position}
            {assign var="id" value=$userWidget.userWidgetId}
            {if $userWidget->getDefWidget() eq 0}
            <div id="widget_{$id}" class="z-dashboardwidgetcontainer draggable" style="width:{math equation='100/x' x=$modvars.Dashboard.widgets_per_row format='%.0d'}%;">
                {img modname='Dashboard' src='mouse.png' __alt='Drag to sort' __title='Drag to sort' id="dragicon`$id`" class='z-dragicon'}

                {if $userWidget->getUrl()}
                <h3><font color="green">{$userWidget->getTitle()}</font></h3>
                <a href="{$userWidget->getUrl()}"></a><br/>
                {elseif $userWidget->getConfContent() neq null}
                <h3><font color="green">{$userWidget->getTitle()}</font></h3>
                {$userWidget->getConfContent()}
                {/if}
                <br/>
                <form class="z-form" id="widgetsform{$userWidget.userWidgetId}" action="{modurl modname="Dashboard" type="user" func="removeWidget"}" method="post" enctype="application/x-www-form-urlencoded">
                    <input type="hidden" name="csrftoken" value="{$token}" />
                    <span class="z-nowrap z-buttons">
                        <button id="dashboard_remove_widget{$userWidget.userWidgetId}" class="z-button z-bt-small" name="id" value="{$id}" type="submit">{gt text="Remove"}</button>
                    </span>
                </form>
            </div>
            {/if}
            {/foreach}
        </div>
    </div>
</div>
