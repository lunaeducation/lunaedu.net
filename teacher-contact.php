<?php session_start(); include_once("_h.php"); ?>

<h2>Teacher Emails</h2>
<hr>

<!-- Loader -->
<div id="loader" class="d-none align-items-center gap-3 my-4" role="status" aria-live="polite">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mb-0">Loading...<br>Please be patient.</p>
</div>

<!-- Teacher list -->
<ul id="teacher-list" class="list-group"></ul>

<script>
window.addEventListener('DOMContentLoaded', () => {
  const loader = document.getElementById("loader");
  const list   = document.getElementById("teacher-list");
  const backendUrl = '/backends/teacher-contact-backend.php';

  function showLoader() {
    loader.classList.remove('d-none');
    loader.classList.add('d-flex');
  }
  function hideLoader() {
    loader.classList.remove('d-flex');
    loader.classList.add('d-none'); 
  }

  showLoader();
  list.innerHTML = "";

  fetch(backendUrl)
    .then(res => {
      if (!res.ok) throw new Error('Network response not ok: ' + res.status);
      return res.json();
    })
    .then(data => {
      if (!data.teachers || data.teachers.length === 0) {
        list.innerHTML = "<li class='list-group-item'>Couldn't get list of teachers. HAC may be unavailable at the moment.</li>";
        return;
      }

      data.teachers.forEach(t => {
        const li = document.createElement("li");
        li.className = "list-group-item";

        const name = document.createElement("strong");
        name.textContent = t.teacher;

        const subjects = document.createElement("span");
        subjects.textContent = " – " + (t.subjects ? t.subjects.join(", ") : "");

        const email = document.createElement("a");
        email.href = "mailto:" + t.email;
        email.textContent = " " + t.email;

        li.appendChild(name);
        li.appendChild(subjects);
        li.appendChild(document.createElement("br"));
        li.appendChild(email);

        list.appendChild(li);
      });
    })
    .catch(err => {
      console.error("Error loading teachers:", err);
      list.innerHTML = "<li class='list-group-item text-danger'>Failed to load. Please try again later.</li>";
    })
    .finally(() => {
      hideLoader();
    });
});
</script>

<?php include_once("_f.php"); ?>
