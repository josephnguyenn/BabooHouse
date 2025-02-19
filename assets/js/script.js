//dropdownmenu//

function toggleDropdown() {
    var dropdown = document.getElementById("userDropdownMenu");
    var button = document.querySelector('.user-dropdown button');
    var isOpen = dropdown.style.display === 'block';
    dropdown.style.display = isOpen ? 'none' : 'block';
    button.setAttribute('aria-expanded', !isOpen);
}


//sidebar//

document.addEventListener('DOMContentLoaded', function() {
    var acc = document.getElementsByClassName("accordion-toggle");
    for (var i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }
});

//switchTab
function switchTab(type) {
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.box').forEach(box => box.classList.remove('active'));

    if (type === 'admin') {
        document.querySelector('.tab-container .tab:nth-child(2)').classList.add('active');
        document.getElementById('admin-notifications').classList.add('active');
    } else {
        document.querySelector('.tab-container .tab:nth-child(1)').classList.add('active');
        document.getElementById('system-notifications').classList.add('active');
    }
}

