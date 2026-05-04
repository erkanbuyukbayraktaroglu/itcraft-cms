<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UsageGuideController extends Controller
{
    public function index(): View
    {
        $guidePath = $this->findGuidePath();

        $content = $guidePath && file_exists($guidePath)
            ? file_get_contents($guidePath)
            : '# Kullanım Kılavuzu bulunamadı';

        $html = $this->markdownToHtml((string) $content);

        return view('admin.usage-guide.index', [
            'html' => $html,
            'guidePath' => $guidePath,
        ]);
    }

    private function findGuidePath(): ?string
    {
        $candidates = [
            base_path('docs/KULLANIM-KILAVUZU.md'),
            base_path('docs/Kullanım-Kılavuzu.md'),
            base_path('docs/Kullanim-Kilavuzu.md'),
            base_path('docs/kullanim-kilavuzu.md'),
            base_path('docs/kullanım-kılavuzu.md'),
            base_path('docs/usage-guide.md'),
            base_path('docs/USAGE-GUIDE.md'),
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate) && is_readable($candidate)) {
                return $candidate;
            }
        }

        $docsPath = base_path('docs');

        if (is_dir($docsPath)) {
            $files = glob($docsPath . '/*.md');

            if ($files) {
                foreach ($files as $file) {
                    $fileName = mb_strtolower(basename($file));

                    if (
                        str_contains($fileName, 'kullanim') ||
                        str_contains($fileName, 'kullanım') ||
                        str_contains($fileName, 'usage') ||
                        str_contains($fileName, 'kilavuz') ||
                        str_contains($fileName, 'kılavuz')
                    ) {
                        return $file;
                    }
                }
            }
        }

        return null;
    }

    private function markdownToHtml(string $markdown): string
    {
        $markdown = str_replace(["\r\n", "\r"], "\n", $markdown);
        $lines = explode("\n", $markdown);

        $html = '';
        $inCode = false;
        $inList = false;
        $inTable = false;
        $codeBuffer = [];
        $tableHeaderDone = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (str_starts_with($trimmed, '```')) {
                if (!$inCode) {
                    if ($inList) {
                        $html .= "</ul>\n";
                        $inList = false;
                    }

                    if ($inTable) {
                        $html .= "</tbody></table>\n";
                        $inTable = false;
                        $tableHeaderDone = false;
                    }

                    $inCode = true;
                    $codeBuffer = [];
                } else {
                    $html .= '<pre><code>' . e(implode("\n", $codeBuffer)) . "</code></pre>\n";
                    $inCode = false;
                    $codeBuffer = [];
                }

                continue;
            }

            if ($inCode) {
                $codeBuffer[] = $line;
                continue;
            }

            if ($trimmed === '') {
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }

                if ($inTable) {
                    $html .= "</tbody></table>\n";
                    $inTable = false;
                    $tableHeaderDone = false;
                }

                continue;
            }

            if (preg_match('/^\|(.+)\|$/', $trimmed)) {
                $cells = array_map('trim', explode('|', trim($trimmed, '|')));

                $isSeparator = true;

                foreach ($cells as $cell) {
                    if (!preg_match('/^:?-{3,}:?$/', $cell)) {
                        $isSeparator = false;
                        break;
                    }
                }

                if ($isSeparator) {
                    continue;
                }

                if (!$inTable) {
                    if ($inList) {
                        $html .= "</ul>\n";
                        $inList = false;
                    }

                    $html .= "<table><tbody>\n";
                    $inTable = true;
                    $tableHeaderDone = false;
                }

                $html .= "<tr>";

                foreach ($cells as $cell) {
                    $tag = !$tableHeaderDone ? 'th' : 'td';
                    $html .= '<' . $tag . '>' . $this->inlineMarkdown($cell) . '</' . $tag . '>';
                }

                $html .= "</tr>\n";
                $tableHeaderDone = true;

                continue;
            }

            if ($inTable) {
                $html .= "</tbody></table>\n";
                $inTable = false;
                $tableHeaderDone = false;
            }

            if (preg_match('/^#{1,6}\s+(.+)$/', $trimmed, $matches)) {
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }

                $level = strspn($trimmed, '#');
                $text = trim($matches[1]);
                $level = min(max($level, 1), 6);

                $html .= '<h' . $level . '>' . $this->inlineMarkdown($text) . '</h' . $level . ">\n";
                continue;
            }

            if (preg_match('/^-{3,}$/', $trimmed)) {
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }

                $html .= "<hr>\n";
                continue;
            }

            if (preg_match('/^[-*]\s+(.+)$/', $trimmed, $matches)) {
                if (!$inList) {
                    $html .= "<ul>\n";
                    $inList = true;
                }

                $html .= '<li>' . $this->inlineMarkdown($matches[1]) . "</li>\n";
                continue;
            }

            if (preg_match('/^\d+\.\s+(.+)$/', $trimmed, $matches)) {
                if (!$inList) {
                    $html .= "<ul>\n";
                    $inList = true;
                }

                $html .= '<li>' . $this->inlineMarkdown($matches[1]) . "</li>\n";
                continue;
            }

            if (str_starts_with($trimmed, '>')) {
                if ($inList) {
                    $html .= "</ul>\n";
                    $inList = false;
                }

                $html .= '<blockquote>' . $this->inlineMarkdown(trim(ltrim($trimmed, '>'))) . "</blockquote>\n";
                continue;
            }

            if ($inList) {
                $html .= "</ul>\n";
                $inList = false;
            }

            $html .= '<p>' . $this->inlineMarkdown($trimmed) . "</p>\n";
        }

        if ($inList) {
            $html .= "</ul>\n";
        }

        if ($inTable) {
            $html .= "</tbody></table>\n";
        }

        if ($inCode) {
            $html .= '<pre><code>' . e(implode("\n", $codeBuffer)) . "</code></pre>\n";
        }

        return $html;
    }

    private function inlineMarkdown(string $text): string
    {
        $text = e($text);

        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text) ?? $text;
        $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text) ?? $text;

        return $text;
    }
}
