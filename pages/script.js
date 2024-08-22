function searchUser() {
  var searchQuery = document.getElementById("search").value;
  var table = document.querySelector('input[name="table"]').value;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
      if (this.readyState === 4 && this.status === 200) {
          document.getElementById("anggota-list").innerHTML = this.responseText;
      }
  };
  xhttp.open("GET", "pages/search.php?table=" + table + "&query=" + searchQuery, true);
  xhttp.send();
}
