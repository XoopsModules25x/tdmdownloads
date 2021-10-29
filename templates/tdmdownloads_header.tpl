<{if $show_breadcrumbs|default:''}>
    <{include file='db:tdmdownloads_breadcrumbs.tpl'}>
<{/if}>

<{if $ads|default:'' != ''}>
    <div class='center'>
        <{$ads}></div>
<{/if}>
