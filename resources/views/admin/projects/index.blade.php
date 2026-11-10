
@extends('admin.layouts.app')

@section('title', 'Data Projects')

@push('styles')
<style>
    .projects-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 35px;
    }

    .projects-header h2 {
        font-size: 30px;
        margin: 0;
    }

    .btn-add-project {
        background: #0d6efd;
        color: #fff;
        padding: 11px 16px;
        border-radius: 7px;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-add-project:hover {
        background: #0b5ed7;
        color: #fff;
    }

    .header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-pdf-project {
    background: #dc3545;
    color: #fff;
    padding: 11px 16px;
    border-radius: 7px;
    text-decoration: none;
    white-space: nowrap;
}

.btn-pdf-project:hover {
    background: #bb2d3b;
    color: #fff;
}
    .projects-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px 20px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .05);
    }

    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .entries-control,
    .search-control {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .entries-control select {
        width: 62px;
        padding: 6px 4px;
        border: 1px solid #ced4da;
        border-radius: 5px;
    }

    .search-control input {
        width: 225px;
        padding: 7px 10px;
        border: 1px solid #ced4da;
        border-radius: 5px;
    }

    #projectTable {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    #projectTable th,
    #projectTable td {
        border: 1px solid #dedede;
        padding: 10px 12px;
        vertical-align: top;
    }

    #projectTable th {
        font-weight: 700;
        white-space: nowrap;
        background: #fff;
    }

    #projectTable tbody tr:hover {
        background: #f8f9fa;
    }

    .project-thumbnail {
        width: 115px;
        height: 115px;
        object-fit: contain;
        border: 1px solid #dedede;
        border-radius: 7px;
        padding: 4px;
        background: #fff;
    }

    .project-description {
        min-width: 240px;
        max-width: 500px;
        overflow-wrap: anywhere;
    }

    .status-text {
        text-transform: lowercase;
    }

    .action-cell {
        min-width: 105px;
    }

    .btn-edit-project,
    .btn-delete-project {
        display: inline-block;
        padding: 7px 12px;
        border: 0;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
        margin-bottom: 3px;
    }

    .btn-edit-project {
        color: #212529;
        background: #ffc107;
    }

    .btn-delete-project {
        color: #fff;
        background: #dc3545;
    }

    .table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .table-footer .pagination {
        margin: 0;
    }

    .table-footer .pagination .page-link {
        color: #0d6efd;
    }

    .table-footer .pagination .active .page-link {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    @media (max-width: 768px) {
        .projects-header h2 {
            font-size: 24px;
        }

        .projects-card {
            padding: 16px 12px;
        }

        .search-control input {
            width: 160px;
        }

        .project-thumbnail {
            width: 80px;
            height: 80px;
        }
    }
</style>
@endpush

@section('content')
<div class="projects-header">
    <h2>Data Projects</h2>

    <div class="header-actions">
        <a href="{{ route('admin.projects.create') }}" class="btn-add-project">
            Tambah Project
        </a>

        <a href="{{ route('admin.projects.pdf') }}"
           class="btn-pdf-project"
           target="_blank">
            Cetak PDF
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="projects-card">
    <div class="table-toolbar">
        <div class="entries-control">
            <select id="entriesPerPage" aria-label="Jumlah data per halaman">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span>entries per page</span>
        </div>

        <div class="search-control">
            <label for="searchProject">Search:</label>
            <input type="search" id="searchProject">
        </div>
    </div>

    <div class="table-responsive">
        <table id="projectTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Kategori</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="projectTableBody">
                @forelse($projects as $project)
                    <tr>
                        <td>{{ $projects->firstItem() + $loop->index }}</td>

                        <td>
                            @if($project->gambar)
                                <img
                                    src="{{ asset('images/projects/' . $project->gambar) }}"
                                    alt="{{ $project->nama }}"
                                    class="project-thumbnail"
                                >
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>

                        <td>{{ $project->nama }}</td>

                        <td>{{ $project->kategori }}</td>

                        <td class="project-description">
                            {{ $project->deskripsi }}
                        </td>

                        <td>
                            <span class="status-text">
                                {{ $project->status ?? 'active' }}
                            </span>
                        </td>

                        <td class="action-cell">
                            <a
                                href="{{ route('admin.projects.edit', $project->id) }}"
                                class="btn-edit-project"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('admin.projects.destroy', $project->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus project ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-delete-project">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            Belum ada data project.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <div id="tableInfo" class="text-muted small"></div>
        <nav aria-label="Navigasi halaman tabel">
            <ul id="tablePagination" class="pagination pagination-sm"></ul>
        </nav>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchProject');
    const entriesSelect = document.getElementById('entriesPerPage');
    const tableBody = document.getElementById('projectTableBody');
    const tableInfo = document.getElementById('tableInfo');
    const pagination = document.getElementById('tablePagination');

    const allRows = Array.from(tableBody.querySelectorAll('tr'))
        .filter(row => row.cells.length > 1);

    let currentPage = 1;

    function renderTable() {
        const keyword = searchInput.value.toLowerCase().trim();
        const perPage = Number(entriesSelect.value);

        const filteredRows = allRows.filter(row =>
            row.textContent.toLowerCase().includes(keyword)
        );

        const total = filteredRows.length;
        const totalPages = Math.max(1, Math.ceil(total / perPage));

        currentPage = Math.min(currentPage, totalPages);

        allRows.forEach(row => {
            row.style.display = 'none';
        });

        const start = (currentPage - 1) * perPage;
        const visibleRows = filteredRows.slice(start, start + perPage);

        visibleRows.forEach((row, index) => {
            row.style.display = '';
            row.cells[0].textContent = start + index + 1;
        });

        if (total === 0) {
            tableInfo.textContent = 'Tidak ada data yang cocok.';
        } else {
            tableInfo.textContent =
                `Showing ${start + 1} to ${Math.min(start + perPage, total)} of ${total} entries`;
        }

        pagination.innerHTML = '';

        function addPage(label, page, disabled = false, active = false) {
            const li = document.createElement('li');
            li.className = 'page-item' +
                (disabled ? ' disabled' : '') +
                (active ? ' active' : '');

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'page-link';
            button.textContent = label;
            button.disabled = disabled;

            button.addEventListener('click', function () {
                currentPage = page;
                renderTable();
            });

            li.appendChild(button);
            pagination.appendChild(li);
        }

        addPage('Previous', Math.max(1, currentPage - 1), currentPage === 1);

        for (let page = 1; page <= totalPages; page++) {
            addPage(String(page), page, false, page === currentPage);
        }

        addPage('Next', Math.min(totalPages, currentPage + 1),
            currentPage === totalPages);
    }

    searchInput.addEventListener('input', function () {
        currentPage = 1;
        renderTable();
    });

    entriesSelect.addEventListener('change', function () {
        currentPage = 1;
        renderTable();
    });

    renderTable();
});
</script>
@endpush
