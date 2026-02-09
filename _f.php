</main>
<style>
#left-ad, #right-ad {
  position: fixed;
  width: 120px;
  height: 600px;
  top: 50%;
  transform: translateY(-50%); 
  z-index: 1000;
}

#left-ad {
  left: 10px;
}

#right-ad {
  right: 10px;
}
<
/* Footer and margin ads behave the same on mobile */
@media (max-width: 768px) {
  #left-ad, #right-ad {
    position: static !important;
    width: 100% !important;
    max-width: 100% !important;
    height: auto !important;
    margin: 10px 0;
    text-align: center;
  }

  #left-ad ins, #right-ad ins,
  .ad-sect ins.adsbygoogle {
    display: block !important;
    width: 100% !important;   /* take up full viewport width */
    max-width: 100% !important;
    height: auto !important;
  }
}

footer a {
    text-decoration: none;
    color: var(--bs-body-color);
}
footer a:hover {
    text-decoration: underline;
}
</style>

<div class="footer">

<?php
$ads = $_SESSION['ads'] ?? '1';

if (isset($_SESSION['id']) && ($ads === '1' || $ads === '3')):
?>
  <div class="ad-sect mt-3">

    <!-- Google Ads Script -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7936589652536632"
         crossorigin="anonymous"></script>

    <!-- Footer AD -->
    <ins class="adsbygoogle"
         style="display:inline-block;width:730px;height:90px"
         data-ad-client="ca-pub-7936589652536632"
         data-ad-slot="6033230692"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>

    <?php if ($ads === '3'): ?>
    <!-- Left AD -->
    <div id="left-ad">
      <ins class="adsbygoogle"
           style="display:inline-block;width:120px;height:600px"
           data-ad-client="ca-pub-7936589652536632"
           data-ad-slot="3140651074"></ins>
      <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>

    <!-- Right AD -->
    <div id="right-ad">
      <ins class="adsbygoogle"
           style="display:inline-block;width:120px;height:600px"
           data-ad-client="ca-pub-7936589652536632"
           data-ad-slot="3140651074"></ins>
      <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>
    <?php endif; ?>

  </div>

<?php endif; ?>

<footer class="pt-0 pb-4 mt-0 <?php if ($isInApp) {?>d-none mt-4<?php } ?>">
  <div>

    <hr class="border-secondary opacity-50 mb-4">

    <div class="row gy-3">

      <!-- Branding -->
      <div class="col-12 col-md-5">
        <h6 class="text-uppercase fw-bold mb-3">Łuna</h6>
        <p class="mb-2">
          A clean and modern way to manage your grades.
        </p>
        <p class="small text-secondary mb-0">
          Built to make academic tracking simple, fast, and stress-free; whether you're at school or on the go.
        </p>
      </div>

      <!-- Legal -->
      <div class="col-6 col-md-2">
        <h6 class="text-uppercase fw-bold mb-3">Quick Links</h6>
        <ul class="list-unstyled mb-0">
          <li><a href="/op/pvpl" class="text-decoration-none">Privacy Policy</a></li>
          <li><a href="/op/tos" class="text-decoration-none">Terms of Service</a></li>
          <li><a href="/admin/panel" class="text-decoration-none">Admin Panel</a></li>
        </ul>
      </div>

<!-- Connect -->
<div class="col-12 col-md-5">

  <div class="row">
    <!-- Left column: text -->
    <div class="col-7">
  <h6 class="text-uppercase fw-bold mb-3">Connect</h6>
      <p class="small mb-2">
        Have feedback or found a bug? Let us know.
      </p>
    </div>

    <!-- Right column: icons stacked vertically -->
    <div class="col-5 d-flex flex-column gap-2 text-end">
      <a href="mailto:contact@lunaedu.net" class="text-decoration-none" target="_blank">
        <span class="te-xt-secondary"><small>contact@lunaedu.net</small></span> <i class="bi bi-envelope"></i>
      </a>
      <a href="https://www.instagram.com/lunafsbs/" class="text-decoration-none" target="_blank">
        <span class="te-xt-secondary"><small>lunafsbs</small></span> <i class="bi bi-instagram"></i>
      </a>
      
      <!---<a href="https://youtube.com/@LunaFSBS" class="text-decoration-none" target="_blank">
        <span class="te-xt-secondary"><small>Łuna</small></span> <i class="bi bi-youtube"></i>
      </a>--->
      
      <a href="https://forms.gle/ckFYrj52sVBA21gG9" class="text-decoration-none" target="_blank">
        <span class="te-xt-secondary"><small>Feedback Google Form</small></span> <i class="bi bi-send-fill"></i>
      </a>
    </div>
  </div>
</div>



    </div>

    <hr class="border-secondary opacity-50 mt-5 mb-4">

    <!-- Footer Bottom -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
      <p class="mb-2 mb-md-0 small text-secondary">
        &copy; 2026 <strong>Łuna</strong>. All rights reserved.
      </p>
      <p class="mb-0 small text-secondary text-md-end">
        <?php
          $elapsed = microtime(true) - SCRIPT_START_TIME;
          $elapsed_formatted = number_format($elapsed, 6, '.', ' ');
          echo "Generated at " . date("H:i:s") . " on " . date("Y-m-d") . " · Rendered in $elapsed_formatted s";
        ?>
      </p>
    </div>

  </div>
</footer>



</div>

</div>

</div> <!--- container --->

<script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/service-worker.js')
    .then(reg => console.log('SW registered'))
    .catch(err => console.log('SW error:', err));
}
</script>

</body>

<!-- Add to Home Screen Modal -->
<div class="modal fade" id="webclipModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Łuna to Home Screen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p>For easier access and a fullscreen experience, you can add Łuna to your home screen.</p>
      </div>
      <div class="modal-footer d-flex">
        <button type="button" class="btn btn-danger" id="declineWebClip">No</button>
        <button type="button" class="btn btn-secondary" id="maybeLaterWebClip">Maybe Later</button>
        <button type="button" class="btn btn-primary" id="acceptWebClip">Yes</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
document.addEventListener('shown.bs.modal', function () {
    //document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
});


    // check for iOS
    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }

    // check for cookie
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    // Show modal only if all conditions are met
    if (
        isIOS() &&                       // iOS device
        !window.navigator.standalone &&  // not already in a Web Clip
        !getCookie("declinedwc")         // cookie not set
    ) {
        var webclipModal = new bootstrap.Modal(document.getElementById('webclipModal'));
        webclipModal.show();

        // Decline: persistent cookie (1 year)
        document.getElementById('declineWebClip').addEventListener('click', function() {
            document.cookie = "declinedwc=1; path=/; max-age=" + (60*60*24*365);
            webclipModal.hide();
        });

        // Maybe Later: 1 day
        document.getElementById('maybeLaterWebClip').addEventListener('click', function() {
            document.cookie = "declinedwc=1; path=/; max-age=" + (60*60*24);
            webclipModal.hide();
        });

        // Yes: redirect
        document.getElementById('acceptWebClip').addEventListener('click', function() {
            window.location.href = '/webclip';
        });
    }

    if (window.innerWidth <= 768) {
        // Resize left/right ads to footer style
        ["left-ad", "right-ad"].forEach(id => {
          const ad = document.querySelector(`#${id} ins.adsbygoogle`);
          if (ad) {
            ad.style.width = "100%";
            ad.style.height = "100px";
            ad.setAttribute("data-ad-format", "auto");
            ad.setAttribute("data-full-width-responsive", "true");
          }
        });
    }
});


</script>

</html>