function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');
    if (sidebar.style.left === '0px') {
        sidebar.style.left = '-250px';
        content.style.marginLeft = '0';
    } else {
        sidebar.style.left = '0px';
        content.style.marginLeft = '250px';
    }
}

//untuk fitur pencarian
function searchTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toLowerCase();
    table = document.getElementById("dataTable");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        var found = false;
        for (j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        if (found) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}


function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this record?")) {
        window.location.href = "hapus.php?id=" + id;
    }
}

document.getElementById('filter_type').addEventListener('change', function() {
    var filterType = this.value;
    if (filterType === 'tanggal') {
        document.getElementById('tanggal_form').style.display = 'block';
        document.getElementById('bulan_form').style.display = 'none';
        document.getElementById('tahun_form').style.display = 'none';
    } else if (filterType === 'bulan') {
        document.getElementById('tanggal_form').style.display = 'none';
        document.getElementById('bulan_form').style.display = 'block';
        document.getElementById('tahun_form').style.display = 'none';
    } else if (filterType === 'tahun') {
        document.getElementById('tanggal_form').style.display = 'none';
        document.getElementById('bulan_form').style.display = 'none';
        document.getElementById('tahun_form').style.display = 'block';
    }
});

