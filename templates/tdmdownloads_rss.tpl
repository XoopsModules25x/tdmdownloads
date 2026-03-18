<?xml version="1.0" encoding="<{$smarty.const._CHARSET}>"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><{$channel_title|default:''}></title>
        <link><{$channel_link|default:''}></link>
        <description><{$channel_desc|default:''}></description>
        <lastBuildDate><{$channel_lastbuild|default:''}></lastBuildDate>
        <docs><{$docs|default:''}></docs>
        <generator><{$channel_generator|default:''}></generator>
        <category><{$channel_category|default:''}></category>
        <managingEditor><{$channel_editor|default:''}></managingEditor>
        <webMaster><{$channel_webmaster|default:''}></webMaster>
        <language><{$channel_language|default:''}></language>
        <atom:link href="<{$xoops_url}><{$smarty.server.REQUEST_URI}>" rel="self" type="application/rss+xml">
            <{if $image_url|default:'' != ''}>
                <image>
                    <title><{$channel_title|default:''}></title>
                    <url><{$image_url|default:''}></url>
                    <link><{$channel_link|default:''}></link>
                    <{if $image_width|default:'' != ''}>
                        <width><{$image_width}></width>
                    <{/if}>
                    <{if $image_height|default:'' != ''}>
                        <height><{$image_height}></height>
                    <{/if}>
                </image>
            <{/if}>
            <{foreach item=item from=$items|default:[]}>
                <item>
                    <title><{$item.title|default:''}></title>
                    <link><{$item.link|default:''}></link>
                    <description><{$item.description|default:''}></description>
                    <pubDate><{$item.pubdate|default:''}></pubDate>
                    <guid><{$item.guid|default:''}></guid>
                </item>
            <{/foreach}>
        </atom:link>
    </channel>
</rss>
