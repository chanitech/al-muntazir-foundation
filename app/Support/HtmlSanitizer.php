<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Cleans rich-text HTML coming from the admin editor before it is saved.
 *
 * Only a safe list of formatting tags and attributes is kept: scripts, event
 * handlers (onclick=...), inline styles (except text alignment) and unsafe
 * links such as "javascript:" are removed. YouTube / Vimeo embeds are allowed.
 */
class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'hr', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'sub', 'sup', 'span', 'div',
        'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'pre', 'code',
        'ul', 'ol', 'li', 'a', 'img', 'figure', 'figcaption',
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'iframe',
    ];

    private const ALLOWED_ATTRIBUTES = [
        '*' => ['style'],
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        'td' => ['colspan', 'rowspan'],
        'th' => ['colspan', 'rowspan'],
        'iframe' => ['src', 'width', 'height', 'allowfullscreen', 'frameborder', 'allow', 'title'],
    ];

    /** Tags removed together with everything inside them. */
    private const DROP_WITH_CONTENT = ['script', 'style', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'link', 'meta', 'svg', 'math', 'noscript', 'template'];

    private const EMBED_HOSTS = ['www.youtube.com', 'youtube.com', 'www.youtube-nocookie.com', 'player.vimeo.com'];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="sanitize-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('sanitize-root');

        if (! $root) {
            return e(strip_tags($html));
        }

        self::cleanChildren($root);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        return trim($output);
    }

    private static function cleanChildren(DOMNode $node): void
    {
        // Copy the list first because nodes are removed while iterating.
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_COMMENT_NODE || $child->nodeType === XML_PI_NODE) {
                $node->removeChild($child);

                continue;
            }

            if (! $child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
                $node->removeChild($child);

                continue;
            }

            self::cleanChildren($child);

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                // Keep the text, drop the unknown wrapper tag.
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);

                continue;
            }

            self::cleanAttributes($child, $tag);

            if ($tag === 'iframe' && ! $child->hasAttribute('src')) {
                $node->removeChild($child);
            }
        }
    }

    private static function cleanAttributes(DOMElement $element, string $tag): void
    {
        $allowed = array_merge(self::ALLOWED_ATTRIBUTES['*'], self::ALLOWED_ATTRIBUTES[$tag] ?? []);

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $name = strtolower($attribute->name);
            $value = trim($attribute->value);

            if (! in_array($name, $allowed, true)) {
                $element->removeAttribute($attribute->name);

                continue;
            }

            if ($name === 'style') {
                $style = self::cleanStyle($value);
                $style === '' ? $element->removeAttribute($attribute->name) : $element->setAttribute('style', $style);

                continue;
            }

            if ($name === 'href' && ! self::isSafeUrl($value, ['http', 'https', 'mailto', 'tel'])) {
                $element->removeAttribute($attribute->name);
            }

            if ($name === 'src') {
                $ok = $tag === 'iframe' ? self::isAllowedEmbed($value) : self::isSafeUrl($value, ['http', 'https']);
                if (! $ok) {
                    $element->removeAttribute($attribute->name);
                }
            }
        }

        if ($tag === 'a' && $element->getAttribute('target') === '_blank') {
            $element->setAttribute('rel', 'noopener noreferrer');
        }
    }

    private static function cleanStyle(string $style): string
    {
        if (preg_match('/text-align\s*:\s*(left|right|center|justify)/i', $style, $match)) {
            return 'text-align: '.strtolower($match[1]).';';
        }

        return '';
    }

    private static function isSafeUrl(string $url, array $schemes): bool
    {
        if ($url === '' || str_starts_with($url, '#') || str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return true;
        }

        // Remove whitespace / control characters that browsers ignore ("java\nscript:").
        $normalised = strtolower(preg_replace('/[\x00-\x20]+/', '', $url));
        $scheme = parse_url($normalised, PHP_URL_SCHEME);

        if ($scheme === null || $scheme === false) {
            // Relative link such as "page.html" or "//cdn..." (protocol-relative).
            return ! str_contains(strtok($normalised, '/?#') ?: '', ':');
        }

        return in_array($scheme, $schemes, true);
    }

    private static function isAllowedEmbed(string $url): bool
    {
        $parts = parse_url($url);

        return ($parts['scheme'] ?? '') === 'https'
            && in_array(strtolower($parts['host'] ?? ''), self::EMBED_HOSTS, true);
    }
}
