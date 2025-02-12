function toggleSidebar() {
    var sidebar = document.querySelector('.sidebar');
    var button = document.getElementById('sidebar-icon');
    sidebar.classList.toggle('active');
    button.setAttribute('aria-expanded', sidebar.classList.contains('active'));
}

function toggleFilter() {
    var sidebar = document.querySelector('.right-sidebar');
    var button = document.getElementById('filter-icon');
    var clsbutton = document.getElementById('close-btn');

    sidebar.classList.toggle('active');

    button.setAttribute('aria-expanded', sidebar.classList.contains('active'));

    button.classList.toggle('hidden', sidebar.classList.contains('active'));
    clsbutton.classList.toggle('hidden', !sidebar.classList.contains('active'));
}