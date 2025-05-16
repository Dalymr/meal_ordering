<?php
// includes/footer.php
// End the content first
?>
</main>
</div><!-- End Container -->
</div><!-- End content-wrapper -->

<footer class="py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4 mb-md-0">
        <h5>FoodFrenzy</h5>
        <p class="mb-0">Votre plateforme de commande de repas en ligne, 2024-2025.</p>
        <div class="d-flex align-items-center mt-3">
          <img src="/img/palestine-flag.png" alt="Palestine Flag" style="height: 20px;"
               onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/0/00/Flag_of_Palestine.svg'; this.onerror=null;">
          <span class="ms-2">Viva Palestina</span>
        </div>
      </div>
      
      <div class="col-md-4 mb-4 mb-md-0">
        <h5>Liens Rapides</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="/public/meals.php" class="text-white">
            <i class="fas fa-utensils me-2"></i>Notre Menu</a>
          </li>
          <li class="mb-2"><a href="/public/about.php" class="text-white">
            <i class="fas fa-info-circle me-2"></i>À propos</a>
          </li>
          <li class="mb-2"><a href="/public/contact.php" class="text-white">
            <i class="fas fa-envelope me-2"></i>Contact</a>
          </li>
          <li class="mb-2"><a href="/public/terms.php" class="text-white">
            <i class="fas fa-file-alt me-2"></i>Conditions Générales</a>
          </li>
        </ul>
      </div>
      
      <div class="col-md-4">
        <h5>Retrouvez-nous</h5>
        <div class="d-flex mb-3">
          <a href="#" class="text-white me-3 fs-5"><i class="fab fa-facebook"></i></a>
          <a href="#" class="text-white me-3 fs-5"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-white me-3 fs-5"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-white fs-5"><i class="fab fa-youtube"></i></a>
        </div>
        <p class="mb-1"><i class="fas fa-phone me-2"></i> +216 71 123 456</p>
        <p class="mb-3"><i class="fas fa-envelope me-2"></i> contact@foodfrenzy.com</p>
        <p class="mt-3 mb-0 copyright">
          <small>&copy; <?= date('Y') ?> FoodFrenzy. Tous droits réservés.</small>
        </p>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="/js/scripts.js"></script>
</body>
</html>
<?php
// Flush the output buffer
if (ob_get_length()) ob_end_flush();
?>
