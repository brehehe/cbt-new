<?php

namespace App\Http\Controllers\Admin;

use App\Models\Master\Question\Answer;
use App\Models\Master\Question\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\Process\Process;

class LatexPreviewController
{
    public function preview(Request $request)
    {
        $data = $request->validate([
            'latex' => 'required|string|max:50000',
            'target_type' => ['nullable', 'string', Rule::in(['question', 'answer'])],
            'target_id' => ['nullable', 'string', 'max:64'],
        ]);

        $latexInput = trim($data['latex'] ?? '');
        if ($latexInput === '') {
            return response()->json([
                'ok' => false,
                'message' => 'LaTeX kosong.',
            ], 422);
        }

        $hash = sha1($latexInput);
        $publicDir = "latex-previews/{$hash}";
        $publicPdf = "{$publicDir}/preview.pdf";
        $publicPng = "{$publicDir}/preview.png";

        if (Storage::disk('public')->exists($publicPdf)) {
            $this->maybePersistPreview($data['target_type'] ?? null, $data['target_id'] ?? null, $publicPdf, $publicPng);

            return response()->json([
                'ok' => true,
                'pdf_url' => Storage::disk('public')->url($publicPdf),
                'png_url' => Storage::disk('public')->url($publicPng),
            ]);
        }

        $tmpDir = storage_path("app/latex-previews/{$hash}");
        if (! File::exists($tmpDir)) {
            File::makeDirectory($tmpDir, 0755, true);
        }

        $texFile = $tmpDir.'/main.tex';
        $content = $this->normalizeLatex($latexInput);
        File::put($texFile, $content);

        $xelatex = new Process([
            '/usr/bin/xelatex',
            '-interaction=nonstopmode',
            '-halt-on-error',
            '-output-directory',
            $tmpDir,
            $texFile,
        ]);
        $xelatex->setTimeout(20);
        $xelatex->run();

        if (! $xelatex->isSuccessful()) {
            \Log::error('LaTeX render failed', [
                'error' => $xelatex->getErrorOutput(),
                'output' => $xelatex->getOutput(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Gagal render LaTeX. Pastikan input valid.',
                'error' => $xelatex->getErrorOutput(),
            ], 500);
        }

        $pdfPath = $tmpDir.'/main.pdf';
        if (! File::exists($pdfPath)) {
            return response()->json([
                'ok' => false,
                'message' => 'File PDF tidak ditemukan setelah render.',
            ], 500);
        }

        $pngProcess = new Process([
            '/usr/bin/pdftoppm',
            '-png',
            '-singlefile',
            $pdfPath,
            $tmpDir.'/preview',
        ]);
        $pngProcess->setTimeout(20);
        $pngProcess->run();

        if (! $pngProcess->isSuccessful()) {
            \Log::error('LaTeX PDF->PNG failed', [
                'error' => $pngProcess->getErrorOutput(),
                'output' => $pngProcess->getOutput(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Gagal konversi PDF ke PNG. Pastikan pdftoppm terpasang.',
                'error' => $pngProcess->getErrorOutput(),
            ], 500);
        }

        Storage::disk('public')->put($publicPdf, File::get($pdfPath));
        $pngPath = $tmpDir.'/preview.png';
        if (File::exists($pngPath)) {
            Storage::disk('public')->put($publicPng, File::get($pngPath));
        }

        $this->maybePersistPreview($data['target_type'] ?? null, $data['target_id'] ?? null, $publicPdf, $publicPng);

        return response()->json([
            'ok' => true,
            'pdf_url' => Storage::disk('public')->url($publicPdf),
            'png_url' => Storage::disk('public')->url($publicPng),
        ]);
    }

    private function maybePersistPreview(?string $targetType, $targetId, string $pdfPath, string $pngPath): void
    {
        if (! $targetType || ! $targetId) {
            return;
        }

        if ($targetType === 'question') {
            Question::where('id', $targetId)->update([
                'latex_preview_pdf' => $pdfPath,
                'latex_preview_png' => $pngPath,
            ]);

            return;
        }

        if ($targetType === 'answer') {
            Answer::where('id', $targetId)->update([
                'latex_preview_pdf' => $pdfPath,
                'latex_preview_png' => $pngPath,
            ]);
        }
    }

    private function normalizeLatex(string $input): string
    {
        if (str_contains($input, '\\documentclass')) {
            return $input;
        }

        $clean = preg_replace('/\\\\begin\{document\}|\\\\end\{document\}/', '', $input);
        $clean = trim($clean);

        return <<<TEX
\\documentclass{article}
\\usepackage{graphicx}
\\usepackage{amsmath}
\\usepackage{amssymb}
\\usepackage{geometry}
\\geometry{a4paper, margin=2.5cm}
\\begin{document}
{$clean}
\\end{document}
TEX;
    }
}
