// public/js/admin.js

document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ Admin JS Loaded");

    // ===============================
    // 1️⃣ Initialize DataTables
    // ===============================
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
                console.warn("⚠️ DataTable init failed on:", table, error);
            }
        });
    }

    // ===============================
    // 2️⃣ Sidebar Toggle (optional)
    // ===============================
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("sidebar-collapsed");
        });
    }

    // ===============================
    // 3️⃣ Auto-hide flash messages
    // ===============================
    const flashMessages = document.querySelectorAll("[role='alert']");
    if (flashMessages.length > 0) {
        setTimeout(() => {
            flashMessages.forEach(msg => {
                msg.classList.add("opacity-0", "transition", "duration-700");
                setTimeout(() => msg.remove(), 800);
            });
        }, 4000);
    }

    // ===============================
    // 4️⃣ Smooth scroll to top on tab change
    // ===============================
    const navLinks = document.querySelectorAll(".nav-link");
    navLinks.forEach(link => {
        link.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    });

    // ===============================
    // 5️⃣ Optional: Highlight active link manually
    // ===============================
    const currentUrl = window.location.href;
    navLinks.forEach(link => {
        if (currentUrl.includes(link.getAttribute("href"))) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });
});
