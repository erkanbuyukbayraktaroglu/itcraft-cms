<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SetupChecklistController extends Controller
{
    public function index(): View
    {
        $checklistPath = $this->findChecklistPath();
        $completedPath = storage_path('app/setup_checklist_completed.flag');

        $content = $checklistPath && file_exists($checklistPath)
            ? file_get_contents($checklistPath)
            : '# Kurulum Checklist bulunamadı';

        $html = $this->markdownToHtml((string) $content);

        return view('admin.setup-checklist.index', [
            'html' => $html,
            'checklistPath' => $checklistPath,
            'isCompleted' => file_exists($completedPath),
            'completedAt' => file_exists($completedPath) ? trim((string) file_get_contents($completedPath)) : null,
        ]);
    }

    public function complete(): RedirectResponse
    {
        $storagePath = storage_path('app');

        if (!File::isDirectory($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        file_put_contents(
            storage_path('app/setup_checklist_completed.flag'),
            now()->format('Y-m-d H:i:s')
        );

        return redirect()
            ->route('admin.setup-checklist.index')
            ->with('success', 'Kurulum checklist kontrolü tamamlandı olarak işaretlendi.');
    }

    public function reopen(): RedirectResponse
    {
        $completedPath = storage_path('app/setup_checklist_completed.flag');

        if (file_exists($completedPath)) {
            @unlink($completedPath);
        }

        return redirect()
            ->route('admin.setup-checklist.index')
            ->with('success', 'Kurulum checklist tekrar aktif hale getirildi.');
    }

    private function findChecklistPath(): ?string
    {
        $candidates = [
            base_path('docs/KURULUM-CHECKLIST.md'),
            base_path('docs/kurulum-checklist.md'),
            base_path('docs/Kurulum-Checklist.md'),
            base_path('docs/SETUP-CHECKLIST.md'),
            base_path('docs/setup-checklist.md'),
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
                        str_contains($fileName, 'kurulum') ||
                        str_contains($fileName, 'checklist') ||
                        str_contains($fileName, 'setup')
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
