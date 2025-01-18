function toggleDropdown() {
    var dropdown = document.getElementById("userDropdownMenu");
    var button = document.querySelector('.user-dropdown button');
    var isOpen = dropdown.style.display === 'block';
    dropdown.style.display = isOpen ? 'none' : 'block';
    button.setAttribute('aria-expanded', !isOpen);
}
