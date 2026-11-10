<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function pdf()
    {
    $projects = \App\Models\Project::orderBy('id', 'asc')->get();

    $pdf = Pdf::loadView('admin.projects.pdf', compact('projects'))
    ->setPaper('a4', 'landscape');

    return $pdf->download('data-project-dian-project.pdf');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('gambar')) {
            $namaGambar = time() . '_' .
                Str::slug(pathinfo(
                    $request->file('gambar')->getClientOriginalName(),
                    PATHINFO_FILENAME
                )) . '.' .
                $request->file('gambar')->extension();

            $request->file('gambar')->move(
                public_path('images/projects'),
                $namaGambar
            );

            $validated['gambar'] = $namaGambar;
        }

        $validated['slug'] = Str::slug($validated['nama']);

        // Pastikan slug tidak duplikat.
        $slugDasar = $validated['slug'];
        $nomor = 1;

        while (Project::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $slugDasar . '-' . $nomor;
            $nomor++;
        }

        Project::create($validated);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project berhasil ditambahkan!');
    }

    
    public function edit(Project $project)
    {
    return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'kategori' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'link' => 'nullable|url|max:255',
    ]);

    if ($request->hasFile('gambar')) {
        $namaGambar = time() . '_' .
            \Illuminate\Support\Str::slug(
                pathinfo(
                    $request->file('gambar')->getClientOriginalName(),
                    PATHINFO_FILENAME
                )
            ) . '.' . $request->file('gambar')->extension();

        $request->file('gambar')->move(
            public_path('images/projects'),
            $namaGambar
        );

        $validated['gambar'] = $namaGambar;
    } else {
        unset($validated['gambar']);
    }

    // Buat slug baru hanya jika nama project berubah.
    if ($project->nama !== $validated['nama']) {
        $slugDasar = \Illuminate\Support\Str::slug($validated['nama']);
        $slug = $slugDasar;
        $nomor = 1;

        while (
            Project::where('slug', $slug)
                ->where('id', '!=', $project->id)
                ->exists()
        ) {
            $slug = $slugDasar . '-' . $nomor;
            $nomor++;
        }

        $validated['slug'] = $slug;
    }

    $project->update($validated);

    return redirect()
        ->route('admin.projects.index')
        ->with('success', 'Project berhasil diperbarui!');
    }
    
    
public function destroy(Project $project)
{
    // Hapus file gambar jika tersimpan di folder project.
    if ($project->gambar) {
        $pathGambar = public_path('images/projects/' . $project->gambar);

        if (file_exists($pathGambar)) {
            unlink($pathGambar);
        }
    }

    $project->delete();

    return redirect()
        ->route('admin.projects.index')
        ->with('success', 'Project berhasil dihapus!');
}

}
