<?php
// public/contact.php
include '../includes/header.php';

$sent = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Here would be your contact form processing logic
  // For demonstration, we'll just set the $sent flag
  $sent = true;
}
?>

<div class="container py-4">
  <div class="row">
    <div class="col-lg-8">
      <h1 class="mb-4">Contactez-nous</h1>
      
      <?php if ($sent): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i>
          Une erreur est survenue lors de l'envoi de votre message. Veuillez réessayer.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <div class="card shadow-sm">
        <div class="card-body">
          <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="name" class="form-label">Nom</label>
              <input type="text" class="form-control" id="name" name="name" required>
              <div class="invalid-feedback">
                Veuillez entrer votre nom.
              </div>
            </div>
            
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
              <div class="invalid-feedback">
                Veuillez entrer une adresse email valide.
              </div>
            </div>
            
            <div class="mb-3">
              <label for="subject" class="form-label">Sujet</label>
              <input type="text" class="form-control" id="subject" name="subject" required>
              <div class="invalid-feedback">
                Veuillez entrer un sujet.
              </div>
            </div>
            
            <div class="mb-3">
              <label for="message" class="form-label">Message</label>
              <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
              <div class="invalid-feedback">
                Veuillez entrer votre message.
              </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-paper-plane me-2"></i>Envoyer
            </button>
          </form>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 mt-4 mt-lg-0">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title">Informations de contact</h5>
          <hr>
          <p><i class="fas fa-envelope me-2 text-primary"></i> support@foodfrenzy.com</p>
          <p><i class="fas fa-phone me-2 text-primary"></i> +33 1 23 45 67 89</p>
          <p><i class="fas fa-map-marker-alt me-2 text-primary"></i> 123 Rue de la Cuisine, 75001 Paris</p>
        </div>
      </div>
      
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Heures d'ouverture</h5>
          <hr>
          <p><strong>Lundi - Vendredi:</strong> 10h00 - 22h00</p>
          <p><strong>Samedi - Dimanche:</strong> 11h00 - 23h00</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
