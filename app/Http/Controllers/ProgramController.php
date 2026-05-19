<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Rekening;
use App\Services\RekeningTransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Spatie\Image\Image;

class ProgramController extends Controller
{
    private const BANNER_DIRECTORY = 'lazismu/program';
    private const BANNER_MAX_WIDTH = 1600;
    private const BANNER_QUALITY = 82;

    public function __construct(private RekeningTransactionService $rekeningTransactions)
    {
    }

    public function index(Request $request)
    {
        $query = Program::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_program', 'like', '%' . $search . '%')
                    ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programs = $query->with('rekening')->latest()->get();
        $rekenings = Rekening::orderBy('namarek')->get();

        return view('lazismu.program.index', compact('programs', 'rekenings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:100',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
            'target' => 'nullable|numeric|min:0',
            'idrek' => 'nullable|exists:rekening,id',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'status' => 'required|in:active,nonactive,selesai',
        ]);

        if ($request->hasFile('banner')) {
            $validated['banner_path'] = $this->storeOptimizedBanner($request->file('banner'));
        }

        unset($validated['banner']);
        $validated['target'] = $validated['target'] ?? 0;
        $validated['terkumpul'] = 0;

        $program = Program::create($validated);
        $this->rekeningTransactions->ensureProgramRekening($program);

        return redirect()->route('lazismu.program.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:100',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
            'target' => 'nullable|numeric|min:0',
            'idrek' => 'nullable|exists:rekening,id',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'status' => 'required|in:active,nonactive,selesai',
        ]);

        if ($request->hasFile('banner')) {
            if ($program->banner_path) {
                Storage::disk('public')->delete($program->banner_path);
            }

            $validated['banner_path'] = $this->storeOptimizedBanner($request->file('banner'));
        }

        unset($validated['banner']);
        $validated['target'] = $validated['target'] ?? 0;
        $program->update($validated);
        $this->rekeningTransactions->ensureProgramRekening($program);

        return redirect()->route('lazismu.program.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        if ($program->setorans()->exists()) {
            return redirect()->route('lazismu.program.index')->with('error', 'Program sudah memiliki setoran dan tidak bisa dihapus.');
        }

        $program->delete();

        return redirect()->route('lazismu.program.index')->with('success', 'Program berhasil dihapus.');
    }

    private function storeOptimizedBanner(UploadedFile $file): string
    {
        Storage::disk('public')->makeDirectory(self::BANNER_DIRECTORY);

        $fileName = Str::uuid() . '.webp';
        $relativePath = self::BANNER_DIRECTORY . '/' . $fileName;
        $absolutePath = Storage::disk('public')->path($relativePath);

        $image = Image::load($file->path());
        $dimensions = getimagesize($file->path());

        if (($dimensions[0] ?? 0) > self::BANNER_MAX_WIDTH) {
            $image->width(self::BANNER_MAX_WIDTH);
        }

        $image
            ->format('webp')
            ->quality(self::BANNER_QUALITY)
            ->save($absolutePath);

        return $relativePath;
    }
}
