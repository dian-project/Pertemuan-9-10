
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Projects</title>

    <style>
        @page {
        size: A4 landscape;
        margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h1 {
            font-size: 24px;
            margin: 0 0 25px 0;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #777;
            padding: 10px 8px;
            vertical-align: middle;
        }

        th {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }

        td {
            font-size: 11px;
        }

        .no {
            width: 5%;
            text-align: center;
        }

        .gambar {
            width: 14%;
            text-align: center;
        }

        .title {
            width: 11%;
        }

        .teknologi {
            width: 12%;
        }

        .description {
            width: 48%;
            text-align: left;
        }

        .status {
            width: 10%;
            text-align: center;
        }

        .project-image {
            width: 110px;
            height: 65px;
            object-fit: contain;
        }

        .empty {
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>

    <h1>Data Projects</h1>

    <table>
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="gambar">Gambar</th>
                <th class="title">Title</th>
                <th class="kategori">Kategori</th>
                <th class="description">Description</th>
                <th class="status">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($projects as $index => $project)
                <tr>
                    <td class="no">{{ $index + 1 }}</td>

                    <td class="gambar">
                        @php
                            $pathGambar = public_path(
                                'images/projects/' . $project->gambar
                            );
                        @endphp

                        @if ($project->gambar && file_exists($pathGambar))
                            <img
                                src="{{ $pathGambar }}"
                                class="project-image"
                                alt="Gambar Project"
                            >
                        @else
                            -
                        @endif
                    </td>

                    <td class="title">
                        {{ $project->nama }}
                    </td>

                    <td class="kategori">
                        {{ $project->kategori }}
                    </td>

                    <td class="description">
                        {{ $project->deskripsi }}
                    </td>

                    <td class="status">
                        {{ $project->status ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty">
                        Belum ada data project.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
