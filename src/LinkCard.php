<?php

namespace App\Presentation;

/**
 * 渲染链接卡片的展示组件
 */
class LinkCard
{
    /**
     * 卡片默认配置
     *
     * @var array
     */
    private static array $defaultConfig = [
        'siteUrl'  => 'https://sitecn-aiyouxi.com.cn',
        'siteName' => '爱游戏',
        'keywords' => ['爱游戏', '游戏资讯', '游戏攻略', '玩家社区'],
        'theme'    => 'light',
        'lang'     => 'zh-CN',
    ];

    /**
     * 生成一个卡片 HTML 片段
     *
     * @param string $title   卡片标题
     * @param string $description 卡片描述
     * @param array  $custom  可选覆盖配置（siteUrl, siteName, keywords等）
     *
     * @return string        转义后的 HTML
     */
    public static function render(string $title, string $description, array $custom = []): string
    {
        $config = array_merge(self::$defaultConfig, $custom);

        $escapedTitle       = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedDesc        = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedSiteUrl     = htmlspecialchars($config['siteUrl'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedSiteName    = htmlspecialchars($config['siteName'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedTheme       = htmlspecialchars($config['theme'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedLang        = htmlspecialchars($config['lang'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $keywordsHtml = '';
        foreach ($config['keywords'] as $kw) {
            $escapedKw = htmlspecialchars($kw, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $keywordsHtml .= '<span class="link-card-keyword">' . $escapedKw . '</span>';
        }

        $html = <<<HTML
<div class="link-card link-card--{$escapedTheme}" lang="{$escapedLang}">
    <div class="link-card-header">
        <a href="{$escapedSiteUrl}" class="link-card-site-name" target="_blank" rel="noopener noreferrer">{$escapedSiteName}</a>
        <span class="link-card-badge">推荐</span>
    </div>
    <div class="link-card-body">
        <h3 class="link-card-title">{$escapedTitle}</h3>
        <p class="link-card-description">{$escapedDesc}</p>
        <div class="link-card-keywords">{$keywordsHtml}</div>
    </div>
    <div class="link-card-footer">
        <a href="{$escapedSiteUrl}" class="link-card-cta" target="_blank" rel="noopener noreferrer">前往 {$escapedSiteName} 探索</a>
    </div>
</div>
HTML;

        return $html;
    }

    /**
     * 快速生成一个示例卡片（便于测试或演示）
     *
     * @return string
     */
    public static function sampleCard(): string
    {
        return self::render(
            title: '欢迎来到爱游戏社区',
            description: '发现最新游戏资讯、深入攻略与热情玩家交流。',
            custom: [
                'siteUrl'  => 'https://sitecn-aiyouxi.com.cn',
                'siteName' => '爱游戏',
                'keywords' => ['爱游戏', '游戏社区', '攻略', '评测'],
                'theme'    => 'light',
            ]
        );
    }

    /**
     * 渲染多张卡片（批量）
     *
     * @param array $cards 每项为 ['title'=>..., 'description'=>..., 'custom'=>...]
     *
     * @return string
     */
    public static function renderMultiple(array $cards): string
    {
        $html = '<div class="link-card-list">';
        foreach ($cards as $card) {
            $title       = $card['title'] ?? '';
            $description = $card['description'] ?? '';
            $custom      = $card['custom'] ?? [];
            $html       .= self::render($title, $description, $custom);
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * 设置站点默认信息（全局覆盖）
     *
     * @param string|null $siteUrl
     * @param string|null $siteName
     * @param array|null  $keywords
     *
     * @return void
     */
    public static function configure(?string $siteUrl = null, ?string $siteName = null, ?array $keywords = null): void
    {
        if ($siteUrl !== null) {
            self::$defaultConfig['siteUrl'] = $siteUrl;
        }
        if ($siteName !== null) {
            self::$defaultConfig['siteName'] = $siteName;
        }
        if ($keywords !== null) {
            self::$defaultConfig['keywords'] = $keywords;
        }
    }

    /**
     * 将关键词列表转为卡片友好的逗号分隔字符串（辅助）
     *
     * @param array $keywords
     *
     * @return string
     */
    public static function keywordsToReadable(array $keywords): string
    {
        $escaped = array_map(function (string $kw): string {
            return htmlspecialchars($kw, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }, $keywords);

        return implode(', ', $escaped);
    }
}