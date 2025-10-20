document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ Admin JS Loaded");

    // Initialize DataTables
    const tables = document.querySelectorAll("table");
    if (tables.length > 0) {
        tables.forEach(table => {
            try {
                new DataTable(table, {
                    searchable: true,
                    fixedHeight: true,
                    perPageSelect: [5, 10, 25, 50],
                    labels: {
                        placeholder: "Search...",
                        perPage: "{select} entries per page",
                        noRows: "No records found",
                        info: "Showing {start} to {end} of {rows} entries",
                    },
                });
            } catch (error) {
                console.warn("⚠️ DataTable init failed:", error);
            }
        });
    }

    // ✅ Force white search bar continuously
    const fixSearchBar = () => {
        const searchInput = document.querySelector('div.dataTables_filter input[type="search"]');
        if (searchInput) {
            searchInput.style.setProperty('background-color', '#ffffff', 'important');
            searchInput.style.setProperty('color', '#111827', 'important');
            searchInput.style.setProperty('border', '1px solid #d1d5db', 'important');
            searchInput.style.setProperty('border-radius', '0.5rem', 'important');
            searchInput.style.setProperty('padding', '0.5rem 0.75rem', 'important');
        }
    };

    // Run once on load
    fixSearchBar();

    // Reapply every second (temporary fix for redraws)
    setInterval(fixSearchBar, 1000);

    // Permanent fix: Observe DOM for redraws
    const observer = new MutationObserver(fixSearchBar);
    observer.observe(document.body, { childList: true, subtree: true });
});
