let sortDirection = 'asc';

function sortByName() {
    const container = document.getElementById('sortable-files');
    const cards = Array.from(container.children);

    // Toggle direction FIRST
    sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';

    const sorted = cards.sort((a, b) => {
        const nameA = a.getAttribute('title').toLowerCase();
        const nameB = b.getAttribute('title').toLowerCase();

        if (sortDirection === 'asc') {
            return nameA.localeCompare(nameB);
        } else {
            return nameB.localeCompare(nameA);
        }
    });

    // Update icon direction
    const icon = document.getElementById('sort-icon');
    icon.style.transform = sortDirection === 'asc' ? 'rotate(0deg)' : 'rotate(180deg)';

    // Re-append sorted nodes
    sorted.forEach(card => container.appendChild(card));
}

function showListView() {
    document.querySelectorAll('.grid-view').forEach(card => {
        card.classList.add('hidden');
    });

    document.querySelectorAll('.list-view').forEach(card => {
        card.classList.remove('hidden');
    });

    // Update active state on buttons
    document.getElementById('listViewButton').classList.add('bg-[#C8EAFF]');
    document.getElementById('gridViewButton').classList.remove('bg-[#C8EAFF]');
}

function showGridView() {
    document.querySelectorAll('.list-view').forEach(card => {
        card.classList.add('hidden');
    });

    document.querySelectorAll('.folder-card.grid-view, .file-card.grid-view').forEach(card => {
        card.classList.remove('hidden');
    });

    // Update active state on buttons
    document.getElementById('gridViewButton').classList.add('bg-[#C8EAFF]');
    document.getElementById('listViewButton').classList.remove('bg-[#C8EAFF]');
}